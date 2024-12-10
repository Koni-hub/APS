<?php
// Database connection
include ('./db_connect.php');

// Fetch images
$house_id = isset($_GET['house_id']) ? intval($_GET['house_id']) : 0;

if ($house_id > 0) {
    $sql = "SELECT image_path, room_name FROM house_images WHERE house_id = ? AND is_primary = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $house_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = array();
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }

    $conn->close();
} else {
    echo 'Invalid house ID.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Images</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <a href="landing.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        ← Back
    </a>
    <h1 class="text-3xl font-bold mb-4">House Information</h1> 
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($images as $image): ?>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Room Image" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($image['room_name']); ?></h2>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>