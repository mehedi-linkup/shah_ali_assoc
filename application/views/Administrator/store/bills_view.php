<style>
	.v-select{
		margin-bottom: 5px;
        float: right;
        min-width: 200px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
        height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options {
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
    #salaryReport label{
        font-size: 13px;
		margin-top: 3px;
    }
    #salaryReport select{
        border-radius: 3px;
        padding: 0px;
		font-size: 13px;
    }
    #salaryReport .form-group{
        margin-right: 10px;
    }
</style>

<div id="salaryReport">
	<div class="row" style="border-bottom:1px solid #ccc;padding: 10px 0;">
		<div class="col-md-12">
			<form class="form-inline" @submit.prevent="showReport">
				<div class="form-group">
					<label>Month</label>
					<select class="form-control" style="min-width:150px;" v-bind:style="{display: months.length > 0 ? 'none' : ''}"></select>
					<v-select v-bind:options="months" v-model="selectedMonth" label="month_name"
							style="display:none"
							v-bind:style="{display: months.length > 0 ? '' : 'none'}"
					></v-select>
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" class="search-button" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top: 10px;display:none;" v-bind:style="{display: bills.length > 0 ? '' : 'none'}">
		<div class="col-md-12">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<div style="display:none;" v-bind:style="{display: bills.length > 0 ? '' : 'none'}">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Store No</th>
								<th>Store Name</th>
								<th>Renter Name</th>
								<th>Owner Name</th>
								<th>Month</th>
								<th>Previous Unit</th>
								<th>Current Unit</th>
								<th>Total Unit</th>
								<th>Electricity bill</th>
								<th>Generator_bill</th>
								<th>Ac bill</th>
								<th>Others bill</th>
								<th>Net Payable</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(bill, sl) in bills">
								<td>{{ sl + 1 }}</td>
								<td>{{ bill.Store_No }}</td>
								<td>{{ bill.Store_Name }}</td>
								<td>{{ bill.Renter_Name }}</td>
								<td>{{ bill.Owner_Name }}</td>
								<td>{{ bill.month_name }}</td>
								<td>{{ bill.previous_unit }}</td>
								<td>{{ bill.current_unit }}</td>
								<td style="text-align:right;">{{ bill.electricity_unit }}</td>
								<td style="text-align:right;">{{ bill.electricity_bill }}</td>
								<td style="text-align:right;">{{ bill.generator_bill }}</td>
								<td style="text-align:right;">{{ bill.ac_bill }}</td>
								<td style="text-align:right;">{{ bill.others_bill }}</td>
								<td style="text-align:right;">{{ bill.net_payable }}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr style="font-weight:bold;">
								<td colspan="8" style="text-align:right;">Total</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.electricity_unit)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.electricity_bill)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.generator_bill)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.ac_bill)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.others_bill)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ bills.reduce((prev, curr) => { return prev + parseFloat(+curr.net_payable )}, 0).toFixed(2) }}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#salaryReport',
		data(){
			return {
				stores: [],
				selectedStore: {
					Store_SlNo: '',
					Store_Name: 'All',
					display_text: 'All',
				},
				renters: [],
				selectedRenter: {
					Renter_SlNo: '',
					Renter_Name: 'All',
					display_text: 'All',
				},
				months: [],
				selectedMonth: {
					month_id: '',
					month_name: 'All'
				},
				bills: [],
				accountType: '<?php echo $accountType; ?>',
				userId: '<?php echo $userId; ?>'
			}
		},
		computed:{
			comStores(){
				return this.stores.map(store => {
					store.display_text = store.Store_SlNo == '' ? store.Store_Name : `${store.Store_Name} - ${store.Store_No}`;
					return store;
				})
			},
			comRenters(){
				return this.renters.map(renter => {
					// renter.display_text = renter.Renter_SlNo == '' ? renter.Renter_Name : `${renter.Renter_Name} - ${renter.Renter_Code}`;
					renter.display_text = renter.display_name
					return renter;
				})
			},
			comOwners(){
				return this.owners.map(renter => {
					// owner.display_text = owner.Owner_SlNo == '' ? owner.Owner_Name : `${owner.Owner_Name} - ${owner.Owner_Code}`;
					owner.display_text = owner.display_name
					return owner;
				})
			}
		},
		created(){
			this.getStores();
			this.getRenters();
			this.getOwners();
			this.getMonths();
		},
		methods: {
			getStores(){
				axios.get('/get_stores').then(res => {
					this.stores = res.data;
					this.stores.unshift({
						Store_SlNo: '',
						Store_Name: 'All'
					})
				})
			},
			getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
					this.renters.unshift({
						Renter_SlNo: '',
						display_name: 'All',
						Renter_Name: 'All'
					})
				})
			},
			getOwners(){
				axios.get('/get_owners').then(res => {
					this.owners = res.data;
					this.owners.unshift({
						Owner_SlNo: '',
						display_name: 'All',
						Owner_Name: 'All'
					})
				})
			},
			getMonths(){
				axios.get('/get_months').then(res => {
					this.months = res.data;
					this.months.unshift({
						month_id: '',
						month_name: 'All'
					})
				})
			},
			onChangeReportType(){
				if(this.reportType == 'summary'){
					this.months = this.months.filter(month => month.month_id != '');
				} else {
					this.months.unshift({
						month_id: '',
						month_name: 'All'
					})
					this.bills = [];
				}
			},
			showReport(){
				this.getStoreBills();
			},
			getStoreBills() {
				let data = {}
				if(this.accountType == 'o') {
					data.ownerId = this.userId;
				} else {
					data.ownerId = '';
				}

				if(this.accountType == 'r') {
					data.renterId = this.userId;
				} else {
					data.renterId = '';
				}
				if(this.selectedMonth == null){
					data.month = '';
				} else {
					data.month = this.selectedMonth.month_id;
				}
				axios.post('/get_store_bills', data)
				.then(res => {
					this.bills = res.data;
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								<h3 style="text-align:center;">Store Bill Reports</h3>
							</div>
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>