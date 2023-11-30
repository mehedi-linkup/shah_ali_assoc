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
		font-size: 11px !important;
	}
	.btn-danger, .btn-danger.focus, .btn-danger:focus {
		background-color: #29b0fc!important;
		border-color: #29b0fc !important;
	}
	.btn-danger:hover {
		border-color: #D15B47 !important;
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
				<label>Process Date</label>
				<input style="height: 25px;" type="date" v-model="billPayment.process_date">
				<label>Last Date</label>
				<input style="height: 25px;" type="date" v-model="billPayment.last_date">
			</div>

			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Store No.</th>
							<th>Store Name</th>
							<!-- <th>Floor</th> -->
							<th>Renter</th>
							<th>Prev. Unit</th>
							<th>Cur. Unit</th>
							<th>Elctr. Unit</th>
							<th>Elctr. Bill</th>
							<th>Generator</th>
							<th>Ac</th>
							<th>Others</th>
							<th>Net Payable</th>
							<!-- <th>Paid</th> -->
						</tr>
					</thead>
					<tbody>
						<template v-for="store in stores">
							<tr>
								<td colspan="12" style="text-align:center;text-transform:uppercase;background-color:#ffa825">{{ store.floor_name }}</td>
							</tr>
							<tr v-for="(store, i) in store.stores" v-bind:style="{background: store.net_payable!=0 ? '#709fd9' : ''}">
								<td>{{ ++i }}</td>
								<td>{{ store.Store_No }}</td>
								<td>{{ store.Store_Name }}</td>
								<!-- <td>{{ store.Floor_Name }}</td> -->
								<td>{{ store.Renter_Name }}</td>
								<td><input style="width:80px;height:20px;text-align:center;" type="number" v-model="store.previous_unit" v-on:input="calculateNetPayable(store)"></td>
								<td><input style="width:80px;height:20px;text-align:center;" type="number" v-model="store.current_unit" v-on:input="calculateNetPayable(store)"></td>
								<td style="text-align: center;">{{ store.electricity_unit }}</td>
								<td style="text-align: center;">{{ store.electricity_bill }}</td>
								<td><input style="width:80px;height:20px;text-align:center;" type="number" v-model="store.generator_bill" v-on:input="calculateNetPayable(store)"></td>
								<td><input style="width:80px;height:20px;text-align:center;" type="number" v-model="store.ac_bill" v-on:input="calculateNetPayable(store)"></td>
								<td><input style="width:80px;height:20px;text-align:center;" type="number" v-model="store.others_bill" v-on:input="calculateNetPayable(store)"></td>
								<td style="text-align: center;">{{store.net_payable}}</td>
								<!-- <td><input style="width: 80px;height: 20px; text-align:center;" type="number" v-model="store.payment" v-on:input="checkPayment(store)"></td> -->
							</tr>
						</template>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="11" style="text-align: center;font-weight: 700">Total</td>
							<td style="font-weight: 700">{{ parseFloat(stores.reduce((prev, curr)=>{ return +prev + +curr.stores.reduce((p, c) => { return +p + +c.net_payable }, 0) }, 0) ).toFixed(2) }}</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="12">
								<button type="button" @click="SaveBillPayment" name="btnSubmit" title="Save" class="btn btn-sm btn-danger pull-right">
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
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#storePayment',
		data() {
			return {
				billPayment: {
					id: null,
					process_date: moment().format("YYYY-MM-DD"),
					last_date: moment().format("YYYY-MM-DD"),
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
			// checkPayment(employee){
			// 	if(parseFloat(employee.payment) > parseFloat(employee.net_payable)){
			// 		alert("Can not paid greater than net payable");
			// 		employee.payment = employee.net_payable;
			// 	}
			// },
			calculateNetPayable(store){
				setTimeout(() => {
					if(+store.current_unit < +store.previous_unit) {
						store.current_unit = store.previous_unit
						store.electricity_unit = 0;
						store.electricity_bill = 0;
						store.net_payable = parseFloat(store.generator_bill + store.ac_bill + store.others_bill).toFixed(2)
					}
				}, 2000);

				store.electricity_unit = store.current_unit - store.previous_unit;
				store.electricity_bill = parseFloat(store.electricity_unit * 4.83).toFixed(2);

				let payable = ( parseFloat(store.electricity_bill) + parseFloat(store.generator_bill) + parseFloat(store.ac_bill) + parseFloat(store.others_bill) ).toFixed(2);

				store.net_payable = payable;
				// store.payment = payable;
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
				
				if(this.payment){
					await axios.post('/get_bill_payments/', {month_id: month_id, details: true}).then(res => {
						let payment = res.data[0];
						this.billPayment.id = payment.id;
						this.billPayment.process_date = payment.process_date;
						this.billPayment.last_date = payment.last_date;
						this.billPayment.month_id = payment.month_id;

						let stores = _.chain(payment.details).groupBy('Store_Floor')
							.map(store => {
									return {
										floor_name: store[0].Floor_Name,
										floor_ranking: store[0].Floor_Ranking,
										stores: store
									}
								}).value()


						stores = _.orderBy(stores, ['floor_ranking'], ['asc'])

						this.stores = stores;
						// this.stores = payment.details;
					})
				} else {
					await axios.get('/get_stores').then(res => {
						let stores = res.data;

						stores.map(store => {
							store.previous_unit = 0;
							store.current_unit = 0;
							store.electricity_unit = 0;
							store.electricity_bill = 0;
							store.generator_bill = 0;
							store.ac_bill = 0;
							store.others_bill = 0;
							store.net_payable = 0;
							return store;
						});
						stores = _.chain(stores).groupBy('Store_Floor')
							.map(store => {
									return {
										floor_name: store[0].Floor_Name,
										floor_ranking: store[0].Floor_Ranking,
										stores: store
									}
								}).value()


						stores = _.orderBy(stores, ['floor_ranking'], ['asc'])

						this.stores = stores;

						this.billPayment.process_date = moment().format("YYYY-MM-DD");
						this.billPayment.last_date = moment().format("YYYY-MM-DD");
					})

					.catch(function (error) {
						console.log(error);
					});
				}	
			},

			getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
				})
			},

			SaveBillPayment() {
				let stores = _.chain(this.stores)
					.flatMap(function(item) {
						return item.stores.map(function(store) {
						return store;
						});
					})
					.value();

				let data = {
					payment: this.billPayment,
					stores: stores,
					
				}
				
				let url = '/add_bill_payment';
				if(this.payment) {
					url = '/update_bill_payment';
				}
				axios.post(url , data)
				.then(async res => {
					let r = res.data;
					if(r.success) {
						let conf = confirm(`${r.message}, Do you want to view sheet?`);
						if(conf){
							window.open('/bill_sheet/'+r.billId, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							this.resetForm();
						} else {
							this.resetForm();
						}
						
					}
				})
			},

			resetForm(){
				this.billPayment = {
					id: null,
					process_date: moment().format("YYYY-MM-DD"),
					last_date: moment().format("YYYY-MM-DD"),
					month_id: null,
				},
				this.month = null,
				this.stores = [];
			}
		}
	})
</script>