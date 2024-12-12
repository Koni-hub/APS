<?php include('./db_connect.php'); ?>
<?php
$result = $conn->query("SELECT COUNT(*) AS unread_count FROM inquire WHERE unread = 0");
$row = $result->fetch_assoc();
$unreadCount = $row['unread_count'];
  ?>
<style>
  .logo {
    margin: auto;
    font-size: 20px;
    background: black;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
  }

  .badge-notify {
    font-size: 0.8rem;
    padding: 0.3rem 0.6rem;
    color: white;
    background-color: #f44336;
  }

  .position-relative {
    position: relative;
  }

  .position-absolute {
    position: absolute;
  }

  .top-0 {
    top: 0;
  }

  .start-100 {
    left: 100%;
  }

  .translate-middle {
    transform: translate(-50%, -50%);
  }

  .badge-rounded-pill {
    border-radius: 50rem;
  }
</style>

<nav class="navbar navbar-light fixed-top bg-primary" style="padding:0;min-height: 3.5rem">
  <div class="container-fluid mt-2 mb-2">
    <div class="col-lg-12">
      <div class="col-md-1 float-left" style="display: flex;">

      </div>
      <div class="col-md-4 float-left text-white">
        <large><b>
            <?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?>
          </b></large>
      </div>
      <div class="float-right d-flex align-items-center">
        <!-- Notification Icon -->
        <div class="mr-4">
          <a href="index.php?page=inquire" class="text-white position-relative">
            <i class="fa fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
              <span class="badge-notify position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $unreadCount; ?>
              </span>
            <?php endif; ?>
          </a>
        </div>
        <!-- Account Settings Dropdown (Login Name) -->
        <div class="dropdown">
          <a href="#" class="text-white dropdown-toggle" id="account_settings" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <?php echo $_SESSION['login_name']; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
            <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
            <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
          </div>
        </div>
      </div>

    </div>

</nav>

<script>
  $('#manage_my_account').click(function() {
    uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>