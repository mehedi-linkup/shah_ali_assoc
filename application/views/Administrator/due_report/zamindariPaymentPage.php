<style>
	#single .v-select{
		margin-bottom: 5px;
	}
	#single .v-select.open .dropdown-toggle{
		border-bottom: 1px solid #ccc;
	}
	#single .v-select .dropdown-toggle{
		padding: 0px;
		height: 25px;
	}
	#single .v-select input[type=search], #single .v-select input[type=search]:focus{
		margin: 0px;
	}
	#single .v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	#single .v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	#single .v-select .vs__actions{
		margin-top:-5px;
	}
	#single .v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
	#single .v-select .dropdown-toggle .clear {
		bottom: 3px;
	}
	
	/* multiple v-select */
	#multiple .v-select{
		margin-bottom: 5px;
	} 
	#multiple .single .open-indicator{
		bottom: 0;
	}
	#multiple .v-select .dropdown-toggle .clear{
		bottom: 5px;
	}
	
	#multiple .single .selected-tag{
		margin: 0;
	}
	#multiple #branchDropdown .vs__actions button{
		display:none;
	}
	#multiple #branchDropdown .vs__actions .open-indicator{
		height:25px;
		margin-top:7px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus {
		height: auto;
	}
	.v-select .open-indicator {
		bottom: -2px;
	}

	/* end of multiple v-select */

	#customerPayment label{
		font-size:13px;
	}
	#customerPayment select{
		border-radius: 3px;
		padding: 0;
	}
	#customerPayment .add-button{
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display:block;
		text-align: center;
		color: white;
	}
	#customerPayment .add-button:hover{
		background-color: #41add6;
		color: white;
	}
	.button-like {
		display: inline-block;
		padding: 0px 5px;
		font-size: 15px;
		text-align: center;
		text-decoration: none;
		cursor: pointer;
		border: 1px solid #3498db;
		color: #3498db;
		background-color: #ffffff;
		border-radius: 3px;
		transition: background-color 0.3s, color 0.3s;
	}

	/* Hover effect */
	.button-like:hover {
		background-color: #3498db;
		color: #ffffff;
	}
