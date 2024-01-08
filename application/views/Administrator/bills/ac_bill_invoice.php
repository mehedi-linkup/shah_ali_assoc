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

    .v-select .selected-tag {
        margin: 0px;
    }
</style>

<div id="purchaseInvoiceReport" class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
        <div class="form-group">
            <label class="col-sm-1 col-sm-offset-2 control-label no-padding-right">Month</label>
            <div class="col-sm-2">
                <v-select v-bind:options="months" v-model="selectedMonth" label="month_name" @input="getBillSheetDetail"></v-select>
            </div> 
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> Invoice no </label>
            <div class="col-sm-2">
                <v-select v-bind:options="invoices" label="invoice" v-model="selectedInvoice" v-on:input="viewInvoice" ></v-select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <input type="button" class="btn btn-primary" value="Show Report" v-on:click="viewInvoice" style="margin-top:0px;width:150px;display: none;">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <br>
        <acbill-invoice v-bind:bill_id="selectedInvoice.id" v-if="showInvoice"></acbill-invoice>
    </div>
</div>



<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/components/acbillInvoice.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#purchaseInvoiceReport',
        data() {
            return {
                invoices: [],
                selectedInvoice: null,
                showInvoice: false,

                billSheet: {},
                billDetails: [],
                months: [],
                selectedMonth: null,
            }
        },
        filters: {
            decimal(value) {
                return value == null ? '0.00' : parseFloat(value).toFixed(2);
            }
        },
        created() {
            this.getBillSheet();
            this.getMonths();
        },
        computed: {
            total() {
                return {
                    totalElectricity: this.billDetails.reduce((p, c) => p + +c.electricity_bill, 0),
                    totalUnit: this.billDetails.reduce((p, c) => p + +c.electricity_unit, 0),
                    totalGenerator: this.billDetails.reduce((p, c) => p + +c.generator_bill, 0),
                    totalAc: this.billDetails.reduce((p, c) => p + +c.ac_bill, 0),
                    totalUtility: this.billDetails.reduce((p, c) => p + +c.others_bill, 0),
                    totalPayable: this.billDetails.reduce((p, c) => p + +c.net_payable, 0),
                }
            }
        },
        methods: {
            getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
				})
			},
            getBillSheet() {
                axios.get("/get_bill_details").then(res => {
                    this.invoices = res.data;

                })
            },
            async viewInvoice() {
                this.showInvoice = false;
                await new Promise(r => setTimeout(r, 500));
                this.showInvoice = true;
            },

            async getBillSheetDetail() {
                await axios.post('/get_bill_details', { monthId: this.selectedMonth.month_id }).then(res => {
                    this.invoices = res.data;
                })
            },

            async print() {
                let printContent = `
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h3 style="margin-top:0px;margin-bottom:2px;text-transform:uppercase;font-weight:700;color:#0774b6!important">Bill Sheet</h3>
                        <p>${this.billSheet.month_name}</p>
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
                <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
            `);

                printWindow.document.body.innerHTML += printContent;

                printWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                printWindow.print();
                printWindow.close();
            },

        }
    })
</script>