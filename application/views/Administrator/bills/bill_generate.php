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
	.pagination {
		margin: 10px 0 !important;
	}
	label {
		padding: 0px 10px;
	}
	td, td input{
		font-size: 10px !important;
	}
	.btn-danger, .btn-danger.focus, .btn-danger:focus {
		background-color: #29b0fc!important;
	}
</style>
<div id="storePayment">
	<div class="row"style="border-bottom:1px solid #ccc;padding: 10px 0;">
		<div class="col-md-12">
			<form class="form-inline" @submit.prevent="getStores">

				<div class="form-group">
					<label class="col-sm-4 control-label no-padding-right"> Month </label>
					<div class="col-sm-7">
					<v-select v-bind:options="months" label="month_name" v-model="month" v-on:input="stores = []"></v-select>
					</div>
					<div class="col-sm-1" style="padding: 0;">
						<a href="<?= base_url('month')?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Month"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
					</div>
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" class="search-button" value="Show">
				</div>
			</form>
		</div>
	</div>
	<br>
	<div class="row" v-if="stores.length > 0">
		<div class="col-md-12">
			
			<div style="margin-top: -15px; margin-bottom: 2px;">
				<label>Payment Date</label>
				<input style="height: 25px;" type="date" v-model="billPayment.payment_date">
			</div>

			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Store No.</th>
							<th>Store Name</th>
							<th>Floor</th>
							<th>Renter</th>
							<th>Prev. Unit</th>
							<th>Cur. Unit</th>
							<th>Elctr. Unit</th>
							<th>Elctr. Bill</th>
							<th>Generator</th>
							<th>Ac</th>
							<th>Others</th>
							<th>Net Payable</th>
							<th>Paid</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(store, i) in stores" v-bind:style="{background: store.net_payable != store.payment ? 'orange' : ''}">
							<td>{{ ++i }}</td>
							<td>{{ store.Store_No }}</td>
							<td>{{ store.Store_Name }}</td>
							<td>{{ store.Floor_Name }}</td>
							<td>{{ store.Renter_Name }}</td>
							<td style="text-align: center;">{{ store.electricity }}</td>
							<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.electricity" v-on:input="calculateNetPayable(store)"></td>
							<td style="text-align: center;">{{ store.electricity }}</td>
							<td style="text-align: center;">{{ store.electricity }}</td>
							<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.generator" v-on:input="calculateNetPayable(store)"></td>
							<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.ac" v-on:input="calculateNetPayable(store)"></td>
							<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.others" v-on:input="calculateNetPayable(store)"></td>
							<td style="text-align: center;">{{store.net_payable}}</td>
							<td><input style="width: 100px;height: 20px; text-align:center;" type="number" v-model="store.payment" v-on:input="checkPayment(store)"></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="10" style="text-align: right;">Total=</td>
							<td>{{ stores.reduce((prev, curr)=>{ return prev + parseFloat(curr.payment) }, 0) }}</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="12">
								<button type="button" @click="SaveBillPayment" name="btnSubmit" title="Save" class="btn btn-sm btn-success pull-right">
									Save
									<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
								</button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#storePayment',
		data() {
			return {
				billPayment: {
					id: null,
					payment_date: moment().format("YYYY-MM-DD"),
					month_id: null,
				},
				stores: [],
				months: [],
				month: null,
				payment: false,
			}
		},
		created() {
			this.getMonths();
		},
		methods: {
			checkPayment(employee){
				if(parseFloat(employee.payment) > parseFloat(employee.net_payable)){
					alert("Can not paid greater than net payable");
					employee.payment = employee.net_payable;
				}
			},
			calculateNetPayable(employee){
				let payable = ((parseFloat(employee.salary) + parseFloat(employee.benefit)) - parseFloat(employee.deduction)).toFixed(2);

				employee.net_payable = payable;
				employee.payment = payable;
			},
			async getStores() {
				if(this.month == null && this.month.month_id == ''){
					alert("Select Month");
					return;
				}
				let month_id = this.month.month_id;

				this.billPayment.month_id = month_id;

				await axios.post('/check_payment_month', {month_id})
				.then(res=>{
					this.payment = false;
					if(res.data.success){
						this.payment = true;
					}
				})

				console.log(this.payment);

				
				// if(this.payment){
				// 	await axios.post('/get_bill_payments/', {month_id: month_id, details: true}).then(res => {
				// 		let payment = res.data[0];
				// 		this.billPayment.id = payment.id;
				// 		this.billPayment.payment_date = payment.payment_date;
				// 		this.billPayment.month_id = payment.month_id;
				// 		this.stores = payment.details;
				// 	})
				// } else {
				// 	await axios.get('/get_stores').then(res => {
				// 		let stores = res.data;

				// 		stores.map(store=>{
				// 			store.electricity = 0;
				// 			store.generator = 0;
				// 			store.ac = 0;
				// 			store.others = 0;
				// 			store.net_payable = store.salary_range;
				// 			store.payment = store.salary_range;
				// 			store.comment = '';
				// 			return store;
				// 		});

				// 		this.stores = stores;
				// 		this.billPayment.payment_date = moment().format("YYYY-MM-DD");
				// 	})

				// 	.catch(function (error) {
				// 		console.log(error);
				// 	});
				// }	
			},

			getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
				})
			},

			SaveBillPayment() {
				
				let data = {
					payment: this.billPayment,
					stores: this.stores,
					
				}
				let url = '/add_bill_payment';
				if(this.payment) {
					url = '/update_bill_payment';
				}
				axios.post(url , data)
				.then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success) {
						this.resetForm();
					}
				})
			},

			resetForm(){
				this.billPayment = {
					id: null,
					payment_date: moment().format("YYYY-MM-DD"),
					month_id: null,
				},
				this.month = null,
				this.stores = [];
			}
		}
	})
</script>