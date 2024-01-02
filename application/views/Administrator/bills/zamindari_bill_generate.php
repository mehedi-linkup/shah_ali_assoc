<style>
	.v-select{
		margin-bottom: 5px;
        float: right;
        min-width: 200px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle {
		padding: 0px;
        height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus {
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

	.ui-datepicker-calendar {
    display: none;
    }

</style>
<div id="processBill">
	<div class="row"style="padding: 10px 0;">
		<div class="col-md-12">
			<form class="form-inline" @submit.prevent="generateBill">

				<div class="form-group">
                    <label>Processing Date</label>
                    <input type="date" class="form-control" v-model="billPayment.process_date">
                </div>

				<div class="form-group">
					<label class="col-sm-2 control-label no-padding-right"> Year </label>
					<div class="col-sm-4">
						<input style="height: 25px;" v-datepicker class="date-own form-control" type="text" v-model="billPayment.year" readonly>
					</div>
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" class="search-button" value="Generate">
				</div>
			</form>
		</div>
	</div>
	<br>
	<div v-if="billSheets.length > 0" style="display:none;" v-bind:style="{ display: billSheets.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="printContent">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
								<th>Year</th>
                                <th>Month Name</th>
                                <th>Total Amount</th>
                                <th>Processed Date</th>
                                <th>Processed By</th>
								<th>Last Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(bill, ind) in billSheets">
                                <td>{{ ind + 1 }}</td>
								<td>{{ bill.year }}</td>
                                <td style="text-align:left;">{{ bill.month_name }}</td>
                                <td style="text-align:right;">{{ bill.total_amount }}</td>
                                <td style="text-align:center;">{{ bill.process_date }}</td>
                                <td style="text-align:center;">{{ bill.added_by }}</td>
								<td style="text-align:center;">{{ bill.last_date }}</td>
                                <td>
                                    <a href="" @click.prevent="viewBillSheet(bill.id)" class="btn btn-info btn-xs" title="View">
                                        <i class="fa fa-file"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	Vue.directive('datepicker', {
		inserted: function (el, binding) {
			$(el).datepicker({
				minViewMode: 2,
				format: 'yyyy'
			}).on('changeDate', function (e) {
				el.dispatchEvent(new Event('input'));
			});
		},
	});
	new Vue({
		el: '#processBill',
		data() {
			return {
				billPayment: {
					id: null,
					process_date: moment().format("YYYY-MM-DD"),
					year: null
				},
				stores: [],
				months: [],
				month: null,
				year: null,
				billSheets: [],
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
			this.getBillSheets();
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
			onChangeYear() {

			},
			
			async generateBill() {
				if(this.billPayment.year == null) {
					alert("Select Year");
					return;
				}
 
                axios.post('/generate_zamindari_bill', {
                    process_date: this.billPayment.process_date,
                    year: this.billPayment.year
                })
                .then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success) {
                        this.getBillSheets();
						this.clearForm()
                    }
                })
                .catch(error => alert(error.message));
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
			getBillSheets() {
                axios.get('/get_zamindari_bill').then(res => {
                    this.billSheets = res.data.zamindaribills;
                })
            },

            viewBillSheet(billSheetId) {
                window.open(`/zamindari_bill_month/${billSheetId}`, '_blank');
            },
			clearForm() {
				this.billPayment = {
					id: null,
					process_date: moment().format("YYYY-MM-DD"),
					year: null
				}
			}
		}
	})
</script>
