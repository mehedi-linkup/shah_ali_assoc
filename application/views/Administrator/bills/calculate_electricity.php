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
		font-size: 13px !important;
	}
	.btn-danger, .btn-danger.focus, .btn-danger:focus {
		background-color: #29b0fc!important;
		border-color: #29b0fc !important;
	}
	.btn-danger:hover {
		border-color: #D15B47 !important;
	}
	hr {
		margin-top: 3px;
		margin-bottom: 3px;
		border-top: 2px solid #277ce5;
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
	<br>
	<div class="row" v-if="stores.length > 0">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right">Floor</label>
			<div class="col-sm-2">
				<v-select v-bind:options="floors" label="Floor_Name" v-model="selectedFloor" @input="onChangeFloor"></v-select>
			</div>
		</div>
		<div style="margin-top: -15px; margin-bottom: 2px;">
			<label class="col-sm-2 control-label">Process Date</label>
			<div class="col-sm-2" style="margin-left: -90px;">
				<input style="height: 25px;" type="date" v-model="billPayment.process_date">
			</div>

			<label class="col-sm-2 control-label">Bill Amount</label>
			<div class="col-sm-2" style="margin-left: -90px;">
				<input style="height: 25px;" type="number" v-model="billPayment.electricity_entry">
			</div>
		</div>

		<div class="col-sm-12">
			<hr>
		</div>
		
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>SL</th>
							<th>Store No.</th>
							<th>Store Name</th>
							<th>Owner</th>
							<th>Renter</th>
							<th>Previous Unit</th>
							<th>Current Unit</th>
							<th>Electricity Unit</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="store in filteredStores">
							<tr>
								<td colspan="14" style="text-align:center;text-transform:uppercase;background-color:#ffa825">{{ store.floor_name }}</td>
							</tr>
							<tr v-for="(store, i) in store.stores" v-bind:style="{background: store.electricity_unit > 0 ? '#709fd9' : ''}">
								<td>{{ ++i }}</td>
								<td>{{ store.Store_No }}</td>
								<td>{{ store.Store_Name }}</td>
								<td>{{ store.Owner_Name }}</td>
								<td>{{ store.Renter_Name }}</td>
								<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.previous_unit" v-on:input="calculateNetPayable(store)"></td>
								<td><input style="width:100px;height:20px;text-align:center;" type="number" v-model="store.current_unit" v-on:input="calculateNetPayable(store)"></td>
								<td style="text-align: center;">{{ store.electricity_unit }}</td>
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
					electricity_entry: 0,
					month_id: null,
				},
				stores: [],
				months: [],
				month: null,
				utilityRate: null,
				payment: false,
				generated: false,
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
			calculateNetPayable(store) {

				setTimeout(() => {
					if(+store.current_unit < +store.previous_unit) {
						store.current_unit = store.previous_unit
						store.electricity_unit = 0;
					}
				}, 2000);

				store.electricity_unit = store.current_unit - store.previous_unit;
			},
			async getStores() {
				if(this.month == null || this.month.month_id == ''){
					alert("Select Month");
					return;
				}
				let month_id = this.month.month_id;

				this.billPayment.month_id = month_id;

				await axios.post('/check_electricity_payment_month', {month_id})
				.then(res=>{
					this.payment = false;
					if(res.data.success){
						this.payment = true;
					}
				})

				await axios.post('/check_payment_month', {month_id})
				.then(res=>{
					this.generated = false;
					if(res.data.success){
						this.generated = true;
					}
				})

				let filter = {
					month_id: month_id, 
					details: true
				}

				if(this.payment){
					await axios.post('/get_electricity_bill_payments/', filter).then(res => {
						let payment = res.data[0];
						this.billPayment.id = payment.id;
						this.billPayment.process_date = payment.process_date;
						this.billPayment.electricity_entry = payment.electricity_entry;
						
						this.billPayment.month_id = payment.month_id;

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
					})
				} else {
					let prevStores;
					if(this.month.orders > 1 && !this.payment) {
						// let prev_month = this.months[this.month.orders - 2];
						let prev_month = this.months.filter(item => item.orders == (this.month.orders - 1));
							await axios.post('/get_electricity_bill_payments/', { 
								month_id: prev_month[0].month_id,
								details: true 
							}).then(res => {
							prevStores = res.data[0]? res.data[0].details : [];
						})
					}

					await axios.post('/get_stores', {is_generate: 'true'}).then(res => {
						let stores = res.data;
						stores.map(store => {
							store.previous_unit = this.month.orders == 1 ? store.Start_Unit : prevStores.length > 0 ? prevStores.find((element => element.Store_SlNo == store.Store_SlNo)).current_unit : 0;
							store.current_unit = 0
							store.electricity_unit = 0;
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
						this.billPayment.id = null;
						this.billPayment.electricity_entry = 0;
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
				if(this.generated == true) {
					alert("Already this month bill has been generated");
					return;
				}

				if(this.billPayment.electricity_entry == 0 || typeof this.billPayment.electricity_entry == undefined ) {
					alert("Electricity entry is zero")
					return;
				}

				let stores = _.chain(this.stores)
					.flatMap(function(item) {
						return item.stores.map(function(store) {
							return store;
						});
					})
					.value();

				// let alertShown = false;
				// stores.forEach(function(element) {
				// 	if (element.electricity_unit <= 0 && !alertShown) {
				// 		alert("Store can't have zero unit");
				// 		alertShown = true;
				// 	}
				// });
				

				let data = {
					payment: this.billPayment,
					stores: stores,
					
				}
				
				let url = '/add_electricity_bill';
				if(this.payment) {
					url = '/update_electricity_bill';
				}
				axios.post(url , data)
				.then(async res => {
					let r = res.data;
					if(r.success) {
						// let conf = confirm(`${r.message}, Do you want to view sheet?`);
						// if(conf){
						// 	window.open('/bill_sheet/'+r.billId, '_blank');
						// 	await new Promise(r => setTimeout(r, 1000));
						// 	this.resetForm();
						// } else {
							alert(r.message);
							this.resetForm();
						// }
						
					}
				})
			},

			resetForm(){
				this.billPayment = {
					id: null,
					process_date: moment().format("YYYY-MM-DD"),
					electricity_entry: 0,
					month_id: null,
				},
				this.month = null,
				this.stores = [];
			}
		}
	})
</script>