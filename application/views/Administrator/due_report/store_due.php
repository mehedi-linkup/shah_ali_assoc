<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
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
</style>

<div class="row" id="storeDueList">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right">Search Type</label>
			<div class="col-sm-2">
				<select class="form-control" v-model="searchType" v-on:change="onChangeSearchType" style="padding:0px;">
					<option value="all">All</option>
					<option value="store">By Store</option>
				</select>
			</div>
		</div>
		<div class="form-group" style="display: none" v-bind:style="{display: searchType == 'store' ? '' : 'none'}">
			<label class="col-sm-2 control-label no-padding-right">Select Store</label>
			<div class="col-sm-2">
				<v-select v-bind:options="stores" v-model="selectedStore" label="display_name" placeholder="Select store"></v-select>
			</div>
		</div>
		<div class="form-group" style="display: none" v-bind:style="{display: searchType == 'area' ? '' : 'none'}">
			<label class="col-sm-1 control-label no-padding-right">Select Area</label>
			<div class="col-sm-2">
				<v-select v-bind:options="areas" v-model="selectedArea" label="District_Name" placeholder="Select area"></v-select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2">
				<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getDues" style="margin-top:0px;border:0px;height:28px;">
			</div>
		</div>
	</div>

	<div class="col-md-12" style="display: none" v-bind:style="{display: dues.length > 0 ? '' : 'none'}">
		<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
			<i class="fa fa-print"></i> Print
		</a>
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Store Id</th>
						<th>Store Name</th>
						<th>Meter No.</th>
						<th>Store Mobile</th>
						<th>Due Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="data in dues">
						<td>{{ data.Store_Code }}</td>
						<td>{{ data.Store_Name }}</td>
						<td>{{ data.meter_no }}</td>
						<td>{{ data.Store_Mobile }}</td>
						<td style="text-align:right">{{ parseFloat(data.due).toFixed(2) }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr style="font-weight:bold;">
						<td colspan="4" style="text-align:right">Total Due</td>
						<td style="text-align:right">{{ parseFloat(totalDue).toFixed(2) }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#storeDueList',
		data(){
			return {
				searchType: 'all',
				stores: [],
				selectedStore: null,
				areas: [],
				selectedArea: null,
				dues: [],
				totalDue: 0.00
			}
		},
		created(){

		},
		methods:{
			onChangeSearchType(){
				if(this.searchType == 'store' && this.stores.length == 0){
					this.getStores();
				} else if(this.searchType == 'area' && this.areas.length == 0) {
					this.getAreas();
				}
				if(this.searchType == 'all'){
					this.selectedStore = null;
					this.selectedArea = null;
				}
			},
			getStores(){
				axios.get('/get_stores').then(res => {
					this.stores = res.data;
				})
			},
			getAreas() {
				axios.get('/get_districts').then(res => {
					this.areas = res.data;
				})
			},
			getDues(){
				if(this.searchType == 'store' && this.selectedStore == null){
					alert('Select store');
					console.log(this.selectedStore);
					return;
				}

				let storeId = this.selectedStore == null ? null : this.selectedStore.Store_SlNo;
				axios.post('/get_store_due', { storeId: storeId }).then(res => {
					if(this.searchType == 'store'){
						this.dues = res.data;
					} else {
						this.dues = res.data.filter(d => parseFloat(d.due) != 0);
					}
					this.totalDue = this.dues.reduce((prev, cur) => { return prev + parseFloat(cur.due) }, 0);
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Store due report</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

				var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				mywindow.document.body.innerHTML += reportContent;

				mywindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				mywindow.print();
				mywindow.close();
			}
		}
	})
</script>