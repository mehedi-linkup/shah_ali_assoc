<style>
	.progress-bar__wrapper {
  		position: relative;
		width: 100px;
	}
	.progress-bar__value {
		position: absolute;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 8px;
		top: 40%;
		left: 50%;
		transform: translate(-50%, -50%);
		color: #fff;
		font-size: 11px;
	}
	progress {
		width: 100%;
		height: 20px;
		border-radius: 50px;
		background-color: #a5a5a5;
		transition: width 300ms ease;
	}
	progress[value]::-webkit-progress-bar {
		width: 100%;
		height: 20px;
		border-radius: 50px;
		background-color: #a5a5a5;
		transition: width 300ms ease;
	}
	progress[value]::-webkit-progress-value {
		width: 0;
		border-radius: 50px;
		background-color: green;
		transition: width 300ms ease;
	}

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
					<v-select v-bind:options="months" label="month_name" v-model="month" v-on:input="onChangeMonth"></v-select>
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

	<div class="row">
		
	</div>
	<br>
	<div class="row" v-if="stores.length > 0">
		<div style="margin-top: -15px; margin-bottom: 2px;">
			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right">Floor</label>
				<div class="col-sm-2">
					<v-select v-bind:options="floors" label="Floor_Name" v-model="selectedFloor" @input="onChangeFloor"></v-select>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Store No.</th>
							<th>Store Name</th>
							<th>Renter</th>
							<th>Electricity Bill</th>
							<th>Generator Bill</th>
							<th>Ac Bill</th>
							<th>Others</th>
							<th>Net Payable</th>
							<th>Paid</th>
							<th>Last Date</th>
							<th>Remaining Days</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="store in filteredStores">
							<tr>
								<td colspan="14" style="text-align:center;text-transform:uppercase;color:#009100">{{ store.floor_name }}</td>
							</tr>
							<tr v-for="(store, i) in store.stores">
								<td>{{ ++i }}</td>
								<td>{{ store.Store_No }}</td>
								<td>{{ store.Store_Name }}</td>
								<td>{{ store.Renter_Name }}</td>
								<td style="text-align: center;">{{ store.electricity_bill }}</td>
								<td>{{ store.generator_bill }}</td>
								<td>{{ store.ac_bill }}</td>
								<td>{{ store.others_bill }}</td>
								<td>{{store.net_payable}}</td>
								<td>
									<div class="progress-bar__wrapper">
										<label class="progress-bar__value" htmlFor="progress-bar"> {{ store.paid_percentage }}% </label>
										<progress id="progress-bar" :value="store.paid_percentage" max="100"></progress>
									</div>
								</td>
								<td><input type="date" v-model="store.last_date"></td>
								<td>{{ store.remaining_day }}</td>
							</tr>
						</template>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="14">
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
				utilityRate: null,
				payment: false,
				floors: [],
				selectedFloor: {
					Floor_SlNo: '',
					Floor_Name: 'All'
				},
				filteredStores: []
			}
		},
		created() {
			this.getMonths();
			this.getFloors();
			this.getUtilityRate();
		},
		filters: {
			formatDate(value) {
				const parsedDate = moment(value);
				return parsedDate.format('DD MMM YYYY');
			}
		},
		methods: {
			async onChangeFloor() {
				if(this.selectedFloor.Floor_SlNo != '') {
					let filteredStores = this.stores.filter(item => item.floor_id == this.selectedFloor.Floor_SlNo)
					this.filteredStores = filteredStores;
				} else {
					this.filteredStores = this.stores
				}
			},
			onChangeMonth() {
				this.stores = [],
				this.selectedFloor = {
					Floor_SlNo: '',
					Floor_Name: 'All'
				}
			},
			calculateNetPayable(store){
				store.generator_bill = store.generator_unit * this.utilityRate.Generator_Rate;

				setTimeout(() => {
					if(+store.current_unit < +store.previous_unit) {
						store.current_unit = store.previous_unit
						store.electricity_unit = 0;
						store.electricity_bill = 0;
						store.net_payable = parseFloat(store.generator_bill + store.ac_bill + store.others_bill).toFixed(2)
					}
				}, 2000);

				store.electricity_unit = store.current_unit - store.previous_unit;
				store.electricity_bill = parseFloat(store.electricity_unit * this.utilityRate.Electricity_Rate).toFixed(2);
				store.generator_bill = parseFloat(store.generator_unit * this.utilityRate.Generator_Rate).toFixed(2);
				store.ac_bill = parseFloat(store.ac_unit * this.utilityRate.Ac_Rate).toFixed(2);
				store.others_bill = parseFloat(+this.utilityRate.Mosque_Rate + +this.utilityRate.Service_Rate + +this.utilityRate.Wasa_Rate).toFixed(2);

				let payable = ( parseFloat(store.electricity_bill) + parseFloat(store.generator_bill) + parseFloat(store.ac_bill) + parseFloat(store.others_bill) ).toFixed(2);

				store.net_payable = payable;
			},
			async getStores() {
				if(this.month == null || this.month.month_id == ''){
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

				let filter = {
					month_id: month_id, 
					details: true
				}
				
				if(this.payment){
					await axios.post('/get_bill_payments/', filter).then(res => {
						let payment = res.data[0];
						this.billPayment.id = payment.id;
						this.billPayment.month_id = payment.month_id;
						payment.details.map(item => {
							item.paid_percentage = parseFloat((100 / +item.net_payable) * +item.bill_paid).toFixed(2);
						});
						payment.details = payment.details.filter(item => +item.net_payable > 0)

						let stores = _.chain(payment.details).groupBy('floor_id')
							.map(store => {
									return {
										floor_id: store[0].floor_id,
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
							store.generator_unit = 0;
							store.generator_bill = 0;
							store.ac_unit = 0;
							store.ac_bill = 0;
							store.others_bill = 0;
							store.net_payable = 0;
							return store;
						});
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
			getFloors() {
				axios.get('/get_floors').then(res => {
					this.floors = res.data;
					this.floors.unshift({
						Floor_SlNo: '',
						Floor_Name: 'All'
					})
				})
			},
			getUtilityRate() {
				axios.get('/get_utility_rate').then(res => {
					this.utilityRate = res.data;
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
				url = '/update_bill_date';
				axios.post(url , data)
				.then(async res => {
					let r = res.data;
					if(r.success) {
						alert(r.message);
						this.resetForm();
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

