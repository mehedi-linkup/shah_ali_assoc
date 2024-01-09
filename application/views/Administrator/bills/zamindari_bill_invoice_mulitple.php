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

    @page {
        size: 21cm 29.7cm;
        margin: 2cm
    }

    .invoice {
        line-height: 120%;
        text-align: justify;
        background: transparent
    }
</style>

<div id="salesInvoiceReport" class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
        <div class="form-group" style="margin-top:10px;">
            <label class="col-sm-1 col-sm-offset-2 control-label no-padding-right"> Month </label>
            <div class="col-sm-2">
                <v-select v-bind:options="months" label="month_name" v-model="selectedMonth" placeholder="Select Month"></v-select>
            </div>

            <label class="col-sm-1 control-label no-padding-right"> Floor </label>
            <div class="col-sm-2">
                <v-select v-bind:options="floors" label="Floor_Name" v-model="selectedFloor" placeholder="Select floor"></v-select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <input type="button" class="btn btn-primary" value="Show Report" v-on:click="viewInvoice" style="margin:0px;width:150px;height:30px;padding:0">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2 invoice">
        <br>
        <bill-mutiple-invoice v-bind:floor_id="selectedFloor.Floor_SlNo" v-bind:month_id="selectedMonth.month_id" v-if="showInvoice"></bill-mutiple-invoice>

    </div>
</div>



<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/components/mutipleZamindaribillInvoice.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#salesInvoiceReport',
        data() {
            return {
                months: [],
                selectedMonth: null,
                floors: [],
                selectedFloor: null,
                showInvoice: false
            }
        },
        created() {
            this.getfloors();
            this.getBillSheet();
        },
        methods: {
            getfloors() {
                axios.get("/get_floors").then(res => {
                    this.floors = res.data;
                })
            },
            getBillSheet() {
                axios.get("/get_months").then(res => {
                    this.months = res.data;
                })
            },
            async viewInvoice() {
                if (this.selectedMonth == null) {
                    alert('Select Month');
                    return;
                }
                
                this.showInvoice = false;
                await new Promise(r => setTimeout(r, 500));
                this.showInvoice = true;
            }
        }
    })
</script>