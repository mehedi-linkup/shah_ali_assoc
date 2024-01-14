<?php $this->load->view('Administrator/dashboard_style'); ?>

<?php

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

$companyInfo = $this->db->query("select * from tbl_company c order by c.Company_SlNo desc limit 1")->row();




$module = $this->session->userdata('module');
if ($module == 'dashboard' or $module == '') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- Header Logo -->
			<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
				<div class="col-md-12 header" style="height: 130px;">
					<img src="<?php echo base_url(); ?>assets/images/big_logo.jpg" class="img img-responsive center-block">
				</div>
			<?php endif; ?>
			<div class="col-md-10 col-md-offset-1">
				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#e1e1ff;" onmouseover="this.style.background = '#d2d2ff'" onmouseout="this.style.background = '#e1e1ff'">
							<a href="<?php echo base_url(); ?>module/BillModule">
								<div class="logo">
									<i class="fa fa-credit-card"></i>
								</div>
								<div class="textModule">
									Generate Module
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#dcf5ea;" onmouseover="this.style.background = '#bdecd7'" onmouseout="this.style.background = '#dcf5ea'">
							<a href="<?php echo base_url(); ?>module/PaymentModule">
								<div class="logo">
									<i class="fa fa-plug" aria-hidden="true"></i>
								</div>
								<div class="textModule">
									Payment Module
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- module/AccountsModule -->
				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#A7ECFB;" onmouseover="this.style.background = '#85e6fa'" onmouseout="this.style.background = '#A7ECFB'">
							<a href="<?php echo base_url(); ?>module/AccountsModule">
								<div class="logo">
									<i class="fa fa-clipboard"></i>
								</div>
								<div class="textModule">
									Accounts Module
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- module/HRPayroll -->
				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#ecffd9;" onmouseover="this.style.background = '#cfff9f'" onmouseout="this.style.background = '#ecffd9'">
							<a href="<?php echo base_url(); ?>module/HRPayroll">
								<div class="logo">
									<i class="fa fa-users"></i>
								</div>
								<div class="textModule">
									HR & Payroll
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- module/ReportsModule -->
				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#c6e2ff;" onmouseover="this.style.background = '#91c8ff'" onmouseout="this.style.background = '#c6e2ff'">
							<a href="<?php echo base_url(); ?>module/ReportsModule">
								<div class="logo">
									<i class="fa fa-calendar-check-o"></i>
								</div>
								<div class="textModule">
									Reports Module
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#e6e6ff;" onmouseover="this.style.background = '#b9b9ff'" onmouseout="this.style.background = '#e6e6ff'">
							<a href="<?php echo base_url(); ?>module/Administration">
								<div class="logo">
									<i class="fa fa-cogs"></i>
								</div>
								<div class="textModule">
									Administration
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#d8ebeb;" onmouseover="this.style.background = '#bddddd'" onmouseout="this.style.background = '#d8ebeb'">
							<a href="<?php echo base_url(); ?>graph">
								<div class="logo">
									<i class="fa fa-bar-chart"></i>
								</div>
								<div class="textModule">
									Business Monitor
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (!isset($CheckOwner) && !isset($CheckRenter)) : ?>
					<div class="col-md-3 col-xs-6 section4">
						<div class="col-md-12 section122" style="background-color:#ffe3d7;" onmouseover="this.style.background = '#ffc0a6'" onmouseout="this.style.background = '#ffe3d7'">
							<a href="<?php echo base_url(); ?>Login/logout">
								<div class="logo">
									<i class="fa fa-sign-out"></i>
								</div>
								<div class="textModule">
									LogOut
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'Administration') { ?>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> Administration Module </h3>
				</div>
				<?php if (array_search("store", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>store">
								<div class="logo">
									<i class="menu-icon fa fa-shopping-bag"></i>
								</div>
								<div class="textModule">
									Store Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<!-- <?php if (array_search("service", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>service">
								<div class="logo">
									<i class="menu-icon fa fa-wrench"></i>
								</div>
								<div class="textModule">
									Service Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("productlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>productlist" target="_blank">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule">
									Product list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("product_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>product_ledger">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule">
									Product Ledger
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("damageEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>damageEntry">
								<div class="logo">
									<i class="menu-icon fa fa-plus-circle"></i>
								</div>
								<div class="textModule">
									Damage Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("damageList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>damageList">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Damage List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("product_transfer", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>product_transfer">
								<div class="logo">
									<i class="menu-icon fa fa-exchange"></i>
								</div>
								<div class="textModule">
									Product Transfer
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("transfer_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>transfer_list">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Transfer List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("received_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>received_list">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Received List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<?php if (array_search("owner", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>owner">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule">
									Owner Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("renter", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renter">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule">
									Renter Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("floor", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>floor">
								<div class="logo">
									<i class="menu-icon fa fa-building-o"></i>
								</div>
								<div class="textModule">
									Floor Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("grade", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>grade">
								<div class="logo">
									<i class="menu-icon fa fa-th-large"></i>
								</div>
								<div class="textModule">
									Grade Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<!-- <?php if (array_search("supplier", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>supplier">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule">
									Supplier Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("brunch", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>brunch">
								<div class="logo">
									<i class="menu-icon fa fa-bank"></i>
								</div>
								<div class="textModule">
									Add Branch
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<?php if (array_search("type", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>type">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Type entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("unit", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>unit">
								<div class="logo">
									<i class="menu-icon fa fa-sitemap"></i>
								</div>
								<div class="textModule">
									Unit Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<!-- <?php if (array_search("area", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>area">
								<div class="logo">
									<i class="menu-icon fa fa-globe"></i>
								</div>
								<div class="textModule">
									Add Area
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->


				<?php if ($this->session->userdata('BRANCHid') == 1 && (isset($CheckSuperAdmin) || isset($CheckAdmin))) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>companyProfile">
								<div class="logo">
									<i class="menu-icon fa fa-bank"></i>
								</div>
								<div class="textModule">
									Company Profile
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (isset($CheckSuperAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>user">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule">
									Create User
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'BillModule') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="col-md-12 header">

					<h3>Bill Module </h3>
				</div>

				<?php if (array_search("electricity", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>electricity">
								<div class="logo">
									<i class="menu-icon fa fa-bolt"></i>
								</div>
								<div class="textModule" style="margin-top: 0px;">
									Electricity Bill Generate
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("bills/generate", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bills/generate">
								<div class="logo">
									<i class="menu-icon fa fa-spinner"></i>
								</div>
								<div class="textModule">
									Bill Generate
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

			
				<!-- <?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>currentStock">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Stock
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("quotation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>quotation">
								<div class="logo">
									<i class="menu-icon fa fa-plus"></i>
								</div>
								<div class="textModule">
									Quotation Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("salesinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>salesinvoice">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Sales Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("returnList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>returnList">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule">
									Sale return list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("sale_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>sale_return_details">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule">
									Sale return Details
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->

				<!-- <?php if (array_search("customerDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customerDue">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Customer Due List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->

				<!-- <?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-credit-card-alt"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Customer Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<div class="logo">
									<i class="menu-icon fa fa-credit-card-alt"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Customer Payment History
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customerlist">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Customer List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>price_list">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule">
									Product Price List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Quotation Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>quotation_record">
								<div class="logo">
									<i class="menu-icon fa fa-file"></i>
								</div>
								<div class="textModule">
									Quotation Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->

				<?php if (array_search("billRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>billRecord">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Bill Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("utilityRates", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>utilityRates">
								<div class="logo">
									<i class="menu-icon fa fa-wrench"></i>
								</div>
								<div class="textModule">
									Rates Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("billInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>billInvoice">
								<div class="logo">
									<i class="menu-icon fa fa-file"></i>
								</div>
								<div class="textModule">
									Bill Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("store_bill_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>store_bill_report">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Store Bill Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("storeDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storeDue">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Store Due Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("storePaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storePaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="margin-top: 1px;">
									Store Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("storelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storelist">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Store List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php } elseif ($module == 'PaymentModule') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> Payment Module </h3>
				</div>
				<?php if (array_search("utility/payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>utility/payment">
								<div class="logo">

									<i class="menu-icon fa fa-inbox"></i>
								</div>
								<div class="textModule">
									Bill Payment
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("paymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>paymentRecord">
								<div class="logo">

									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Payment Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("acPaymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>acPaymentRecord">
								<div class="logo">

									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									AC Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("paymentinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>paymentinvoice">
								<div class="logo">

									<i class="menu-icon fa fa-file"></i>
								</div>
								<div class="textModule">
									Payment Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("acinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>acinvoice">
								<div class="logo">

									<i class="menu-icon fa fa-file"></i>
								</div>
								<div class="textModule">
									AC Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("store_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>store_payment_report">
								<div class="logo">

									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Store Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("renterDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renterDue">
								<div class="logo">

									<i class="menu-icon fa fa-file-text"></i>
								</div>
								<div class="textModule">
									Renter Due Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("renterPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renterPaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Renter Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- <?php if (array_search("utilityReturns", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>purchaseReturns">
								<div class="logo">
									<i class="menu-icon fa fa-rotate-right"></i>
								</div>
								<div class="textModule">
									Purchase Return
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("purchaseRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>purchaseRecord">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule">
									Purchase Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("AssetsEntry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>AssetsEntry">
								<div class="logo">
									<i class="menu-icon fa fa-line-chart"></i>
								</div>
								<div class="textModule">
									Asset Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("purchaseInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>purchaseInvoice">
								<div class="logo">
									<i class="menu-icon fa fa-print"></i>
								</div>
								<div class="textModule">
									Purchase Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("supplierDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>supplierDue">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Supplier Due Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("supplierPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>supplierPaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-credit-card-alt"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Supplier Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("supplierList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>supplierList" target="_blank">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Supplier List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("returnsList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>returnsList">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Purchase Return list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule" style="margin-top: 0; line-height: 14px;">
									Purchase Return Details
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("reorder_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>reorder_list">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Re-Order List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->
<?php } elseif ($module == 'NoticeModule') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> Notification Module </h3>
				</div>
				<?php if (array_search("check_bill", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check_bill">
								<div class="logo">
									<i class="menu-icon fa fa-balance-scale"></i>
								</div>
								<div class="textModule">
									Check Bill
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("upcoming_bill", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>upcoming_bill">
								<div class="logo">
									<i class="menu-icon fa fa-calendar-o"></i>

								</div>
								<div class="textModule">
									Upcoming Bills
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("news_entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>news_entry">
								<div class="logo">
									<i class="menu-icon fa fa-newspaper-o"></i>
								</div>
								<div class="textModule">
									News Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("notice_entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>notice_entry">
								<div class="logo">
									<i class="menu-icon fa fa-bell-o"></i>
								</div>
								<div class="textModule">
									Notice Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php } elseif ($module == 'AccountsModule') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> Accounts Module </h3>
				</div>
				<?php if (array_search("cashTransaction", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cashTransaction">
								<div class="logo">
									<i class="menu-icon fa fa-medkit"></i>
								</div>
								<div class="textModule">
									Cash Transaction
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("bank_transactions", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_transactions">
								<div class="logo">
									<i class="menu-icon fa fa-bank"></i>
								</div>
								<div class="textModule">
									Bank Transactions
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("collection_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>collection_view">
								<div class="logo">
									<i class="menu-icon fa fa-gg"></i>
								</div>
								<div class="textModule" style="line-height: 13px;">
									Collection View
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cash_view">
								<div class="logo">
									<i class="menu-icon fa fa-ils"></i>
								</div>
								<div class="textModule" style="line-height: 13px;">
									Cash View
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("account", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>account">
								<div class="logo">
									<i class="menu-icon fa fa-plus-square-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Transaction Accounts
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("bank_accounts", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_accounts">
								<div class="logo">
									<i class="menu-icon fa fa-bank"></i>
								</div>
								<div class="textModule">
									Bank Accounts
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("check/entry", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/entry">
								<div class="logo">
									<i class="menu-icon fa fa-credit-card-alt"></i>
								</div>
								<div class="textModule">
									Cheque Entry
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("check/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/list">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									All Cheque list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("check/reminder/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/reminder/list">
								<div class="logo">
									<i class="menu-icon fa fa-bell"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Reminder Cheque list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("check/pending/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/pending/list">
								<div class="logo">
									<i class="menu-icon fa fa-hourglass-half"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Pending Cheque list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("check/dis/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/dis/list">
								<div class="logo">
									<i class="menu-icon fa fa-times"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Dishonoured Cheque list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("check/paid/list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>check/paid/list">
								<div class="logo">
									<i class="menu-icon fa fa-check-square-o"></i>
								</div>
								<div class="textModule">
									Paid Cheque list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>TransactionReport" target="_blank">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Cash Transaction Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Bank Transaction Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Bank Ledger
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cashStatment">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Cash Statement
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<div class="logo">
									<i class="menu-icon fa fa-credit-card-alt"></i>
								</div>
								<div class="textModule">
									Balance In Out
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'HRPayroll') { ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> HR & Payroll Module </h3>
				</div>
				<?php if (array_search("salary_payment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>salary_payment">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule">
									Salary Payment
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("employee", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>employee">
								<div class="logo">
									<i class="menu-icon fa fa-users"></i>
								</div>
								<div class="textModule">
									Add Employee
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>emplists/all">
								<div class="logo">
									<i class="menu-icon fa fa-list-ol"></i>
								</div>
								<div class="textModule">
									All Employee List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("designation", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>designation">
								<div class="logo">
									<i class="menu-icon fa fa-binoculars"></i>
								</div>
								<div class="textModule">
									Add Designation
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("depertment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>depertment">
								<div class="logo">
									<i class="menu-icon fa fa-plus-square"></i>
								</div>
								<div class="textModule">
									Add Department
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("month", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>month">
								<div class="logo">
									<i class="menu-icon fa fa-calendar"></i>
								</div>
								<div class="textModule">
									Add Month
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Salary Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

			</div>
			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'ReportsModule') { ?>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<h3> Reports Module </h3>
				</div>

				<!-- <?php if (array_search("profitLoss", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>profitLoss">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Profit & Loss Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<?php if (array_search("collection_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>collection_view">
								<div class="logo">
									<i class="menu-icon fa fa-gg"></i>
								</div>
								<div class="textModule">
									Collection View
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("cash_view", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cash_view">
								<div class="logo">
									<i class="menu-icon fa fa-ils"></i>
								</div>
								<div class="textModule">
									Cash View
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("billInvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>billInvoice">
								<div class="logo">
									<i class="menu-icon fa fa-print"></i>
								</div>
								<div class="textModule">
									Bill invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("billRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>billRecord">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule">
									Bill Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("renterDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renterDue">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Renter Due Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("renterPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renterPaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Renter Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("renterList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>renterList" target="_blank">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Renter List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("ownerList", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>ownerList">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Owner List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<!-- <?php if (array_search("purchase_return_details", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>purchase_return_details">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Purchase Return Details
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<?php if (array_search("paymentinvoice", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>paymentinvoice">
								<div class="logo">
									<i class="menu-icon fa fa-print"></i>
								</div>
								<div class="textModule">
									Payment invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("paymentRecord", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>paymentRecord">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Payment Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("storeDue", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storeDue">
								<div class="logo">
									<i class="menu-icon fa fa-rotate-right"></i>
								</div>
								<div class="textModule">
									Store Due Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("storePaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storePaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-list-ul"></i>
								</div>
								<div class="textModule">
									Store Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("storelist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>storelist">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Store List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<!-- <?php if (array_search("customerPaymentReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customerPaymentReport">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Customer Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("customer_payment_history", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customer_payment_history">
								<div class="logo">
									<i class="menu-icon fa fa-user-plus"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Customer Payment History
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("customerlist", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>customerlist">
								<div class="logo">
									<i class="menu-icon fa fa-th-list"></i>
								</div>
								<div class="textModule">
									Customer List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("price_list", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>price_list">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									Product price list
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("quotation_invoice_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>quotation_invoice_report">
								<div class="logo">
									<i class="fa fa-sticky-note-o" aria-hidden="true"></i>
								</div>
								<div class="textModule">
									Quotation Invoice
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("quotation_record", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>quotation_record">
								<div class="logo">
									<i class="fa fa-file-text" aria-hidden="true"></i>
								</div>
								<div class="textModule">
									Quotation Record
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<!-- <?php if (array_search("currentStock", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>currentStock">
								<div class="logo">
									<i class="fa fa-shopping-basket" aria-hidden="true"></i>
								</div>
								<div class="textModule">
									Stock
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?> -->
				<?php if (array_search("TransactionReport", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>TransactionReport">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Cash Transaction Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("bank_transaction_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_transaction_report">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Bank Transaction Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("cash_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cash_ledger">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Bank Ledger
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("bank_ledger", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6 ">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>bank_ledger">
								<div class="logo">
									<i class="menu-icon fa fa-file-text-o"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Bank Ledger
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("cashStatment", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>cashStatment">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule">
									Cash Statement
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<?php if (array_search("BalanceSheet", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>BalanceSheet">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule">
									Balance In Out
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("emplists/all", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>emplists/all">
								<div class="logo">
									<i class="menu-icon fa fa-list"></i>
								</div>
								<div class="textModule">
									All Employee List
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
				<?php if (array_search("salary_payment_report", $access) > -1 || isset($CheckSuperAdmin) || isset($CheckAdmin)) : ?>
					<div class="col-md-2 col-xs-6">
						<div class="col-md-12 section20">
							<a href="<?php echo base_url(); ?>salary_payment_report">
								<div class="logo">
									<i class="menu-icon fa fa-money"></i>
								</div>
								<div class="textModule" style="line-height: 13px; margin-top: 0;">
									Salary Payment Report
								</div>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'AssetManagement') { ?>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<img src="<?php echo base_url(); ?>assets/erp.jpg" class="img img-responsive center-block">
				</div>
				<div class="col-md-12 txtBody">
					Asset Management
				</div>
			</div>
			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->

<?php } elseif ($module == 'ProductionModule') { ?>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Header Logo -->
				<div class="col-md-12 header">
					<img src="<?php echo base_url(); ?>assets/erp.jpg" class="img img-responsive center-block">
				</div>
				<div class="col-md-12 txtBody">
					Production Module
				</div>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->
<?php } ?>

<?php if (isset($CheckOwner) || isset($CheckRenter)) : ?>

	<style>
		.view-all-news {
			font-weight: bold;
			color: white !important;
			border: 1px solid;
			padding: 4px 11px;
			height: 32px;
			border-radius: 5px;
			background: #72c02c !important;
		}
		.view-all-news a {
			color: white !important;
			text-decoration: none;
		}
		.breakingNews a {
			color: #ff0000ab;
		}
		.breakingNews {
			display: flex;
			margin-bottom: 20px;
		}
		.breakingNews i {
			color: #72c02c;
			margin-right: 5px;
		}
		.news-panel {
			height: 400px; 
			overflow: hidden; 
			border: 1px solid #ddd; 
			position: relative;
		}

		.news-content {
			padding: 15px; 
			animation: scroll-up 15s linear infinite; 
			animation-play-state: running;
		}

		.news-content:hover {
			animation-play-state: paused; /* Pause the animation on hover */
		}

		#notice-board-ticker ul li {
			list-style: none;
			background: url("/assets/extra/img/bullet_tick.png") no-repeat top left;
			margin-bottom: 5px;
		}
		#notice-board-ticker ul a {
			margin-left: 20px;
			border-bottom: 1px dotted #666;
		}

		@keyframes scroll-up {
		0% {
			transform: translateY(100%); 
		}

		100% {
			transform: translateY(-100%); 
		}
		}
  </style>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="breakingNews">
			<?php 
				$this->load->model('Model_table', "mt", TRUE);

				$userData = $this->session->userdata();
				$userTableData = $this->db->query("select * from tbl_user where User_SlNo = ". $userData['userId'])->row();
				$clauses = "";
				if($userTableData->UserType == 'o') {
					$clauses .= "and s.owner_id = '$userTableData->ref_id'";
				} else if($userTableData->UserType == 'r') {
					$clauses .= "and s.renter_id = '$userTableData->ref_id'";
				}

                $billDetails = $this->mt->lastBillDetails($clauses);
			?>
			
			<marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4">
				<ul class="list-inline">
				<?php foreach ($billDetails as $key => $value) { ?>
				<li style="font-size:16px;font-weight:bold;"><i class="fa fa-square"></i><a href="javascript:void(0)"><?php echo $value->bill_text ?></a></li>
				<?php } ?>
				</ul>
			</marquee>
			<!-- <span class="view-all-news"> -->
				<a class="btn btn-sm btn-warning" href="javascript:void(0)">Recent Bills</a>
			<!-- </span> -->
		</div>
	</div>

	<div class="col-md-7 col-xs-7">
		<div class="panel panel-default news-panel">
			<h2 style="padding:15px">নোটিশ বোর্ড</h2>
			<?php  
				$startOfWeek = date('Y-m-d', strtotime('monday this week'));
				$currentDate = date('Y-m-d');
				$notices = $this->db->select('*')->get('tbl_notice')->result();

				foreach ($notices as $notice) {
					if ($notice->add_date >= $startOfWeek && $notice->add_date <= $currentDate) {
						$notice->newStatus = 'n';
					}
				}
			?>

			<div id="notice-board-ticker">
				<ul>

					<?php foreach ($notices as $key=>$notice) { ?>
						<li>
							<a href="<?php echo base_url() . 'notice_view/' . $notice->notice_code ?>" title="<?php echo $notice->notice_title ?>"><?php echo $notice->notice_title ?></a><strong style="color:red"><?php echo @$notice->newStatus == 'n'? '(নতুন)' : ''?></strong>
						</li>
					<?php } ?>
				</ul>	
				<a style="position:absolute;bottom:0;" class="btn right" href="<?php echo base_url(); ?>notice_view" title="সকল নোটিশ">সকল</a>
			</div>
		</div>
	</div>

	<div class="col-md-5 col-xs-5">
		<div class="panel panel-default news-panel">
			<div class="panel-body news-content">
				<?php  
					$news = $this->db->select('*')->get('tbl_news')->result();

					foreach ($news as $item) {
						if ($item->add_date >= $startOfWeek && $item->add_date <= $currentDate) {
							$item->newStatus = 'n';
						}
					}
					?>
				<?php foreach ($news as $key => $value) { ?>
					<p style="margin-bottom: 15px;"><span style="color:green;font-style:italic;">Date: <?php echo $value->add_date ?></span><br><a href="<?php echo base_url() . 'news_view/' . $value->news_code ?>" title="<?php echo $value->news_title ?>"><?php echo $value->news_title ?></a><strong style="color:red"> (নতুন)</strong></p>
				<?php } ?>						
			</div>
			<div style="position: absolute;bottom: 0;"><a class="btn btn-primary" style="float:right" href="<?php echo base_url(); ?>news_view" title="সকল নোটিশ">সকল</a></div>
		</div>
	</div>
</div>
<?php endif; ?>