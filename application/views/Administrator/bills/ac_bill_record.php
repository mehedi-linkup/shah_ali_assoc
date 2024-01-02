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
		padding: 5px;
		border: 1px solid #454545;
	}
    .record-table th{
        text-align: center;
    }
</style>

<div id="processBill">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
            <form class="form-inline" id="searchForm" @submit.prevent="getBillPayments">

                <div class="form-group">
					<label>Search Type</label>
					<select class="form-control" v-model="searchType" @change="onChangeSearchType">
						<option value="">All</option>
						<option value="month">By Month</option>
						<option value="renter">By Renter</option>
						<option value="store">By Store</option>
						<option value="floor">By Floor</option>
					</select>
				</div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'month' && months.length > 0 ? '' : 'none'}">
					<label>Month</label>
					<v-select v-bind:options="months" v-model="selectedMonth" label="month_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'renter' && renters.length > 0 ? '' : 'none'}">
					<label>Renter</label>
					<v-select v-bind:options="renters" v-model="selectedRenter" label="display_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'store' && stores.length > 0 ? '' : 'none'}">
					<label>Store</label>
					<v-select v-bind:options="stores" v-model="selectedStore" label="display_name"></v-select>
				</div>

				<div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'floor' && floors.length > 0 ? '' : 'none'}">
					<label>Floor</label>
					<v-select v-bind:options="floors" v-model="selectedFloor" label="Floor_Name"></v-select>
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
   
    <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: billPayments.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Invoice</th>
                            <th>Month Name</th>
                            <th>Store Name</th>
                            <th>Renter Name</th>
                            <th>Floor Name</th>
                            <th>AC Bill</th>
                            <th>Last Date</th>
                            <th>Process By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(payment, ind) in billPayments">
                            <td>{{ ind + 1 }}</td>
                            <td style="text-align:left;">{{ payment.invoice }}</td>
                            <td style="text-align:left;">{{ payment.month_name }}</td>
                            <td style="text-align:left;">{{ payment.Store_Name }}</td>
                            <td style="text-align:left;">{{ payment.Renter_Name }}</td>
                            <td style="text-align:left;">{{ payment.Floor_Name }}</td>
                            <td style="text-align:left;">{{ payment.ac_bill }}</td>
                            <td style="text-align:center;">{{ payment.last_date }}</td>
                            <td style="text-align:left;">{{ payment.added_by }}</td>
                            <td style="text-align:center;">
                                <a href="" title="Bill Invoice" v-bind:href="`/ac_bill_invoice_print/${payment.id}`" target="_blank"><i class="fa fa-file"></i></a>
                            </td>
                        </tr>
						<tr>
							<td colspan="6"></td>
							<td style="text-align:left;">{{ parseFloat(billPayments.reduce((prev, cur)=> { return +prev + +cur.ac_bill  }, 0) ).toFixed(2) }}</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
                    </tbody>
                </table>
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
        el: '#processBill',

        data() {
            return {
                searchType: '',
                processingDate: moment().format('YYYY-MM-DD'),
                months: [],
                selectedMonth: null,
                stores: [],
                selectedStore: null,
                floors: [],
                selectedFloor: null,
                renters: [],
                selectedRenter: null,
                billPayments: [],
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
            }
        },

        filters: {
            decimal(value) {
                return value == null ? '0.00' : parseFloat(value).toFixed(2);
            }
        },
        created() {
            this.getMonths();
            // this.getBillPayments();
        },

        methods: {
            getStores(){
				axios.get('/get_stores').then(res => {
					this.stores = res.data;
					this.stores.unshift({
						Store_SlNo: '',
						display_name: 'All'
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
            getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
					this.renters.unshift({
						Renter_SlNo: '',
						display_name: 'All'
					})
				})
			},
			getFloors(){
				axios.get('/get_floors').then(res => {
					this.floors = res.data;
					this.floors.unshift({
						Floor_SlNo: '',
						Floor_Name: 'All'
					})
				})
			},

            // getBillPayments() {
            //     axios.post('/get_payment_record', { details: true }).then(res => {
			// 		console.log(res.data)
            //         this.billPayments = res.data;
            //     })
            // },
            getBillPayments() {

                if(this.searchType != 'store'){
					this.selectedStore = null;
				}

				if(this.searchType != 'renter'){
					this.selectedRenter = null;
				}

				if(this.searchType != 'month'){
					this.selectedMonth = null;
				}

				if(this.searchType != 'floor'){
					this.selectedFloor = null;
				}

                let filter = {
                    storeId: this.selectedStore == null || this.selectedStore.Store_SlNo == '' ? '' : this.selectedStore.Store_SlNo,
					renterId: this.selectedRenter == null || this.selectedRenter.Renter_SlNo == '' ? '' : this.selectedRenter.Renter_SlNo,
                    floorId: this.selectedFloor == null || this.selectedFloor.Floor_SlNo == '' ? '' : this.selectedFloor.Floor_SlNo,
					month: this.selectedMonth == null || this.selectedMonth.month_id == '' ? '' : this.selectedMonth.month_id,
                    dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					details: true
                };
                axios.post('/get_bill_details', filter).then(res => {
                    this.billPayments = res.data;
                })
            },
            viewBillPayment(id) {
                window.open(`/bill_sheet/${id}`, '_blank');
            },

            onChangeSearchType(){
				this.billPayments = [];
				if(this.searchType == 'month'){
					this.getMonths();
				} 
				else if(this.searchType == 'renter'){
					this.getRenters();
				}
				else if(this.searchType == 'store'){
					this.getStores();
				}
				else if(this.searchType == 'floor'){
					this.getFloors();
				}
			},

            async print() {
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Bill Sheet Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var printWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				printWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

                printWindow.document.head.innerHTML += `
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

            
				printWindow.document.body.innerHTML += reportContent;

                let rows = printWindow.document.querySelectorAll('.record-table tr');
                rows.forEach(row => {
                    row.lastChild.remove();
                });

				printWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				printWindow.print();
				printWindow.close();
            }
        }
    })
</script>