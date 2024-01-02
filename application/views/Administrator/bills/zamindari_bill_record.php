<style>
    .v-select{
		margin-top:-2.5px;
        float: right;
        min-width: 180px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
        height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
	#searchForm select{
		padding:0;
		border-radius: 4px;
	}
	#searchForm .form-group{
		margin-right: 5px;
	}
	#searchForm *{
		font-size: 13px;
	}
	.record-table{
		width: 100%;
		border-collapse: collapse;
	}
	.record-table thead{
		background-color: #0097df;
		color:white;
	}
	.record-table th, .record-table td{
		padding: 3px;
		border: 1px solid #454545;
	}
    .record-table th{
        text-align: center;
    }
</style>
<div id="billsRecord">
    
	<div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="form-group">
					<label>Search Type</label>
					<select class="form-control" v-model="searchType" @change="onChangeSearchType">
						<option value="">All</option>
						<option value="month">By Month</option>
					</select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == '' && months.length > 0 ? '' : 'none'}">
					<label>Month</label>
					<v-select v-bind:options="months" v-model="selectedMonth" label="month_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType != '' ? '' : 'none'}">
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType != '' ? '' : 'none'}">
					<input type="date" class="form-control" v-model="dateTo">
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: billSheets.length > 0 || billSheetDetails.length ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<table 
					class="record-table" 
					v-if="(searchTypesForDetails.includes(searchType))" 
					style="display:none" 
					v-bind:style="{display: (searchTypesForDetails.includes(searchType)) ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Store No</th>
							<th>Store Name</th>
							<th>Floor Name</th>
							<th>Owner Name</th>
							<th>Month</th>
							<th>Savings Deposit</th>
							<th>Service Charge </th>
							<th>Tax Surcharge </th>
							<th>Shop Rent </th>
							<th>Last Date </th>
							<th>Net Payable</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="bill in billSheetDetails">
							<td>{{ bill.invoice }}</td>
							<td>{{ bill.Store_No }}</td>
							<td>{{ bill.Store_Name }}</td>
                            <td>{{ bill?.Floor_Name }}</td>
							<td>{{ bill?.Owner_Name }}</td>
							<td>{{ bill?.month_name }}</td>
							<td>{{ bill?.savings_deposit }}</td>
							<td>{{ bill?.service_charge }}</td>
							<td>{{ bill?.tax_surcharge }}</td>
							<td>{{ bill?.shop_rent }}</td>
							<td>{{ bill?.last_date }}</td>
							<td>{{ bill?.net_payable }}</td>
							<td style="text-align:center;">
								<a href="" title="zamindar bill Invoice" v-bind:href="`/zamindar_bill_invoice_print/${bill.id}`" target="_blank"><i class="fa fa-file"></i></a>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="6" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + parseFloat(curr.savings_deposit)}, 0) }}</td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + parseFloat(curr.service_charge)}, 0) }}</td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + parseFloat(curr.tax_surcharge)}, 0) }}</td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + parseFloat(curr.shop_rent)}, 0) }}</td>
							<td></td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + parseFloat(curr.net_payable)}, 0) }}</td>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<template
					v-if="searchTypesForRecord.includes(searchType)"  
					style="display:none;" 
					v-bind:style="{display: searchTypesForRecord.includes(searchType) ? '' : 'none'}" >
					<table class="record-table">
						<thead>
							<tr>
                                <th>Sl</th>
                                <th>Month Name</th>
                                <th>Total Amount</th>
                                <th>Processed Date</th>
                                <th>Processed By</th>
                                <th>View</th>
							</tr>
						</thead>
						<tbody>
                            <tr v-for="(billSheet, ind) in billSheets">
                                <td>{{ ind + 1 }}</td>
                                <td style="text-align:left;">{{ billSheet.month_name }}</td>
                                <td style="text-align:center;">{{ billSheet.total_amount }}</td>
                                <td style="text-align:center;">{{ billSheet.process_date }}</td>
                                <td style="text-align:left;">{{ billSheet.added_by }}</td>
                                <td style="text-align: center;">
                                    <a href="" @click.prevent="viewBillSheet(billSheet.id)" class="" title="View">
                                        <i class="fa fa-file"></i>
                                    </a>
                                </td>
                            </tr>
						</tbody>
					</table>
				</template>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#billsRecord',
		data(){
			return {
				searchType: '',
				dateFrom: moment().format('YYYY-MM-DD'),
				dateTo: moment().format('YYYY-MM-DD'),
                processingDate: moment().format('YYYY-MM-DD'),

                months: [],
                selectedMonth: null,
                billSheets: [],
                billSheetDetails: [],
				searchTypesForDetails: [''],
				searchTypesForRecord: ['month']
			}
		},
        created() {
            this.getMonths();
        },
		methods: {
			onChangeSearchType() {
                this.billSheets = [];
				this.billSheetDetails = [];
			},
            getMonths() {
                axios.get('/get_months').then(res => {
                    this.months = res.data;
                })
            },
			getSearchResult(){
				if(this.searchType != '') {
					this.selectedMonth = null;
				}
				if(this.searchTypesForDetails.includes(this.searchType)){
                    this.getBillDetails();
				} else {
					this.getBillRecord();
				}
			},
			getBillRecord(){
				let filter = {
					userFullName: this.selectedUser == null || this.selectedUser.FullName == '' ? '' : this.selectedUser.FullName,
					customerId: this.selectedCustomer == null || this.selectedCustomer.Customer_SlNo == '' ? '' : this.selectedCustomer.Customer_SlNo,
					employeeId: this.selectedEmployee == null || this.selectedEmployee.Employee_SlNo == '' ? '' : this.selectedEmployee.Employee_SlNo,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo
				}

				let url = '/get_zamindari_bill';
			
				axios.post(url, filter)
				.then(res => {
					this.billSheets = res.data.zamindaribills;
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
			getBillDetails(){
				console.log(this.selectedMonth)
				let filter = {
					monthId: this.selectedMonth == null || this.selectedMonth.month_id == '' ? '' : this.selectedMonth.month_id,
				}

                axios.post('/get_zamindari_bill_details', filter).then(res => {
                    this.billSheetDetails = res.data;
                })
			},
			viewBillSheet(id) {
				window.open(`/zamindari_bill_month/${id}`, '_blank');
            },
			async print(){
				let dateText = '';
				if(this.dateFrom != '' && this.dateTo != ''){
					dateText = `Statement from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
				}

				let userText = '';
				if(this.selectedUser != null && this.selectedUser.FullName != '' && this.searchType == 'user'){
					userText = `<strong>Sold by: </strong> ${this.selectedUser.FullName}`;
				}

				let customerText = '';
				if(this.selectedCustomer != null && this.selectedCustomer.Customer_SlNo != '' && this.searchType == 'customer'){
					customerText = `<strong>Customer: </strong> ${this.selectedCustomer.Customer_Name}<br>`;
				}

				let employeeText = '';
				if(this.selectedEmployee != null && this.selectedEmployee.Employee_SlNo != '' && this.searchType == 'employee'){
					employeeText = `<strong>Employee: </strong> ${this.selectedEmployee.Employee_Name}<br>`;
				}

				let productText = '';
				if(this.selectedProduct != null && this.selectedProduct.Product_SlNo != '' && this.searchType == 'quantity'){
					productText = `<strong>Product: </strong> ${this.selectedProduct.Product_Name}`;
				}

				let categoryText = '';
				if(this.selectedCategory != null && this.selectedCategory.ProductCategory_SlNo != '' && this.searchType == 'category'){
					categoryText = `<strong>Category: </strong> ${this.selectedCategory.ProductCategory_Name}`;
				}


				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Sales Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								${userText} ${customerText} ${employeeText} ${productText} ${categoryText}
							</div>
							<div class="col-xs-6 text-right">
								${dateText}
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.head.innerHTML += `
					<style>
						.record-table{
							width: 100%;
							border-collapse: collapse;
						}
						.record-table thead{
							background-color: #0097df;
							color:white;
						}
						.record-table th, .record-table td{
							padding: 3px;
							border: 1px solid #454545;
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
				reportWindow.document.body.innerHTML += reportContent;

				if(this.searchType == '' || this.searchType == 'user'){
					let rows = reportWindow.document.querySelectorAll('.record-table tr');
					rows.forEach(row => {
						row.lastChild.remove();
					})
				}


				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>