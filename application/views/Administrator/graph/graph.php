<style>
    .widgets {
        width: 100%;
        min-height: 100px;
        padding: 8px;
        box-shadow: 0px 1px 2px #454545;
        border-radius: 3px;
        text-align: center;
    }
    .widgets .widget-icon {
        width: 40px;
        height: 40px;
        padding-top: 8px;
        border-radius: 50%;
        color: white;
    }
    .widgets .widget-content {
        flex-grow: 2;
        font-weight: bold;
    }
    .widgets .widget-content .widget-text {
        font-size: 13px;
        color: #6f6f6f;
    }
    .widgets .widget-content .widget-value {
        font-size: 16px;
    }

    .custom-table-bordered,
    .custom-table-bordered>tbody>tr>td, 
    .custom-table-bordered>tbody>tr>th, 
    .custom-table-bordered>tfoot>tr>td, 
    .custom-table-bordered>tfoot>tr>th, 
    .custom-table-bordered>thead>tr>td, 
    .custom-table-bordered>thead>tr>th{
        border: 1px solid #224079;
    }
</style>
<div id="graph">
    <div class="row" v-if="showData" style="display:none;" v-bind:style="{ display: showData ? '' : 'none' }">
        <div class="col-md-12">
            <marquee scrollamount="3" onmouseover="this.stop();" onmouseout="this.start();" direction="left" height="30" bgcolor="#224079" style="color:white;padding-top:5px;margin-bottom: 15px;">{{ paymentText }}</marquee>
        </div>
    </div>
    <div class="row" v-if="showData" style="display:none;" v-bind:style="{ display: showData ? '' : 'none' }">
       
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #666633;">
                <div class="widget-icon" style="background-color: #666633;text-align:center;">
                    <i class="fa fa-money fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Today's Collection</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ todaysCollection | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #008241;">
                <div class="widget-icon" style="background-color: #008241;text-align:center;">
                    <i class="fa fa-shopping-cart fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Monthly Bills</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ thisMonthBill | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ff8000;">
                <div class="widget-icon" style="background-color: #ff8000;text-align:center;">
                    <i class="fa fa-reply fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Store Due</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ storeDue | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ff8000;">
                <div class="widget-icon" style="background-color: #ff8000;text-align:center;">
                    <i class="fa fa-reply fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Renter Due</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ renterDue | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ae0000;">
                <div class="widget-icon" style="background-color: #ae0000;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Cash Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ cashBalance | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #663300;">
                <div class="widget-icon" style="background-color: #663300;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Bank Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ bankBalance | decimal }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" v-if="showData" style="display:none;margin-top: 10px;" v-bind:style="{ display: showData ? '' : 'none' }">
        
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #666633;">
                <div class="widget-icon" style="background-color: #666633;text-align:center;">
                    <i class="fa fa-building fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Asset Value</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ assetValue | decimal }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ff8000;">
                <div class="widget-icon" style="background-color: #ff8000;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Invest Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ investBalance | decimal }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2  col-xs-6">
            <div class="widgets" style="border-top: 5px solid #ae0000;">
                <div class="widget-icon" style="background-color: #ae0000;text-align:center;">
                    <i class="fa fa-dollar fa-2x"></i>
                </div>
                
                <div class="widget-content">
                    <div class="widget-text">Loan Balance</div>
                    <div class="widget-value"><?php echo $this->session->userdata('Currency_Name');?> {{ loanBalance | decimal }}</div>
                </div>
            </div>
        </div>
       
    </div>
    <div class="row" style="margin-top:20px;margin-bottom: 25px;">
        
        <div class="col-md-12" v-if="salesGraph == 'monthly'">
            <h3 class="text-center">This Month's Payment</h3>
            <sales-chart
            type="ColumnChart"
            :data="paymentData"
            :options="paymentChartOptions"
            />
        </div>
        <div class="col-md-12" v-else>
            <h3 class="text-center">This Year's Payment</h3>
            <sales-chart
            type="ColumnChart"
            :data="yearlyPaymentData"
            :options="yearlyPaymentsChartOptions"
            />
        </div>
        <div class="col-md-12 text-center">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-primary" @click="salesGraph = 'monthly'">Monthly</button>
                <button type="button" class="btn btn-warning" @click="salesGraph = 'yearly'">Yearly</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3 class="text-center">Top Store</h3>
            <top-product-chart
            type="PieChart"
            :data="topStores"
            :options="topStoresOptions"
            />
        </div>
        <div class="col-md-4 col-md-offset-2">
            <table class="table custom-table-bordered">
                <thead>
                    <tr>
                        <td class="text-center" colspan="2" style="background-color: #224079;color: white;font-weight: 900;">Top Renter</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="renter in topRenters">
                        <td width="75%">{{renter.renter_name}}</td>
                        <td width="25%">{{renter.amount}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <div class="col-md-6">
            <h3 class="text-center">Top Customers</h3>
            <top-customer-chart
            type="PieChart"
            :data="topCustomers"
            :options="topCustomersOptions"
            />
        </div> -->
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/vue-google-charts.browser.js"></script>

<script>
    let googleChart = VueGoogleCharts.GChart;
    new Vue({
        el: '#graph',
        components: {
            'sales-chart': googleChart,
            'top-product-chart': googleChart,
            'top-customer-chart': googleChart
        },
        filters: {
            decimal(value) {
                return value == null || value == '' ? '0.00' : parseFloat(value).toFixed(2);
            }
        },
        data () {
            return {
                billData: [
                    ['Date', 'Bill']
                ],
                paymentData: [
                    ['Date', 'Payment']
                ],
                paymentChartOptions: {
                    chart: {
                        title: 'Bill',
                        subtitle: "This month's payments data",
                    }
                },
                yearlyBillData: [
                    ['Month', 'Bill']
                ],
               
                yearlyBillsChartOptions: {
                    chart: {
                        title: 'Bills',
                        subtitle: "This year's Bills data",
                    }
                },
                yearlyPaymentData: [
                    ['Month', 'Payment']
                ],
                yearlyPaymentsChartOptions: {
                    chart: {
                        title: 'Payments',
                        subtitle: "This year's Payments data",
                    }
                },
                topRenters: [
                    ['Renter', 'Amount']
                ],
                topRentersOptions: {
                    chart: {
                        title: 'Top Renter',
                        subtitle: "Top Renter"
                    }
                },
                topStores : ['Store', 'Amount'],
                topStoresOptions: {
                    chart: {
                        title: 'Top Store',
                        subtitle: "Top Store"
                    }
                },
                
                paymentText: '',
                thisMonthBill: 0,
                todaysCollection: 0,
                cashBalance: 0,
                storeDue: 0,
                renterDue: 0,
                bankBalance: 0,
                assetValue: 0,
                investBalance: 0,
                loanBalance: 0,
                showData: false,
                salesGraph: 'monthly'
            }
        },
        created(){
            this.getGraphData();
            setInterval(() => {
                this.getGraphData();
            }, 10000);
        },
        methods: {
            getGraphData(){
                axios.get('/get_graph_data').then(res => {
                    this.billData = [
                        ['Date', 'Bill']
                    ]
                    res.data.monthly_bill_record.forEach(d => {
                        this.billData.push(d);
                    })

                    this.paymentData = [
                        ['Date', 'Payment']
                    ]
                    res.data.monthly_payment_record.forEach(d => {
                        this.paymentData.push(d);
                    })

                    this.yearlyBillData = [
                        ['Month', 'Bill']
                    ]
                    res.data.yearly_bill_record.forEach(d => {
                        this.yearlyBillData.push(d);
                    })

                    this.yearlyPaymentData = [
                        ['Month', 'Payment']
                    ]
                    res.data.yearly_payment_record.forEach(d => {
                        this.yearlyPaymentData.push(d);
                    })

                    this.paymentText = res.data.payment_text.map(bill => {
                        return bill.payment_text;
                    }).join(' | ');

                    this.todaysBill         = res.data.todays_bill;
                    this.thisMonthBill      = res.data.this_month_Bill;
                    this.todaysCollection   = res.data.todays_collection;
                    this.cashBalance        = res.data.cash_balance;
                    this.renterDue        = res.data.renter_due;
                    this.storeDue        = res.data.store_due;
                    this.bankBalance        = res.data.bank_balance;
                    this.assetValue         = res.data.asset_value;
                    this.investBalance      = res.data.invest_balance;
                    this.loanBalance        = res.data.loan_balance;

                    this.topRenters       = res.data.top_renters;
                    this.topStores       = res.data.top_stores;

                    // this.topRenters = [
                    //     ['Renter', 'Amount']
                    // ]
                    // res.data.top_renters.forEach(p => {
                    //     this.topRenters.push([p.renter_name, parseFloat(p.amount)]);
                    // })
                    
                    this.topStores = [
                        ['Store', 'Amount']
                    ]
                    res.data.top_stores.forEach(p => {
                        this.topStores.push([p.store_name, parseFloat(p.amount)]);
                    })

                    this.showData = true;
                })
            }
        }
    })
</script>
