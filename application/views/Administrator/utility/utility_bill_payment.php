<style>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}
	.highlight-row {
		background-color: #ffeeba; 
		color: #eb4c0f;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#branchDropdown .vs__actions button {
		display: none;
	}

	#branchDropdown .vs__actions .open-indicator {
		height: 15px;
		margin-top: 7px;
	}

	.utility-header {
		color: #005a8b;
	}

	.title_design {
		text-align: center;
		margin: unset;
		margin-bottom: 10px;
		background-color: #7cc2e5;
		/* border-radius: 4px; */
		color: #fff;
	}

	.k_label {
		width: 60%;
	}

	.k_input {
		width: 30%;
		height: 26px;
		text-align: center;
	}

	.kaf {
		display: flex;
	}

	.kaf_2,
	.kaf_1 {
		width: 50%;
		float: left;
	}

	.sub-title {
		text-align: center;
		padding: 2px 10px;
		font-size: 20px;
		width: 100%;
		background: #7CC2E5;
		color: #fff;
		/* border-radius: 4px; */
	}

	.sec_title {
		background: rgb(209 209 209);
		/* color: rgb(255, 255, 255); */
		padding: 6px 10px;
		margin: 0px 0px 10px 0px;
		font-weight: bold;
	}

	.sec_title_extra {
		background: rgb(159 234 251);
		/* color: rgb(255, 255, 255); */
		padding: 6px 10px;
		margin: 0px 0px 10px 0px;
		font-weight: bold;
	}

	select.form-control {
		padding: 0px 4px;
	}
</style>