</style>
<div id="customerPayment">
<div class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row" style="border-bottom: 1px solid #ccc;padding-bottom: 15px;margin-bottom: 15px;">
				<div class="col-md-12" style="margin-bottom:5px; padding:10px">
					<form @submit.prevent="saveOwnerPayment">
						<div class="row">
							<div class="col-md-5 col-md-offset-1">
								<div class="form-group" style="display:none;" v-bind:style="{display: payment.ZPayment_Paymentby == 'bank' ? '' : 'none'}">
									<label class="col-md-4 control-label">Bank Account</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<v-select v-bind:options="filteredAccounts" v-model="selectedAccount" label="display_text" placeholder="Select account"></v-select>
									</div>
								</div>
								<div id="multiple" class="form-group">
									<label class="col-md-4 control-label">Month</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7 col-xs-12">
										<select class="form-control" v-if="months.length == 0"></select>
										<v-select multiple dense v-bind:options="months" v-model="selectedMonth" label="month_name" @input="getOwnerDue" v-if="months.length > 0"></v-select>
									</div>
								</div>
								<div id="single" class="form-group">
									<label class="col-md-4 control-label">Owner</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7 col-xs-12">
										<select class="form-control" v-if="owners.length == 0"></select>
										<v-select v-bind:options="owners" v-model="selectedOwner" label="display_name" @input="getOwnerDue" v-if="owners.length > 0"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">Status</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<textarea class="form-control" v-model="payment.invoice_text" disabled></textarea>
									</div>
								</div>
							
								<!-- <div class="form-group">
									<label class="col-md-4 control-label">Due</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<input type="text" class="form-control" v-model="payment.ZPayment_bill_amount" disabled>
									</div>
								</div> -->
							</div>

							<div class="col-md-5">
								<div class="form-group">
									<label class="col-md-4 control-label">Payment Date</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<input type="date" class="form-control" v-model="payment.ZPayment_date" required @change="getZamindariPayments" v-bind:disabled="userType == 'u' ? true : false">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Note</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<textarea class="form-control" v-model="payment.ZPayment_notes"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">Amount</label>
									<label class="col-md-1">:</label>
									<div class="col-md-7">
										<input type="number" class="form-control" v-model="payment.ZPayment_amount" readonly>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-7 col-md-offset-5">
										<input type="submit" class="btn btn-success btn-sm" value="Save">
										<input type="button" class="btn btn-danger btn-sm" value="Cancel" @click="resetForm">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-sm-12 form-inline">
					<div class="form-group">
						<label for="filter" class="sr-only">Filter</label>
						<input type="text" class="form-control" v-model="filter" placeholder="Filter">
					</div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<datatable :columns="columns" :data="payments" :filter-by="filter" style="margin-bottom: 5px;">
							<template scope="{ row }">
								<tr>
									<td>{{ row.ZPayment_invoice }}</td>
									<td>{{ row.ZPayment_date }}</td>
									<td>{{ row.Owner_Name }}</td>
									<td>{{ row.Store_Name }}</td>
									<td>{{ row.ZPayment_amount }}</td>
									<td>{{ row.ZPayment_notes }}</td>
									<td>{{ row.ZPayment_Addby }}</td>
									<td>
										<a type="button" class="button-like button edit" target="_blank" :href="`/zamindari_payment_invoice_print/${row.ZPayment_id}`" >
											<i class="fa fa-file-o"></i>
										</a>
										<?php if($this->session->userdata('accountType') != 'u'){?>
										<button type="button" class="button edit" @click="editPayment(row)">
											<i class="fa fa-pencil"></i>
										</button>
										<button type="button" class="button" @click="deletePayment(row.ZPayment_id)">
											<i class="fa fa-trash"></i>
										</button>
										<?php }?>
									</td>
								</tr>
							</template>
						</datatable>
						<datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select-2.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#customerPayment',
		data(){
			return {
				payment: {
					ZPayment_id: 0,
					ZPayment_storeID: null,
					ZPayment_ownerID: null,
					ZPayment_TransactionType: 'CR',
					ZPayment_Paymentby: 'cash',
					zamindari_detail_id : null,
					ZPayment_date: moment().format('YYYY-MM-DD'),
					ZPayment_amount: '',
					ZPayment_notes: '',
					invoices: [],
					invoice_text: '',
					ZPayment_bill_amount: ''
				},
				payments: [],
				months: [], 
				selectedMonth: [],
				owners: [],
				selectedOwner: {
					display_name: 'Select Owner',
					Owner_Name: ''
				},
			
				accounts: [],
                selectedAccount: null,
				userType: '<?php echo $this->session->userdata("accountType");?>',
				
				columns: [
                    { label: 'Zamindari Invoice', field: 'ZPayment_invoice', align: 'center' },
                    { label: 'Date', field: 'ZPayment_date', align: 'center' },
                    { label: 'Owner', field: 'Owner_Name', align: 'center' },
                    { label: 'Store', field: 'Store_Name', align: 'center' },
                    { label: 'Amount', field: 'ZPayment_amount', align: 'center' },
                    { label: 'Note', field: 'ZPayment_notes', align: 'center' },
                    { label: 'Saved By', field: 'ZPayment_Addby', align: 'center' },
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: ''
			}
		},
		computed: {
            filteredAccounts(){
                let accounts = this.accounts.filter(account => account.status == '1');
                return accounts.map(account => {
                    account.display_text = `${account.account_name} - ${account.account_number} (${account.bank_name})`;
                    return account;
                })
            },
        },
		created(){
			this.getOwners();
			this.getMonths();
			this.getAccounts();
			this.getZamindariPayments();
		},
		methods:{
			getZamindariPayments(){
				let data = {
					dateFrom: this.payment.ZPayment_date,
					dateTo: this.payment.ZPayment_date
				}
				axios.post('/get_zamindari_payments', data).then(res => {
					this.payments = res.data;
				})
			},
			getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
				})
			},
			getOwners(){
				axios.get('/get_owners').then(res => {
					this.owners = res.data;
				})
			},
		
			getOwnerDue(){
				if(event.type == "click"){
					return;
				}

				if(this.selectedMonth.length <= 0){
					this.payment.ZPayment_bill_amount = 0;
					return;
				}

				if(this.selectedOwner == null || this.selectedOwner.Owner_SlNo == undefined){
					this.payment.ZPayment_bill_amount = 0
					return;
				}

				
				axios.post('/get_owner_zamindariDue', {ownerId: this.selectedOwner.Owner_SlNo, month: this.selectedMonth }).then(res => {
					this.payment.invoices = res.data;
					this.payment.invoice_text = res.data.map(item => item.invoice).join(', ');
					this.payment.ZPayment_bill_amount = res.data.reduce((prev, curr) => {
						if(curr.payment_status == 'unpaid') {
							return prev + +curr.net_payable;
						} else {
							return prev + 0;
						}
					}, 0);
					this.payment.ZPayment_amount = +this.payment.ZPayment_bill_amount
				})
			},
			getAccounts(){
                axios.get('/get_bank_accounts')
                .then(res => {
                    this.accounts = res.data;
                })
            },
			saveOwnerPayment(){
				if(+this.payment.ZPayment_amount <= 0) {
					alert("payment must be greater than zero");
					return;
				}
				if(this.payment.ZPayment_Paymentby == 'bank'){
					if(this.selectedAccount == null){
						alert('Select an account');
						return;
					} else {
						this.payment.account_id = this.selectedAccount.account_id;
					}
				} else {
					this.payment.account_id = null;
				}
				if(this.selectedOwner == null || this.selectedOwner.Owner_SlNo == undefined){
					alert('Select Owner');
					return;
				}

				this.payment.ZPayment_ownerID = this.selectedOwner.Owner_SlNo;

				let url = '/add_zamindari_payment';
				if(this.payment.ZPayment_id != 0){
					url = '/update_zamindari_payment';
				}
				axios.post(url, this.payment).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
						this.getZamindariPayments();
						let invoiceConfirm = confirm('Do you want to view invoice?');
						if(invoiceConfirm == true){
							window.open('/paymentAndReport/'+r.paymentId, '_blank');
						}
					}
				})
			},
			editPayment(payment){
				let keys = Object.keys(this.payment);
				keys.forEach(key => {
					this.payment[key] = payment[key];
				})

				this.selectedOwner = {
					Owner_SlNo: payment.ZPayment_ownerID,
					Owner_Name: payment.Owner_Name,
					display_name: `${payment.ZPayment_ownerID} - ${payment.Owner_Name}`
				}

				if(payment.ZPayment_Paymentby == 'bank'){
					this.selectedAccount = {
						account_id: payment.account_id,
						account_name: payment.account_name,
						account_number: payment.account_number,
						bank_name: payment.bank_name,
						display_text: `${payment.account_name} - ${payment.account_number} (${payment.bank_name})`
					}
				}
			},
			deletePayment(paymentId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_zamindari_payment', {paymentId: paymentId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getZamindariPayments();
					}
				})
			},
			resetForm(){
				this.payment.ZPayment_id = 0;
				this.payment.ZPayment_ownerID = '';
				this.payment.ZPayment_amount = '';
				this.payment.ZPayment_notes = '';
				
				this.selectedOwner = {
					display_name: 'Select Owner',
					Owner_Name: ''
				}
				
				this.payment.ZPayment_bill_amount = 0;
				
			}
		}
	})
</script>