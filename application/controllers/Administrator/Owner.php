<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Owner extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->cbrunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->model('SMS_model', 'sms', true);
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Owner";
        $data['customerCode'] = $this->mt->generateOwnerCode();
        $data['content'] = $this->load->view('Administrator/add_owner', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function customerlist()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Owner List";
        $data['content'] = $this->load->view("Administrator/reports/customer_list", $data, true);
        $this->load->view("Administrator/index", $data);
    }

    public function getOwners(){
        $data = json_decode($this->input->raw_input_stream);

        $ownerTypeClause = "";
        if(isset($data->ownerType) && $data->ownerType != null){
            $ownerTypeClause = " and Owner_Type  = '$data->ownerType'";
        }
        $clauses = "";
        if(isset($data->is_member) && $data->is_member != null) {
            $clauses .= " and c.is_member = '$data->is_member'";
        }
        $owners = $this->db->query("
            select
                c.*,
                d.District_Name,
                concat_ws(' - ', c.Owner_UserName, c.Owner_Name, c.Owner_Mobile) as display_name
            from tbl_owner c
            left join tbl_district d on d.District_SlNo = c.area_ID
            left join tbl_store s on s.Store_SlNo = c.store_id
            where c.status = 'a'
            and c.Owner_Type != 'G'
            and (c.Owner_brunchid = ? or c.Owner_brunchid = 0)
            $clauses $ownerTypeClause
            order by c.Owner_SlNo desc
        ", $this->session->userdata('BRANCHid'))->result();
        echo json_encode($owners);
    }

    public function getOwnerDue(){
        $data = json_decode($this->input->raw_input_stream);
        
        $clauses = "";
        if(isset($data->ownerId) && $data->ownerId != null){
            $clauses .= " and o.Owner_SlNo = '$data->ownerId'";
        }
        $dueResult = $this->mt->ownerDue($clauses);
        echo json_encode($dueResult);
    }

    public function getOwnerPayments(){
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if(isset($data->paymentType) && $data->paymentType != '' && $data->paymentType == 'received'){
            $clauses .= " and cp.CPayment_TransactionType = 'CR'";
        }
        if(isset($data->paymentType) && $data->paymentType != '' && $data->paymentType == 'paid'){
            $clauses .= " and cp.CPayment_TransactionType = 'CP'";
        }

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != ''){
            $clauses .= " and cp.CPayment_date between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->customerId) && $data->customerId != '' && $data->customerId != null){
            $clauses .= " and cp.CPayment_customerID = '$data->customerId'";
        }

        $payments = $this->db->query("
            select
                cp.*,
                c.Owner_Code,
                c.Owner_Name,
                c.Owner_Mobile,
                ba.account_name,
                ba.account_number,
                ba.bank_name,
                case cp.CPayment_TransactionType
                    when 'CR' then 'Received'
                    when 'CP' then 'Paid'
                end as transaction_type,
                case cp.CPayment_Paymentby
                    when 'bank' then concat('Bank - ', ba.account_name, ' - ', ba.account_number, ' - ', ba.bank_name)
                    when 'By Cheque' then 'Cheque'
                    else 'Cash'
                end as payment_by
            from tbl_customer_payment cp
            join tbl_customer c on c.Owner_SlNo = cp.CPayment_customerID
            left join tbl_bank_accounts ba on ba.account_id = cp.account_id
            where cp.CPayment_status = 'a'
            and cp.CPayment_brunchid = ? $clauses
            order by cp.CPayment_id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($payments);
    }

    public function addOwnerPayment(){
        $res = ['success'=>false, 'message'=>''];
        try{
            $paymentObj = json_decode($this->input->raw_input_stream);
    
            $payment = (array)$paymentObj;
            $payment['CPayment_invoice'] = $this->mt->generateOwnerPaymentCode();
            $payment['CPayment_status'] = 'a';
            $payment['CPayment_Addby'] = $this->session->userdata("FullName");
            $payment['CPayment_AddDAte'] = date('Y-m-d H:i:s');
            $payment['CPayment_brunchid'] = $this->session->userdata("BRANCHid");

            $this->db->insert('tbl_customer_payment', $payment);
            $paymentId = $this->db->insert_id();

            if($paymentObj->CPayment_TransactionType == 'CR') {
                $currentDue = $paymentObj->CPayment_TransactionType == 'CR' ? $paymentObj->CPayment_previous_due - $paymentObj->CPayment_amount : $paymentObj->CPayment_previous_due + $paymentObj->CPayment_amount;
                //Send sms
                $customerInfo = $this->db->query("select * from tbl_customer where Owner_SlNo = ?", $paymentObj->CPayment_customerID)->row();
                $sendToName = $customerInfo->owner_name != '' ? $customerInfo->owner_name : $customerInfo->Customer_Name;
                $currency = $this->session->userdata('Currency_Name');

                $message = "Dear {$sendToName},\nThanks for your payment. Received amount is {$currency} {$paymentObj->CPayment_amount}. Current due is {$currency} {$currentDue}";
                $recipient = $customerInfo->Owner_Mobile;
                $this->sms->sendSms($recipient, $message);
            }

            $res = ['success'=>true, 'message'=>'Payment added successfully', 'paymentId'=>$paymentId];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateOwnerPayment(){
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

    public function deleteOwnerPayment(){
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

    public function addOwner()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $ownerObj = json_decode($this->input->post('data'));

            // echo json_encode($ownerObj);
            // return;
            
            $ownerCodeCount = $this->db->query("select * from tbl_owner where Owner_Code = ?", $ownerObj->Owner_Code)->num_rows();
            if($ownerCodeCount > 0){
                $ownerObj->Owner_Code = $this->mt->generateOwnerCode();
            }

            $ownerUserCount = $this->db->query("select * from tbl_owner where Owner_UserName = ?", $ownerObj->Owner_UserName)->num_rows();
            if($ownerUserCount > 0){
                $res = ['success'=>false, 'message'=>"Username Exist!"];
                echo json_encode($res);
                return;
            }

            $owner = (array)$ownerObj;
            unset($owner['Owner_SlNo']);
            $owner["is_member"] = $ownerObj->is_member == '1' ? 'true' : 'false';
            $owner["Owner_brunchid"] = $this->session->userdata("BRANCHid");

            $ownerId = null;
            $res_message = "";

            $duplicateMobileQuery = $this->db->query("select * from tbl_owner where Owner_Mobile = ? and Owner_brunchid = ?", [$ownerObj->Owner_Mobile, $this->session->userdata("BRANCHid")]);

            if($duplicateMobileQuery->num_rows() > 0) {
                $duplicateOwner = $duplicateMobileQuery->row();

                unset($owner['Owner_Code']);
                $owner["UpdateBy"]   = $this->session->userdata("FullName");
                $owner["UpdateTime"] = date("Y-m-d H:i:s");
                $owner["status"]     = 'a';
                $this->db->where('Owner_SlNo', $duplicateOwner->Owner_SlNo)->update('tbl_owner', $owner);
                
                $ownerId = $duplicateOwner->Owner_SlNo;
                $ownerObj->Owner_Code = $duplicateOwner->Owner_Code;
                $res_message = 'Owner updated successfully';
            } else {
                $owner["AddBy"] = $this->session->userdata("FullName");
                $owner["AddTime"] = date("Y-m-d H:i:s");
    
                $this->db->insert('tbl_owner', $owner);
                $ownerId = $this->db->insert_id();

                $res_message = 'Owner added successfully';
            }
            

            if(!empty($_FILES)) {
                $config['upload_path'] = './uploads/owners/';
                $config['allowed_types'] = 'gif|jpg|png';

                $imageName = $ownerObj->Owner_Code;
                $config['file_name'] = $imageName;
                $this->load->library('upload', $config);
                $this->upload->do_upload('image');
                //$imageName = $this->upload->data('file_ext'); /*for geting uploaded image name*/

                $config['image_library'] = 'gd2';
                $config['source_image'] = './uploads/owners/'. $imageName ; 
                $config['new_image'] = './uploads/owners/';
                $config['maintain_ratio'] = TRUE;
                $config['width']    = 640;
                $config['height']   = 480;

                $this->load->library('image_lib', $config); 
                $this->image_lib->resize();

                $imageName = $ownerObj->Owner_Code . $this->upload->data('file_ext');

                $this->db->query("update tbl_owner set image_name = ? where Owner_SlNo = ?", [$imageName, $ownerId]);
            }

            
            // Insert user as owner role
            $checkUsername = $this->db->query("select * from tbl_user where User_Name = ?", $ownerObj->Owner_UserName)->num_rows();
            if($checkUsername > 0){
                $res = ['success'=>false, 'message'=>'Username already exists'];
                echo json_encode($res);
                exit;
            }
    
            $udata = array(
                "User_Name"                 => $ownerObj->Owner_UserName,
                "FullName"                  => $ownerObj->Owner_Name,
                "UserEmail"                 => $ownerObj->Owner_Email,
                "ref_id"                    => $ownerId,
                "Brunch_ID"                 => $this->session->userdata("BRANCHid"),
                "userBrunch_id"             => $this->session->userdata("BRANCHid"),
                "User_Password"             => md5(12345),
                "UserType"                  => 'o',
                "AddTime"                   => date('Y-m-d H:i:s')
            );
            $this->mt->save_data("tbl_user", $udata);

            $res = ['success'=>true, 'message' => $res_message, 'ownerCode'=>$this->mt->generateOwnerCode()];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateOwner()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $ownerObj = json_decode($this->input->post('data'));

            // echo json_encode($ownerObj);
            // return;

            $ownerUserCount = $this->db->query("select * from tbl_owner where Owner_UserName = ? and Owner_SlNo != ? ", [$ownerObj->Owner_UserName, $ownerObj->Owner_SlNo])->num_rows();
            if($ownerUserCount > 0){
                $res = ['success'=>false, 'message'=>"Username Exist!"];
                echo json_encode($res);
                return;
            }
          
            $ownerMobileCount = $this->db->query("select * from tbl_owner where Owner_Mobile = ? and Owner_SlNo != ? and Owner_brunchid = ?", [$ownerObj->Owner_Mobile, $ownerObj->Owner_SlNo, $this->session->userdata("BRANCHid")])->num_rows();

            if($ownerMobileCount > 0){
                $res = ['success'=>false, 'message'=>'Mobile number already exists'];
                echo Json_encode($res);
                exit;
            }
            $owner = (array)$ownerObj;
            $ownerId = $ownerObj->Owner_SlNo;

            unset($owner["Owner_SlNo"]);
            $owner["is_member"] = $ownerObj->is_member == '1' ? 'true' : 'false';
            $owner["Owner_brunchid"] = $this->session->userdata("BRANCHid");
            $owner["UpdateBy"] = $this->session->userdata("FullName");
            $owner["UpdateTime"] = date("Y-m-d H:i:s");

            $this->db->where('Owner_SlNo', $ownerId)->update('tbl_owner', $owner);
            $old_owner = $this->db->query("select * from tbl_owner where Owner_SlNo = '$ownerId'")->row();

            if(!empty($_FILES)) {
                if($old_owner->image_name && $old_owner->image_name){
                    @unlink("./uploads/owners/".$old_owner->image_name);
                }
                $config['upload_path'] = './uploads/owners/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';

                $imageName = $ownerObj->Owner_Code;
                $config['file_name'] = $imageName;
                $this->load->library('upload', $config);
                $this->upload->do_upload('image');
                //$imageName = $this->upload->data('file_ext'); /*for geting uploaded image name*/

                $config['image_library'] = 'gd2';
                $config['source_image'] = './uploads/owners/'. $imageName ; 
                $config['new_image'] = './uploads/owners/';
                $config['maintain_ratio'] = TRUE;
                $config['width']    = 640;
                $config['height']   = 480;

                $this->load->library('image_lib', $config); 
                $this->image_lib->resize();

                $imageName = $ownerObj->Owner_Code . $this->upload->data('file_ext');

                $this->db->query("update tbl_owner set image_name = ? where Owner_SlNo = ?", [$imageName, $ownerId]);

            }

            /* update username in user table */
            $checkUsername = $this->db->query("select * from tbl_user where User_Name = ? and ref_id != ? and UserType = 'o'", [$ownerObj->Owner_UserName, $ownerId])->num_rows();
            if($checkUsername > 0) {
                $res = ['success'=>false, 'message'=>'Username already exists'];
                echo json_encode($res);
                exit;
            }
    
            $udata = array(
                "User_Name"     => $ownerObj->Owner_UserName,
                "FullName"      => $ownerObj->Owner_Name,
                "UserEmail"     => $ownerObj->Owner_Email,
                "ref_id"        => $ownerId
            );

            $this->db->where("ref_id", $ownerId);
            $this->db->where("UserType", 'o');
            $this->db->update("tbl_user", $udata);
        
            $res = ['success'=>true, 'message'=>'Owner updated successfully', 'ownerCode'=>$this->mt->generateOwnerCode()];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateOwnerStorId()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $ownerObj = json_decode($this->input->post('data'));
            // echo json_encode($ownerObj);
            // return;
            $this->db->query("update tbl_owner set store_id = ? where Owner_SlNo = ? ", [$ownerObj->store_id, $ownerObj->owner_id]);
            $res = ['success'=>true, 'message'=>'Owner updated successfully'];
        } catch(Exception $ex) {
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }
        echo json_encode($res);
    }

    public function customeredit()
    {
        $data['title'] = "Edit Customer";
        $id = $this->input->post('edit');
        $query = $this->db->query("SELECT tbl_customer.*, tbl_district.* FROM tbl_customer left join tbl_district on tbl_district.District_SlNo=tbl_customer.area_ID where tbl_customer.Customer_SlNo = '$id'");
        $data['selected'] = $query->row();
        $this->load->view('Administrator/edit/customer_edit', $data);
    }

    public function deleteOwner()
    {
        $res = ['success'=>false, 'message'=>''];
        try{
            $data = json_decode($this->input->raw_input_stream);

            $this->db->query("update tbl_owner set status = 'd' where Owner_SlNo = ?", $data->ownerId);
            $this->db->query("update tbl_user set status = 'd' where UserType = 'o' and ref_id = ?", $data->ownerId);

            $res = ['success'=>true, 'message'=>'Owner deleted'];
        } catch (Exception $ex){
            $res = ['success'=>false, 'message'=>$ex->getMessage()];
        }

        echo json_encode($res);
    }

    function customer_due()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = 'Customer Due';
        $data['content'] = $this->load->view('Administrator/due_report/customer_due', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function search_customer_due()
    {
        $BRANCHid = $this->session->userdata('BRANCHid');
        $dAta['searchtype'] = $searchtype = $this->input->post('searchtype');
        $dAta['Sales_startdate'] = $Sales_startdate = $this->input->post('Sales_startdate');
        $dAta['Sales_enddate'] = $Sales_enddate = $this->input->post('Sales_enddate');
        $dAta['customerID'] = $customerID = $this->input->post('customerID');
        $this->session->set_userdata($dAta);

        if ($searchtype == "All") {
            $result = $this->db->join('tbl_customer', 'tbl_customer.Customer_SlNo=tbl_salesmaster.SalseCustomer_IDNo', 'left')
                ->where('tbl_salesmaster.SaleMaster_branchid', $BRANCHid)
                ->group_by('tbl_salesmaster.SalseCustomer_IDNo')
                ->get('tbl_salesmaster');
        }
        if ($searchtype == "Customer") {
            $result = $this->db->join('tbl_customer', 'tbl_customer.Customer_SlNo=tbl_salesmaster.SalseCustomer_IDNo', 'left')
                ->where('tbl_salesmaster.SalseCustomer_IDNo', $customerID)
                ->where('tbl_salesmaster.SaleMaster_branchid', $BRANCHid)
                ->group_by('tbl_salesmaster.SalseCustomer_IDNo')
                ->get('tbl_salesmaster');
        }

        $datas["records"] = $result->result();
        $this->load->view('Administrator/due_report/customer_due_list', $datas);
    }


    function customer_due_payment($Custid)
    {
        $result = $this->db->query("SELECT tbl_salesmaster.*, tbl_customer.* FROM tbl_salesmaster left join tbl_customer on tbl_customer.Customer_SlNo = tbl_salesmaster.SalseCustomer_IDNo WHERE tbl_salesmaster.SalseCustomer_IDNo = '$Custid' group by tbl_salesmaster.SalseCustomer_IDNo");
        $datas["record"] = $result->result();
        $this->load->view('Administrator/due_report/customer_due_payment', $datas);
    }


    public function customerPaymentPage()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Customer Payment";
        $data['paymentHis'] = $this->Billing_model->fatch_all_payment();
        $query0 = $this->db->query("SELECT * FROM tbl_customer_payment ORDER BY CPayment_id DESC LIMIT 1");
        $row = $query0->row();

        @$invoice = $row->CPayment_invoice;
        $previousinvoice = substr($invoice, 3, 11);
        if (!empty($invoice)) {
            if ($previousinvoice < 10) {
                $purchInvoice = 'TR-00' . ($previousinvoice + 1);
            } else if ($previousinvoice < 100) {
                $purchInvoice = 'TR-0' . ($previousinvoice + 1);
            } else {
                $purchInvoice = 'TR-' . ($previousinvoice + 1);
            }
        } else {
            $purchInvoice = 'TR-001';
        }
        $data['purchInvoice'] = $purchInvoice;
        $data['customers'] = $this->Customer_model->get_customer_name_code_brunch_wise();
        $data['content'] = $this->load->view('Administrator/due_report/customerPaymentPage', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function fatch_customer_name($Custid = null)
    {
        $customer = $this->db->where('Customer_SlNo',$Custid)->get('tbl_customer')->row();

        $data = array(
            'cus_name'      => $customer->Customer_Name,
            'due'           => $this->mt->getCustomerDueById($Custid)
        );

        echo json_encode($data);
    }

    function paymentEdit($payID = null)
    {
        $data['edit'] = $this->db->where('CPayment_id', $payID)->get('tbl_customer_payment')->row();
        $this->load->view('Administrator/edit/payment_edit_customer', $data);
    }

    function paymentDelete($payID = null)
    {

        $attr = array(
            'CPayment_status' => 'd'
        );

        $this->db->where('CPayment_id', $payID);
        $qu = $this->db->update('tbl_customer_payment', $attr);

        if ($this->db->affected_rows()) {
            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }

    function paymentUpdate($payID = null)
    {

        $attr = array(
            "CPayment_date" => $this->input->post('paymentDate', TRUE),
            "CPayment_invoice" => $this->input->post('tr_id', TRUE),
            "CPayment_customerID" => $this->input->post('CustID', TRUE),
            "CPayment_TransactionType" => $this->input->post('tr_type', TRUE),
            "CPayment_amount" => $this->input->post('paidAmount', TRUE),
            "CPayment_notes" => $this->input->post('Note', TRUE),
            "CPayment_Paymentby" => $this->input->post('Paymentby', TRUE),
            "CPayment_Addby" => $this->session->userdata("FullName"),
            "CPayment_brunchid" => $this->session->userdata("BRANCHid"),
            "CPayment_UpdateDAte" => date('Y-m-d'),
        );

        $this->db->where('CPayment_id', $payID);
        $qu = $this->db->update('tbl_customer_payment', $attr);

        if ($this->db->affected_rows()) {
            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }


    public function custome_PaymentAmount()
    {
        $data = array(
            "CPayment_date" => $this->input->post('paymentDate', TRUE),
            "CPayment_invoice" => $this->input->post('tr_id', TRUE),
            "CPayment_customerID" => $this->input->post('CustID', TRUE),
            "CPayment_TransactionType" => $this->input->post('tr_type', TRUE),
            "CPayment_amount" => $this->input->post('paidAmount', TRUE),
            "CPayment_notes" => $this->input->post('Note', TRUE),
            "CPayment_Paymentby" => $this->input->post('Paymentby', TRUE),
            "CPayment_Addby" => $this->session->userdata("FullName"),
            "CPayment_brunchid" => $this->session->userdata("BRANCHid"),
            "CPayment_AddDAte" => date('Y-m-d'),
            "CPayment_status" => 'a',
        );
        $pid["PamentID"] = $this->mt->insert_payment("tbl_customer_payment", $data);
        $this->session->set_userdata($pid);
        $datas["PamentID"] = $pid["PamentID"];
        $searchtype = $this->session->userdata('searchtype');
        $Sales_startdate = $this->session->userdata('Sales_startdate');
        $Sales_enddate = $this->session->userdata('Sales_enddate');
        $customerID = $this->session->userdata('customerID');
        if ($searchtype == "All") {
            $sql = "SELECT tbl_salesmaster.*, tbl_customer.* FROM tbl_salesmaster left join tbl_customer on tbl_customer.Customer_SlNo = tbl_salesmaster.SalseCustomer_IDNo WHERE tbl_salesmaster.SaleMaster_SaleDate between  '$Sales_startdate' and '$Sales_enddate' group by tbl_salesmaster.SalseCustomer_IDNo";
        }
        if ($searchtype == "Customer") {
            $sql = "SELECT tbl_salesmaster.*, tbl_customer.* FROM tbl_salesmaster left join tbl_customer on tbl_customer.Customer_SlNo = tbl_salesmaster.SalseCustomer_IDNo WHERE tbl_salesmaster.SalseCustomer_IDNo = '$customerID' and  tbl_salesmaster.SaleMaster_SaleDate between  '$Sales_startdate' and '$Sales_enddate' group by tbl_salesmaster.SalseCustomer_IDNo";
        }

        $datas["record"] = $this->mt->ccdata($sql);
        $this->load->view('Administrator/due_report/customer_due_list', $datas);
    }

    function paymentAndReport($id = Null)
    {
        
        $data['title'] = "Customer Payment Reports";
        if ($id != 'pr') {
            $pid["PamentID"] = $id;
            $this->session->set_userdata($pid);
        }
        $data['content'] = $this->load->view('Administrator/due_report/paymentAndReport', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function customer_payment_report()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Customer Payment Reports";
        $branch_id = $this->session->userdata('BRANCHid');

        $data['content'] = $this->load->view('Administrator/payment_reports/customer_payment_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function getCustomerLedger(){
        $data = json_decode($this->input->raw_input_stream);
        $previousDueQuery = $this->db->query("select ifnull(previous_due, 0.00) as previous_due from tbl_customer where Customer_SlNo = '$data->customerId'")->row();
        
        $payments = $this->db->query("
            select 
                'a' as sequence,
                sm.SaleMaster_SlNo as id,
                sm.SaleMaster_SaleDate as date,
                concat('Sales ', sm.SaleMaster_InvoiceNo) as description,
                sm.SaleMaster_TotalSaleAmount as bill,
                sm.SaleMaster_PaidAmount as paid,
                sm.SaleMaster_DueAmount as due,
                0.00 as returned,
                0.00 as paid_out,
                0.00 as balance
            from tbl_salesmaster sm
            where sm.SalseCustomer_IDNo = '$data->customerId'
            and sm.Status = 'a'
            
            UNION
            select
                'b' as sequence,
                cp.CPayment_id as id,
                cp.CPayment_date as date,
                concat('Received - ', 
                    case cp.CPayment_Paymentby
                        when 'bank' then concat('Bank - ', ba.account_name, ' - ', ba.account_number, ' - ', ba.bank_name)
                        when 'By Cheque' then 'Cheque'
                        else 'Cash'
                    end, ' ', cp.CPayment_notes
                ) as description,
                0.00 as bill,
                cp.CPayment_amount as paid,
                0.00 as due,
                0.00 as returned,
                0.00 as paid_out,
                0.00 as balance
            from tbl_customer_payment cp
            left join tbl_bank_accounts ba on ba.account_id = cp.account_id
            where cp.CPayment_TransactionType = 'CR'
            and cp.CPayment_customerID = '$data->customerId'
            and cp.CPayment_status = 'a'

            UNION
            select
                'c' as sequence,
                cp.CPayment_id as id,
                cp.CPayment_date as date,
                concat('Paid - ', 
                    case cp.CPayment_Paymentby
                        when 'bank' then concat('Bank - ', ba.account_name, ' - ', ba.account_number, ' - ', ba.bank_name)
                        else 'Cash'
                    end, ' ', cp.CPayment_notes
                ) as description,
                0.00 as bill,
                0.00 as paid,
                0.00 as due,
                0.00 as returned,
                cp.CPayment_amount as paid_out,
                0.00 as balance
            from tbl_customer_payment cp
            left join tbl_bank_accounts ba on ba.account_id = cp.account_id
            where cp.CPayment_TransactionType = 'CP'
            and cp.CPayment_customerID = '$data->customerId'
            and cp.CPayment_status = 'a'
            
            UNION
            select
                'd' as sequence,
                sr.SaleReturn_SlNo as id,
                sr.SaleReturn_ReturnDate as date,
                'Sales return' as description,
                0.00 as bill,
                0.00 as paid,
                0.00 as due,
                sr.SaleReturn_ReturnAmount as returned,
                0.00 as paid_out,
                0.00 as balance
            from tbl_salereturn sr
            join tbl_salesmaster smr on smr.SaleMaster_InvoiceNo  = sr.SaleMaster_InvoiceNo
            where smr.SalseCustomer_IDNo = '$data->customerId'
            
            order by date, sequence, id
        ")->result();

        $previousBalance = $previousDueQuery->previous_due;

        foreach($payments as $key=>$payment){
            $lastBalance = $key == 0 ? $previousDueQuery->previous_due : $payments[$key - 1]->balance;
            $payment->balance = ($lastBalance + $payment->bill + $payment->paid_out) - ($payment->paid + $payment->returned);
        }

        if((isset($data->dateFrom) && $data->dateFrom != null) && (isset($data->dateTo) && $data->dateTo != null)){
            $previousPayments = array_filter($payments, function($payment) use ($data){
                return $payment->date < $data->dateFrom;
            });

            $previousBalance = count($previousPayments) > 0 ? $previousPayments[count($previousPayments) - 1]->balance : $previousBalance;

            $payments = array_filter($payments, function($payment) use ($data){
                return $payment->date >= $data->dateFrom && $payment->date <= $data->dateTo;
            });

            $payments = array_values($payments);
        }

        $res['previousBalance'] = $previousBalance;
        $res['payments'] = $payments;
        echo json_encode($res);
    }

    function search_customer_payments()
    {
        $dAta['searchtype']= $searchtype = $this->input->post('searchtype');
        $dAta['startdate']=$startdate = $this->input->post('startdate');
        $dAta['enddate']=$enddate = $this->input->post('enddate');
        $dAta['customerID']=$customerID = $this->input->post('customerID');
        $this->session->set_userdata($dAta);
		//echo "<pre>";print_r($dAta);exit;
		$BRANCHid = $this->session->userdata("BRANCHid");
        if($searchtype == "All"){
            $sql = "SELECT tbl_customer_payment.*, tbl_customer.* 
                    FROM tbl_customer_payment 
                    left join tbl_customer on tbl_customer.Customer_SlNo = tbl_customer_payment.CPayment_customerID 
                    where tbl_customer.Customer_brunchid='$BRANCHid' 
                    AND tbl_customer_payment.CPayment_date between '$startdate' and '$enddate'";
			$result = $this->db->query($sql);
        }
        else if($searchtype == "Customer"){

        	$this->db->select('tbl_customer_payment.*, tbl_customer.*');
        	$this->db->from('tbl_customer_payment');
        	$this->db->join('tbl_customer', 'tbl_customer_payment.CPayment_customerID = tbl_customer.Customer_SlNo', 'left');
        	$this->db->where('tbl_customer_payment.CPayment_customerID',$customerID);
        	$this->db->where('tbl_customer_payment.CPayment_date >=', $startdate)->where('tbl_customer_payment.CPayment_date <=', $enddate);
            $this->db->group_by('tbl_customer_payment.CPayment_invoice');
            $this->db->order_by('tbl_customer_payment.CPayment_date');
            $result = $this->db->get();
        }

        $dueSql = "SELECT 
            c.Customer_Name,
            c.previous_due,
            (select ifnull(sum(SaleMaster_SubTotalAmount), 0.00) 
                from tbl_salesmaster 
                where SalseCustomer_IDNo = c.Customer_SlNo
                and SaleMaster_SaleDate < '$startdate') as salesAmount,
            (select ifnull(sum(CPayment_amount), 0.00) 
                from tbl_customer_payment 
                where CPayment_customerID = c.Customer_SlNo
                and CPayment_date < '$startdate') as paidAmount,
            (select ifnull(sum(sr.SaleReturn_ReturnAmount), 0.00)
                from tbl_salereturn sr
                join tbl_salesmaster sm on sm.SaleMaster_InvoiceNo = sr.SaleMaster_InvoiceNo
                where sm.SalseCustomer_IDNo = c.Customer_SlNo
                and sr.SaleReturn_ReturnDate < '$startdate') as returnAmount,
            (select (c.previous_due + salesAmount) - (paidAmount + returnAmount)) as dueAmount
            from tbl_customer c
            where Customer_SLNo = '$customerID'";

        $dueResult = $this->db->query($dueSql);

        $datas["record"] = $result->result();
        $datas["recordss"] = $result->row();
        $datas["due"] = $dueResult->row();
		//echo "<pre>";print_r($datas["record"]);exit;
        $this->load->view('Administrator/payment_reports/customer_payment_report_list', $datas);
    }

    public function advance_payment()
    {
        $data['title'] = "Customer Advance Payment";
        $data['content'] = $this->load->view('Administrator/due_report/customer_advance_payment', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function advance_payment_customer_search()
    {
        $data['customerID'] = $this->input->post('customerID');
        $this->load->view('Administrator/due_report/advance_payment_customer_search', $data);
    }

    public function advance_payment_insert()
    {
        $data = array(
            "CPayment_date" => $this->input->post('CAPdate', TRUE),
            "CPayment_customerID" => $this->input->post('CustID', TRUE),
            "CPayment_amount" => $this->input->post('AdvanceAmount', TRUE),
            "CPayment_notes" => $this->input->post('Note', TRUE),
            "CPayment_Addby" => $this->session->userdata("FullName"),
            "CPayment_brunchid" => $this->session->userdata("BRANCHid")
        );
        $pid["PamentID"] = $this->mt->insert_payment("tbl_customer_payment", $data);
        $this->session->set_userdata($pid);
        $datas["PamentID"] = $pid["PamentID"];
        $this->load->view('Administrator/due_report/customer_advance_payment', $datas);
    }

    public function customer_advance_payment_to_report()
    {
        $data['title'] = "Customer Advance Payment Report";
        $data['content'] = $this->load->view('Administrator/due_report/customer_advance_payment_to_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    function searchcustomer()
    {
        $data['Searchkey'] = $this->input->post('Searchkey');
        $this->load->view('Administrator/ajax/search_customer', $data);
    }

    public function customerPaymentHistory(){
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Customer Payment History";
        $data['content'] = $this->load->view('Administrator/reports/customer_payment_history', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

}
