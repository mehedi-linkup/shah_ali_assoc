<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Billing_model');
        $this->load->library('cart');
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->helper('form');
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Utility Bill Payment";
        $invoice = $this->mt->generateUtilityInvoice();
        $data['paymentId'] = 0;
        $data['invoice'] = $invoice;
        $data['content'] = $this->load->view('Administrator/utility/utility_bill_payment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getUtilityPayment() {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and up.payment_date between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->user_id) && $data->user_id != ''){
            $clauses .= " and up.saved_by = '$data->user_id'";
        }

        if(isset($data->month_id) && $data->month_id != ''){
            $clauses .= " and up.month_id = '$data->month_id'";
        }
    
        if(isset($data->id) && $data->id != 0 && $data->id != ''){
            $clauses .= " and up.id = '$data->id'";
            $paymentDetails = $this->db->query("
                SELECT upd.*,
                    s.Store_SlNo,
                    s.Store_No,
                    s.Store_Name,
                    s.floor_id,
                    s.meter_no,
                    s.square_feet,
                    o.Owner_Name,
                    re.Renter_Name,
                    f.Floor_Name,
                    f.Floor_Ranking,
                    bs.process_date,
                    bsd.electricity_unit,
                    bsd.current_unit,
                    bsd.generator_unit
                from tbl_utility_payment_details upd
                join tbl_bill_sheet_details bsd on bsd.id = upd.bill_detail_id
                join tbl_bill_sheet bs on bs.id = bsd.bill_id
                join tbl_store s on s.Store_SlNo = upd.store_id
                left join tbl_owner o on o.Owner_SlNo = s.owner_id
                left join tbl_renter re on re.Renter_SlNo = s.renter_id
                left join tbl_floor f on f.Floor_SlNo = s.floor_id
                where upd.status = 'a'
                and upd.utility_payment_id = '$data->id'
            ")->result();
            $res['paymentDetails'] = $paymentDetails;
        }

        $payments = $this->db->query("
            SELECT up.*,
            m.month_name,
            u.User_Name

            from tbl_utility_payment up
            join tbl_month m on m.month_id = up.month_id
            left join tbl_user u on u.User_SlNo = up.saved_by
            where up.status = 'a'
            and up.branch_id = ?
            $clauses
            order by up.id desc
        ", $this->session->userdata("BRANCHid"))->result();

        $res['payments'] = $payments;

        echo json_encode($res);
    }

    public function getUtilityPaymentsRecord()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and bs.payment_date between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->user_id) && $data->user_id != ''){
            $clauses .= " and bs.saved_by = '$data->user_id'";
        }

        if(isset($data->monthId) && $data->monthId != ''){
            $clauses .= " and bs.month_id = '$data->monthId'";
        }
        if(isset($data->month_id) && $data->month_id != ''){
            $clauses .= " and bs.month_id = '$data->monthId'";
        }
        $clausesDetails = "";
        if(isset($data->storeId) && $data->storeId != ''){
            $clausesDetails .= " and bd.store_id = '$data->storeId'";
        }
        if(isset($data->renterId) && $data->renterId != ''){
            $clausesDetails .= " and s.renter_id = '$data->renterId'";
        }
        if(isset($data->floorId) && $data->floorId != ''){
            $clausesDetails .= " and s.floor_id = '$data->floorId'";
        }



        $payments = $this->db->query("
            SELECT bs.*,
            m.month_name,
            u.User_Name

            from tbl_utility_payment bs
            join tbl_month m on m.month_id = bs.month_id
            left join tbl_user u on u.User_SlNo = bs.saved_by

            where bs.status = 'a'
            and bs.branch_id = ?
            $clauses
            order by bs.id desc
        ", $this->session->userdata("BRANCHid"))->result();

        if(isset($data->details)){
            foreach($payments as $payment){
                $payment->details = $this->db->query("
                    SELECT bd.*,
                    s.Store_SlNo,
                    s.Store_No,
                    s.Store_Name,
                    s.floor_id,
                    o.Owner_Name,
                    re.Renter_Name,
                    f.Floor_Name,
                    f.Floor_Ranking

                    from tbl_utility_payment_details bd
                    join tbl_store s on s.Store_SlNo = bd.store_id
                    left join tbl_owner o on o.Owner_SlNo = s.owner_id
                    left join tbl_renter re on re.Renter_SlNo = s.renter_id
                    left join tbl_floor f on f.Floor_SlNo = s.floor_id
                    where bd.status = 'a'
                    and bd.utility_payment_id = '$payment->id'
                    $clausesDetails
                ")->result();
            }
        }

        echo json_encode($payments);
    }
    public function utilityEdit($paymentId){
        $data['title'] = "Utility Bill Payment";
        $data['paymentId'] = $paymentId;
        $data['invoice'] = $this->db->query("select invoice from tbl_utility_payment where id = ?", $paymentId)->row()->invoice;
        $data['content'] = $this->load->view('Administrator/utility/utility_bill_payment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function purchaseExcel()
    {
        $this->cart->destroy();
        $data['title'] = "Purchase Order";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_order_excel', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function createProductSheet()
    {
        $this->cart->destroy();
        $data['title'] = "Create Product Sheet";
        $data['content'] = $this->load->view('Administrator/purchase/product_sheet', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function excelFileFormate()
    {
        $data['title'] = "Purchase Order";
        $data['content'] = $this->load->view('Administrator/purchase/excel_file_foramate', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function returns()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['returnId'] = 0;
        $data['title'] = "Purchase Return";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_return', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function purchaseReturnEdit($returnId)
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['returnId'] = $returnId;
        $data['title'] = "Purchase Return";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_return', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function damage_entry()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Damage Entry";
        $data['damageCode'] = $this->mt->generateDamageCode();
        $data['content'] = $this->load->view('Administrator/purchase/damage_entry', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function stock()
    {
        $data['title'] = "Purchase Stock List";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_stock_list', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function Selectsuplier()
    {
    
        
        $sid = $this->input->post('sid');
        $query = $this->db->query("SELECT * FROM tbl_supplier where Supplier_SlNo = '$sid'");
        $data['Supplier'] = $query->row();
        $this->load->view('Administrator/purchase/ajax_suplier', $data);
    }

    function SelectPruduct()
    {
        $ProID = $this->input->post('ProID');
        $querys = $this->db->query("
            SELECT 
            tbl_product.*,
            tbl_unit.*, 
            tbl_brand.*  
            FROM tbl_product
            left join tbl_unit on tbl_unit.Unit_SlNo=tbl_product.Unit_ID
            left join tbl_brand on tbl_brand.brand_SiNo=tbl_product.brand
            where tbl_product.Product_SlNo = '$ProID'
        ");

        $data['Product'] = $querys->row();
        $this->load->view('Administrator/purchase/ajax_product', $data);
    }

    function SelectCat()
    {
        $data['ProCat'] = $this->input->post('ProCat');
        $this->load->view('Administrator/purchase/ajax_CatWiseProduct', $data);
    }

    public function addUtilityPayment()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $data = json_decode($this->input->raw_input_stream);

            // echo json_encode($data);
            // return;
            $invoice = $data->payment->invoice;
            $invoiceCount = $this->db->query("select * from tbl_utility_payment where invoice = ?", $invoice)->num_rows();
            if($invoiceCount != 0){
                $invoice = $this->mt->generateUtilityInvoice();
            }

            $utility = array(
                'invoice' => $invoice,
                'payment_date' => $data->payment->date,
                'month_id' => $data->payment->month_id,
                'total_electricity_bill' => $data->payment->total_electricity_bill,
                'total_generator_bill' => $data->payment->total_generator_bill,
                'total_ac_bill' => $data->payment->total_ac_bill,
                'total_others_bill' => $data->payment->total_others_bill,
                'total_late_fee' => $data->payment->total_late_fee,
                'total_payment' => $data->payment->total_payment,
                'total_due' => $data->payment->total_due,
                'status' => 'a',
                'saved_by' => $this->session->userdata("FullName"),
                'saved_at' => date('Y-m-d H:i:s'),
                'branch_id' => $this->session->userdata('BRANCHid')
            );

            $this->db->insert('tbl_utility_payment', $utility);
            $utilityId = $this->db->insert_id();

            $utilityDetailsArr = [];

            foreach($data->storeBills as $utility){
                $utilityDetails = array(
                    'utility_payment_id' => $utilityId,
                    'bill_detail_id' => $utility->bill_detail_id,
                    'store_id' => $utility->Store_SlNo,
                    'electricity_bill' => $utility->electricity_due,
                    'generator_bill' => $utility->generator_due,
                    'ac_bill' => $utility->ac_due,
                    'others_bill' => $utility->others_due,
                    'late_fee' => @$utility->late_fee_payment ? $utility->late_fee_payment : '0' ,
                    'payment' => $utility->payment,
                    'comment' => @$utility->comment? $utility->comment : '',
                    'status' => 'a',
                    'saved_by' => $this->session->userdata("FullName"),
                    'saved_at' => date('Y-m-d H:i:s'),
                    'branch_id' => $this->session->userdata('BRANCHid')
                );

                $this->db->insert('tbl_utility_payment_details', $utilityDetails);

                $utilityPaymentDetailId = $this->db->insert_id();

                array_push($utilityDetailsArr, $utilityPaymentDetailId);

                $transactionCount = $this->db->query("select * from tbl_transaction where store_id = ? and month_id = ? and branch_id = ?", [$utility->Store_SlNo, $data->payment->month_id, $this->session->userdata('BRANCHid')])->num_rows();
                if($transactionCount == 0){
                    $transaction = array(
                        'store_id' => $utility->Store_SlNo,
                        'month_id' => $data->payment->month_id,
                        'electricity' => $utility->electricity_due,
                        'generator' => $utility->generator_due,
                        'ac' => $utility->ac_due,
                        'others' => $utility->others_due,
                        'late_fee' => @$utility->late_fee_payment ? $utility->late_fee_payment : '0',
                        'payment' => $utility->payment,
                        'due' => $utility->due,
                        'branch_id' => $this->session->userdata('BRANCHid')
                    );

                    $this->db->insert('tbl_transaction', $transaction);
                } else {
                    $this->db->query("
                        UPDATE tbl_transaction 
                        SET 
                            electricity = electricity + ?, 
                            generator = generator + ?, 
                            ac = ac + ?, 
                            others = others + ?, 
                            late_fee = late_fee + ?, 
                            payment = payment + ?, 
                            due = due + ? 
                        WHERE 
                            store_id = ? 
                            AND month_id = ?
                            AND branch_id = ?
                    ", [
                        $utility->electricity_due, 
                        $utility->generator_due, 
                        $utility->ac_due, 
                        $utility->others_due, 
                        @$utility->late_fee ? $utility->late_fee : '0' , 
                        $utility->payment, 
                        $utility->due, 
                        $utility->Store_SlNo,
                        $data->payment->month_id,
                        $this->session->userdata('BRANCHid')
                    ]);
                }
            }

            $res=['success'=>true, 'message'=>'Payment Success', 'utilityId'=> $utilityId];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateUtilityPayment()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $data = json_decode($this->input->raw_input_stream);

            // echo json_encode($data);
            // return; 

            $paymentId = $data->payment->id;
            
            $utility = array(
                'invoice' => $data->payment->invoice,
                'payment_date' => $data->payment->date,
                'month_id' => $data->payment->month_id,
                'total_electricity_bill' => $data->payment->total_electricity_bill,
                'total_generator_bill' => $data->payment->total_generator_bill,
                'total_ac_bill' => $data->payment->total_ac_bill,
                'total_others_bill' => $data->payment->total_others_bill,
                'total_late_fee' => $data->payment->total_late_fee,
                'total_payment' => $data->payment->total_payment,
                'total_due' => $data->payment->total_due,
                'status' => 'a',
                'updated_by' => $this->session->userdata("FullName"),
                'updated_at' => date('Y-m-d H:i:s'),
                'branch_id' => $this->session->userdata('BRANCHid')
            );

            $this->db->where('id', $paymentId);
            $this->db->update('tbl_utility_payment', $utility);

            foreach($data->storeBills as $utility){
                $utilityDetails = array(
                    'utility_payment_id' => $paymentId,
                    'bill_detail_id' => $utility->bill_detail_id,
                    'store_id' => $utility->Store_SlNo,
                    'electricity_bill' => $utility->electricity_due,
                    'generator_bill' => $utility->generator_due,
                    'ac_bill' => $utility->ac_due,
                    'others_bill' => $utility->others_due,
                    'late_fee' => @$utility->late_fee_payment ? $utility->late_fee_payment : '0' ,
                    'payment' => $utility->payment,
                    'comment' => @$utility->comment? $utility->comment : '',
                    'status' => 'a',
                    'updated_by' => $this->session->userdata("FullName"),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'branch_id' => $this->session->userdata('BRANCHid')
                );

                $this->db->where('id', $utility->old_payment_detail_id);
                $this->db->update('tbl_utility_payment_details', $utilityDetails);

                $transactionCount = $this->db->query("select * from tbl_transaction where store_id = ? and month_id = ? and branch_id = ?", [$utility->Store_SlNo, $data->payment->month_id, $this->session->userdata('BRANCHid')])->num_rows();
                if($transactionCount == 0){
                    $transaction = array(
                        'store_id' => $utility->Store_SlNo,
                        'month_id' => $data->payment->month_id,
                        'electricity' => $utility->electricity_due,
                        'generator' => $utility->generator_due,
                        'ac' => $utility->ac_due,
                        'others' => $utility->others_due,
                        'late_fee' => @$utility->late_fee_payment ? $utility->late_fee_payment : '0',
                        'payment' => $utility->payment,
                        'due' => $utility->due,
                        'branch_id' => $this->session->userdata('BRANCHid')
                    );

                    $this->db->insert('tbl_transaction', $transaction);
                } else {
                    $this->db->query("
                        UPDATE tbl_transaction 
                        SET 
                            electricity = electricity + ?, 
                            generator = generator + ?, 
                            ac = ac + ?, 
                            others = others + ?, 
                            late_fee = late_fee + ?, 
                            payment = payment + ?, 
                            due = due + ? 
                        WHERE 
                            store_id = ? 
                            AND month_id = ?
                            AND branch_id = ?
                    ", [
                        $utility->electricity_due, 
                        $utility->generator_due, 
                        $utility->ac_due, 
                        $utility->others_due, 
                        @$utility->late_fee ? $utility->late_fee : '0' , 
                        $utility->payment, 
                        $utility->due, 
                        $utility->Store_SlNo,
                        $data->payment->month_id,
                        $this->session->userdata('BRANCHid')
                    ]);
                }
            }
            $res=['success'=>true, 'message'=>'Payment Success', 'utilityId'=> $paymentId];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function purchase_bill()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Purchase Invoice";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_bill', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function purchase_invoice_search()
    {
        $id = $this->input->post('purchasemsid');
        $data['PurchID'] = $id;
        $this->session->set_userdata('PurchID',$id);
        $data['purchase'] = $this->Purchase_model->single_purchase_master_info($id);
        $data['products'] = $this->Purchase_model->invoice_wise_purchase_products($id);
        $this->load->view('Administrator/purchase/purchase_invoice_search', $data);
    }

    public function payment_record()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Payment Record";
        $data['content'] = $this->load->view('Administrator/utility/payment_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function ac_payment_record()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Ac Record";
        $data['content'] = $this->load->view('Administrator/utility/ac_payment_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getPurchaseRecord(){
        $data = json_decode($this->input->raw_input_stream);
        $branchId = $this->session->userdata("BRANCHid");
        $clauses = "";
        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and pm.PurchaseMaster_OrderDate between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->userFullName) && $data->userFullName != ''){
            $clauses .= " and pm.AddBy = '$data->userFullName'";
        }

        if(isset($data->supplierId) && $data->supplierId != ''){
            $clauses .= " and pm.Supplier_SlNo = '$data->supplierId'";
        }

        $purchases = $this->db->query("
            select 
                pm.*,
                s.Supplier_Code,
                s.Supplier_Name,
                s.Supplier_Mobile,
                s.Supplier_Address,
                br.Brunch_name
            from tbl_purchasemaster pm
            left join tbl_supplier s on s.Supplier_SlNo = pm.Supplier_SlNo
            left join tbl_brunch br on br.brunch_id = pm.PurchaseMaster_BranchID
            where pm.PurchaseMaster_BranchID = '$branchId'
            and pm.status = 'a'
            $clauses
        ")->result();

        foreach($purchases as $purchase){
            $purchase->purchaseDetails = $this->db->query("
                select 
                    pd.*,
                    p.Product_Name,
                    pc.ProductCategory_Name
                from tbl_purchasedetails pd
                join tbl_product p on p.Product_SlNo = pd.Product_IDNo
                join tbl_productcategory pc on pc.ProductCategory_SlNo = p.ProductCategory_ID
                where pd.PurchaseMaster_IDNo = ?
                and pd.Status != 'd'
            ", $purchase->PurchaseMaster_SlNo)->result();
        }

        echo json_encode($purchases);
    }

    public function getPurchaseDetails(){
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->supplierId) && $data->supplierId != ''){
            $clauses .= " and s.Supplier_SlNo = '$data->supplierId'";
        }

        if(isset($data->productId) && $data->productId != ''){
            $clauses .= " and p.Product_SlNo = '$data->productId'";
        }

        if(isset($data->categoryId) && $data->categoryId != ''){
            $clauses .= " and pc.ProductCategory_SlNo = '$data->categoryId'";
        }

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and pm.PurchaseMaster_OrderDate between '$data->dateFrom' and '$data->dateTo'";
        }

        $saleDetails = $this->db->query("
            select 
                pd.*,
                p.Product_Name,
                pc.ProductCategory_Name,
                pm.PurchaseMaster_InvoiceNo,
                pm.PurchaseMaster_OrderDate,
                s.Supplier_Code,
                s.Supplier_Name
            from tbl_purchasedetails pd
            join tbl_product p on p.Product_SlNo = pd.Product_IDNo
            join tbl_productcategory pc on pc.ProductCategory_SlNo = p.ProductCategory_ID
            join tbl_purchasemaster pm on pm.PurchaseMaster_SlNo = pd.PurchaseMaster_IDNo
            join tbl_supplier s on s.Supplier_SlNo = pm.Supplier_SlNo
            where pd.Status != 'd'
            and pd.PurchaseDetails_branchID = '$this->brunch'
            $clauses
        ")->result();

        echo json_encode($saleDetails);
    }

    /*Delete Purchase Record*/
    public function  deletePurchase(){
        $res = ['success'=>false, 'message'=>''];
        try{
            $data = json_decode($this->input->raw_input_stream);
            $purchase = $this->db->select('*')->where('PurchaseMaster_SlNo', $data->purchaseId)->get('tbl_purchasemaster')->row();
            if($purchase->status != 'a'){
                $res = ['success'=>false, 'message'=>'Purchase not found'];
                echo json_encode($res);
                exit;
            }
            
            $returnCount = $this->db->query("select * from tbl_purchasereturn pr where pr.PurchaseMaster_InvoiceNo = ? and pr.Status = 'a'", $purchase->PurchaseMaster_InvoiceNo)->num_rows();
            if($returnCount != 0) {
                $res = ['success'=>false, 'message'=>'Unable to delete. Purchase return found'];
                echo json_encode($res);
                exit;
            }

            /*Get Purchase Details Data*/
            $purchaseDetails = $this->db->select('Product_IDNo,PurchaseDetails_TotalQuantity,PurchaseDetails_TotalAmount')->where('PurchaseMaster_IDNo',$data->purchaseId)->get('tbl_purchasedetails')->result();

            foreach($purchaseDetails as $detail) {
                $stock = $this->mt->productStock($detail->Product_IDNo);
                if($detail->PurchaseDetails_TotalQuantity > $stock) {
                    $res = ['success'=>false, 'message'=>'Product out of stock, Purchase can not be deleted'];   
                    echo json_encode($res);
                    exit;
                }
            }

            foreach($purchaseDetails as $product){
                $previousStock = $this->mt->productStock($product->Product_IDNo);

                $this->db->query("
                    update tbl_currentinventory 
                    set purchase_quantity = purchase_quantity - ? 
                    where product_id = ?
                    and branch_id = ?
                ", [$product->PurchaseDetails_TotalQuantity, $product->Product_IDNo, $this->session->userdata('BRANCHid')]);

                $this->db->query("
                    update tbl_product set 
                    Product_Purchase_Rate = (((Product_Purchase_Rate * ?) - ?) / ?)
                    where Product_SlNo = ?
                ", [
                    $previousStock,
                    $product->PurchaseDetails_TotalAmount,
                    ($previousStock - $product->PurchaseDetails_TotalQuantity),
                    $product->Product_IDNo
                ]);
            }

            /*Delete Purchase Details*/
            $this->db->set('Status', 'd')->where('PurchaseMaster_IDNo',$data->purchaseId)->update('tbl_purchasedetails');

            /*Delete Purchase Master Data*/
            $this->db->set('status', 'd')->where('PurchaseMaster_SlNo',$data->purchaseId)->update('tbl_purchasemaster');
            
            $res = ['success'=>true, 'message'=>'Successfully deleted'];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }
        
        echo json_encode($res);
    }


    public function purchase_supplierName()
    {
        $id = $this->input->post('Supplierid');
        $sql = mysql_query("SELECT * FROM tbl_supplier WHERE Supplier_SlNo = '$id'");
        $row = mysql_fetch_array($sql);
        $datas['SupplierName'] = $row['Supplier_Name'];
        $this->load->view('Administrator/purchase/purchase_supplier_name', $datas);
    }

    function search_purchase_record()
    {
        $datas['title'] = 'Product';
        $dAta['searchtype'] = $searchtype = $this->input->post('searchtype');
        $dAta['productsearchtype'] = $productsearchtype = $this->input->post('productsearchtype');
        $dAta['Purchase_startdate'] = $Purchase_startdate = $this->input->post('Purchase_startdate');
        $dAta['Purchase_enddate'] = $Purchase_enddate = $this->input->post('Purchase_enddate');
        $dAta['Supplierid'] = $Supplierid = $this->input->post('Supplierid');
        $dAta['Productid'] = $Productid = $this->input->post('Productid');
        $this->session->set_userdata($dAta);

        $BranchID = $this->session->userdata('BRANCHid');

        if ($searchtype == "All") {
            $sql = "SELECT tbl_purchasemaster.*, tbl_supplier.* FROM tbl_purchasemaster left join tbl_supplier on tbl_supplier.Supplier_SlNo = tbl_purchasemaster.Supplier_SlNo WHERE tbl_purchasemaster.PurchaseMaster_BranchID='$BranchID' and tbl_purchasemaster.status = 'a' AND tbl_purchasemaster.PurchaseMaster_OrderDate between '$Purchase_startdate' AND '$Purchase_enddate'";
        } elseif ($searchtype == "Supplier") {
            $sql = "SELECT tbl_purchasemaster.*, tbl_supplier.* FROM tbl_purchasemaster left join tbl_supplier on tbl_supplier.Supplier_SlNo = tbl_purchasemaster.Supplier_SlNo WHERE tbl_purchasemaster.Supplier_SlNo = '$Supplierid' and tbl_purchasemaster.status = 'a' and  tbl_purchasemaster.PurchaseMaster_OrderDate between  '$Purchase_startdate' and '$Purchase_enddate'";
        }
        /* else if($searchtype == "Product"){
            $sql = "SELECT tbl_purchasemaster.*,tbl_purchasedetails.*, tbl_supplier.* FROM tbl_purchasemaster left join tbl_purchasedetails on tbl_purchasedetails.PurchaseMaster_IDNo = tbl_purchasemaster.PurchaseMaster_SlNo left join tbl_supplier on tbl_supplier.Supplier_SlNo = tbl_purchasemaster.Supplier_SlNo WHERE tbl_purchasedetails.Product_IDNo = '$Productid' and  tbl_purchasemaster.PurchaseMaster_OrderDate between '$Purchase_startdate' and '$Purchase_enddate'";
        } */
        $result = $this->db->query($sql);
        $datas["record"] = $result->result();
        $this->load->view('Administrator/purchase/purchase_record_list', $datas);
        //$this->load->view('Administrator/index',$datas);
    }

    function purchase_stock()
    {
        $data['title'] = "Purchase Stock";
        $data['content'] = $this->load->view('Administrator/stock/purchase_stock', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function addDamage()
    {
        $res = ['success'=>false, 'message'=>''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $damage = array(
                'Damage_InvoiceNo' => $data->Damage_InvoiceNo,
                'Damage_Date' => $data->Damage_Date,
                'Damage_Description' => $data->Damage_Description,
                'status' => 'a',
                'AddBy' => $this->session->userdata("FullName"),
                'AddTime' => date('Y-m-d H:i:s'),
                'Damage_brunchid' => $this->session->userdata('BRANCHid')
            );

            $this->db->insert('tbl_damage', $damage);
            $damageId = $this->db->insert_id();

            $damageDetails = array(
                'Damage_SlNo' => $damageId,
                'Product_SlNo' => $data->Product_SlNo,
                'DamageDetails_DamageQuantity' => $data->DamageDetails_DamageQuantity,
                'damage_rate' => $data->damage_rate,
                'damage_amount' => $data->damage_amount,
                'status' => 'a',
                'AddBy' => $this->session->userdata("FullName"),
                'AddTime' => date('Y-m-d H:i:s')
            );

            $this->db->insert('tbl_damagedetails', $damageDetails);

            $this->db->query("
                update tbl_currentinventory ci 
                set ci.damage_quantity = ci.damage_quantity + ? 
                where product_id = ? 
                and ci.branch_id = ?
            ", [$data->DamageDetails_DamageQuantity, $data->Product_SlNo, $this->session->userdata('BRANCHid')]);

            $res = ['success'=>true, 'message'=>'Damage entry success', 'newCode' => $this->mt->generateDamageCode()];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateDamage(){
        $res = ['success'=>false, 'message'=>''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $damageId = $data->Damage_SlNo;

            $damage = array(
                'Damage_InvoiceNo' => $data->Damage_InvoiceNo,
                'Damage_Date' => $data->Damage_Date,
                'Damage_Description' => $data->Damage_Description,
                'UpdateBy' => $this->session->userdata("FullName"),
                'UpdateTime' => date('Y-m-d H:i:s')
            );

            $this->db->where('Damage_SlNo', $damageId)->update('tbl_damage', $damage);

            $oldProduct = $this->db->query("select * from tbl_damagedetails where Damage_SlNo = ?", $damageId)->row();

            $this->db->query("
                update tbl_currentinventory ci 
                set ci.damage_quantity = ci.damage_quantity - ? 
                where product_id = ? 
                and ci.branch_id = ?
            ", [$oldProduct->DamageDetails_DamageQuantity, $oldProduct->Product_SlNo, $this->session->userdata('BRANCHid')]);

            $damageDetails = array(
                'Product_SlNo' => $data->Product_SlNo,
                'DamageDetails_DamageQuantity' => $data->DamageDetails_DamageQuantity,
                'damage_rate' => $data->damage_rate,
                'damage_amount' => $data->damage_amount,
                'UpdateBy' => $this->session->userdata("FullName"),
                'UpdateTime' => date('Y-m-d H:i:s')
            );

            $this->db->where('Damage_SlNo', $damageId)->update('tbl_damagedetails', $damageDetails);
            
            $this->db->query("
                update tbl_currentinventory ci 
                set ci.damage_quantity = ci.damage_quantity + ? 
                where product_id = ? 
                and ci.branch_id = ?
            ", [$data->DamageDetails_DamageQuantity, $data->Product_SlNo, $this->session->userdata('BRANCHid')]);

            $res = ['success'=>true, 'message'=>'Damage updated successfully', 'newCode' => $this->mt->generateDamageCode()];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function getDamages(){
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->damageId) && $data->damageId != ''){
            $clauses .= " and d.Product_SlNo = '$data->damageId'";
        }
        $damages = $this->db->query("
            select
                dd.Product_SlNo,
                dd.DamageDetails_DamageQuantity,
                dd.damage_rate,
                dd.damage_amount,
                d.Damage_SlNo,
                d.Damage_InvoiceNo,
                d.Damage_Date,
                d.Damage_Description,
                p.Product_Code,
                p.Product_Name
            from tbl_damagedetails dd
            join tbl_damage d on d.Damage_SlNo = dd.Damage_SlNo
            join tbl_product p on p.Product_SlNo = dd.Product_SlNo
            where d.status = 'a' and dd.status = 'a'
            $clauses
        ")->result();

        echo json_encode($damages);
    }

    public function deleteDamage(){
        $res = ['success'=>false, 'message'=>''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $damageId = $data->damageId;

            $oldProduct = $this->db->query("select * from tbl_damagedetails where Damage_SlNo = ?", $damageId)->row();
            $this->db->query("
                update tbl_currentinventory ci 
                set ci.damage_quantity = ci.damage_quantity - ? 
                where product_id = ? 
                and ci.branch_id = ?
            ", [$oldProduct->DamageDetails_DamageQuantity, $oldProduct->Product_SlNo, $this->session->userdata('BRANCHid')]);

            $this->db->where('Damage_SlNo', $damageId)->update('tbl_damage', ['status'=>'d']);
            $this->db->where('Damage_SlNo', $damageId)->update('tbl_damagedetails', ['status'=>'d']);

            $res = ['success'=>true, 'message'=>'Damage deleted successfully', 'newCode' => $this->mt->generateDamageCode()];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function damage_product_list()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Product damage list";
        $data['products'] = $this->db->query("select * from tbl_product p where p.status = 'a' and p.is_service = 'false'")->result();
        $data['content'] = $this->load->view('Administrator/purchase/damage_list', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function damage_select_product()
    {
        $prod_id = $this->input->post('prod_id');
        if ($prod_id == 'All') {
            $data['records'] = $this->Product_model->all_damage_product_list();
        } else {
            $data['records'] = $this->Product_model->demage_poduct_list_by_product_id($prod_id);
        }
        $this->load->view('Administrator/purchase/damage_list_search', $data);
    }

    public function purchaseInvoicePrint($purchaseId)
    {
        $data['title'] = "Purchase Invoice";
        $data['purchaseId'] = $purchaseId;
        $data['content'] = $this->load->view('Administrator/purchase/purchase_to_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function returns_list()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Purchase Return";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_return_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function purchase_return_record()
    {
        $datas['searchtype'] = $searchtype = $this->input->post('searchtype');
        $datas['productID'] = $productID = $this->input->post('productID');
        $datas['startdate'] = $startdate = $this->input->post('startdate');
        $datas['enddate'] = $enddate = $this->input->post('enddate');
        $this->session->set_userdata($datas);
        //echo "<pre>";print_r($datas);exit;
        $this->load->view('Administrator/purchase/return_list', $datas);
    }

    public function purchase_update_form($PurchaseMaster_SlNo)
    {
        $this->cart->destroy();
        $data['title'] = "Product Purchase Update";
        $data['pm_sup'] = $pm_sup = $this->Billing_model->select_supplier_purhase_master($PurchaseMaster_SlNo);
        $data['product_purchase_det'] = $cartData =  $this->Billing_model->select_product_parchase_details($PurchaseMaster_SlNo);
        $this->_purchase_update_add_cart($cartData,$pm_sup);
        $data['products'] = $this->Product_model->products_by_brunch();

        $data['content'] = $this->load->view('Administrator/purchase/purchase_order_update', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    /*Used to add product details to cart for update product purchase.... called in purchase_update_form()*/
    private function _purchase_update_add_cart($cartData,$pm_sup){

        foreach ($cartData as $data):
            $insert_data = array(
                'id' => $data->Product_SlNo,
                'ProCat' => $data->ProductCategory_ID,
                'name' => $data->Product_Name,
                'category' => $data->ProductCategory_Name,
                'proCode' => $data->Product_Code,
                'price' => $data->Product_Purchase_Rate,
                'cost' => $data->purchase_cost,
                'qty' => $data->PurchaseDetails_TotalQuantity,
                'PurchaseMaster_SlNo' => $pm_sup->PurchaseMaster_SlNo,
                'PurchaseDetails_SlNo' => $data->PurchaseDetails_SlNo,
                'PurchaseMaster_InvoiceNo' => $pm_sup->PurchaseMaster_InvoiceNo,
                'PurchaseMaster_PaidAmount' => $pm_sup->PurchaseMaster_PaidAmount,
            );
        $this->cart->insert($insert_data);
        endforeach;
    }

    public function product_delete()
    {
        // $id = $this->input->post('deleted');
        $PurchaseMaster_SlNo = $this->input->post('PurchaseMaster_SlNo');
        $PurchaseMaster_InvoiceNo = $this->input->post('PurchaseMaster_InvoiceNo');
        $PurchaseMaster_TotalAmount = $this->input->post('PurchaseMaster_TotalAmount');
        $PurchaseMaster_DiscountAmount = $this->input->post('PurchaseMaster_DiscountAmount');
        $PurchaseMaster_Tax = $this->input->post('PurchaseMaster_Tax');
        $PurchaseMaster_Freight = $this->input->post('PurchaseMaster_Freight');
        $PurchaseMaster_SubTotalAmount = $this->input->post('PurchaseMaster_SubTotalAmount');
        $PurchaseMaster_PaidAmount = $this->input->post('PurchaseMaster_PaidAmount');
        $PurchaseMaster_DueAmount = $this->input->post('PurchaseMaster_DueAmount');

        $id = $this->input->post('PurchaseDetails_SlNo');
        $Product_IDNo = $this->input->post('Product_IDNo');
        $PurchaseDetails_TotalQuantity = $this->input->post('PurchaseDetails_TotalQuantity');
        $PurchaseDetails_TotalAmount = $this->input->post('PurchaseDetails_TotalAmount');
        //exit;
        $fld = 'PurchaseDetails_SlNo';
        $delete = $this->mt->delete_data("tbl_purchasedetails", $id, $fld);
        if (isset($delete)) {
            $SSI = mysql_query("SELECT * FROM tbl_purchaseinventory WHERE purchProduct_IDNo='$Product_IDNo'");
            $sirow = mysql_fetch_array($SSI);
            $data1['PurchaseInventory_TotalQuantity'] = $sirow['PurchaseInventory_TotalQuantity'] - $PurchaseDetails_TotalQuantity;
            $this->Billing_model->update_purchaseinventory("tbl_purchaseinventory", $data1, $Product_IDNo);

            $count = $this->db->from("tbl_purchasedetails")->where('PurchaseMaster_IDNo', $PurchaseMaster_SlNo)->count_all_results();
            if ($count == 0) {
                $data2['PurchaseMaster_TotalAmount'] = 0;
                $data2['PurchaseMaster_DiscountAmount'] = 0;
                $data2['PurchaseMaster_Tax'] = 0;
                $data2['PurchaseMaster_Freight'] = 0;
                $data2['PurchaseMaster_DueAmount'] = 0;
                $data2['PurchaseMaster_SubTotalAmount'] = 0;
                $this->Billing_model->update_purchasemaster("tbl_purchasemaster", $data2, $PurchaseMaster_SlNo);
            } else {
                $totalAmount = $PurchaseMaster_TotalAmount - $PurchaseDetails_TotalAmount;
                $data2['PurchaseMaster_TotalAmount'] = $totalAmount;
                $data2['PurchaseMaster_SubTotalAmount'] = $PurchaseMaster_SubTotalAmount - ($PurchaseDetails_TotalAmount / 100 * $PurchaseMaster_Tax + $PurchaseDetails_TotalAmount);
                $this->Billing_model->update_purchasemaster("tbl_purchasemaster", $data2, $PurchaseMaster_SlNo);
            }
            /* $SP = mysql_query("SELECT * FROM tbl_supplier_payment WHERE `SPayment_invoice`='$PurchaseMaster_InvoiceNo'");
            $cprow = mysql_fetch_array($SP);		
            $data['SPayment_amount']= $total;
            $this->Billing_model->update_supplier_payment("tbl_supplier_payment",$data,$PurchaseMaster_InvoiceNo); */
        }
        redirect('Administrator/Purchase/purchase_update_form/' . $PurchaseMaster_SlNo, 'refresh');
        //$this->load->view('Administrator/sales/product_sales_update');
    }

    /*Purchase Record Update*/
    public function Purchase_order_update()
    {
        $purchInvoice = $this->input->post('purchInvoice');
        $purch_id = $this->input->post('PurchaseMaster_SlNo');
        /*Purchase Master Update*/
        $Purchase = array(
            "Supplier_SlNo" => $this->input->post('SupplierID'),
            "PurchaseMaster_InvoiceNo" => $purchInvoice,
            "PurchaseMaster_OrderDate" => $this->input->post('Purchase_date'),
            "PurchaseMaster_PurchaseFor" => $this->input->post('PurchaseFor'),
            "PurchaseMaster_Description" => $this->input->post('Notes'),
            "PurchaseMaster_TotalAmount" => $this->input->post('subTotal'),
            "PurchaseMaster_DiscountAmount" => $this->input->post('purchDiscount'),
            "PurchaseMaster_Tax" => $this->input->post('vatPersent'),
            "PurchaseMaster_Freight" => $this->input->post('purchFreight'),
            "PurchaseMaster_SubTotalAmount" => $this->input->post('purchTotal'),
            "PurchaseMaster_PaidAmount" => $this->input->post('PurchPaid'),
            "PurchaseMaster_DueAmount" => $this->input->post('purchaseDue'),
            "UpdateBy" => $this->session->userdata("FullName"),
            "PurchaseMaster_BranchID" => $this->session->userdata("BRANCHid"),
            "UpdateTime" => date("Y-m-d H:i:s")
        );
        $this->Billing_model->purchaseOrderUpdate($Purchase, $purchInvoice);

        /*Supplier Payment Update*/
        $data = array(
            "SPayment_date" => $this->input->post('Purchase_date', TRUE),
            "SPayment_invoice" => $purchInvoice,
            "SPayment_customerID" => $this->input->post('SupplierID', TRUE),
            "SPayment_amount" => $this->input->post('PurchPaid', TRUE),
            "SPayment_notes" => $this->input->post('Notes', TRUE),
            "SPayment_Addby" => $this->session->userdata("FullName"),
            "SPayment_brunchid" => $this->session->userdata("BRANCHid")
        );
        $this->Billing_model->update_supplier_payment_data("tbl_supplier_payment", $data, $purchInvoice);

        /*CartData Insert Or Update to purchase details */
        if ($cart = $this->cart->contents()){
            foreach ($cart as $item){
                $order_detail = array(
                    'PurchaseMaster_IDNo' => $purch_id,
                    'Product_IDNo' => $item['id'],
                    'PurchaseDetails_TotalQuantity' => $item['qty'],
                    'PurchaseDetails_Rate' => $item['price'],
                    'UpdateBy' => $this->session->userdata("FullName"),
                    'UpdateTime' => date('Y-m-d H:i:s')
                );

                $oldPurchaseDetail =  $this->db->where('PurchaseMaster_IDNo',$purch_id)->where('Product_IDNo',$item['id'])->get('tbl_purchasedetails')->row();
                if(count($oldPurchaseDetail)>0):

                    /*update old details*/
                    $this->db->where('PurchaseMaster_IDNo',$purch_id)->where('Product_IDNo',$item['id'])->update('tbl_purchasedetails',$order_detail);
                    $newQty  = $item['qty'] - $oldPurchaseDetail->PurchaseDetails_TotalQuantity;
                    $item['qty'] = $newQty;
                    $this->_addStock($item);

                else:

                    /*insert new details*/
                    $this->Billing_model->update_purchase_detail($order_detail);
                    $this->_addStock($item);

                endif;


                /*Update Product Purchase Rate*/
                $Pid = $item['id'];
                $Pfld = 'Product_SlNo';
                $ProductPrice = array('Product_Purchase_Rate' => $item['price'],);
                $this->mt->update_data("tbl_product", $ProductPrice, $Pid, $Pfld);

            }// end foreach
        }// end if

        $this->cart->destroy();
        $xx['purchaseforprint'] = $purch_id;
        $this->session->set_userdata($xx);
        echo json_encode(true);

    }

    /*Used in Purchase Update*/
    private function _addStock($item){
        // Stock add
        $rox = $this->db->where('product_id',$item['id'])->get('tbl_currentinventory')->row();
        $id = $rox->inventory_id;
        $oldStock = $rox->purchase_quantity;

        if($rox->product_id == $item['id']){
            $addStock = array(
                'product_id'           => $item['id'],
                'purchase_quantity'=> $oldStock+$item['qty']
            );
            $this->mt->update_data("tbl_currentinventory",$addStock,$id,'inventory_id');
        }else{
            $addStock = array(
                'product_id'                     => $item['id'],
                'purchase_quantity' => $item['qty']
            );
            $this->mt->save_data("tbl_currentinventory",$addStock);
        }
    }

    function select_supplier()
    {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label no-padding-right" for="Supplierid"> Select Supplier </label>
            <div class="col-sm-3">
                <select name="Supplierid" id="Supplierid" data-placeholder="Choose a Supplier..." class="chosen-select">
                    <option value=""></option>
                    <?php
                    $sql = $this->db->query("SELECT * FROM tbl_supplier where Supplier_brinchid='" . $this->brunch . "' order by Supplier_Name desc");
                    $row = $sql->result();
                    foreach ($row as $row) { ?>
                        <option value="<?php echo $row->Supplier_SlNo; ?>"><?php echo $row->Supplier_Name; ?>
                            (<?php echo $row->Supplier_Code; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php
    }

    function select_product()
    {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label no-padding-right" for="Productid"> Select Product </label>
            <div class="col-sm-3">
                <select name="Productid" id="Productid" data-placeholder="Choose a Product..." class="chosen-select">
                    <option value=""></option>
                    <?php
                    $sql = $this->db->query("SELECT * FROM tbl_product order by Product_Name desc");
                    $row = $sql->result();
                    foreach ($row as $row) { ?>
                        <option value="<?php echo $row->Product_SlNo; ?>"><?php echo $row->Product_Name; ?>
                            (<?php echo $row->Product_Code; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php
    }

    public function getPurchaseReturns() {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->supplierId) && $data->supplierId != '') {
            $clauses .= " and pr.Supplier_IDdNo = '$data->supplierId'";
        }

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != '') {
            $clauses .= " and pr.PurchaseReturn_ReturnDate between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->id) && $data->id != '') {
            $clauses .= " and pr.PurchaseReturn_SlNo = '$data->id'";

            $res['returnDetails'] = $this->db->query("
                select 
                    prd.*,
                    p.Product_Code,
                    p.Product_Name
                from tbl_purchasereturndetails prd
                join tbl_product p on p.Product_SlNo = prd.PurchaseReturnDetailsProduct_SlNo
                where prd.PurchaseReturn_SlNo = ?
                and prd.Status = 'a'
            ", $data->id)->result();
        }
        
        $returns = $this->db->query("
            select 
                pr.*,
                pm.PurchaseMaster_SlNo,
                s.Supplier_Code,
                s.Supplier_Name,
                s.Supplier_Mobile,
                s.Supplier_Address
            from tbl_purchasereturn pr 
            join tbl_purchasemaster pm on pm.PurchaseMaster_InvoiceNo = pr.PurchaseMaster_InvoiceNo
            join tbl_supplier s on s.Supplier_SlNo = pr.Supplier_IDdNo
            where pr.Status = 'a'
            and pr.PurchaseReturn_brunchID = ?
            $clauses
            order by pr.PurchaseReturn_SlNo desc
        ", $this->brunch)->result();

        $res['returns'] = $returns;
        echo json_encode($res);
    }

    public function purchaseReturnInvoice($id) {
        $data['title'] = "Purchase return Invoice";
        $data['id'] = $id;
        $data['content'] = $this->load->view('Administrator/purchase/purchase_return_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function deletePurchaseReturn() {
        $res = ['success' => false, 'message' => ''];

        try {
            $data = json_decode($this->input->raw_input_stream);
    
            $oldReturn = $this->db->query("select * from tbl_purchasereturn where PurchaseReturn_SlNo = ?", $data->id)->row();

            $this->db->query("delete from tbl_purchasereturn where PurchaseReturn_SlNo = ?", $data->id);
            $returnDetails = $this->db->query("select * from tbl_purchasereturndetails where PurchaseReturn_SlNo = ?", $data->id)->result();
    
            foreach($returnDetails as $product) {
                $this->db->query("
                    update tbl_currentinventory set 
                    purchase_return_quantity = purchase_return_quantity - ? 
                    where product_id = ? 
                    and branch_id = ?
                ", [$product->PurchaseReturnDetails_ReturnQuantity, $product->PurchaseReturnDetailsProduct_SlNo, $this->brunch]);
            }
    
            $this->db->query("delete from tbl_purchasereturndetails where PurchaseReturn_SlNo = ?", $data->id);

            $supplierInfo = $this->db->query("select * from tbl_supplier where Supplier_SlNo = ?", $oldReturn->Supplier_IDdNo)->row();
            if($supplierInfo->Supplier_Type == 'G') {

                $this->db->query("
                    delete from tbl_supplier_payment 
                    where SPayment_invoice = ? 
                    and SPayment_customerID = ?
                    and SPayment_amount = ?
                    limit 1
                ", [
                    $oldReturn->PurchaseMaster_InvoiceNo,
                    $oldReturn->Supplier_IDdNo,
                    $oldReturn->PurchaseReturn_ReturnAmount
                ]);

            }

            $res = ['success' => true, 'message' => 'Purchase return deleted'];
        } catch(Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function purchaseReturnDetails() {
        $data['title'] = "Purchase return details";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_return_details', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function checkPurchaseReturn($invoice)
    {
        $res = ['found'=>false];

        $returnCount = $this->db->query("select * from tbl_purchasereturn where PurchaseMaster_InvoiceNo = ? and Status = 'a'", $invoice)->num_rows();
        
        if($returnCount != 0) {
            $res = ['found'=>true];
        }

        echo json_encode($res);
    }

    public function payment_invoice()  {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Payment Invoice"; 
		$data['content'] = $this->load->view('Administrator/utility/payment_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function ac_invoice()  {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Ac Invoice"; 
		$data['content'] = $this->load->view('Administrator/utility/ac_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function storePaymentReport()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Store Payment Report";
        $data['content'] = $this->load->view('Administrator/utility/store_payment_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }


    public function paymentInvoicePrint($paymentId) {
        $data['title'] = "Payment Invoice";
        $data['paymentId'] = $paymentId;
        $data['content'] = $this->load->view('Administrator/utility/paymentAndreport', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function paymentAcInvoicePrint($paymentId) {
        $data['title'] = "AC Invoice";
        $data['paymentId'] = $paymentId;
        $data['content'] = $this->load->view('Administrator/utility/paymentAcAndreport', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    
    public function getStorePayments(){
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and bs.payment_date between '$data->dateFrom' and '$data->dateTo'";
        }
        if(isset($data->storeId) && $data->storeId != ''){
            $clauses .= " and bsd.store_id = '$data->storeId'";
        }
      
        if(isset($data->renterId) && $data->renterId != ''){
            $clauses .= " and s.renter_id = '$data->renterId'";
        }
        if(isset($data->ownerId) && $data->ownerId != ''){
            $clauses .= " and s.owner_id = '$data->ownerId'";
        }
        if(isset($data->floorId) && $data->floorId != ''){
            $clauses .= " and s.floor_id = '$data->floorId'";
        }

        if(isset($data->month) && $data->month != ''){
            $clauses .= " and bs.month_id = '$data->month'";
        }

        $payments = $this->db->query("
            select 
                bsd.*,
                bs.*,
                s.Store_No,
                s.Store_Name,
                s.meter_no,
                o.Owner_Name,
                re.Renter_Name,
                f.Floor_Name,
                m.month_name,
                u.User_Name
            from tbl_utility_payment_details bsd
            join tbl_utility_payment bs on bs.id = bsd.utility_payment_id
            join tbl_month m on m.month_id = bs.month_id
            join tbl_store s on s.Store_SlNo = bsd.store_id
            left join tbl_owner o on o.Owner_SlNo = s.owner_id
            left join tbl_renter re on re.Renter_SlNo = s.renter_id
            left join tbl_floor f on f.Floor_SlNo = s.floor_id
            left join tbl_user u on u.User_SlNo = bs.saved_by
            where bsd.branch_id = 1
            and bsd.status = 'a'
            $clauses
            order by bsd.id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($payments);
    }

    public function getStoreDueForPayment() {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->storeId) && $data->storeId != ''){
            $clauses .= " and bsd.store_id = '$data->storeId'";
        }

        if(isset($data->month) && $data->month != ''){
            $clauses .= " and bs.month_id = '$data->month'";
        }

        $dueResult = $this->db->query("
            SELECT
                s.Store_SlNo,
                s.Store_No,
                s.Store_Name,
                s.Store_TIN,
                s.meter_no,
                s.Store_Mobile,
                bs.id as bill_id,
                bs.month_id as bill_month,
                bs.process_date,
                bs.last_date, 
                bsd.id as bill_detail_id,
                bsd.electricity_bill,
                bsd.generator_bill,
                bsd.ac_bill,
                bsd.others_bill,
                bsd.net_payable,
                up.id as payment_id,
                up.invoice as payment_invoice,
                up.payment_date,
                up.month_id as payment_month,
                IFNULL(SUM(upd.electricity_bill), 0) as electricity_payment,
                IFNULL(SUM(upd.generator_bill), 0) as generator_payment,
                IFNULL(SUM(upd.ac_bill), 0) as ac_payment,
                IFNULL(SUM(upd.others_bill), 0) as others_payment,
                IFNULL(SUM(upd.late_fee), 0) as late_fee_payment,
                IFNULL(SUM(upd.payment), 0) as subtotal_payment,
                ifnull((
                    SELECT bsd.electricity_bill -  ifnull(SUM(upd.electricity_bill),0)
                    FROM tbl_utility_payment_details upd
                    WHERE upd.bill_detail_id = bsd.id
                ), 0) as electricity_due,
                
                ifnull((
                    SELECT bsd.generator_bill - ifnull(SUM(upd.generator_bill),0)
                    FROM tbl_utility_payment_details upd
                    WHERE upd.bill_detail_id = bsd.id
                ), 0) as generator_due,
                ifnull((
                    SELECT bsd.ac_bill - ifnull(SUM(upd.ac_bill),0)
                    FROM tbl_utility_payment_details upd
                    WHERE upd.bill_detail_id = bsd.id
                ), 0) as ac_due,
                ifnull((
                    SELECT bsd.others_bill - ifnull(SUM(upd.others_bill),0)
                    FROM tbl_utility_payment_details upd
                    WHERE upd.bill_detail_id = bsd.id
                ), 0) as others_due,
                ifnull((
                    SELECT bsd.net_payable - ifnull(SUM(upd.payment),0)
                    FROM tbl_utility_payment_details upd
                    WHERE upd.bill_detail_id = bsd.id
                ), 0) as previous_due
                FROM
                    tbl_bill_sheet_details bsd
                    JOIN tbl_bill_sheet bs ON bs.id = bsd.bill_id
                    LEFT JOIN tbl_utility_payment_details upd ON upd.bill_detail_id = bsd.id
                    LEFT JOIN tbl_utility_payment up ON up.id = upd.utility_payment_id
                    left JOIN tbl_store s ON s.Store_SlNo = bsd.store_id
                where bsd.branch_id = ? AND bsd.status = 'a' $clauses
                GROUP BY bsd.id
            ", $this->session->userdata('BRANCHid'))->result();

            // GROUP BY s.Store_SlNo, s.Store_No,
            // s.Store_Name, s.Store_TIN, s.meter_no, s.Store_Mobile, bs.id, bs.month_id, bs.process_date, bs.last_date, bsd.id, bsd.electricity_bill, bsd.generator_bill, bsd.ac_bill, bsd.others_bill, bsd.net_payable, up.id, up.invoice, up.payment_date, up.month_id;
        echo json_encode($dueResult);
    }

    public function zamindariPaymentPage()
    {
        $data['title'] = "Zomindari Payment";
        // $data['paymentId'] = $paymentId;
        // $data['content'] = $this->load->view('Administrator/utility/zamindariPaymentAndreport', $data, TRUE);

        $data['paymentHis'] = $this->Billing_model->fatch_all_payment();
        $data['content'] = $this->load->view('Administrator/due_report/zamindariPaymentPage', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function zamindariPaymentHistory() {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Customer Payment History";
        $data['content'] = $this->load->view('Administrator/reports/customer_payment_history', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getZamindariPayments() {
        $data = json_decode($this->input->raw_input_stream);
        // echo json_encode($data);
        // return; 
        $clauses = "";

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and zp.ZPayment_date between '$data->dateFrom' and '$data->dateTo'";
        }
        if(isset($data->storeId) && $data->storeId != '' && $data->storeId != null){
            $clauses .= " and zp.ZPayment_storeID = '$data->storeId'";
        }
        if(isset($data->ownerId) && $data->ownerId != '' && $data->ownerId != null){
            $clauses .= " and zp.ZPayment_ownerID = '$data->ownerId'";
        }
        if(isset($data->paymentId) && $data->paymentId != '' && $data->paymentId != null){
            $clauses .= " and zp.ZPayment_id = '$data->paymentId'";
        }

        $payments = $this->db->query("
            select
                zp.*,
                o.Owner_Code,
                o.Owner_Name,
                o.Owner_Mobile,
                o.is_member,
                s.Store_Name,
                s.square_feet,
                s.Store_No,
                s.floor_id,
                m.month_name,
                (
                    select zd.last_date from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as last_date,
                (
                    select zd.savings_deposit from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as savings_deposit,
                (
                    select zd.membership_fee from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as membership_fee,
                (
                    select zd.shop_rent from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as shop_rent,
                (
                    select zd.tax_surcharge from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as tax_surcharge,
                (
                    select zd.service_charge from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as service_charge,
                (
                    select zd.net_payable from tbl_zamindari_details zd
                    join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                    where zd.owner_id = zp.ZPayment_ownerID and zd.store_id = zp.ZPayment_storeID and zm.month_id = zp.Zamindari_monthID and zd.status = 'a' limit 1
                ) as net_payable
            from tbl_zamindari_payment zp
            join tbl_owner o on o.Owner_SlNo = zp.ZPayment_ownerID
            join tbl_store s on s.Store_SlNo = zp.ZPayment_storeID
            left join tbl_floor f on f.Floor_SlNo = s.floor_id
            join tbl_month m on m.month_id = zp.Zamindari_monthID
            where zp.ZPayment_status = 'a'
            and zp.ZPayment_branchid = ? $clauses
            order by zp.ZPayment_id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($payments);
    }

    public function getZamindariDue() {
        $data = json_decode($this->input->raw_input_stream);

        $branchId = $this->session->userdata('BRANCHid');

        $clauses = "";
        if(isset($data->month) && count($data->month) > 0) {
            $clauses = " and (";
           $lastArr = count($data->month);
            foreach ($data->month as $key => $item) {
                if($key < $lastArr - 1) {
                    $clauses .= " zm.month_id = '$item->month_id' or ";
                } else {
                    $clauses .= " zm.month_id = '$item->month_id')";
                }
            }
        }   
        if(isset($data->ownerId) && $data->ownerId != null){
            $clauses .= " and zd.owner_ID = '$data->ownerId'";
        }
        $dueResult = $this->db->query("
            select
                zd.*, m.month_name as original_month_name, m.month_id as original_month_id,
                (
                    select ifnull(sum(zp.ZPayment_amount), 0) from tbl_zamindari_payment zp
                    where zp.ZPayment_ownerID = zd.owner_id
                    and zp.Zamindari_monthID = zm.month_id
                ) as zamindari_payment,
                (   
                    select 
                    CASE
                        WHEN IFNULL(SUM(zp.ZPayment_amount), 0) > 0 THEN 'paid'
                        ELSE 'unpaid'
                    END AS payment_status
                    from tbl_zamindari_payment zp
                    where zp.ZPayment_ownerID = zd.owner_id 
                    and zp.Zamindari_monthID = zm.month_id 
                ) as payment_status
                from tbl_zamindari_details zd
                join tbl_zamindari_month zm on zm.id = zd.zamindari_month_id
                join tbl_month m on m.month_id = zm.month_id
                join tbl_owner o on o.Owner_SlNo = zd.owner_id
                where zd.branch_id = '$branchId' and 
                zd.status = 'a'
                $clauses
            ")->result();

        echo json_encode($dueResult);
    }
    

    public function addZamindariPayment() {
        $res = ['success'=>false, 'message'=>''];
        try{
            $paymentObj = json_decode($this->input->raw_input_stream);
            // echo json_encode($paymentObj);
            // return;
            
            foreach ($paymentObj->invoices as $key => $value) {
                $ZPayment = [];
                if($value->payment_status == 'unpaid') {
                    $ZPayment["ZPayment_date"] = $paymentObj->ZPayment_date;
                    $ZPayment["ZPayment_invoice"] = $value->invoice;
                    $ZPayment["Zamindari_monthID"] = $value->original_month_id;
                    $ZPayment["ZPayment_storeID"] = $value->store_id;
                    $ZPayment["ZPayment_ownerID"] = $value->owner_id;
                    $ZPayment["ZPayment_amount"] = $value->net_payable;
                    $ZPayment["ZPayment_status"] = 'a';
                    $ZPayment["ZPayment_Paymentby"] = 'cash';
                    $ZPayment["ZPayment_notes"] = $paymentObj->ZPayment_notes;
                    $ZPayment["ZPayment_Addby"] = $this->session->userdata("FullName");
                    $ZPayment["ZPayment_AddDate"] = date('Y-m-d H:i:s');
                    $ZPayment["ZPayment_branchid"] = $this->session->userdata("BRANCHid");
    
                    $this->db->insert('tbl_zamindari_payment', $ZPayment);
                    $paymentId = $this->db->insert_id();
                }
                // print_r($this->db->error());
                // return;
            }


            // if($paymentObj->CPayment_TransactionType == 'CR') {
            //     $currentDue = $paymentObj->CPayment_TransactionType == 'CR' ? $paymentObj->CPayment_previous_due - $paymentObj->CPayment_amount : $paymentObj->CPayment_previous_due + $paymentObj->CPayment_amount;
            //     //Send sms
            //     $customerInfo = $this->db->query("select * from tbl_customer where Customer_SlNo = ?", $paymentObj->CPayment_customerID)->row();
            //     $sendToName = $customerInfo->owner_name != '' ? $customerInfo->owner_name : $customerInfo->Customer_Name;
            //     $currency = $this->session->userdata('Currency_Name');

            //     $message = "Dear {$sendToName},\nThanks for your payment. Received amount is {$currency} {$paymentObj->CPayment_amount}. Current due is {$currency} {$currentDue}";
            //     $recipient = $customerInfo->Customer_Mobile;
            //     $this->sms->sendSms($recipient, $message);
            // }

            $res = ['success'=>true, 'message'=>'Payment added successfully', 'paymentId'=>$paymentId];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateCustomerPayment(){
        $res = ['success'=>false, 'message'=>''];
        try{
            $paymentObj = json_decode($this->input->raw_input_stream);
            $paymentId = $paymentObj->CPayment_id;
    
            $payment = (array)$paymentObj;
            unset($payment['CPayment_id']);
            $payment['update_by'] = $this->session->userdata("FullName");
            $payment['CPayment_UpdateDAte'] = date('Y-m-d H:i:s');

            $this->db->where('CPayment_id', $paymentObj->CPayment_id)->update('tbl_customer_payment', $payment);
            
            $res = ['success'=>true, 'message'=>'Payment updated successfully', 'paymentId'=>$paymentId];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function deleteCustomerPayment(){
        $res = ['success'=>false, 'message'=>''];
        try{
            $data = json_decode($this->input->raw_input_stream);
    
            $this->db->set(['CPayment_status'=>'d'])->where('CPayment_id', $data->paymentId)->update('tbl_customer_payment');
            
            $res = ['success'=>true, 'message'=>'Payment deleted successfully'];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    function zamindariPaymentInvoicePrint($paymentId = Null)
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Zamindari Payment Invoice";
        $data['paymentId'] = $paymentId;
        $data['content'] = $this->load->view('Administrator/utility/zamindariPaymentAndreport', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

}
