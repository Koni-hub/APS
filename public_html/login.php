<!DOCTYPE html>
<html lang="en">
    <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include ('./db_connect.php');
ob_start();
if (!isset($_SESSION['system'])) {
    $query_result = $conn->query("SELECT * FROM system_settings LIMIT 1");

    if ($query_result === false) {
        die('Query error: ' . $conn->error);
    }

    $system = $query_result->fetch_array(MYSQLI_ASSOC);

    if (is_array($system)) {
        foreach ($system as $k => $v) {
            $_SESSION['system'][$k] = $v;
        }
    } else {
        echo 'No system settings found.';
    }
}
ob_end_flush();

?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo $_SESSION['system']['name'] ?>
	</title>
	<link rel="stylesheet" href="styles.css">

	<?php include ('./header.php'); ?>

	<?php
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");
	?>

</head>

<body>
	<div class="login-page">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<center>
						<h1>SHERMELLE APARTMENT MANAGEMENT SYSTEM</h1>
					</center>

				</div>
				<div class="col-md-6">
					<div class="form-container">
						<h2><span>Admin Login</span></h2>
						<hr id="Indicator">
						<form id="login-form" style="text-align: center;">
							<div class="form-group">
								<input type="text" id="username" name="username" class="form-control" placeholder="Username">
							</div>
							<div class="form-group">
								<input type="password" id="password" name="password" class="form-control" placeholder="Password">
							</div>
							<button type="submit" class="btn btn-sm btn-block btn-wave col-md-12"
								style="border: 3px solid gold; background-color: gray; color: white; transition: border-color 0.2s;">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$('#login-form').submit(function (e) {
			e.preventDefault()
			$('#login-form button[type="submit"]').attr('disabled', true).html('Logging in...');
			if ($(this).find('.alert-danger').length > 0)
				$(this).find('.alert-danger').remove();
			$.ajax({
				url: 'ajax.php?action=login',
				method: 'POST',
				data: $(this).serialize(),
				error: err => {
					console.log(err)
					$('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
				},
				success: function (resp) {
					console.log('Error daw: ', resp);
					if (resp == 1) {
						location.href = 'index.php?page=home';
					} else if (resp == 0) {
						$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
						$('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
					} else  {
						$('#login-form').prepend('<div class="alert alert-danger">ERROR</div>')
					}
				}
			})
		});
	</script>
</body>

</html>