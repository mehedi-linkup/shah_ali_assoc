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
						<option value="month">By Month summary</option>
					</select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == '' && months.length > 0 ? '' : 'none'}">
					<label>Month </label>
					<v-select v-bind:options="months" v-model="selectedMonth" label="month_name"></v-select>
				</div>

				<div class="form-group">
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group">
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
							<th>Month Name</th>
							<th>Floor Name</th>
							<th>Electricity Unit</th>
							<th>Electricity Bill</th>
							<th>Generator Bill</th>
							<th>AC Bill</th>
							<th>Other Bill</th>
							<th>Net Payable</th>
							<th>Last Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="bill in billSheetDetails">
							<tr>
								<td colspan="13" style="text-align:center;text-transform:uppercase;background-color:#ffa825">{{ bill.floor_name }}</td>
							</tr>
							<tr v-for="(bill, i) in bill.stores">
								<td>{{ bill.invoice }}</td>
								<td>{{ bill.Store_No }}</td>
								<td>{{ bill.Store_Name }}</td>
								<td>{{ bill.month_name }}</td>
								<td>{{ bill?.Floor_Name }}</td>
								<td>{{ bill?.electricity_unit }}</td>
								<td>{{ bill?.electricity_bill }}</td>
								<td>{{ bill?.generator_bill }}</td>
								<td>{{ bill?.ac_bill }}</td>
								<td>{{ bill?.others_bill }}</td>
								<td>{{ bill?.net_payable }}</td>
								<td>{{ bill?.last_date }}</td>
								<td style="text-align:center;">
									<a href="" title="bill Invoice" v-bind:href="`/bill_invoice_print/${bill.id}`" target="_blank"><i class="fa fa-file"></i></a>
								</td>
							</tr>
						</template>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="5" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.electricity_unit}, 0) }, 0) }}</td>
							<td style="text-align:right;">{{ parseFloat(billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.electricity_bill}, 0) }, 0) ).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.generator_bill}, 0) }, 0) ).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.ac_bill}, 0) }, 0) ).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.others_bill}, 0) }, 0) ).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(billSheetDetails.reduce((prev, curr)=>{return prev + +curr.stores.reduce((p, c)=>{return p + +c.net_payable}, 0) }, 0) ).toFixed(2) }}</td>
							<td></td>
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
				console.log(this.searchType);
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

				let url = '/get_bill_sheet';
				// if(this.recordType == 'with_details'){
				// 	url = '/get_bill_sheet_record';
				// }

				axios.post(url, filter)
				.then(res => {
					// if(this.recordType == 'with_details'){
					// 	this.sales = res.data;
					// } else {
						this.billSheets = res.data.billSheets;
					// }
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
			getBillDetails(){
				let filter = {
					monthId: this.selectedMonth == null || this.selectedMonth.month_id == '' ? '' : this.selectedMonth.month_id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo
				}

                axios.post('/get_bill_details', filter).then(res => {
                    // this.billSheetDetails = res.data.filter(item => item.net_payable > 0);
					let stores =  res.data.filter(item => item.net_payable > 0);
					stores = _.chain(stores).groupBy('floor_id')
							.map(store => {
									return {
										floor_id: store[0].floor_id,
										floor_name: store[0].Floor_Name,
										floor_ranking: store[0].Floor_Ranking,
										stores: store
									}
								}).value()
					stores = _.orderBy(stores, ['floor_ranking'], ['asc'])
					this.billSheetDetails = stores;
                })
			},
			viewBillSheet(id) {
                window.open(`/bill_sheet/${id}`, '_blank');
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
								<h3>Bill Record</h3>
							</div>
						</div>
						<div class="row">
							<!--<div class="col-xs-6">
								${userText} ${customerText} ${employeeText} ${productText} ${categoryText}
							</div>
							<div class="col-xs-6 text-right">
								${dateText}
							</div>-->
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

				// if(this.searchType == '' || this.searchType == 'user'){
				// 	let rows = reportWindow.document.querySelectorAll('.record-table tr');
				// 	rows.forEach(row => {
				// 		row.lastChild.remove();
				// 	})
				// }


				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>