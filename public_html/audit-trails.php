<?php 
include ('./db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user_audit_trails ORDER BY login_time DESC";
$result = $conn->query($sql);
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <b>List of Audit Trails</b>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div id="report">
                        <table id="audit-trails-table" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Login Time</th>
                                    <!-- <th>IP Address</th> -->
                                    <!-- <th>Device Info</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        if($result->num_rows > 0 ):
                                        while($row = $result->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo htmlspecialchars($row['username']) ?></td>
                                        <td><?php echo date('F j, Y, g:i a', strtotime($row['login_time'])) ?></td>
                                        <!-- <td><?php echo htmlspecialchars($row['ip_address']) ?></td> -->
                                        <!-- <td><?php echo htmlspecialchars($row['device_info']) ?></td> -->
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr>
                                        <th colspan="4"><center>No Data on audit trails.</center></th>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#audit-trails-table').DataTable();
});
</script>