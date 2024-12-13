<?php include('db_connect.php');?>

<style>
	.dt-button {
            background-color: #28A745;
            color: #ffffff;
            border: none;
            padding: 0.5rem 5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .dt-button:hover {
            background-color: #10b981;
        }

        .dt-button:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.5);
        }
</style>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Payments</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_invoice">
					<i class="fa fa-plus"></i> New Invoice
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Date</th>
									<th class="">Tenant</th>
									<th class>Room ID</th>
									<th class>House Type</th>
									<th class="">Invoice</th>
									<th class="">Amount</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$invoices = $conn->query("SELECT p.*, 
													t.room_id, 
													CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, 
													c.name AS category_name 
												FROM payments p 
												INNER JOIN tenants t ON t.id = p.tenant_id 
												INNER JOIN categories c ON c.id 
												WHERE t.status = 1 
												ORDER BY DATE(p.date_created) DESC;
												");
								while($row=$invoices->fetch_assoc()):
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['room_id']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['category_name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['invoice']) ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo number_format($row['amount'],2) ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_invoice" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_invoice" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width: 100px;
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function() {
		$('table').DataTable({
				dom: 'Bfrtip',
			buttons: [
				{
					extend: 'print',
					 text: 'Print',
					exportOptions: {
						columns: ':not(:last-child)' // Exclude the last column from printing
					},
					title: function() {
                        // Get the current date in a specific format
                        let today = new Date();
                        let dateString = today.toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });
                        // Return the title with the current date
                        return 'List of Payments - ' + dateString;
                    },
					customize: function(win) {
						// Add footer for signature and "Reported By"
						$(win.document.body).append('<div style="text-align: center; margin-top: 20px;">' +
							'<div style="display: flex; justify-content: space-between; padding: 10px;">' +
							'<div style="flex: 1; text-align: left; padding-left: 10px;">Signature: </div>' +
							'<div style="flex: 1; text-align: center;">Reported By: </div>' +
							'</div>' +
							'</div>');
					}
				}
			],
		});
	});
	
	$('#new_invoice').click(function(){
		uni_modal("New invoice","manage_payment.php","mid-large")
		
	})
	$('.edit_invoice').click(function(){
		uni_modal("Manage invoice Details","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_invoice').click(function(){
		_conf("Are you sure to delete this invoice?","delete_invoice",[$(this).attr('data-id')])
	})
	
	function delete_invoice($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payment',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>

<!-- Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.print.min.js"></script>

<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script