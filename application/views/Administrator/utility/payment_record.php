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
		padding: 3px;
		border: 1px solid #454545;
	}
    .record-table th{
        text-align: center;
    }
	.record-table thead>tr>th {
			background-color: #0097df !important;
			color: white;
			border: 1px solid #4e4e4e;
		}

</style>

<div id="processBill">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
            <form class="form-inline" id="searchForm" @submit.prevent="getBillPaymentsPost">
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
    <div class="row">
        <div class="col-md-12" style="margin-bottom:10px;margin-top:5px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <div v-if="billPayments.length > 0" style="display:none;" v-bind:style="{ display: billPayments.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="reportContent">
                    <table class="table table-bordered record-table">
                        <thead>
                            <tr>
                                <th>Sl</th>
								<th>Invoice</th>
                                <th>Month Name</th>
								<th>Store Name</th>
								<th>Renter Name</th>
								<th>Floor Name</th>
								<th>Electricity</th>
								<th>Generator</th>
								<th>AC Bill</th>
								<th>Other Bill</th>
								<th>Late Fee</th>
                                <th>Total Amount</th>
                                <th>Payment Date</th>
                                <th>Payment By</th>
                                <th style="text-align: center;">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(payment, ind) in billPayments">
                                <td>{{ ind + 1 }}</td>
                                <td style="text-align:left;">{{ payment.invoice }}</td>
                                <td style="text-align:left;">{{ payment.month_name }}</td>
                                <td style="text-align:left;">{{ payment.details[0].Store_Name }}</td>
                                <td style="text-align:left;">{{ payment.details[0].Renter_Name }}</td>
                                <td style="text-align:left;">{{ payment.details[0].Floor_Name }}</td>
                                <td style="text-align:left;">{{ payment.details[0].electricity_bill }}</td>
                                <td style="text-align:left;">{{ payment.details[0].generator_bill }}</td>
                                <td style="text-align:left;">{{ payment.details[0].ac_bill }}</td>
                                <td style="text-align:left;">{{ payment.details[0].others_bill }}</td>
                                <td style="text-align:left;">{{ payment.details[0].late_fee }}</td>
                                <td style="text-align:center;">{{ payment.total_payment_amount }}</td>
                                <td style="text-align:center;">{{ payment.payment_date }}</td>
                                <td style="text-align:left;">{{ payment.User_Name }}</td>
                                <td style="text-align: center;">
                                    <a href="" @click.prevent="viewBillPayment(payment.id)" class="" title="View">
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

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#processBill',
        filters: {
            decimal(value) {
                return value == null ? '0.00' : parseFloat(value).toFixed(2);
            }
        },

        data() {
            return {
                processingDate: moment().format('YYYY-MM-DD'),
                months: [],
                selectedMonth: null,
                billPayments: [],
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
            }
        },

        created() {
            this.getMonths();
            this.getBillPayments();
        },

        methods: {
            getMonths() {
                axios.get('/get_months').then(res => {
                    this.months = res.data;
                })
            },
            getBillPayments() {
                axios.post('/get_payment_record', { details: true }).then(res => {
					console.log(res.data)
                    this.billPayments = res.data;
                })
            },
            getBillPaymentsPost() {
                let filter = {
                    dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					details: true
                };
                axios.post('/get_payment_record', filter).then(res => {
					console.log(res.data)
                    this.billSheets = res.data;
                })
            },
            viewBillPayment(id) {
                window.open(`/bill_sheet/${id}`, '_blank');
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