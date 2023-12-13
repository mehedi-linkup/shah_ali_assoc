<?php
    class Graph extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $access = $this->session->userdata('userId');
            $this->branchId = $this->session->userdata('BRANCHid');
            if($access == '' ){
                redirect("Login");
            }
            $this->load->model('Model_table', "mt", TRUE);
        }
        
        public function graph(){
            $access = $this->mt->userAccess();
            if(!$access){
                redirect(base_url());
            }
            $data['title'] = "Graph";
            $data['content'] = $this->load->view('Administrator/graph/graph', $data, true);
            $this->load->view('Administrator/index', $data);
        }

        public function getGraphData(){
            // Monthly Record
            $monthlyBillRecord = [];
            $year = date('Y');
            $month = date('m');
            $dayNumber = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for($i = 1; $i <= $dayNumber; $i++){
                $date = $year . '-' . $month . '-'. sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(bs.total_amount), 0) as bill_amount
                    from tbl_bill_sheet bs
                    where bs.process_date = ?
                    and bs.status = 'a'
                    and bs.branch_id = ?
                    group by bs.process_date
                ", [$date, $this->branchId]);

                $amount = 0.00;

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->bill_amount;
                }
                $sale = [sprintf("%02d", $i), $amount];
                array_push($monthlyBillRecord, $sale);
            }

            $monthlyPaymentRecord = [];

            for($i = 1; $i <= $dayNumber; $i++) {
                $date = $year . '-' . $month . '-'. sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(up.total_payment), 0) as payment_amount 
                    from tbl_utility_payment up 
                    where up.payment_date = ?
                    and up.status = 'a'
                    and up.branch_id = ?
                    group by up.payment_date
                ", [$date, $this->branchId]);

                $amount = 0.00;

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->payment_amount;
                }
                $sale = [sprintf("%02d", $i), $amount];
                array_push($monthlyPaymentRecord, $sale);
            }

            $yearlyBillRecord = [];
            for($i = 1; $i <= 12; $i++) {
                $yearMonth = $year . sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(bs.total_amount), 0) as bill_amount
                    from tbl_bill_sheet bs
                    where extract(year_month from bs.process_date) = ?
                    and bs.status = 'a'
                    and bs.branch_id = ?
                    group by extract(year_month from bs.process_date)
                ", [$yearMonth, $this->branchId]);

                $amount = 0.00;
                $monthName = date("M", mktime(0, 0, 0, $i, 10));

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->bill_amount;
                }
                $sale = [$monthName, $amount];
                array_push($yearlyBillRecord, $sale);
            }

            $yearlyPaymentRecord = [];
            for($i = 1; $i <= 12; $i++) {
                $yearMonth = $year . sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(up.total_payment), 0) as payment_amount
                    from tbl_utility_payment up
                    where extract(year_month from up.payment_date) = ?
                    and up.status = 'a'
                    and up.branch_id = ?
                    group by extract(year_month from up.payment_date)
                ", [$yearMonth, $this->branchId]);

                $amount = 0.00;
                $monthName = date("M", mktime(0, 0, 0, $i, 10));

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->payment_amount;
                }
                $sale = [$monthName, $amount];
                array_push($yearlyPaymentRecord, $sale);
            }

            // Payment text for marquee
            $payment_text = $this->db->query("
                select 
                    concat(
                        'Invoice: ', up.invoice,
                        ', Store: ', s.Store_Code, ' - ', s.Store_Name,
                        ', Renter: ', r.Renter_Code, ' - ', r.Renter_Name,
                        ', Paid: ', up.total_payment,
                        ', Due: ', up.total_due
                    ) as payment_text
                from tbl_utility_payment up
                join tbl_utility_payment_details upd
                join tbl_store s on s.Store_SlNo = upd.store_id
                join tbl_renter r on r.Renter_SlNo = s.renter_id
                where up.status = 'a'
                and up.branch_id = ?
                order by up.id desc limit 20
            ", $this->branchId)->result();

            // This Month's Sale
            $thisMonthBill = $this->db->query("
                select 
                    ifnull(sum(ifnull(bs.total_amount, 0)), 0) as total_amount
                from tbl_bill_sheet bs
                where bs.status = 'a'
                and month(bs.process_date) = ?
                and year(bs.process_date) = ?
                and bs.branch_id = ?
            ", [$month, $year, $this->branchId])->row()->total_amount;

            // Today's Cash Collection
            $todaysCollection = $this->db->query("
                select 
                ifnull((
                    select sum(ifnull(up.total_payment, 0)) 
                    from tbl_utility_payment up
                    where up.status = 'a'
                    and up.branch_id = " . $this->branchId . "
                    and up.payment_date = '" . date('Y-m-d') . "'
                ), 0) +
                ifnull((
                    select sum(ifnull(ct.In_Amount, 0)) 
                    from tbl_cashtransaction ct
                    where ct.status = 'a'
                    and ct.Tr_branchid = " . $this->branchId . "
                    and ct.Tr_date = '" . date('Y-m-d') . "'
                ), 0) as total_amount
            ")->row()->total_amount;

            // Cash Balance
            $cashBalance = $this->mt->getTransactionSummary()->cash_balance;

            // Top Customers
            $topRenters = $this->db->query("
                select 
                r.Renter_Name as renter_name,
                ifnull(sum(up.total_payment), 0) as amount
                from tbl_utility_payment up 
                join tbl_utility_payment_details upd on upd.utility_payment_id = up.id
                join tbl_store s on s.Store_SlNo = upd.store_id
                join tbl_renter r on r.Renter_SlNo = s.renter_id
                where up.branch_id = ?
                group by r.Renter_SlNo
                order by amount desc 
                limit 10
            ", $this->branchId)->result();

            // Top Products
            $topStores = $this->db->query("
                select 
                s.Store_Name as store_name,
                ifnull(sum(up.total_payment), 0) as amount
                from tbl_utility_payment up 
                join tbl_utility_payment_details upd on upd.utility_payment_id = up.id
                join tbl_store s on s.Store_SlNo = upd.store_id
                where up.branch_id = ?
                group by s.Store_SlNo
                order by amount desc 
                limit 10
            ", $this->branchId)->result();

            // Customer Due
            $storeDueResult = $this->mt->storeDue();
            $storeDue = array_sum(array_map(function($due) {
                return $due->due;
            }, $storeDueResult));

            // Supplier Due
            $renterDueResult = $this->mt->renterDue();
            $renterDue = array_sum(array_map(function($due) {
                return $due->due;
            }, $renterDueResult));

            // Bank balance
            $bankTransactions = $this->mt->getBankTransactionSummary();
            $bankBalance = array_sum(array_map(function($bank){
                return $bank->balance;
            }, $bankTransactions));

            // Invest balance
            $investTransactions = $this->mt->getInvestmentTransactionSummary();
            $investBalance = array_sum(array_map(function($bank){
                return $bank->balance;
            }, $investTransactions));

            // Loan balance
            $loanTransactions = $this->mt->getLoanTransactionSummary();
            $loanBalance = array_sum(array_map(function($bank){
                return $bank->balance;
            }, $loanTransactions));

            //Assets Value
            $assets = $this->mt->assetsReport();
            $assets_value = array_reduce($assets, function($prev, $curr){ return $prev + $curr->approx_amount;});

            $responseData = [
                'monthly_bill_record'    => $monthlyBillRecord,
                'monthly_payment_record' => $monthlyPaymentRecord,

                'yearly_bill_record'     => $yearlyBillRecord,
                'yearly_payment_record'  => $yearlyPaymentRecord,

                'payment_text'           => $payment_text,
                
                'this_month_bill'        => $thisMonthBill,
                'todays_collection'      => $todaysCollection,
                'cash_balance'           => $cashBalance,
                'top_renters'            => $topRenters,
                'top_stores'             => $topStores,
                'store_due'              => $storeDue,
                'renter_due'             => $renterDue,
                'bank_balance'           => $bankBalance,
                'invest_balance'         => $investBalance,
                'loan_balance'           => $loanBalance,
                'asset_value'            => $assets_value,
            ];

            echo json_encode($responseData, JSON_NUMERIC_CHECK);
        }
    }
?>