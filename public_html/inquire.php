<?php 
include ('./db_connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM inquire";
$result = $conn->query($sql);
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <b>List of Inquiry</b>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div id="report">
                        <table id="inquiry-table" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Message</th>
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
                                        <td><?php echo htmlspecialchars($row['name']) ?></td>
                                        <td><?php echo htmlspecialchars($row['email']) ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']) ?></td>
                                        <td><?php echo htmlspecialchars($row['message']) ?></td>
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
    $('#inquiry-table').DataTable();
});
</script>
<script>
    $('.nav_collapse').click(function(){
        console.log($(this).attr('href'))
        $($(this).attr('href')).collapse()
    })
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>