<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include ('./db_connect.php');
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login() {
		// Extract POST variables
		extract($_POST);
	
		// Sanitize input to avoid SQL injection
		$username = $this->db->real_escape_string($username);
		$password = md5($password);
	
		// Prepare and execute login query
		$qry = $this->db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
		if ($qry === false) {
			die('Prepare failed: ' . htmlspecialchars($this->db->error));
		}
		$qry->bind_param("ss", $username, $password);
		$qry->execute();
		$result = $qry->get_result();
	
		if ($result->num_rows > 0) {
			// Fetch user data
			$row = $result->fetch_assoc();
			foreach ($row as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
	
			// Log user login
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$deviceInfo = $_SERVER['HTTP_USER_AGENT'];
	
			$logStmt = $this->db->prepare("INSERT INTO user_audit_trails (user_id, username, ip_address, device_info) VALUES (?, ?, ?, ?)");
			if ($logStmt === false) {
				die('Prepare failed: ' . htmlspecialchars($this->db->error));
			}
			$logStmt->bind_param("isss", $row['id'], $row['username'], $ipAddress, $deviceInfo);
			if (!$logStmt->execute()) {
				die('Execute failed: ' . htmlspecialchars($logStmt->error));
			}
			$logStmt->close();
	
			$qry->close();
			return 1;
		} else {
			$qry->close();
			return 0;
		}
	}
	
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
				}
				return 1;
		}else{
			return 3;
		}
	}

	function save_house() {
		extract($_POST);
		
		// Prepare the house data
		$data = " house_no = '$house_no' ";
		$data .= ", description = '$description' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", price = '$price' ";
		$data .= ", NumberOfRooms = '$room_count' ";
		$data .= ", roomPrefixName = '$room_prefix_name' ";

		$room_prefix_name = (string)$room_prefix_name;
		$roomCount = (int)$room_count;
		
		// Check if house number already exists
		if (empty($id)) {
			// Insert new house record
			$save = $this->db->query("INSERT INTO houses SET $data");
			$id = $this->db->insert_id; // Get the inserted house ID

			// Create table for rooms
			$tablePrefix = "roomtbl_" . $room_prefix_name;
			$createPrefixTBL = "CREATE TABLE $tablePrefix (
				id INT AUTO_INCREMENT PRIMARY KEY,
				room_name VARCHAR(255),
				room_status VARCHAR(255)
			)";
			if (!$this->db->query($createPrefixTBL)) {
				 return 3; // Error creating room table
			}

			// Insert rooms into the newly created table
			$stmt = $this->db->prepare("INSERT INTO `$tablePrefix` (room_name, room_status) VALUES (?, ?)");
			if (!$stmt) {
				die('Prepare failed: ' . $this->db->error); // Debugging
			}
			$roomStatus = "Available";
			for ($i = 1; $i <= $roomCount; $i++) {
				$roomName = "Room_" . $room_prefix_name . $i;
				$stmt->bind_param('ss', $roomName, $roomStatus);
				if (!$stmt->execute()) {
					die('Execute failed: ' . $stmt->error); // Debugging
				}
			}

		} else {
			// Update existing house record
			$save = $this->db->query("UPDATE houses SET $data WHERE id = $id");
		}
		
		if ($save) {
			$targetDir = "uploads/";
			
			if (!is_dir($targetDir)) {
				mkdir($targetDir, 0777, true);
			}
			
			// Handle primary image
			if (!empty($_FILES['primary_image']['name'])) {
				// Fetch the old primary image
				$oldPrimaryImage = $this->db->query("SELECT image_path FROM house_images WHERE house_id = $id AND is_primary = 1")->fetch_assoc();
				
				if ($oldPrimaryImage) {
					// Delete old primary image file
					$oldPrimaryImagePath = $oldPrimaryImage['image_path'];
					if (file_exists($oldPrimaryImagePath)) {
						unlink($oldPrimaryImagePath);
					}
					
					// Remove old primary image record
					$this->db->query("DELETE FROM house_images WHERE house_id = $id AND is_primary = 1");
				}
				
				// Upload the new primary image
				$primaryImagePath = $targetDir . $house_no . "_primary_" . basename($_FILES["primary_image"]["name"]);
				if (move_uploaded_file($_FILES["primary_image"]["tmp_name"], $primaryImagePath)) {
					// Insert new primary image record
					$this->db->query("INSERT INTO house_images (house_id, image_path, is_primary) VALUES ('$id', '$primaryImagePath', 1)");
				}
			}
			
			// Handle room images
			if (!empty($_FILES['room_images']['name'][0])) {
				$roomImages = $_FILES['room_images']['name'];
				$roomNames = $_POST['room_names']; // Ensure this is an array of names
				
				// Remove existing room images if updating
				$existingRoomImages = $this->db->query("SELECT image_path FROM house_images WHERE house_id = $id AND is_primary = 0")->fetch_all(MYSQLI_ASSOC);
				foreach ($existingRoomImages as $image) {
					$roomImagePath = $image['image_path'];
					if (file_exists($roomImagePath)) {
						unlink($roomImagePath);
					}
				}
				$this->db->query("DELETE FROM house_images WHERE house_id = $id AND is_primary = 0");
				
				for ($i = 0; $i < count($roomImages); $i++) {
					if (!empty($roomImages[$i])) {
						$roomImagePath = $targetDir . $house_no . "_room_" . basename($roomImages[$i]);
						if (move_uploaded_file($_FILES["room_images"]["tmp_name"][$i], $roomImagePath)) {
							// Insert new room images
							$this->db->query("INSERT INTO house_images (house_id, image_path, room_name, is_primary) VALUES ('$id', '$roomImagePath', '$roomNames[$i]', 0)");
						}
					}
				}
			}
			
			return 1; // Success
		}
	}
	

	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete) {
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function delete_house() {
		// Extract POST data
		extract($_POST);
		
		try {
			// Fetch image paths to delete them from the filesystem
			$result = $this->db->query("SELECT image_path FROM house_images WHERE house_id = $id");
			
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$image_path = $row['image_path'];
					if (file_exists($image_path)) {
						unlink($image_path);
					} else {
						return 0;
					}
				}
				$delete_house_images = $this->db->query("DELETE FROM house_images WHERE house_id = $id");

				if ($delete_house_images) {
					$deleteHouse = $this->db->query("DELETE FROM houses WHERE id = $id");

					if($deleteHouse) {
						return 1;
					} else {
						return 0;
					}
				} else {
					return 0;
				}
			}
		} catch (Exception $e) {
			return 0; // Failure
		}
	}
	
	function save_tenant() {
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", house_id = '$house_id' ";
		$date_in = date('Y-m-d');
		$data .= ", date_in = '$date_in' ";
		$data .= ", room_id = '$room_id'";
		$room_id = (string)$room_id;

		error_log("Room ID is: " . $room_id);

		// Check for existing room_id
		$query = "SELECT id FROM tenants WHERE room_id = '$room_id'";
		if(!empty($id)){
			$query .= " AND id != $id";
		}
		$result = $this->db->query($query);

		if($result && $result->num_rows > 0){
			return 2; // Room ID already exists
		}

		if(empty($id)){
			$save = $this->db->query("INSERT INTO tenants SET $data");
		}else{
			$save = $this->db->query("UPDATE tenants SET $data WHERE id = $id");
		}
	
		if ($save) {
			// Fetch roomPrefixName for the house_id
			$houseQuery = "SELECT roomPrefixName FROM houses WHERE id = ?";
			$houseStmt = $this->db->prepare($houseQuery);
			$houseStmt->bind_param('i', $house_id);
			$houseStmt->execute();
			$houseResult = $houseStmt->get_result();
	
			if ($houseResult->num_rows > 0) {
				error_log('Value of room ID '. $room_id);
				$house = $houseResult->fetch_assoc();
				$roomPrefixName = $house['roomPrefixName'];
	
				try {
					// Update room status in the dynamically named table
					$roomTable = "roomtbl_" . $this->db->real_escape_string($roomPrefixName);
					error_log('Value of table name with prefix: '. $roomTable);
				
					// Disable SQL safe mode
					$disableSafeModeQuery = "SET SQL_SAFE_UPDATES = 0";
					$this->db->query($disableSafeModeQuery);
				
					$updateRoomQuery = "UPDATE `$roomTable` SET room_status = 'Occupied' WHERE room_name = '" . $this->db->real_escape_string($room_id) . "'";
				
					// Execute the update query
					if ($this->db->query($updateRoomQuery) === FALSE) {
						error_log('Failed to update room status: ' . $this->db->error);
						return 2;
					}
				
					// Enable SQL safe mode again
					$enableSafeModeQuery = "SET SQL_SAFE_UPDATES = 1";
					$this->db->query($enableSafeModeQuery);
				
					error_log('Database Query: ' . $updateRoomQuery);
				
				} catch (Exception $error_db) {
					error_log('Database error: ' . $error_db->getMessage());
				}
			}
			return 1; // Successfully saved and room status updated
		} else {
			return 0; // Failed to save tenant
		}
	}
	
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}

	function get_tdetails() {
		$data = array();
	
		$stmt = $this->db->prepare("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, h.price FROM tenants t INNER JOIN houses h ON h.id = t.house_id WHERE t.id = ?");
		$stmt->bind_param('i', $_POST['id']);
		$stmt->execute();
		$tenant_result = $stmt->get_result();
		$tenant_info = $tenant_result->fetch_assoc();
	
		if ($tenant_info) {
			$name = $tenant_info['name'];
			$price = $tenant_info['price'];
			$date_in = $tenant_info['date_in'];
		} else {
			return json_encode(['error' => 'Tenant not found']);
		}
	
		$date_in_obj = new DateTime($date_in);
		$current_date = new DateTime();
		$interval = $current_date->diff($date_in_obj);
		$months = ($interval->y * 12) + $interval->m;
		$countMounts = $months ? $months : 1;
		$data['months'] = $countMounts;
	
		// Total payable amount
		$payable = $price;
		$data['payable'] = number_format($price, 2);

		// Calculate total paid
		$stmt = $this->db->prepare("SELECT (amount) AS paid FROM payments WHERE tenant_id = ?");
		$stmt->bind_param('i', $_POST['id']);
		error_log('Tenant ID: ' . $_POST['id']);
		$stmt->execute();
		$paid_result = $stmt->get_result();
		$paid = $paid_result->num_rows > 0 ? $paid_result->fetch_assoc()['paid'] : 0;
		$data['paid'] = number_format($paid, 2);
		$data['price'] = number_format($price, 2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y', strtotime($date_in));
		$data['next_month_payable'] = number_format($price , 2);

		// Logs error

		error_log('Paid: ' . $paid);
		error_log('Price for months: ' . $price);
		error_log('Months: ' . $countMounts);
		error_log('Next month Payable: ' . $price * $countMounts);
	
		return json_encode($data);
	}

	function save_payment(){
		extract($_POST);
		$data = "";
		
		if (empty($invoice)) {
            $invoice = generate_invoice($this->db);
        }
    
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		
		// Ensure `invoice` is included in the data
        if (!str_contains($data, 'invoice')) {
            $data .= ", invoice='$invoice' ";
        }
    
		if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set $data");
			$id=$this->db->insert_id;
		}else{
			$save = $this->db->query("UPDATE payments set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}

	function remove_image() {
		extract($_POST);
		
		$query = $this->db->query("SELECT image_path FROM house_images WHERE id = $imageId");
		
		if ($query && $query->num_rows > 0) {
			$result = $query->fetch_assoc();
			$imagePath = $result['image_path'];
			
			if (unlink($imagePath)) {
				$delete = $this->db->query("DELETE FROM house_images WHERE id = $imageId");
				
				if ($delete) {
					return 1;
				}
			}
		}
		
		return 0;
	}

	function fetch_rooms($houseID) {
		// Log the received houseID
		error_log("Received houseID: " . $houseID);
		// Fetch the roomPrefixName for the selected house
		$query = $this->db->prepare("SELECT roomPrefixName FROM houses WHERE id = ?");
		$query->bind_param('i', $houseID);
		$query->execute();
		$result = $query->get_result();
		
		if ($result->num_rows > 0) {
			$house = $result->fetch_assoc();
			$roomPrefixName = $house['roomPrefixName'];
	
			// Fetch rooms from the dynamically named table
			$tableName = "roomtbl_" . $roomPrefixName;
			$roomQuery = $this->db->prepare("SELECT id, room_name, room_status FROM `$tableName`");
			$roomQuery->execute();
			$roomResult = $roomQuery->get_result();
			
			$options = '';
			while ($room = $roomResult->fetch_assoc()) {
				$disabled = ($room['room_status'] === 'Occupied') ? 'disabled' : '';
				$options .= '<option value="' . htmlspecialchars($room['room_name']) . '" ' . $disabled . '>' . htmlspecialchars($room['room_name']) . '</option>';
			}
			
			return $options;
		} else {
			return '<option disabled>No rooms found.</option>';
		}
	}
}