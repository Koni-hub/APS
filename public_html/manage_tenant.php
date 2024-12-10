<?php 
include ('./db_connect.php'); 

// Initialize variables
$selectedHouseID = isset($_POST['house_id']) ? intval($_POST['house_id']) : null;
$rooms = [];

// Fetch tenant data if id is set
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM tenants WHERE id = " . intval($_GET['id']));
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}

// Fetch categories (houses) for the house dropdown
$query = "SELECT id as houseID, name FROM categories";
$categoriesResult = $conn->query($query);

// Fetch room data if a house is selected
if ($selectedHouseID) {
    // Get roomPrefixName for the selected house
    $houseQuery = "SELECT roomPrefixName FROM houses WHERE id = ?";
    if ($stmt = $conn->prepare($houseQuery)) {
        $stmt->bind_param("i", $selectedHouseID);
        $stmt->execute();
        $houseResult = $stmt->get_result();

        if ($houseResult->num_rows > 0) {
            $house = $houseResult->fetch_assoc();
            $roomPrefixName = $house['roomPrefixName'];

            // Fetch rooms from the dynamically named table
            $roomQuery = "SELECT id, room_name, room_status FROM roomtbl_" . $conn->real_escape_string($roomPrefixName);
            if ($roomStmt = $conn->prepare($roomQuery)) {
                $roomStmt->execute();
                $roomResult = $roomStmt->get_result();

                if ($roomResult->num_rows > 0) {
                    while ($room = $roomResult->fetch_assoc()) {
                        $rooms[] = $room; // Store rooms to display
                    }
                }
                $roomStmt->close();
            }
        }
        $stmt->close();
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-tenant" method="post">
        <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">
        <div class="row form-group">
            <div class="col-md-4">
                <label for="" class="control-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Middle Name</label>
                <input type="text" class="form-control" name="middlename" value="<?php echo isset($middlename) ? htmlspecialchars($middlename) : '' ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Contact #</label>
                <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? htmlspecialchars($contact) : '' ?>" required>
            </div>
            <div class="col-md-4">
                <label for="room_id" class="control-label">Room ID</label>
                <select name="room_id" id="room_id" class="custom-select select2">
                    <option value="" disabled selected>Select House Room</option>
                    
                    <?php
                    echo '<script> console.log("Selected House ID: ' . $selectedHouseID . '"); </script>';
                    ?>

                    <?php
                    if ($selectedHouseID) {
                        foreach ($rooms as $room) {
                            $disabled = ($room['room_status'] == 'Occupied') ? 'disabled' : '';
                            // bug: room_name does not effect??
                            echo '<option value="' . htmlspecialchars($room['room_name']) . '" ' . $disabled . '>' . htmlspecialchars($room['room_name']) . '</option>';
                        }
                    } else {
                        echo '<option disabled>Select a house first.</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="house_id" class="control-label">House</label>
                <select name="house_id" id="house_id" class="custom-select select2">
                    <option value="" disabled selected>Select House</option>
                    <?php
                    if ($categoriesResult != null) {
                        while ($row = $categoriesResult->fetch_assoc()) {
                            $houseID = htmlspecialchars($row['houseID']);
                            $houseName = htmlspecialchars($row['name']);
                            echo '<option value="' . $houseID . '">' . $houseName . '</option>';
                        }
                    } else {
                        echo '<option disabled>No houses available.</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('room_id').addEventListener('change', function() {
        var selectedRoom = this.value;
        console.log("Selected room:", selectedRoom);
    });
</script>

<script>
$(document).ready(function(){
    // Update room options based on selected house
    $('#house_id').change(function() {
        var houseID = $(this).val();
        if (houseID) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: { action: 'fetch_rooms', house_id: houseID },
                success: function(response) {
                    $('#room_id').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('#room_id').html('<option value="" disabled>Error fetching rooms</option>');
                }
            });
        } else {
            $('#room_id').html('<option value="" disabled selected>Select House Room</option>');
        }
    });

    $('#manage-tenant').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url:'ajax.php?action=save_tenant',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved.",'success');
                    setTimeout(function(){
                        location.reload();
                    },1000);
                } else if (resp == 2) {
                    alert_toast("Room ID already exists.",'danger');
                    end_load();
                } else {
                    alert_toast("An error occurred.",'danger');
                    end_load();
                }
            }
        });
    });
});
</script>