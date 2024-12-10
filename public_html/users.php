<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i> New User</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table table-striped table-bordered" id="user_table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include ('./db_connect.php');
                            $type = ["", "Admin", "Landlord"];
                            $users = $conn->query("SELECT * FROM users ORDER BY name ASC");
                            $i = 1;
                            while ($row = $users->fetch_assoc()):

                            $type = [
                                "Admin" => "Administrator",
                                "Landlord" => "Landlord"
                            ];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo ucwords($row['id']); ?></td>
                            <td><?php echo ucwords($row['name']); ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $type[$row['type'] == 0 ? "Admin" : "Landlord"]; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Action</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item edit_user" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_user" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#user_table').DataTable();

        // New User Button
        $('#new_user').on('click', function() {
            uni_modal('New User', 'manage_user.php');
        });

        // Edit User
        $('.edit_user').on('click', function() {
            uni_modal('Edit User', 'manage_user.php?id=' + $(this).data('id'));
        });

        // Delete User
        $(document).on("click", ".delete_user", function () {
            var userId = $(this).data('id'); 

            if (confirm("Are you sure you want to delete this user with ID " + userId + "?")) {
                $.ajax({
                    url: 'ajax.php?action=delete_user',
                    method: 'POST',
                    data: { id: userId },
                    success: function(resp) {
                        if (resp == 1) {
                            alert("Data successfully deleted");
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            alert("Data unsuccessfully deleted");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        alert("An error occurred while deleting the user");
                    }
                });
            }
        });
    });
</script>