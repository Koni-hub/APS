<?php
include('db_connect.php');

header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Retrieve house_id from query parameter
$house_id = isset($_GET['house_id']) ? intval($_GET['house_id']) : 0;

if ($house_id > 0) {
    // SQL query to select image_path, room_name, and is_primary
    $sql = "SELECT id, house_id, image_path, room_name, is_primary FROM house_images WHERE house_id = ? ORDER BY is_primary DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $house_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $images = [];
        $primaryImageFound = false;
        
        while ($row = $result->fetch_assoc()) {
            if ($row['is_primary']) {
                // Handle the primary image separately
                $response['banner'] = $row['image_path'];
                $response['house_id'] = $row['house_id'];
                $response['bannerId'] = $row['id'];
                $primaryImageFound = true;
            } else {
                // Add room images
                $images[] = [
                    'id' => $row['id'],
                    'path' => $row['image_path'],
                    'name' => $row['room_name']
                ];
            }
        }
        
        // If no primary image was found, use a default banner
        if (!$primaryImageFound) {
            $response['banner'] = 'path/to/default/banner.jpg';
        }
        
        $response['success'] = true;
        $response['images'] = $images;
    } else {
        $response['message'] = 'No images found for this house.';
    }
    
    $stmt->close();
} else {
    $response['message'] = 'Invalid house ID.';
}

$conn->close();

// Output response in JSON format
echo json_encode($response);
?>
