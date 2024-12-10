<?php include('db_connect.php'); ?>

<?php
// Ensure no extra output or errors before JSON response
ob_start();
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParams = ['image_id', 'image_path', 'image_name', 'house_no'];
    foreach ($requiredParams as $param) {
        if (!isset($_POST[$param])) {
            $response['message'] = "Missing required parameter: $param";
            echo json_encode($response);
            ob_end_flush();
            exit;
        }
    }

    $imageId = intval($_POST['image_id']);
    $imagePath = trim($_POST['image_path']);
    $imageName = trim($_POST['image_name']);
    $newImageFile = isset($_FILES['image_file']) ? $_FILES['image_file'] : null;
    $isPrimary = isset($_POST['is_primary']) ? (int)$_POST['is_primary'] : 0;
    $houseNo = trim($_POST['house_no']);

    if (!empty($newImageFile['name'])) {
        // Delete the old image if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Handle image upload
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $response['message'] = 'Failed to create upload directory.';
                echo json_encode($response);
                ob_end_flush();
                exit;
            }
        }

        $imageExtension = pathinfo($newImageFile['name'], PATHINFO_EXTENSION);
        $newImageName = $houseNo . ($isPrimary ? '_primary_' : '_room_') . basename($newImageFile['name'], '.' . $imageExtension) . '.' . $imageExtension;
        $newImagePath = $uploadDir . $newImageName;

        if (move_uploaded_file($newImageFile['tmp_name'], $newImagePath)) {
            // Update image record
            if ($isPrimary) {
                // Set this image as primary
                $query = "UPDATE house_images SET image_path = ?, room_name = ?, is_primary = 1 WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $newImagePath, $imageName, $imageId);
                $stmt->execute();

                // Set other images as non-primary
                $conn->query("UPDATE house_images SET is_primary = 0 WHERE house_id = (SELECT house_id FROM house_images WHERE id = $imageId) AND id != $imageId");
            } else {
                // Update image record as room image
                $query = "UPDATE house_images SET image_path = ?, room_name = ?, is_primary = 0 WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $newImagePath, $imageName, $imageId);
                $stmt->execute();
            }
        } else {
            $response['message'] = 'Failed to upload new image.';
            echo json_encode($response);
            ob_end_flush();
            exit;
        }
    } else {
        // Update image record without uploading a new file
        $query = $isPrimary ? "UPDATE house_images SET room_name = ?, is_primary = 1 WHERE id = ?" : "UPDATE house_images SET room_name = ?, is_primary = 0 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $imageName, $imageId);
        $stmt->execute();
    }

    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Image updated successfully.';
    } else {
        $response['message'] = 'No changes made or database update failed: ' . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
ob_end_flush();
?>