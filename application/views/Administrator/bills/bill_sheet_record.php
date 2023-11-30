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
</style>

<div id="processBill">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
            <form class="form-inline" id="searchForm" @submit.prevent="getBillSheetsPost">
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
    <div v-if="billSheets.length > 0" style="display:none;" v-bind:style="{ display: billSheets.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="printContent">
                    <table class="table table-bordered record-table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Month Name</th>
                                <th>Total Amount</th>
                                <th>Processed Date</th>
                                <th>Processed By</th>
                                <th style="text-align: center;">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(billSheet, ind) in billSheets">
                                <td>{{ ind + 1 }}</td>
                                <td style="text-align:left;">{{ billSheet.month_name }}</td>
                                <td style="text-align:center;">{{ billSheet.total_amount }}</td>
                                <td style="text-align:center;">{{ billSheet.process_date }}</td>
                                <td style="text-align:left;">{{ billSheet.processed_by }}</td>
                                <td style="text-align: center;">
                                    <a href="" @click.prevent="viewBillSheet(billSheet.id)" class="" title="View">
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
                billSheets: [],
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
            }
        },

        created() {
            this.getMonths();
            this.getBillSheets();
        },

        methods: {
            getMonths() {
                axios.get('/get_months').then(res => {
                    this.months = res.data;
                })
            },
            getBillSheets() {
                axios.get('/get_bill_sheet').then(res => {
                    this.billSheets = res.data.billSheets;
                })
            },
            getBillSheetsPost() {
                let filter = {
                    dateFrom: this.dateFrom,
					dateTo: this.dateTo
                };
                axios.post('/get_bill_sheet', filter).then(res => {
                    this.billSheets = res.data.billSheets;
                })
            },
            viewBillSheet(id) {
                window.open(`/bill_sheet/${id}`, '_blank');
            },

            async print() {
                let printContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Bill Sheet Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#printContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var printWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				printWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

              

				printWindow.document.body.innerHTML += printContent;

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