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

	/* .v-select .vs__actions {
		margin-top: -5px;
	} */

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
	<div class="col-xs-12 col-md-12 col-lg-12" style="border:1px solid #ccc;margin-bottom:5px; padding:10px">
		<div class="row" style="margin-bottom: 10px;">
			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right">Payment Invoice</label>
				<div class="col-sm-2">
					<input type="text" id="invoiceNo" class="form-control" v-model="order.invoice" readonly />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Date </label>
				<div class="col-sm-2">
					<input class="form-control" id="salesDate" type="date" v-model="order.order_date" v-bind:disabled="userType == 'u' ? true : false" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Month </label>
				<div class="col-sm-2">
                    <v-select v-bind:options="months" label="month_name" v-model="month"></v-select>
				</div>
			</div>
		</div>
		<div class="row">
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
						<h5 style="text-align: center;background: rgb(0 90 139);padding: 6px;color: #fff;margin-top:0px">Renter Info:</h5>
						<div class="col-md-5" style="border-right: 1px solid #ccc;">

							<div class="form-group">
								<label class="col-sm-1 control-label"> Floor </label>
								<div class="col-sm-2">
									<select class="form-control" v-if="floors.length == 0"></select>
									<v-select v-bind:options="floors" v-model="selectedFloor" label="Floor_Name" v-if="floors.length > 0"></v-select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-1 control-label"> Store Grade </label>
								<div class="col-sm-2">
									<select class="form-control" v-if="grades.length == 0"></select>
									<v-select v-bind:options="grades" v-model="selectedGrade" label="Grade_Name" v-if="grades.length > 0"></v-select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-1 control-label"> Store </label>
								<div class="col-sm-2">
									<select class="form-control" v-if="stores.length == 0"></select>
									<v-select v-bind:options="stores" v-model="selectedStore" label="display_text" v-if="stores.length > 0"></v-select>
								</div>
							</div>
						
							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Renter </label>
								<div class="col-sm-7">
									<v-select v-bind:options="renters" label="display_name" v-model="selectedRenter" v-on:input="renterOnChange"></v-select>
								</div>
								<div class="col-sm-1" style="padding: 0;">
									<a href="<?= base_url('renter') ?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Renter"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Name </label>
								<div class="col-sm-8">
									<input type="text" id="customerName" placeholder="Name" class="form-control" v-model="selectedRenter.Renter_Name" readonly />
								</div>
							</div>

                            <div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-sm-8">
									<input type="number" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedRenter.Renter_Mobile" autocomplete="off" readonly />
								</div>
							</div>

							<div class="form-group">
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
											<th style="color:#000;">Store No</th>
											<th style="color:#000;">Start Date</th>
											<th style="color:#000;">Years</th>
											<th style="color:#000;">Amount</th>
											<th style="color:#000;">Paid</th>
											<th style="color:#000;">Due</th>
										</tr>
									</thead>
									<tbody>
										<template v-for="order in customer_orders">
											<tr v-for="(item,index) in order.orderDetails">
												<td>{{order.invoice}}</td>
												<td>{{order.order_date | dateOnly('DD-MM-YYYY')}}</td>
												<td>
													<span>{{ item.item_name}}</span>
												</td>
												<td style="text-align: right;">{{item.total_price}}</td>
												<td>
													<a href="javascript:" @click="existSaleClick(order,index)">
														<i class="fa fa-check"></i>
													</a>
												</td>
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
						<div class="col-md-12" style="margin-top:10px;">
							<div class="form-group row clearfix ">
                                <label class="col-sm-2" style="text-align: right;">Electricity Bill : </label>
								<div class="col-sm-2 no-padding">
									<input type="text" v-model="order_details.quantity" class="form-control" require>
								</div>
								<label class="col-sm-2" style="text-align: right;">AC Bill : </label>
								<div class="col-sm-2 no-padding">
									<input type="text" v-model="order_details.quantity" class="form-control" require>
								</div>
								<label class="col-sm-2" style="text-align: right;">Generator Bill : </label>
								<div class="col-sm-2 no-padding">
									<input type="text" v-model="order_details.quantity" class="form-control" require>
								</div>
                                <hr>
								<label class="col-sm-2" style="text-align: right;"> Others : </label>
								<div class="col-sm-2 no-padding">
									<input type="text" v-model="order_details.quantity" class="form-control" require>
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
									<tr style="display: none;">
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Fabric Price</label>
												<div class="col-xs-12">
													<input type="number" id="subTotal" class="form-control" v-model="order.fabric_price" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Charges</label>
												<div class="col-xs-12">
													<input type="number" class="form-control" v-model="order.charge" v-on:input="calculateTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total</label>
												<div class="col-xs-12">
													<input type="number" id="total" class="form-control" v-model="order.total" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Advance</label>
												<div class="col-xs-12">
													<input type="number" id="paid" class="form-control" v-model="order.advance" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label">Balance</label>
												<div class="col-xs-12">
													<input type="number" id="due" class="form-control" v-model="order.balance" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<div class="col-sm-12" style="margin-top: 10px;">
													<input type="button" class="btn btn-primary btn-sm" value="Save Payment" v-on:click.prevent="saveOrder" v-bind:disabled="orderProgress ? true : false" style="color: #fff;margin-top: 0px;width:100%;padding:5px;font-weight:bold;">
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
				orderItems: [],
				itemSections: [],
                months: [],
				month: null,
                stores: [],
                selectedStore: null,
				grades: [],
				selectedGrade: null,
				floors: [],
				selectedFloor: null,
				owners: [],
				selectedOwner: null,
				renters: [],
				selectedRenter: {
                    Renter_Name: '',
                    Renter_Mobile: '',
                    Renter_PreAddress: ''
                },
                employees: [],
                selectedEmployee: null,

				order: {
					id: parseInt('<?php echo $id; ?>'),
					invoice: '<?php echo $invoice; ?>',
					order_date: moment().format('YYYY-MM-DD'),
					delivery_date: moment().day(7).format('YYYY-MM-DD'),
					order_status: 'pending',
					customer_id: '',
					measurement_master: '',
					cutting_master: '',
					material_man: '',
					serial_man: '',
					sewing_master: '',
					finish_man: '',

					fabric_price: 0.00,
					charge: 0.00,
					total: 0.00,
					advance: 0.00,
					balance: 0.00,
				},
				orderProgress: false,
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
				order_details: {
					order_details_id: '',
					body_part: '',
					order_item_id: '',
					top_F_L: '',
					top_F_R: '',
					top_B_L: '',
					top_B_R: '',
					top_H_L: '',
					top_H_R: '',
					top_luze_1: '',
					top_luze_2: '',
					top_luze_3: '',
					top_height: '',
					top_chest: '',
					top_belly: '',
					top_hip: '',
					top_shoulder: '',
					top_shoulder_down: '',
					top_D: '',
					top_hand: '',
					top_ARM: '',
					top_M: '',
					top_N: '',
					top_throat: '',
					top_cuff: '',
					top_mohuri: '',
					top_gher: '',
					extra_item_top: '',
					b_f: '',
					b_b: '',
					b_waist: '',
					b_hip: '',
					b_height: '',
					b_thai: '',
					b_mohuri: '',
					b_hai: '',
					b_fly: '',
					b_knee: '',
					b_FD: '',
					extra_item_bottom: '',
					quantity: '',
					unit_price: '',
					total_price: '',
				},
				order_info: [],
				cart: [],

				userType: '<?php echo $this->session->userdata("accountType"); ?>',
				customer_orders: [],
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
			// pItem(sale) {
			// 	return 'shirt';
			// }
		},
		async created() {
			await this.getEmployees();
            this.getMonths();
            this.getFloors();
			this.getGrades();
            this.getOwners();
            this.getRenters();
			this.getStores();

			
			if (this.order.id != 0) {
				await this.getOrders();
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
				await axios.post('/get_stores', {
					
				}).then(res => {
					let stores = res.data;
                    this.stores = stores.map(store => {
					    store.display_text = store.Store_SlNo == '' ? store.Store_Name : `${store.Store_Name} - ${store.Store_No}`;
					    return store;
                    });
				})
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
			addToCart() {

				if (this.order_details.body_part == '') {
					alert('Select a Body Part.')
					return;
				}
				if (this.order_details.order_item_id == '') {
					alert('Select an order item.')
					return;
				}
				if (this.order_details.quantity == '' || this.order_details.quantity == '0') {
					alert('Minimum quantity 1 required.')
					return;
				}
				if (Object.keys(this.order_info).length == 0) {
					alert('Order Info Required')
					return;
				}

				this.orderItems.forEach(ele => {
					if (ele.order_item_id == this.order_details.order_item_id) {
						this.order_details.item_name = ele.item_name;
						this.order_details.unit_price = ele.charge;
					}
				})

				this.order_details.total_price = parseFloat(this.order_details.unit_price) * parseInt(this.order_details.quantity);

				if (this.order_details.hasOwnProperty("orderInfo")) {
					delete this.order_details.orderInfo;
				}
				if (this.order_details.hasOwnProperty("item_name_en")) {
					delete this.order_details.item_name_en;
				}
				this.order_info.forEach(ele => {
					delete ele.sub;
				})

				let item = {
					orderDetails: this.order_details,
					orderInfo: this.order_info,
				}

				this.cart.push(item);

				this.calculateTotal();
				this.orderItems = [];
				this.itemSections = [];
				this.clearOrderDetails();
				this.clearOrderInfo();

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
					console.log(data.orderInfo, this.order_info);
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

				console.log(this.order_details.orderInfo);

				delete this.order_details.orderInfo;
			},
			saveOrder() {
				if (this.order.measurement_master == '') {
					alert('Select measurement master!')
					return;
				}
				// if (this.order.cutting_master == '') {
				// 	alert('Select cutting master!')
				// 	return;
				// }
				// if (this.order.material_man == '') {
				// 	alert('Select material man!')
				// 	return;
				// }
				if (this.order.serial_man == '') {
					alert('Select serial man man!')
					return;
				}
				// if (this.order.sewing_master == '') {
				// 	alert('Select sewing master!')
				// 	return;
				// }
				// if (this.order.finish_man == '') {
				// 	alert('Select sewing master!')
				// 	return;
				// }
				if (this.selectedCustomer.Customer_Mobile == '' || this.selectedCustomer.Customer_Name == '' || this.selectedCustomer.Customer_Address == '') {
					alert('Customer Mobile/Name/Address required!')
					return;
				}

				let filter = {
					order: this.order,
					cart: this.cart,
					customer: this.selectedCustomer,
				}

				this.order.customer_id = this.selectedCustomer.Customer_SlNo


				let url = "/add_order";
				if (this.order.id != 0) {
					url = "/update_order";
				}


				console.log(filter, url);
				// return;

				axios.post(url, filter).then(async res => {
					let r = res.data;
					if (r.success) {
						let conf = confirm('Order success, Do you want to view invoice?');
						if (conf) {
							window.open('/order_invoice_print/' + r.id, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							window.location = '/order_entry';
						} else {
							window.location = '/order_entry';
						}
					} else {
						alert(r.message);
						this.saleOnProgress = false;
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

			async renterOnChange() {
				if (this.selectedCustomer.Customer_SlNo == '') {
					return;
				}

				axios.post('/get_orders', {
					customerId: this.selectedCustomer.Customer_SlNo,
					limit: 10
				}).then(res => {
					this.customer_orders = res.data;
				});

			},
			calculateTotal() {

				let sum = 0;
				this.cart.forEach(element => {
					sum += parseFloat(element.orderDetails.total_price);
				});

				this.order.charge = sum;
				this.order.total = parseFloat(sum) + parseFloat(this.order.fabric_price == '' ? 0 : this.order.fabric_price);

				this.order.balance = (this.order.total - parseFloat(this.order.advance == '' ? 0 : this.order.advance)).toFixed(2);
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
			async getOrders() {
				await axios.post('/get_orders', {
					id: this.order.id
				}).then(res => {

					// this.order_details.body_part = res.data[0].orderDetails[0].body_part;
					// this.getOrderItem();

					var order = res.data[0];
					this.order = order;
					// delete this.order.orderDetails

					this.selectedCustomer = {
						Customer_SlNo: order.customer_id,
						Customer_Code: order.Customer_Code,
						Customer_Name: order.Customer_Name,
						display_name: order.Customer_Code + ' - ' + order.Customer_Name,
						Customer_Mobile: order.Customer_Mobile,
						Customer_Address: order.Customer_Address,
						Customer_Type: order.Customer_Type
					};

					for (let i = 0; i < res.data[0].orderDetails.length; i++) {
						let item = {
							orderDetails: res.data[0].orderDetails[i],
							orderInfo: res.data[0].orderDetails[i].orderInfo,
						}
						this.cart.push(item);

					}
					for (let i = 0; i < res.data[0].orderDetails.length; i++) {
						delete this.cart[0].orderDetails.orderInfo
					}

				})
			},
			chargeCalculate() {
				let total_tk = 0;
				this.typeChargeAmount.forEach(item => {
					if (item.type == 'shirt') {
						total_tk += this.sales.shirt * item.charge_tk;
					}
					if (item.type == 'panjabi') {
						total_tk += this.sales.panjabi * item.charge_tk;
					}
					if (item.type == 'coat') {
						total_tk += this.sales.coat * item.charge_tk;
					}
					if (item.type == 'watch_coat') {
						total_tk += this.sales.watch_coat * item.charge_tk;
					}
					if (item.type == 'safari') {
						total_tk += this.sales.safari * item.charge_tk;
					}
					if (item.type == 'appron') {
						total_tk += this.sales.appron * item.charge_tk;
					}
					if (item.type == 'prince_coat') {
						total_tk += this.sales.prince_coat * item.charge_tk;
					}
					if (item.type == 'mujib_coat') {
						total_tk += this.sales.mujib_coat * item.charge_tk;
					}
					if (item.type == 'sherwani') {
						total_tk += this.sales.sherwani * item.charge_tk;
					}
					if (item.type == 'pant') {
						total_tk += this.sales.pant * item.charge_tk;
					}
					if (item.type == 'trousers') {
						total_tk += this.sales.trousers * item.charge_tk;
					}
				})
				this.sales.charge = total_tk;
				this.calculateTotal();

			},
			CollarValue(val) {
				this.sales.Shirt_CollarValue = val;
			},
			PlateValue(val) {
				this.sales.Shirt_PlateValue = val;
			},
			clearOrderDetails() {
				this.order_details = {
					order_details_id: '',
					body_part: '',
					order_item_id: '',
					top_F_L: '',
					top_F_R: '',
					top_B_L: '',
					top_B_R: '',
					top_H_L: '',
					top_H_R: '',
					top_height: '',
					top_chest: '',
					top_belly: '',
					top_hip: '',
					top_shoulder: '',
					top_hand: '',
					top_M: '',
					top_N: '',
					top_throat: '',
					top_cuff: '',
					top_mohuri: '',
					top_gher: '',
					b_f: '',
					b_b: '',
					b_waist: '',
					b_hip: '',
					b_height: '',
					b_thai: '',
					b_mohuri: '',
					b_hai: '',
					b_fly: '',
					b_knee: '',
					quantity: '',
					unit_price: '',
					total_price: '',
				}
			},

			clearOrderInfo() {
				this.order_info = {};
			},
		}
	})
</script>