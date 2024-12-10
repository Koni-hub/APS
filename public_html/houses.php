<?php include('db_connect.php'); ?>
<?php 
if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1) {
    echo "<script>window.location.href = 'forbidden.php';</script>";
    exit();
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-house" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header">
                            House Form
                        </div>
                        <div class="card-body">
                            <div class="form-group" id="msg"></div>
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label class="control-label">House No</label>
                                <input type="text" class="form-control" name="house_no" required="">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category</label>
                                <select name="category_id" class="custom-select" required>
                                    <?php 
                                    $categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                                    if($categories->num_rows > 0):
                                    while($row = $categories->fetch_assoc()) :
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <option selected="" value="" disabled="">Please check the category list.</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Description</label>
                                <textarea name="description" cols="30" rows="4" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Price</label>
                                <input type="number" class="form-control text-right" name="price" step="any" required="">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Room Count</label>
                                <input type="number" class="form-control text-right" name="room_count" step="any" required="">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Room Prefix Name</label>
                                <input type="text" class="form-control" name="room_prefix_name" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Primary House Image</label>
                                <div id="current-primary-image" style="margin-bottom: 10px;">
                                    <!-- The image will be injected here via JavaScript -->
                                </div>
                                <input type="file" class="form-control" name="primary_image" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Room Images</label>
                                <div id="current-room-images" style="margin-bottom: 10px;">
                                    <!-- The images will be injected here via JavaScript -->
                                </div>
                                <div id="image-uploads">
                                    <div class="image-upload">
                                        <input type="file" class="form-control" name="room_images[]" accept="image/*">
                                        <input type="text" class="form-control mt-2" name="room_names[]" placeholder="Room Name (e.g., Kitchen)">
                                        <button type="button" class="btn btn-danger remove-images" style="margin-top: 5px;">Remove</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info" id="add-image">Add Another Image</button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                    <button class="btn btn-sm btn-default col-sm-3" type="reset">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <b>House List</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">House</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $house = $conn->query("SELECT h.*, c.name as cname FROM houses h INNER JOIN categories c ON c.id = h.category_id ORDER BY h.id ASC");
                                while($row = $house->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td class="">
                                        <p>House #: <b><?php echo $row['house_no'] ?></b></p>
                                        <p><small>House Type: <b><?php echo $row['cname'] ?></b></small></p>
                                        <!-- <p><small>Description: <b><?php echo $row['description'] ?></b></small></p>  Comment for responsiveness -->
                                        <p><small>Price: <b><?php echo number_format($row['price'],2) ?></b></small></p>
                                        <p><small>Room Count: <b><?php echo $row['NumberOfRooms'] ?></b></small></p>
                                        <p><small>Room Prefix Name: <b><?php echo $row['roomPrefixName'] ?></b></small></p>
                                        <!-- <?php
                                        $primaryImage = $conn->query("SELECT * FROM house_images WHERE house_id = " . $row['id'] . " AND is_primary = 1")->fetch_assoc();
                                        if ($primaryImage):
                                        ?>
                                        <p><img src="<?php echo $primaryImage['image_path'] ?>" alt="Primary Image" style="max-width: 100px; max-height: 100px;"/></p>
                                        <?php endif; ?>
                                        <?php
                                        $images = $conn->query("SELECT * FROM house_images WHERE house_id = " . $row['id'] . " AND is_primary = 0");
                                        while($img = $images->fetch_assoc()):
                                        ?>
                                        <p><img src="<?php echo $img['image_path'] ?>" alt="<?php echo $img['room_name'] ?>" style="max-width: 100px; max-height: 100px;"/> <small><?php echo $img['room_name'] ?></small></p>
                                        <?php endwhile; ?> -->
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_house" type="button" 
                                        data-count_room="<?php echo $row['NumberOfRooms'] ?>" 
                                        data-room_prefix_name="<?php echo $row['roomPrefixName'] ?>"
                                        data-id="<?php echo $row['id'] ?>" 
                                        data-house_no="<?php echo $row['house_no'] ?>" 
                                        data-description="<?php echo $row['description'] ?>"
                                        data-category_id="<?php echo $row['category_id'] ?>" 
                                        data-price="<?php echo $row['price'] ?>">Edit</button>
                                        <button class="btn btn-sm btn-danger delete_house" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
    <!-- Update Image Modal -->
    <div class="modal fade" id="update-image-modal" tabindex="-1" role="dialog" aria-labelledby="updateImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateImageModalLabel">Update Room Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update-image-form">
                    <div class="modal-body">
                        <input type="hidden" id="update-image-id" name="image_id">
                        <input type="hidden" id="update-image-path" name="image_path">
                        <input type="hidden" class="form-control" id="update-house-no" name="house_no">
                        <div class="form-group">
                            <label for="update-image-name">Room Name</label>
                            <input type="text" class="form-control" id="update-image-name" name="image_name" required>
                        </div>
                        <div class="form-group">
                            <label for="update-image-file">New Image</label>
                            <input type="file" class="form-control-file" id="update-image-file" name="image_file" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="current-image">Current Image</label>
                            <img id="current-image" src="" alt="Current Image" class="img-fluid">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset;
        padding: unset;
        line-height: 1em;
    }
    .image-upload {
        margin-bottom: 10px;
    }
    .remove-image {
        display: none;
    }
</style>

<script>
    // Add new image upload fields
    document.getElementById('add-image').addEventListener('click', function() {
        var container = document.getElementById('image-uploads');
        var newUpload = document.createElement('div');
        newUpload.className = 'image-upload';
        newUpload.innerHTML = `
            <input type="file" class="form-control" name="room_images[]" accept="image/*">
            <input type="text" class="form-control mt-2" name="room_names[]" placeholder="Room Name (e.g., Kitchen)">
            <button type="button" class="btn btn-danger remove-images" style="margin-top: 5px;">Remove</button>
        `;
        container.appendChild(newUpload);

        // Show remove button if there are multiple uploads
        var removeButtons = document.querySelectorAll('.remove-image');
        if (removeButtons.length > 1) {
            removeButtons.forEach(function(btn) {
                btn.style.display = 'inline-block';
            });
        }
    });

    // Remove image upload fields
    document.getElementById('image-uploads').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-images')) {
            e.target.parentElement.remove();
        }
    });

    $('#manage-house').on('reset', function(e){
        $('#msg').html('');
    });
    $('#manage-house').submit(function(e){
        e.preventDefault();
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'ajax.php?action=save_house',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if(resp == 1){
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else if(resp == 2){
                    $('#msg').html('<div class="alert alert-danger">House number already exists.</div>');
                    end_load();
                } else if (resp == 3) {
                    $('#msg').html('<div class="alert alert-danger">Error Problem Encountered on Database.</div>');
                    end_load();
                }
            }
        });
    });
    
    $('.edit_house').click(function() {
        start_load();
        
        var id = $(this).attr('data-id');
        var house_no = $(this).attr('data-house_no');
        var description = $(this).attr('data-description');
        var category_id = $(this).attr('data-category_id');
        var price = $(this).attr('data-price');
        var room_count = $(this).attr('data-count_room');
        var room_prefix_name = $(this).attr('data-room_prefix_name');
        
        var form = $('#manage-house');
        form.get(0).reset();
        form.find("[name='id']").val(id);
        form.find("[name='house_no']").val(house_no);
        form.find("[name='description']").val(description);
        form.find("[name='price']").val(price);
        form.find("[name='category_id']").val(category_id);
        form.find("[name='room_count']").val(room_count);
        form.find("[name=room_prefix_name]").val(room_prefix_name);
        
        // Fetch banner and multi-images
        $.ajax({
            url: 'fetch_images.php',
            method: 'GET',
            data: { house_id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var house_id = response.house_id;
                    if (response.banner) {
                        $('#current-primary-image').html(
                            '<div class="image-wrapper">' +
                                '<img src="' + response.banner + '" alt="Primary Image" class="img-thumbnail" />' +
                                '<button type="button" class="btn btn-warning text-white update-image" data-id="' + response.bannerId + '" data-path="' + response.banner + '" data-house-id="' + house_id + '">Update</button>' +
                                '<button type="button" class="btn btn-danger remove-image" data-id="' + response.bannerId + '" style="display: inline-block; margin-left: 10px;">Remove</button>' +
                            '</div>'
                        );
                    } else {
                        $('#current-primary-image').html('<p>No primary image available.</p>');
                    }
                    
                    // Clear previous room images
                    $('#current-room-images').empty();

                    response.images.forEach(function(imageData) {
                        var imagePath = imageData.path; // Image path
                        var roomName = imageData.name;   // Room name
                        var imageId = imageData.id;      // Image ID

                        $('#current-room-images').append(
                            '<div class="image-wrapper">' +
                                '<img src="' + imagePath + '" alt="' + roomName + '" class="img-thumbnail" />' +
                                '<p>' + roomName + '</p>' +
                                '<button type="button" class="btn btn-warning text-white update-image" data-id="' + imageId + '" data-path="' + imagePath + '" data-name="' + roomName + '" data-house-id="' + house_id + '">Update</button>' +
                                '<button type="button" class="btn btn-danger remove-image" data-id="' + imageId + '" style="display: inline-block; margin-left: 10px;">Remove</button>' +
                            '</div>'
                        );
                    });

                } else {
                    console.error('No images found for this house:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching images:', error); // Debugging: Log the error
            },
            complete: function() {
                end_load();
            }
        });
    });

    $(document).on('click', '.delete_house', function() {
        var houseId = $(this).attr('data-id');

        if (confirm("Are you sure you want to delete this house no " + houseId + "?")) {
            $.ajax({
                url: 'ajax.php?action=delete_house',
                method: 'POST',
                data: { id: houseId },
                success: function (resp) {
                    if (resp == 1) {
                        alert("Data successfully deleted");
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        alert("An error occurred. Please try again.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("AJAX request failed: " + textStatus + " - " + errorThrown);
                },
                complete: function() {
                    end_load(); // Assuming end_load() handles UI states or cleanup
                }
            });
        }
    });


    // Handle Remove Button Click
    $(document).on('click', '.remove-image', function() {
        var imageId = $(this).data('id');

        if (confirm("Are you sure you want to delete this image?")) {
            $.ajax({
                url: 'ajax.php?action=remove_image',
                method: 'POST',
                data: { imageId: imageId },
                success: function(response) {
                    if (response == 1) {
                        alert("Image successfully deleted.");
                    } else {
                        alert("An error occurred while deleting the image.");
                    } 
                },
                error: function() {
                    alert("An error occurred while sending the request. ". error);
                }
            });
        }
    });

    // Handle Update Button Click
    $(document).on('click', '.update-image', function() {
        var imageId = $(this).data('id');
        var imagePath = $(this).data('path');
        var imageName = $(this).data('name');
        var houseID = $(this).data('house-id');

        $('#update-house-no').val(houseID);
        $('#update-image-id').val(imageId);
        $('#update-image-path').val(imagePath);
        $('#update-image-name').val(imageName);


        $('#current-image').attr('src', imagePath ? imagePath : 'default-image.jpg');

        $('#update-image-modal').modal('show');
    });

    // Handle form submission
    $('#update-image-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        
        $.ajax({
            url: 'update_image.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("Raw response:", response);
                try {
                    // Ensure response is treated as JSON
                    var parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;
                    if (parsedResponse.success) {
                        alert("Image updated successfully!");
                        location.reload();
                    } else {
                        alert("An error occurred: " + (parsedResponse.message || "Please try again."));
                    }
                } catch (error) {
                    alert("Error parsing response: " + error.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });

</script>