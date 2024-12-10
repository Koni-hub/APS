<?php
ob_start();
include 'admin_class.php'; 
$crud = new Action();

// Check if 'action' is set in the request (GET or POST)
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : null);

// Check the action and call the appropriate method
if ($action) {
    switch ($action) {
        case 'login':
            $login = $crud->login();
            if ($login) echo $login;
            break;

        case 'login2':
            $login = $crud->login2();
            if ($login) echo $login;
            break;

        case 'logout':
            $logout = $crud->logout();
            if ($logout) echo $logout;
            break;

        case 'logout2':
            $logout = $crud->logout2();
            if ($logout) echo $logout;
            break;

        case 'save_user':
            $save = $crud->save_user();
            if ($save) echo $save;
            break;

        case 'delete_user':
            $delete = $crud->delete_user();
            if ($delete) echo $delete;
            break;

        case 'signup':
            $signup = $crud->signup();
            if ($signup) echo $signup;
            break;

        case 'update_account':
            $update = $crud->update_account();
            if ($update) echo $update;
            break;

        case 'save_settings':
            $save = $crud->save_settings();
            if ($save) echo $save;
            break;

        case 'save_category':
            $save = $crud->save_category();
            if ($save) echo $save;
            break;

        case 'delete_category':
            $delete = $crud->delete_category();
            if ($delete) echo $delete;
            break;

        case 'save_house':
            $save = $crud->save_house();
            if ($save) echo $save;
            break;

        case 'delete_house':
            $delete = $crud->delete_house();
            if ($delete) echo $delete;
            break;

        case 'save_tenant':
            $save = $crud->save_tenant();
            if ($save) echo $save;
            break;

        case 'delete_tenant':
            $delete = $crud->delete_tenant();
            if ($delete) echo $delete;
            break;

        case 'get_tdetails':
            $get = $crud->get_tdetails();
            if ($get) echo $get;
            break;

        case 'save_payment':
            $save = $crud->save_payment();
            if ($save) echo $save;
            break;

        case 'delete_payment':
            $delete = $crud->delete_payment();
            if ($delete) echo $delete;
            break;

        case 'remove_image':
            $removeImg = $crud->remove_image();
            if ($removeImg) echo $removeImg;
            break;

        case 'fetch_rooms':
            if (isset($_POST['house_id'])) {
                $houseID = intval($_POST['house_id']); // Sanitize input
                $fetchRooms = $crud->fetch_rooms($houseID); // Pass the houseID argument
                if ($fetchRooms) echo $fetchRooms;
            } else {
                echo '<option disabled>Select a house first</option>';
            }
            break;

        default:
            echo 'Invalid action';
            break;
    }
} else {
    echo 'No action specified';
}

ob_end_flush();
?>