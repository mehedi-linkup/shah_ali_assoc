<style>
    .balance-section{
        width: 100%;
        min-height: 150px;
        background-color: #f0f1d3;
        border: 1px solid #cfcfcf;
        text-align: center;
        padding: 25px 10px;
        border-radius: 5px;
    }

    .balance-section h3{
        margin: 0;
        padding: 0;
    }

    .account-section{
        display: flex;
        border: 1px solid #cfcfcf;
        border-radius: 5px;
        overflow:hidden;
        margin-bottom: 20px;
    }

    .account-section h3{
        margin: 10px 0;
        padding: 0;
    }

    .account-section .col1{
        background-color: #247195;
        color: white;
        flex: 1;
        display: flex;
        align-items: center; 
    }
    .account-section .col2{
        background-color: #def1f8;
        flex: 2;
        padding: 10px;
        align-items: center; 
        text-align:center;
    }

    
</style>
<?php 
    $totalGeneratedBill = array_reduce($collection_summary, function ($prev, $cur) {
        return $prev + $cur->generated_bill;
    }, 0);
    $totalReceivedBill = array_reduce($collection_summary, function ($prev, $cur) {
        return $prev + $cur->received_bill;
    }, 0);
    $totalDueBill = array_reduce($collection_summary, function ($prev, $cur) {
        return $prev + $cur->due_bill;
    }, 0);

?>
<div id="cashView">
    <div class="row">
        <div class="col-md-4">
            <div class="balance-section">
                <i class="fa fa-money fa-3x"></i>
                <h3>Generated Bill</h3>
                <h1><?php echo $this->session->userdata('Currency_Name');?> <?php echo number_format($totalGeneratedBill, 2);?></h1>
            </div>
        </div>

        <div class="col-md-4">
            <div class="balance-section">
                <i class="fa fa-bank fa-3x"></i>
                <h3>Received Bill</h3>
                <h1><?php echo $this->session->userdata('Currency_Name');?> <?php echo number_format($totalReceivedBill, 2);?></h1>
            </div>
        </div>

        <div class="col-md-4">
            <div class="balance-section">
                <i class="fa fa-dollar fa-3x"></i>
                <h3>Total Due</h3>
                <h1><?php echo $this->session->userdata('Currency_Name');?> <?php echo number_format($totalDueBill, 2);?></h1>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 25px;">
        <?php foreach($collection_summary as $account){?>
        <div class="col-md-3 col-xs-6">
            <div class="account-section">
                <div class="col1">
                    <i class="fa fa-dollar fa-3x"></i>
                </div>
                <div class="col2">
                    <h3><span style="font-weight: 700;color:green;text-align:center;font-size:18px;text-transform:uppercase;"><?php echo $account->month_name; ?></span> </h3>
                    <h6><span style="font-weight: 700;color:#d50695">Generated:</span> <?php echo $this->session->userdata('Currency_Name');?> <?php echo $account->generated_bill;?></h6> 
                    <h6><span style="font-weight: 700;color:#d50695">Received:</span> <?php echo $this->session->userdata('Currency_Name');?> <?php echo $account->received_bill;?></h6> 
                    <h6><span style="font-weight: 700;color:#d50695">Due:</span> <?php echo $this->session->userdata('Currency_Name');?> <?php echo $account->due_bill; ?></h6>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