<div id="sales" class="row">
	<div class="col-xs-12 col-md-12 col-lg-12" style="margin-bottom:5px; padding:10px">
		<div class="widget-box">
			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="form-group">
							<label class="col-sm-1 control-label no-padding-right">Payment Inv</label>
							<div class="col-sm-2">
								<input type="text" id="invoiceNo" class="form-control" v-model="payment.invoice" readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label no-padding-right"> Date </label>
							<div class="col-sm-2">
								<input class="form-control" id="salesDate" type="date" v-model="payment.date" v-bind:disabled="userType == 'u' ? true : false" />
							</div>
						</div>
					
						<div class="form-group">
							<label class="col-sm-1 control-label no-padding-right"> Month </label>
							<div class="col-sm-2">
								<v-select v-bind:options="months" label="month_name" v-model="selectedMonth"></v-select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-1 control-label no-padding"> Payment Type </label>
							<div class="col-sm-2">
								<input type="radio" id="store" value="store" v-model="paymentType" v-on:change="onPaymentTypeChange"> <label for="store">Store</label>&nbsp;
								<!-- <input type="radio" id="renter" value="renter" v-model="paymentType" v-on:change="onPaymentTypeChange"> <label for="renter">Renter</label>  -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-md-9 col-lg-9">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Payment Information</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<!-- Renter info part start -->
					<div class="row" style="margin: 0px;border:1px solid #ccc;padding-bottom:10px;margin-bottom:15px;">
						<h5 style="text-align: center;background: rgb(0 90 139);padding: 6px;color: #fff;margin-top:0px">Store Info:</h5>
						<div class="col-md-5" style="border-right: 1px solid #ccc;">

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label"> Floor </label>
								<div class="col-sm-8">
									<select class="form-control" v-if="floors.length == 0"></select>
									<v-select v-bind:options="floors" v-model="selectedFloor" label="Floor_Name" v-if="floors.length > 0" @input="onChangeFloor()"></v-select>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label"> Grade </label>
								<div class="col-sm-8">
									<select class="form-control" v-if="grades.length == 0"></select>
									<v-select v-bind:options="grades" v-model="selectedGrade" label="Grade_Name" v-if="grades.length > 0" @input="onChangeFloor()"></v-select>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label"> Store </label>
								<div class="col-sm-8">
									<select class="form-control" v-if="stores.length == 0"></select>
									<v-select v-bind:options="stores" v-model="selectedStore" label="display_text" v-if="stores.length > 0" v-on:input="storeOnChange"></v-select>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Name </label>
								<div class="col-sm-8">
									<input type="text" id="customerName" placeholder="Name" class="form-control" v-model="selectedStore.Store_Name" readonly />
								</div>
							</div>

                            <div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-sm-8">
									<input type="number" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedStore.Store_Mobile" autocomplete="off" readonly />
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'store' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Meter No </label>
								<div class="col-sm-8">
									<input type="number" id="meterNo" placeholder="Meter No" class="form-control" v-model="selectedStore.meter_no" autocomplete="off" readonly />
								</div>
							</div>


							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'renter' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Renter </label>
								<div class="col-sm-7">
									<v-select v-bind:options="renters" label="display_name" v-model="selectedRenter" v-on:input="storeOnChange"></v-select>
								</div>
								<div class="col-sm-1" style="padding: 0;">
									<a href="<?= base_url('renter') ?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Renter"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
								</div>
							</div>
							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'renter' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Name </label>
								<div class="col-sm-8">
									<input type="text" id="customerName" placeholder="Name" class="form-control" v-model="selectedRenter.Renter_Name" readonly />
								</div>
							</div>

                            <div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'renter' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-sm-8">
									<input type="number" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedRenter.Renter_Mobile" autocomplete="off" readonly />
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: paymentType == 'renter' ? '' : 'none'}">
								<label class="col-sm-4 control-label no-padding-right"> Address </label>
								<div class="col-sm-8">
									<textarea id="address" placeholder="Address" class="form-control" v-model="selectedRenter.Renter_PreAddress" readonly></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-7" style="max-height: 154px;overflow: auto;">
							<div class="table-responsive">
								<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
									<thead>
										<tr>
											<th style="color:#000;">All</th>
											<th style="color:#000;">Store No</th>
											<th style="color:green;">G.Date</th>
											<th style="color:red;">L.Date</th>
											<th style="color:#000;">Amount</th>
											<th style="color:#000;">Paid</th>
											<th style="color:#000;">Due</th>
										</tr>
									</thead>
									<tbody>
										<template>
											<tr v-for="(store, index) in store_bills" :class="{'highlight-row': isExpire(store.last_date) }">
												<td><input type="checkbox" v-model="store.isSelect" id="" @change="onSelectStore"></td>
												<td>{{store.Store_No}}</td>
												<td>{{store.process_date | dateOnly('DD MMM YYYY')}}</td>
												<td>{{store.last_date | dateOnly('DD MMM YYYY')}}</td>
												<td style="text-align: right;">{{store.net_payable}}</td>
												<td>{{ store.total_payment }}</td>
												<td>{{ +store.net_payable - +store.total_payment }}</td>
											</tr>
										</template>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Customer info part end -->


					<!-- Body top part and body down part info start -->
					<div class="row" style="margin: 0px;border:1px solid #ccc;padding-bottom:10px;margin-bottom:15px;">
						<h5 style="text-align: center;background: rgb(0 90 139);padding: 6px;color: #fff;margin-top:0px">Utility Info:</h5>
						<!-- order info start -->
						<div v-if="filter_store_bills.length > 0" v-for="store in filter_store_bills" class="col-md-12" style="margin-top:10px;">
							<div class="utility-header">
								<strong>Store Name: </strong> <span style="color: #be2300;">{{ store.Store_Name }}</span> <strong>&nbsp;/&nbsp; Meter No: </strong> <span style="color: #be2300;">{{ store.meter_no }}</span>
							</div>
							<hr v-if="filter_store_bills.length > 0" style="margin-top:10px;margin-bottom:10px">
							<div class="form-group row clearfix ">
                                <label class="col-sm-2">Electricity Bill : </label>
								<div class="col-sm-2 no-padding">
									<input type="number" v-model="store.electricity_bill_payment" class="form-control" require @input="onChangeUtiltiy(store)">
								</div>
								<label class="col-sm-2" style="text-align: right;">AC Bill : </label>
								<div class="col-sm-2 no-padding">
									<input type="number" v-model="store.ac_bill_payment" class="form-control" require @input="onChangeUtiltiy(store)">
								</div>
								<label class="col-sm-2" style="text-align: right;">Generator Bill : </label>
								<div class="col-sm-2 no-padding-left">
									<input type="number" v-model="store.generator_bill_payment" class="form-control" require @input="onChangeUtiltiy(store)">
								</div>
                                <hr>
								<label class="col-sm-2" > Others : </label>
								<div class="col-sm-2 no-padding">
									<input type="number" v-model="store.others_bill_payment" class="form-control" require @input="onChangeUtiltiy(store)">
								</div>
								<label class="col-sm-2" style="text-align: right;"> Late Fee : </label>
								<div class="col-sm-2 no-padding">
									<input type="number" v-model="store.late_fee" class="form-control" require @input="onChangeUtiltiy(store)">
								</div>

								<label class="col-sm-2" style="text-align: right;"> Comment : </label>
								<div class="col-sm-2 no-padding-left">
									<!-- <textarea v-model="store.comment" class="form-control"><textarea> -->
										<textarea class="form-control" id=""  cols="" rows="2" v-model="store.comment"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-3 col-lg-3">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Amount Details</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table style="color:#000;margin-bottom: 0px;border-collapse: collapse;">
									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total Electricity Bill</label>
												<div class="col-xs-12">
													<input type="number" id="subTotal" class="form-control" v-model="payment.total_electricity_bill" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total Generator Bill</label>
												<div class="col-xs-12">
													<input type="number" class="form-control" v-model="payment.total_generator_bill" v-on:input="calculateTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total AC Bill</label>
												<div class="col-xs-12">
													<input type="number" class="form-control" v-model="payment.total_ac_bill" v-on:input="calculateTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total Others Bill</label>
												<div class="col-xs-12">
													<input type="number" class="form-control" v-model="payment.total_others_bill" v-on:input="calculateTotal" readonly />
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total Late Fee</label>
												<div class="col-xs-12">
													<input type="number" class="form-control" v-model="payment.total_late_fee" v-on:input="calculateTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total</label>
												<div class="col-xs-12">
													<input type="number" id="total" class="form-control" v-model="payment.total_payment_amount" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Due</label>
												<div class="col-xs-12">
													<input type="number" id="due" class="form-control" v-model="payment.total_due" readonly />
												</div>
											</div>
										</td>
									</tr>


									<tr>
										<td>
											<div class="form-group">
												<div class="col-sm-12" style="margin-top: 10px;">
													<input type="button" class="btn btn-primary btn-sm" value="Save Payment" v-on:click.prevent="savePayment" v-bind:disabled="paymentProgress ? true : false" style="color: #fff;margin-top: 0px;width:100%;padding:5px;font-weight:bold;">
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#sales',
		data() {
			return {
				payment: {
					id: parseInt('<?php echo $paymentId; ?>'),
					invoice: '<?php echo $invoice; ?>',
					date: moment().format('YYYY-MM-DD'),
					total_electricity_bill: 0,
					total_generator_bill: 0,
					total_ac_bill: 0,
					total_others_bill: 0,
					total_late_fee: 0,
					total_payment_amount: 0,
					total_due: 0
				},
				paymentType: 'store',
                months: [],
				selectedMonth: null,
                stores: [],
                selectedStore: {
					Store_SlNo: '',
					Store_Name: '',
					display_text: 'Select Store',
					Store_Mobile: '',
					meter_no: '',
				},
				grades: [],
				selectedGrade: {
                    Grade_SlNo: '',
					Grade_Name: 'Select Grade',
				},
				floors: [],
				selectedFloor: {
					Floor_SlNo: '',
					Floor_Name: 'Select Floor',
				},
				owners: [],
				selectedOwner: null,
				renters: [],
				selectedRenter: {
					Renter_SlNo: '',
                    Renter_Name: '',
                    Renter_Mobile: '',
                    Renter_PreAddress: '',
					display_name: 'Select Renter'
                },
                employees: [],
                selectedEmployee: null,
				paymentProgress: false,
				employees: [],
				customers: [],
				selectedCustomer: {
					Customer_SlNo: '',
					Customer_Code: '',
					Customer_Name: '',
					display_name: 'Select---',
					Customer_Mobile: '',
					Customer_Address: '',
					Customer_Type: ''
				},
				cart: [],
				userType: '<?php echo $this->session->userdata("accountType"); ?>',
				store_bills: [],
				filter_store_bills: [],
			}
		},
		filters: {
			dateOnly(datetime, format) {
				return moment(datetime).format(format);
			}
		},
		watch: {

		},
		computed: {
		},
		async created() {
			await this.getEmployees();
            this.getMonths();
            this.getFloors();
			this.getGrades();
            this.getOwners();
            this.getRenters();
			this.getStores();

			
			if (this.payment.id != 0) {
				await this.getPayments();
			}
		},
		methods: {
			getEmployees() {
				axios.get('/get_employees').then(res => {
					this.employees = res.data;
				})
			},
			
            getGrades(){
				axios.get('/get_grades').then(res => {
					this.grades = res.data;
				})
			},
			getFloors(){
				axios.get('/get_floors').then(res => {
					this.floors = res.data;
				})
			},
            getOwners(){
				axios.get('/get_owners').then(res => {
					this.owners = res.data;
				})
			},
            getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
				})
			},
            getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
				})
			},

            async getStores() {
				let filter = {
					GradeId: this.selectedGrade?.Grade_SlNo,
					FloorId: this.selectedFloor?.Floor_SlNo,
				}

				await axios.post('/get_stores', filter).then(res => {
					let stores = res.data;
                    this.stores = stores.map(store => {
					    store.display_text = store.Store_SlNo == '' ? store.Store_Name : `${store.Store_Name} - ${store.Store_No}`;
					    return store;
                    });
				})
			},
			onChangeFloor() {
				this.selectedStore = {
					Store_SlNo: '',
					Store_Name: '',
					display_text: 'Select Store',
					Store_Mobile: '',
					meter_no: '',
				}
				this.getStores();
			},
			onSelectStore() {
				let filterStoreBills = this.store_bills.filter(item => item.isSelect == true);
				
				filterStoreBills.map(item => {
					item.electricity_bill_payment = (item.electricity_bill_payment==0?item.electricity_bill:item.electricity_bill_payment)
					item.generator_bill_payment = (item.generator_bill_payment==0?item.generator_bill:item.generator_bill_payment)
					item.ac_bill_payment = (item.ac_bill_payment==0?item.ac_bill:item.ac_bill_payment)
					item.others_bill_payment = (item.others_bill_payment==0?item.others_bill:item.others_bill_payment)
					item.payment = +item.electricity_bill_payment + +item.generator_bill_payment + +item.ac_bill_payment + +item.others_bill_payment;
					return item;
				})
				this.filter_store_bills = filterStoreBills;
				// start calculate total from here
				this.calculateTotal()
				console.log(this.filter_store_bills)

			},
			onChangeUtiltiy(store) {
				if(+store.electricity_bill < +store.electricity_bill_payment) {
					store.electricity_bill_payment = store.electricity_bill;
				}
				if(+store.generator_bill < +store.generator_bill_payment) {
					store.generator_bill_payment = store.generator_bill;
				}
				if(+store.ac_bill < +store.ac_bill_payment) {
					store.ac_bill_payment = store.ac_bill;
				}
				if(+store.others_bill < +store.others_bill_payment) {
					store.others_bill_payment = store.others_bill;
				}

				store.payment = +store.electricity_bill_payment + +store.generator_bill_payment + +store.ac_bill_payment + +store.others_bill_payment + +store.late_fee;

				this.calculateTotal();
			},
			onPaymentTypeChange(){
				if(this.paymentType == 'store') {
					this.selectedRenter = {
						Renter_SlNo: '',
						Renter_Name: '',
						Renter_Mobile: '',
						Renter_PreAddress: '',
						display_name: 'Select Renter'
					}
				} else if(this.paymentType == 'renter') {
					this.selectedStore = {
						Store_SlNo: '',
						Store_Name: '',
						display_text: 'Select Store',
						Store_Mobile: '',
						meter_no: '',
					}
					this.selectedGrade = {
						Grade_SlNo: '',
						Grade_Name: 'Select Grade',
					},
					this.selectedFloor = {
						Floor_SlNo: '',
						Floor_Name: 'Select Floor',
					}
				}


				// this.clearProduct();
				// this.getProducts();
			},
			isExpire(date) {
				const currentDate = new Date();
				const lastDate = new Date(date);
				return lastDate < currentDate;
			},

			async getOrderItem() {
				// this.order_details.order_item_id = '';
				this.itemSections = [];
				let filter = {
					body_part: this.order_details.body_part
				}
				await axios.post('/get-order-item', filter).then(res => {

					this.orderItems = res.data;
				})
			},
			async getItemSection() {
				this.order_info = [];
				let filter = {
					order_type: this.order_details.order_item_id
				}
				await axios.post('/get-item-section', filter).then(res => {

					res.data.forEach(ele => {
						let item = {
							section_id: ele.section_id,
							section_name: ele.section_name,
							section_name_en: ele.section_name_en,
							input_status: ele.input_status,
							section_sub_id: '',
							value: '',
							sub: ele.sub,
							extra_title: '',
						}
						this.order_info.push(item);
					})

				})
			},
		
			deleteCartItem(index) {
				this.cart.splice(index, 1);
				this.calculateTotal();
			},

			async editCartItem(data, index) {
				this.order_details = data.orderDetails;
				await this.getOrderItem();
				await this.getItemSection();


				setTimeout(() => {
					data.orderInfo.forEach(ele1 => {
						this.order_info.forEach(ele2 => {
							if (ele1.section_name_en == ele2.section_name_en && ele1.section_name_en != 'extra') {
								ele2.section_sub_id = ele1.section_sub_id;
								if (ele2.input_status == 'yes') {
									ele2.value = ele1.value;
								}
							} else if (ele1.section_name_en == ele2.section_name_en && ele1.section_name_en == 'extra') {
								ele2.extra_title = ele1.extra_title;
								ele2.value = ele1.value;
							}
						})
					})
				}, 500);
				this.cart.splice(index, 1);

			},

			async existSaleClick(item, index) {
				// this.order_details = item.orderDetails;
				// this.orderItems = []
				// this.itemSections = []
				// this.order_info = {};
				// this.order_details = {};
				// console.log(item, index);

				this.order.customer_id = item.customer_id;
				this.order.measurement_master = item.measurement_master;
				this.order.cutting_master = item.cutting_master;
				this.order.material_man = item.material_man;
				this.order.serial_man = item.serial_man;
				this.order.sewing_master = item.sewing_master;
				this.order.finish_man = item.finish_man;

				this.order_details = item.orderDetails[index];

				await this.getOrderItem();
				await this.getItemSection();

				// console.log(item);
				// console.log(this.order_info);
				// return

				let o_Info = item.orderDetails[index].orderInfo;

				setTimeout(() => {
					o_Info.forEach(ele1 => {
						this.order_info.forEach(ele2 => {
							if (ele1.section_name_en == ele2.section_name_en && ele1.section_name_en != 'extra') {
								ele2.section_sub_id = ele1.section_sub_id;
								if (ele2.input_status == 'yes') {
									ele2.value = ele1.value;
								}
							} else if (ele1.section_name_en == ele2.section_name_en && ele1.section_name_en == 'extra') {
								ele2.extra_title = ele1.extra_title;
								ele2.value = ele1.value;
							}
						})
					})
				}, 500);

				delete this.order_details.orderInfo;
			},
			savePayment() {
			
				if (this.filter_store_bills.length <= 0 || this.filter_store_bills == null) {
					alert('Add store to pay');
					return;
				}

				this.payment.month_id = this.selectedMonth.month_id

				let filter = {
					payment: this.payment,
					storeBills: this.filter_store_bills,
				}

				this.paymentProgress = true;

				let url = "/add_utility_payment";
				if (this.payment.id != 0) {
					url = "/update_utility_payment";
				}

				axios.post(url, filter).then(async res => {
					let r = res.data;
					if (r.success) {
						let conf = confirm( r.message + ', Do you want to view invoice?');
						if (conf) {
							r.utilityDetailsArr.forEach(element => {
								window.open('/utility_invoice_print/' + element, '_blank');
							});
							await new Promise(r => setTimeout(r, 1000));
							// window.location = '/utility/payment';
						} else {
							// window.location = '/utility/payment';
						}
					} else {
						alert(r.message);
						this.paymentProgress = false;
					}
				})
			},

			checkCustomer() {
				if (!this.customer_find && this.selectedCustomer.Customer_Mobile.length == 11) {
					axios.post('/check_customer', {
						mobile: this.selectedCustomer.Customer_Mobile
					}).then(res => {
						if (res.data != null) {
							this.selectedCustomer = res.data;
							this.customer_find = true;
						}
					})
				}
			},

			async storeOnChange() {
				if (this.selectedStore.Store_SlNo == '') {
					return;
				}
				if (this.selectedMonth == null || this.selectedMonth == "") {
					alert("Select Month");
					this.selectedStore = {
						Store_SlNo: '',
						Store_Name: '',
						display_text: 'Select Store',
						Store_Mobile: '',
						meter_no: '',
					}
					return;
				}
				
				axios.post('/get_store_bills', {
					storeId: this.selectedStore.Store_SlNo,
					month: this.selectedMonth.month_id
				}).then(res => {
					this.store_bills = res.data;
				});

			},
			calculateTotal() {
				let total_electricity_bill = 0;
				let total_generator_bill = 0;
				let total_ac_bill = 0;
				let total_others_bill = 0;
				let total_late_fee = 0;

				let total_net_payable = 0;

				this.filter_store_bills.forEach(element => {
					total_electricity_bill += parseFloat(element.electricity_bill_payment);
					total_generator_bill += parseFloat(element.generator_bill_payment);
					total_ac_bill += parseFloat(element.ac_bill_payment);
					total_others_bill += parseFloat(element.others_bill_payment);
					total_late_fee += parseFloat(element.late_fee);
					total_net_payable +=  parseFloat(element.net_payable);
				});

				let total_payment_amount = parseFloat( +total_electricity_bill + +total_generator_bill + +total_ac_bill + +total_others_bill + +total_late_fee ).toFixed(2);

				this.payment.total_electricity_bill = parseFloat(+total_electricity_bill).toFixed(2);
				this.payment.total_generator_bill = parseFloat(+total_generator_bill).toFixed(2);
				this.payment.total_ac_bill = parseFloat(+total_ac_bill).toFixed(2);
				this.payment.total_others_bill = parseFloat(+total_others_bill).toFixed(2);
				this.payment.total_late_fee = parseFloat(+total_late_fee).toFixed(2);
				this.payment.total_payment_amount = total_payment_amount;
				this.payment.total_due = parseFloat(+total_net_payable + +total_late_fee - +total_payment_amount).toFixed(2);
				console.log(this.payment)
			},

			async customerExistCheck(mobile) {
				let hasCustomer = false;

				await axios.post('/check_customer', {
					mobile: mobile,
					check_mobile: true
				}).then(res => {
					if (res.data > 0)
						hasCustomer = true;
				})

				return hasCustomer;
			},
			async getPayments() {
				await axios.post('/get_utility_payment', { id: this.payment.id }).then(res => {
					let r = res.data;
					let payments = r.payments[0];
					this.payment.invoice = payments.invoice;
					this.payment.date = payments.payment_date;
					this.payment.total_payment_amount = payments.total_payment_amount;

					this.selectedMonth = {
						month_id: payments.month_id,
						month_name: payments.month_name
					}

				// let filterStoreBills = this.store_bills.filter(item => item.isSelect == true);
				
				// filterStoreBills.map(item => {
				// 	item.electricity_bill_payment = (item.electricity_bill_payment==0?item.electricity_bill:item.electricity_bill_payment)
				// 	item.generator_bill_payment = (item.generator_bill_payment==0?item.generator_bill:item.generator_bill_payment)
				// 	item.ac_bill_payment = (item.ac_bill_payment==0?item.ac_bill:item.ac_bill_payment)
				// 	item.others_bill_payment = (item.others_bill_payment==0?item.others_bill:item.others_bill_payment)
				// 	item.payment = +item.electricity_bill_payment + +item.generator_bill_payment + +item.ac_bill_payment + +item.others_bill_payment;
				// 	return item;
				// })
				// this.filter_store_bills = filterStoreBills;


					r.paymentDetails.forEach(store => {
						this.selectedStore.Store_SlNo = store.Store_SlNo
						this.storeOnChange();
					});
				})
			},
			
			
			
			clearOrderDetails() {
				this.order_details = {
				
				}
			},

			clearOrderInfo() {
				this.order_info = {};
			},
		}
	})
</script>