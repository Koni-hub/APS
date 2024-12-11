<?php 
include ('./db_connect.php');

function generate_invoice($conn) {
    $exists = true;
    $invoice = '';
    while ($exists) {
        $invoice = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $check = $conn->query("SELECT id FROM payments WHERE invoice = '$invoice'");

        if (!$check) {
            die("Database Query Failed: " . $conn->error);
        }

        $exists = $check->num_rows > 0;
    }
    return $invoice;
}

$invoice = generate_invoice($conn);

if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM payments where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="" class="control-label">Invoice: </label>
            <input type="text" class="form-control" name="invoice" value="<?php echo isset($invoice) ? $invoice : $auto_invoice ?>" readonly>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value=""></option>

            <?php 
            $tenant = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM tenants where status = 1 order by name asc");
            while($row=$tenant->fetch_assoc()):
            ?>
            <option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
            <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group" id="details">
            
        </div>
        <div class="form-group">
            <label for="" class="control-label">Amount Paid: </label>
            <input required type="number" class="form-control text-right" step="any" name="amount"  value="<?php echo isset($amount) ? $amount :'' ?>" >
        </div>
</div>
    </form>
</div>
<div id="details_clone" style="display: none">
    <div class='d'>
        <large><b>Details</b></large>
        <hr>
        <p>Tenant: <b class="tname"></b></p>
        <p>Monthly Rental Rate: <b class="price"></b></p>
        <p>New Price: <b class="new_price"></b></p>
        <p>Outstanding Balance: <b class="outstanding_amount"></b></p>
        <p>Overpaid Balance: <b class="overpaid_amount"></b></p>
        <p>Rent Started: <b class='rent_started'></b></p>
        <p>Payable Months: <b class="payable_months"></b></p>
        <hr>
    </div>
</div>
<script>
    $(document).ready(function(){
        if('<?php echo isset($id)? 1:0 ?>' == 1)
            $('#tenant_id').trigger('change') 
    });
    $('.select2').select2({
        placeholder:"Please Select Here",
        width:"100%"
    });
    
    $('#tenant_id').change(function() {
        if ($(this).val() <= 0) return false;

        start_load();
        $.ajax({
            url: 'ajax.php?action=get_tdetails',
            method: 'POST',
            data: { id: $(this).val(), pid: '<?php echo isset($id) ? $id : '' ?>' },
            success: function(resp) {
            if (resp) {
                resp = JSON.parse(resp);
                console.log(resp);

                var details = $('#details_clone .d').clone();
                var new_price;
                var overpaid_amount = 0;
                var outstanding_amount = 0;

                // Convert string values to numbers for calculations
                var payable = parseFloat(resp.payable.replace(/,/g, ''));
                var paid = parseFloat(resp.paid.replace(/,/g, ''));
                var price = parseFloat(resp.price.replace(/,/g, ''));

                if (paid > price) {
                    // (Advance payment)
                    overpaid_amount = paid - price; // Calculate the overpaid amount
                    new_price = price; // The new price remains the original price
                    console.log(`You have overpaid by: ${overpaid_amount}`);
                } else if (paid < price) {
                    // (Debit payment)
                    outstanding_amount = price - paid; // Calculate the outstanding amount
                    new_price = price; // The new price remains the original price
                    console.log(`You still owe: ${outstanding_amount}`);
                } else {
                    // (Exact payment)
                    new_price = price; // No changes; payment is exactly the price
                    console.log("Payment completed successfully.");
                }

                if (overpaid_amount) {
                    new_price = price - overpaid_amount; 
                }
                if (outstanding_amount) {
                    new_price = price + outstanding_amount; 
                }

                let formatted_price = new_price.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                let formatted_overpaid_amount = overpaid_amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                let formatted_outstanding_amount = outstanding_amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                details.find('.tname').text(resp.name);
                details.find('.price').text(resp.price);
                details.find('.new_price').text(formatted_price);
                details.find('.rent_started').text(resp.rent_started);
                details.find('.payable_months').text(resp.months);
                details.find('.overpaid_amount').text(formatted_overpaid_amount);
                details.find('.outstanding_amount').text(formatted_outstanding_amount);

                $('#details').html(details);
            }
        },
        complete: function() {
            end_load();
        }
        });
    });


    $('#manage-payment').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        let formData = new FormData($(this)[0]);
        if (!formData.has('invoice')) {
            formData.append('invoice', 'auto-generate'); // Placeholder if invoice is auto-generated on the server
        }
        $.ajax({
            url:'ajax.php?action=save_payment',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully saved.",'success')
                        setTimeout(function(){
                            location.reload()
                        },1000)
                }
            }
        })
    })
</script>