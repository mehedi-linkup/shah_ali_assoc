<div id="storeListReport">
    <div style="display:none;" v-bind:style="{display: stores.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-12">
                <a href="" @click.prevent="printStoreList"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>

        <div class="row" style="margin-top:15px;">
            <div class="col-md-12">
                <div class="table-responsive" id="printContent">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Sl</th>
                            <th>Store Id</th>
                            <th>Store No</th>
                            <th>Store Name</th>
                            <th>TIN</th>
                            <th>Type</th>
                            <th>Floor</th>
                            <th>Grade</th>
                            <th>Owner</th>
                            <th>Renter</th>
                            <th>Phone</th>
                            <th>Total Employee</th>
                            <th>Dimension</th>
                        </thead>
                        <tbody>
                            <tr v-for="(store, sl) in stores">
                                <td>{{ sl + 1 }}</td>
                                <td>{{ store.Store_Code }}</td>
                                <td>{{ store.Store_No }}</td>
                                <td>{{ store.Store_Name }}</td>
                                <td>{{ store.Store_TIN }}</td>
                                <td>{{ store.ProductType_Name }}</td>
                                <td>{{ store.Floor_Name }}</td>
                                <td>{{ store.Grade_Name }}</td>
                                <td>{{ store.Owner_Name }}</td>
                                <td>{{ store.Renter_Name }}</td>
                                <td>{{ store.Store_Mobile }}</td>
                                <td>{{ store.employee_number }}</td>
                                <td>{{ store.square_feet }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;text-align:center;" v-bind:style="{display: stores.length > 0 ? 'none' : ''}">
        No records found
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>

<script>
    new Vue({
        el: '#storeListReport',
        data() {
            return {
                stores: []
            }
        },
        created() {
            this.getStores();
        },
        methods: {
            getStores() {
                axios.get('/get_stores').then(res => {
                    this.stores = res.data;
                })
            },

            async printStoreList() {
                let printContent = `
                    <div class="container">
                        <h4 style="text-align:center">Store List</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#printContent').innerHTML}
							</div>
						</div>
                    </div>
                `;

                let printWindow = window.open('', '', `width=${screen.width}, height=${screen.height}`);
                printWindow.document.write(`
                    <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
                `);

                printWindow.document.body.innerHTML += printContent;
                printWindow.focus();
                await new Promise(r => setTimeout(r, 1000));
                printWindow.print();
                printWindow.close();
            }
        }
    })
</script>