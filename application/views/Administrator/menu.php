<style>
	.module_title {
		background-color: #00BE67 !important;
		text-align: center;
		font-size: 18px !important;
		font-weight: bold;
		font-style: italic;
		color: #fff !important;
	}

	.module_title span {
		font-size: 18px !important;
	}
</style>

<?php
// print_r($this->session->userdata()); die();
$userID =  $this->session->userdata('userId');
$CheckSuperAdmin = $this->db->where('UserType', 'm')->where('User_SlNo', $userID)->get('tbl_user')->row();

$CheckAdmin = $this->db->where('UserType', 'a')->where('User_SlNo', $userID)->get('tbl_user')->row();
$CheckRenter = $this->db->where('UserType', 'r')->where('User_SlNo', $userID)->get('tbl_user')->row();
$CheckOwner = $this->db->where('UserType', 'o')->where('User_SlNo', $userID)->get('tbl_user')->row();

$userAccessQuery = $this->db->where('user_id', $userID)->get('tbl_user_access');
$access = [];
if ($userAccessQuery->num_rows() != 0) {
	$userAccess = $userAccessQuery->row();
	$access = json_decode($userAccess->access);
}

$module = $this->session->userdata('module');

if ($module == 'dashboard' or $module == '') {
	?>
	<ul class="nav nav-list">
		
		<li class="active">
			<!-- module/dashboard -->
			<a href="<?php echo base_url(); ?>">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>
		

		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>module/BillModule">
				<i class="menu-icon fa fa-credit-card"></i>
				<span class="menu-text"> Generate Module </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>

		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>module/PaymentModule">
				<i class="menu-icon fa fa-plug" aria-hidden="true"></i>
				<span class="menu-text"> Payment Module </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>
		
		
		<li class="">
			<a href="<?php echo base_url(); ?>module/NoticeModule">
				<i class="menu-icon fa fa-bell-o" aria-hidden="true"></i>
				<span class="menu-text"><?php echo isset($CheckOwner) || isset($CheckRenter) ? 'Notice' : 'Notification Module' ?></span>
			</a>
			<b class="arrow"></b>
		</li>

		<?php if (isset($CheckOwner) || isset($CheckRenter) ) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>module/TransactionModule">
				<i class="menu-icon fa fa-exchange" aria-hidden="true"></i>
				<span class="menu-text"> Transaction </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>

		
		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			
			<a href="<?php echo base_url(); ?>module/AccountsModule">
				<i class="menu-icon fa fa-clipboard"></i>
				<span class="menu-text"> Accounts Module </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>


		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			<!-- module/HRPayroll -->
			<a href="<?php echo base_url(); ?>module/HRPayroll">
				<i class="menu-icon fa fa-users"></i>
				<span class="menu-text"> HR & Payroll </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>

		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>module/ReportsModule">
				<i class="menu-icon fa fa-calendar-check-o"></i>
				<span class="menu-text"> Reports Module </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>

		<?php if (!isset($CheckOwner) && !isset($CheckRenter) ) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>module/Administration">
				<i class="menu-icon fa fa-cogs"></i>
				<span class="menu-text"> Administration </span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>
	</ul>
<?php } elseif ($module == 'Administration') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>
			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/Administration" class="module_title">
				<span>Administration</span>
			</a>
		</li>

		<?php if (array_search("sms", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>sms">
					<i class="menu-icon fa fa-mobile"></i>
					<span class="menu-text"> Send SMS </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("store", $access) > -1
			|| array_search("storelist", $access) > -1
			|| array_search("store_ledger", $access) > -1
			|| isset($CheckSuperAdmin)
			|| isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-shopping-bag"></i>
					<span class="menu-text"> Store Info </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("store", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>store">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Entry
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storelist" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								Store List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<!-- <?php if (array_search("store_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>store_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("customer", $access) > -1
			|| array_search("supplier", $access) > -1
			|| array_search("brunch", $access) > -1
			|| array_search("category", $access) > -1
			|| array_search("unit", $access) > -1
			|| array_search("area", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
		<li class="<?php echo $_SERVER['REQUEST_URI']=='/renter'? 'open': ''?>">
			<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
				<i class="menu-icon fa fa-cog"></i>
				<span class="menu-text"> Settings </span>

				<b class="arrow fa fa-angle-down"></b>
			</a>

			<b class="arrow"></b>

			<ul class="submenu" <?php echo $_SERVER['REQUEST_URI']=='/renter'? 'nav-show': ''?> style="display: <?php echo $_SERVER['REQUEST_URI']=='/renter'? 'block': ''?>">
				<?php if (array_search("owner", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>owner">
							<i class="menu-icon fa fa-caret-right"></i>
							Owner Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<?php if (array_search("renter", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>renter" style="color:<?php echo $_SERVER['REQUEST_URI']=='/renter'? '#f2c05a': ''?>">
							<i class="menu-icon fa fa-caret-right" style="display: <?php echo $_SERVER['REQUEST_URI']=='/renter'? 'block': ''?>; "></i>
							Renter Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<?php if (array_search("floor", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>floor">
							<i class="menu-icon fa fa-caret-right"></i>
							Floor Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<?php if (array_search("grade", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>grade">
							<i class="menu-icon fa fa-caret-right"></i>
							Grade Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<!-- <?php if (array_search("supplier", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>supplier">
							<i class="menu-icon fa fa-caret-right"></i>
							Supplier Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?> -->

				<!-- <?php if (array_search("brunch", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>brunch">
							<i class="menu-icon fa fa-caret-right"></i>
							Add Branch
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?> -->

				<?php if (array_search("type", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>type">
							<i class="menu-icon fa fa-caret-right"></i>
							Type Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<?php if (array_search("unit", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>unit">
							<i class="menu-icon fa fa-caret-right"></i>
							Unit Entry
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>

				<?php if (array_search("month", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<li class="">
						<a href="<?php echo base_url(); ?>month">
							<i class="menu-icon fa fa-caret-right"></i>
							<span class="menu-text"> Month Entry </span>
						</a>
						<b class="arrow"></b>
					</li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif;?>

		<?php if($this->session->userdata('BRANCHid') == 1 && (isset($CheckSuperAdmin) || isset($CheckAdmin))) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>companyProfile">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Company Profile </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>user">
					<i class="menu-icon fa fa-user-plus"></i>
					<span class="menu-text"> Create User </span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckSuperAdmin) && $this->session->userdata('BRANCHid') == 1) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>user_activity">
					<i class="menu-icon fa fa-list"></i>
					<span class="menu-text"> User Activity</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (array_search("database_backup", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>database_backup">
					<i class="menu-icon fa fa-database"></i>
					<span class="menu-text"> Database Backup </span>
				</a>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'BillModule') { ?>
	<ul class="nav nav-list">

		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/BillModule" class="module_title">
				<span> Generate Module </span>
			</a>
		</li>

		<?php if (array_search("bills/generate", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>bills/generate">
					<i class="menu-icon fa fa-spinner" aria-hidden="true"></i>

					<span class="menu-text"> Bill Generate </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("billInvoiceMutiple", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>billInvoiceMutiple">
					<i class="menu-icon fa fa-th-list" aria-hidden="true"></i>

					<span class="menu-text"> Bill Invoice Muliple </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("bill_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>bill_record">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Bill Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		<?php if (array_search("acBillRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>acBillRecord">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> AC Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>
		
		<?php if (array_search("zamidariRent/generate", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>zamidariRent/generate">
					<i class="menu-icon fa fa-spinner" aria-hidden="true"></i>

					<span class="menu-text"> Zamindari Rent</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("zamindari_bill_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>zamindari_bill_record">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Zamindari Bill Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		
		<!-- <?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>currentStock">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Stock </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<!-- <?php if (array_search("quotation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>quotation">
					<i class="menu-icon fa fa-plus-square"></i>
					<span class="menu-text"> Quotation Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<?php if (
			array_search("service", $access) > -1
			|| array_search("servicelist", $access) > -1
			|| array_search("utilityRates", $access) > -1
			|| array_search("zamindariRates", $access) > -1
			|| isset($CheckSuperAdmin)
			|| isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-wrench"></i>
					<span class="menu-text"> Rates Info </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("utilityRates", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>utilityRates">
								<i class="menu-icon fa fa-caret-right"></i>
								Rates Entry
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("zamindariRates", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>zamindariRates">
								<i class="menu-icon fa fa-caret-right"></i>
								Zamindari Rates Entry
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<!-- <?php if (array_search("servicelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>servicelist" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								Service List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
					<!-- <?php if (array_search("service", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>service">
								<i class="menu-icon fa fa-caret-right"></i>
								Service Entry
							</a>

							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("servicelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>servicelist" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								Service List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("salesinvoice", $access) > -1
			|| array_search("returnList", $access) > -1
			|| array_search("sale_return_details", $access) > -1
			|| array_search("customerDue", $access) > -1
			|| array_search("customerPaymentReport", $access) > -1
			|| array_search("customer_payment_history", $access) > -1
			|| array_search("customerlist", $access) > -1
			|| array_search("productwiseSales", $access) > -1
			|| array_search("customerwiseSales", $access) > -1
			|| array_search("invoiceProductDetails", $access) > -1
			|| array_search("price_list", $access) > -1
			|| array_search("quotation_invoice_report", $access) > -1
			|| array_search("quotation_record", $access) > -1
			|| array_search("store_wise_report", $access) > -1
			|| array_search("billInvoice", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<!-- <?php if (array_search("salesinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>salesinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Sales Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("returnList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>returnList">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("sale_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>sale_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Due List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment History
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerlist">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>price_list">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Price List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>quotation_record">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<?php if (array_search("billInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>billInvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Bill Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<?php if (array_search("billInvoiceMutiple", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>billInvoiceMutiple">
								<i class="menu-icon fa fa-caret-right"></i>
								Mutiple Bill Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("store_bill_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>store_bill_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Bill Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storeDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storeDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storePaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storePaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storelist">
								<i class="menu-icon fa fa-caret-right"></i>
								Store List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					
				</ul>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->

<?php } elseif ($module == 'PaymentModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/PaymentModule" class="module_title">
				<span> Payment Module </span>
			</a>
		</li>

		<?php if (array_search("utility/payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>utility/payment">
					<i class="menu-icon fa fa-inbox"></i>
					<span class="menu-text"> Bill Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("paymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
		<li class="">
			<a href="<?php echo base_url(); ?>paymentRecord">
				<i class="menu-icon fa fa-th-list"></i>
				<span class="menu-text">Payment Record</span>
			</a>
			<b class="arrow"></b>
		</li>
		<?php endif; ?>

		<?php if (array_search("acPaymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>acPaymentRecord">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> AC Record </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("zamindariPaymentPage", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>zamindariPaymentPage">
					<i class="menu-icon fa fa-inbox"></i>
					<span class="menu-text"> Zomindari Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<!-- <?php if (array_search("purchaseReturns", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>purchaseReturns">
					<i class="menu-icon fa fa-rotate-right"></i>
					<span class="menu-text"> Purchase Return </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

	
<!-- 
		<?php if (array_search("AssetsEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>AssetsEntry">
					<i class="menu-icon fa fa-shopping-cart"></i>
					<span class="menu-text"> Assets Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<?php if (
			array_search("purchaseInvoice", $access) > -1
			|| array_search("renterDue", $access) > -1
			|| array_search("supplierPaymentReport", $access) > -1
			|| array_search("supplierList", $access) > -1
			|| array_search("returnsList", $access) > -1
			|| array_search("purchase_return_details", $access) > -1
			|| array_search("reorder_list", $access) > -1
			|| array_search("assets_report", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<!-- <?php if (array_search("assets_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>assets_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Assets Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("purchaseInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>purchaseInvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					

					<!-- <?php if (array_search("supplierDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>supplierDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
<!-- 
					<?php if (array_search("supplierPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>supplierPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Supplier Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("supplierList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>supplierList" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Supplier List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("returnsList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>returnsList">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("reorder_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>reorder_list">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Re-Order List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
					
					<?php if (array_search("paymentinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>paymentinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Payment Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("acinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>acinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								AC Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("store_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>store_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Renter Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Renter Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterList" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Renter List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("ownerList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>ownerList" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Owner List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>
	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'NoticeModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>

		<li>
			<a href="<?php echo base_url(); ?>module/NoticeModule" class="module_title">
				<span><?php echo isset($CheckOwner) || isset($CheckRenter) ? 'Notice' : 'Notification Module' ?></span>
			</a>
		</li>

		<?php if (array_search("check_bill", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>check_bill">
					<i class="menu-icon fa fa-balance-scale"></i>
					<span class="menu-text">  Check Bill </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("upcoming_bill", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>upcoming_bill">
					<i class="menu-icon fa fa-calendar-o"></i>
					<span class="menu-text"> Upcoming Bills </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("news_entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>news_entry">
					<i class="menu-icon fa fa-newspaper-o"></i>
					<span class="menu-text"> News Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("notice_entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>notice_entry">
					<i class="menu-icon fa fa-bell-o"></i>
					<span class="menu-text"> Notice Entry </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
			<li class="<?php echo $_SERVER['REQUEST_URI']=='/module/NoticeModule' || $_SERVER['REQUEST_URI']=='/notice_view' || $_SERVER['REQUEST_URI']=='/news_view' ? 'open': ''?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-bell-o"></i>
					<span class="menu-text"> Notice </span>
					<b class="arrow fa fa-angle-down"></b>

				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>notice_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Notice View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>			
					<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>news_view">
								<i class="menu-icon fa fa-caret-right"></i>
								News View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
		
				</ul>
			</li>
		<?php endif; ?>
	</ul>
<?php } elseif ($module == 'TransactionModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>

		<li>
			<a href="<?php echo base_url(); ?>module/TransactionModule" class="module_title">
				<span> Transaction </span>
			</a>
		</li>

		<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
			<li class="<?php echo $_SERVER['REQUEST_URI']=='/module/TransactionModule' || $_SERVER['REQUEST_URI']=='/bill_view' || $_SERVER['REQUEST_URI']=='/payment_view' || $_SERVER['REQUEST_URI']=='/due_view' ? 'open': ''?>">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-exchange"></i>
					<span class="menu-text"> Transaction</span>
					<b class="arrow fa fa-angle-down"></b>

				</a>

				<b class="arrow"></b>
				

				<ul class="submenu">
					<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bill_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Bill
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>			
					<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>payment_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Payment
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>due_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Due
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
		
				</ul>
			</li>
		<?php endif; ?>
	</ul>
<?php } elseif ($module == 'AccountsModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/AccountsModule" class="module_title">
				<span> Account Module </span>
			</a>
		</li>

		<?php if (array_search("cashTransaction", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>cashTransaction">
					<i class="menu-icon fa fa-medkit"></i>
					<span class="menu-text"> Cash Transaction </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("bank_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li>
				<a href="<?php echo base_url(); ?>bank_transactions">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Bank Transactions </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<!-- <?php if (array_search("customerPaymentPage", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>customerPaymentPage">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Customer Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<!-- <?php if (array_search("supplierPayment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>supplierPayment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Supplier Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<?php if (array_search("collection_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>collection_view">
					<i class="menu-icon fa fa-gg"></i>
					<span class="menu-text">Collection View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>cash_view">
					<i class="menu-icon fa fa-ils"></i>
					<span class="menu-text">Cash View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("loan_transactions", $access) > -1
			|| array_search("loan_view", $access) > -1
			|| array_search("loan_transaction_report", $access) > -1
			|| array_search("loan_ledger", $access) > -1
			|| array_search("loan_accounts", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Loan </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("loan_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("loan_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("loan_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("loan_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("loan_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>loan_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Loan Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>
		
		<?php if (
			array_search("investment_transactions", $access) > -1
			|| array_search("investment_transaction_report", $access) > -1
			|| array_search("investment_view", $access) > -1
			|| array_search("investment_ledger", $access) > -1
			|| array_search("investment_account", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Investment </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("investment_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_transactions">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transection
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("investment_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_view">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment View
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("investment_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("investment_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					
					<?php if (array_search("investment_account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>investment_account">
								<i class="menu-icon fa fa-caret-right"></i>
								Investment Account
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("account", $access) > -1
			|| array_search("bank_accounts", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li>
				<a href="" class="dropdown-toggle">
					<i class="menu-icon fa fa-bank"></i>
					<span class="menu-text"> Account Head </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li>
							<a href="<?php echo base_url(); ?>account">
								<i class="menu-icon fa fa-caret-right"></i>
								Transaction Accounts 
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
					<?php if (array_search("bank_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li>
							<a href="<?php echo base_url(); ?>bank_accounts">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Accounts
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>
		
		<?php if (
			array_search("check/entry", $access) > -1
			|| array_search("check/list", $access) > -1
			|| array_search("check/reminder/list", $access) > -1
			|| array_search("check/pending/list", $access) > -1
			|| array_search("check/dis/list", $access) > -1
			|| array_search("check/paid/list", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>

			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Cheque </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("check/entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/entry">
								<i class="menu-icon fa fa-caret-right"></i>
								New Cheque Entry
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/reminder/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/reminder/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Reminder Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/pending/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/pending/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Pending Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/dis/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/dis/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Dishonoured Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("check/paid/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>check/paid/list">
								<i class="menu-icon fa fa-caret-right"></i>
								Paid Cheque list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("TransactionReport", $access) > -1
			|| array_search("bank_transaction_report", $access) > -1
			|| array_search("cash_ledger", $access) > -1
			|| array_search("bank_ledger", $access) > -1
			|| array_search("cashStatment", $access) > -1
			|| array_search("BalanceSheet", $access) > -1
			|| array_search("balance_sheet", $access) > -1
			|| array_search("day_book", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Reports </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cashStatment">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Statement
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<!-- <?php if (array_search("balance_sheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>balance_sheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance Sheet
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
					
					<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance In Out
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("day_book", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>day_book">
								<i class="menu-icon fa fa-caret-right"></i>
								Day Book
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'HRPayroll') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/HRPayroll" class="module_title">
				<span>HR & Payroll</span>
			</a>
		</li>

		<?php if (array_search("salary_payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>salary_payment">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text"> Salary Payment </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("employee", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>employee">
					<i class="menu-icon fa fa-users"></i>
					<span class="menu-text"> Add Employee </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("designation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>designation">
					<i class="menu-icon fa fa-binoculars"></i>
					<span class="menu-text"> Add Designation </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("depertment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>depertment">
					<i class="menu-icon fa fa-plus-square"></i>
					<span class="menu-text"> Add Department </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("month", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>month">
					<i class="menu-icon fa fa-calendar"></i>
					<span class="menu-text"> Add Month </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("emplists/all", $access) > -1 
			|| array_search("emplists/active", $access) > -1 
			|| array_search("emplists/deactive", $access) > -1 
			|| array_search("salary_payment_report", $access) > -1 
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/all">
								<i class="menu-icon fa fa-caret-right"></i>
								All Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/active", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/active">
								<i class="menu-icon fa fa-caret-right"></i>
								Active Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?><?php if (array_search("emplists/deactive", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/deactive">
								<i class="menu-icon fa fa-caret-right"></i>
								Deactive Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Salary Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>

	</ul><!-- /.nav-list -->
<?php } elseif ($module == 'ReportsModule') { ?>
	<ul class="nav nav-list">
		<li class="active">
			<a href="<?php echo base_url(); ?>module/dashboard">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		<li>
			<a href="<?php echo base_url(); ?>module/ReportsModule" class="module_title">
				<span>Reports Module</span>
			</a>
		</li>

		<!-- <?php if (array_search("profitLoss", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>profitLoss">
					<i class="menu-icon fa fa-medkit"></i>
					<span class="menu-text"> Profit & Loss Report </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->

		<?php if (array_search("collection_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>collection_view">
					<i class="menu-icon fa fa-gg" aria-hidden="true"></i>
					<span class="menu-text">Collection View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>cash_view">
					<i class="menu-icon fa fa-ils"></i>
					<span class="menu-text">Cash View</span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?>

		<?php if (
			array_search("billInvoice", $access) > -1
			|| array_search("bill_record", $access) > -1
			|| array_search("renterDue", $access) > -1
			|| array_search("renterPaymentReport", $access) > -1
			|| array_search("supplierList", $access) > -1
			|| array_search("returnsList", $access) > -1
			|| array_search("purchase_return_details", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Bill Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("billInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>billInvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Bill Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bill_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bill_record">
								<i class="menu-icon fa fa-caret-right"></i>
								Bill Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Renter Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Renter Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("renterList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>renterList" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Renter List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("ownerList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>ownerList" target="_blank">
								<i class="menu-icon fa fa-caret-right"></i>
								<span class="menu-text"> Owner List </span>
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<!-- <?php if (array_search("returnsList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>returnsList">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Purchase Return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

				</ul>
			</li>
		<?php endif; ?>


		<?php if (
			array_search("paymentinvoice", $access) > -1
			|| array_search("paymentRecord", $access) > -1
			|| array_search("storeDue", $access) > -1
			|| array_search("storePaymentReport", $access) > -1
			|| array_search("storelist", $access) > -1
			|| array_search("customerPaymentReport", $access) > -1
			|| array_search("customer_payment_history", $access) > -1
			|| array_search("customerlist", $access) > -1
			|| array_search("productwiseSales", $access) > -1
			|| array_search("customerwiseSales", $access) > -1
			|| array_search("invoiceProductDetails", $access) > -1
			|| array_search("price_list", $access) > -1
			|| array_search("quotation_invoice_report", $access) > -1
			|| array_search("quotation_record", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file-o"></i>
					<span class="menu-text"> Payment Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("paymentinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>paymentinvoice">
								<i class="menu-icon fa fa-caret-right"></i>
								Payment Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("paymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>paymentRecord">
								<i class="menu-icon fa fa-caret-right"></i>
								Payment Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storeDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storeDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Due Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storePaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storePaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Store Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("storelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>storelist">
								<i class="menu-icon fa fa-caret-right"></i>
								Store List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<!-- <?php if (array_search("returnList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>returnList">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return list
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("sale_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>sale_return_details">
								<i class="menu-icon fa fa-caret-right"></i>
								Sale return Details
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerDue">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Due List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer Payment History
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>customerlist">
								<i class="menu-icon fa fa-caret-right"></i>
								Customer List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>price_list">
								<i class="menu-icon fa fa-caret-right"></i>
								Product Price List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->
<!-- 
					<?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Invoice
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

					<!-- <?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>quotation_record">
								<i class="menu-icon fa fa-caret-right"></i>
								Quotation Record
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?> -->

				</ul>
			</li>
		<?php endif; ?>


		<!-- <?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>currentStock">
					<i class="menu-icon fa fa-th-list"></i>
					<span class="menu-text"> Stock </span>
				</a>
				<b class="arrow"></b>
			</li>
		<?php endif; ?> -->


		<?php if (
			array_search("TransactionReport", $access) > -1
			|| array_search("bank_transaction_report", $access) > -1
			|| array_search("cash_ledger", $access) > -1
			|| array_search("bank_ledger", $access) > -1
			|| array_search("cashStatment", $access) > -1
			|| array_search("BalanceSheet", $access) > -1
			|| array_search("day_book", $access) > -1
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)
		) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file"></i>
					<span class="menu-text"> Accounts Report </span>

					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">
					<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Transaction Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<i class="menu-icon fa fa-caret-right"></i>
								Bank Ledger
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>cashStatment">
								<i class="menu-icon fa fa-caret-right"></i>
								Cash Statement
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<i class="menu-icon fa fa-caret-right"></i>
								Balance In Out
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("day_book", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>day_book">
								<i class="menu-icon fa fa-caret-right"></i>
								Day Book
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>


		<?php if (array_search("emplists/all", $access) > -1 
			|| array_search("emplists/active", $access) > -1 
			|| array_search("emplists/deactive", $access) > -1 
			|| array_search("salary_payment_report", $access) > -1 
			|| isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
			<li class="">
				<a href="<?php echo base_url(); ?>" class="dropdown-toggle">
					<i class="menu-icon fa fa-file-o"></i>
					<span class="menu-text"> Employee Report </span>
					<b class="arrow fa fa-angle-down"></b>
				</a>

				<b class="arrow"></b>

				<ul class="submenu">

					<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/all">
								<i class="menu-icon fa fa-caret-right"></i>
								All Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/active", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/active">
								<i class="menu-icon fa fa-caret-right"></i>
								Active Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("emplists/deactive", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>emplists/deactive">
								<i class="menu-icon fa fa-caret-right"></i>
								Deactive Employee List
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

					<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
						<li class="">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<i class="menu-icon fa fa-caret-right"></i>
								Salary Payment Report
							</a>
							<b class="arrow"></b>
						</li>
					<?php endif; ?>

				</ul>
			</li>
		<?php endif; ?>
	</ul><!-- /.nav-list -->
<?php } ?>