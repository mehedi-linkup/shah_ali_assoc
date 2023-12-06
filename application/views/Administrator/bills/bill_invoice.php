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
        <div class="form-group" style="margin-top:10px;">
            <label class="col-sm-1 col-sm-offset-2 control-label no-padding-right"> Invoice no </label>
            <label class="col-sm-1 control-label no-padding-right"> : </label>
            <div class="col-sm-3">
                <v-select v-bind:options="invoices" label="month_name" v-model="selectedInvoice" v-on:input="getBillSheetDetail" placeholder="Select Invoice"></v-select>
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

        <div v-if="showInvoice" id="billSheet">

            <div v-if="billDetails.length > 0" style="display:none;" v-bind:style="{ display: billDetails.length > 0 ? '' : 'none'}">
                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
                    </div>

                    <div class="col-xs-12 text-center">
                        <h3 style="margin-top:0px;margin-bottom:2px;text-transform:uppercase;font-weight:700;color:#0774b6">Bill Sheet</h3>
                        <p>{{ billSheet.month_name }}</p>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive" id="printContent">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">Sl</th>
                                        <th style="text-align: left;">Store No</th>
                                        <th style="text-align: left;">Store Name</th>
                                        <th style="text-align: left;">Renter Name</th>
                                        <th style="text-align: left;">Electricity Unit</th>
                                        <th style="text-align: left;">Electricity Bill</th>
                                        <th style="text-align: left;">Generator Bill</th>
                                        <th style="text-align: left;">AC Bill</th>
                                        <th style="text-align: left;">Others</th>
                                        <th style="text-align: right;">Net Payable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(billDetail, ind) in billDetails">
                                        <td>{{ ind + 1 }}</td>
                                        <td style="text-align:left;">{{ billDetail.Store_No }}</td>
                                        <td style="text-align:left;">{{ billDetail.Store_Name }}</td>
                                        <td style="text-align:left;">{{ billDetail.Renter_Name }}</td>
                                        <td style="text-align:left;">{{ billDetail.current_unit }}</td>
                                        <td style="text-align:left;">{{ billDetail.electricity_bill | decimal }}</td>
                                        <td style="text-align:left;">{{ billDetail.generator_bill | decimal }}</td>
                                        <td style="text-align:left;">{{ billDetail.ac_bill | decimal }}</td>
                                        <td style="text-align:left;">{{ billDetail.others_bill | decimal }}</td>
                                        <td style="text-align:right;">{{ billDetail.net_payable | decimal }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:center;font-weight:700">Total</td>
                                        <td style="text-align:left;font-weight:700">{{ total.totalUnit | decimal }}</td>
                                        <td style="text-align:left;font-weight:700">{{ total.totalElectricity | decimal }}</td>
                                        <td style="text-align:left;font-weight:700">{{ total.totalGenerator | decimal }}</td>
                                        <td style="text-align:left;font-weight:700">{{ total.totalAc | decimal }}</td>
                                        <td style="text-align:left;font-weight:700">{{ total.totalUtility | decimal }}</td>
                                        <td style="text-align:right;font-weight:700">{{ total.totalPayable | decimal }}</td>
                                    </tr>
                                </tfoot>
                            </table>
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
<script src="<?php echo base_url(); ?>assets/js/vue/components/purchaseInvoice.js"></script>

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
                billDetails: []
            }
        },
        filters: {
            decimal(value) {
                return value == null ? '0.00' : parseFloat(value).toFixed(2);
            }
        },
        created() {
            this.getBillSheet();
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
            getBillSheet() {
                axios.get("/get_bill_sheet").then(res => {
                    this.invoices = res.data.billSheets;
                })
            },
            async viewInvoice() {
                this.showInvoice = false;
                await new Promise(r => setTimeout(r, 500));
                this.showInvoice = true;
            },

            async getBillSheetDetail() {
                await axios.post('/get_bill_sheet', { id: this.selectedInvoice.id }).then(res => {
                    // this.invoices = res.data.billSheets;
                    this.billDetails = res.data.billDetails;
                    this.showInvoice = true;
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