<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select.open .dropdown-toggle{
		border-bottom: 1px solid #ccc;
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
	#services label{
		font-size:13px;
	}
	#services select{
		border-radius: 3px;
	}
	#services .add-button{
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display:block;
		text-align: center;
		color: white;
	}
	#services .add-button:hover{
		background-color: #41add6;
		color: white;
	}
</style>
<div id="services">
		<form @submit.prevent="saveService">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
			<div class="col-md-6 col-sm-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Service Id:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="service.Service_Code">
					</div>
				</div>

				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Category:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="categories.length == 0"></select>
						<v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name" v-if="categories.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/category" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div> -->

				<div class="form-group clearfix" style="display:none;">
					<label class="control-label col-md-4">Brand:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="brands.length == 0"></select>
						<v-select v-bind:options="brands" v-model="selectedBrand" label="brand_name" v-if="brands.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Service Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="service.Service_Name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Unit:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="units.length == 0"></select>
						<v-select v-bind:options="units" v-model="selectedUnit" label="Unit_Name" v-if="units.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/unit" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">VAT:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="service.vat">
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Rate:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="service.Service_SellingPrice" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<div class="col-md-7 col-md-offset-4">
						<input type="submit" class="btn btn-success btn-sm" value="Save">
					</div>
				</div>
			</div>
		</div>
		</form>

		<div class="row">
			<div class="col-sm-12 form-inline">
				<div class="form-group">
					<label for="filter" class="sr-only">Filter</label>
					<input type="text" class="form-control" v-model="filter" placeholder="Filter">
				</div>
			</div>
			<div class="col-md-12">
				<div class="table-responsive">
					<datatable :columns="columns" :data="services" :filter-by="filter">
						<template scope="{ row }">
							<tr>
								<td>{{ row.Service_Code }}</td>
								<td>{{ row.Service_Name }}</td>
								<td>{{ row.Service_SellingPrice }}</td>
								<td>{{ row.vat }}</td>
								<td>{{ row.Unit_Name }}</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editService(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteService(row.Service_SlNo)">
										<i class="fa fa-trash"></i>
									</button>
									<?php }?>
									<!-- <button type="button" class="button" @click="window.location = `/Administrator/services/barcodeGenerate/${row.Service_SlNo}`">
										<i class="fa fa-barcode"></i>
									</button> -->
								</td>
							</tr>
						</template>
					</datatable>
					<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
				</div>
			</div>
		</div>


</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#services',
		data(){
			return {
				service: {
					Service_SlNo: '',
					Service_Code: "<?php echo $serviceCode;?>",
					Service_Name: '',
					ServiceCategory_ID: '',
					Service_SellingPrice: '',
					Unit_ID: '',
					vat: 0,
					is_service: true
				},
				services: [],
				categories: [],
				selectedCategory: null,
				brands: [],
				selectedBrand: null,
				units: [],
				selectedUnit: null,

				columns: [
                    { label: 'Service Id', field: 'Service_Code', align: 'center', filterable: false },
                    { label: 'Service Name', field: 'Service_Name', align: 'center' },
                    { label: 'Price', field: 'Service_SellingPrice', align: 'center' },
                    { label: 'VAT', field: 'vat', align: 'center' },
                    { label: 'Unit', field: 'Unit_Name', align: 'center' },
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: ''
			}
		},
		created(){
			this.getCategories();
			this.getBrands();
			this.getUnits();
			this.getServices();
		},
		methods:{
			getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getBrands(){
				axios.get('/get_brands').then(res => {
					this.brands = res.data;
				})
			},
			getUnits(){
				axios.get('/get_units').then(res => {
					this.units = res.data;
				})
			},
			getServices(){
				axios.get('/get_services').then(res => {
					this.services = res.data;
				})
			},
			saveService(){
				if(this.selectedUnit == null){
					alert('Select unit');
					return;
				}
				

				// this.service.ServiceCategory_ID = this.selectedCategory.ServiceCategory_SlNo;
				this.service.Unit_ID = this.selectedUnit.Unit_SlNo;

				let url = '/add_service';
				if(this.service.Service_SlNo != 0){
					url = '/update_service';
				}
				axios.post(url, this.service)
				.then(res=>{
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.clearForm();
						this.service.Service_Code = r.serviceId;
						this.getServices();
					}
				})
				
			},
			editService(service){
				let keys = Object.keys(this.service);
				keys.forEach(key => {
					this.service[key] = service[key];
				})

				this.service.is_service = service.is_service == 'true' ? true : false;

				this.selectedCategory = {
					ServiceCategory_SlNo: service.ServiceCategory_ID,
					ServiceCategory_Name: service.ServiceCategory_Name
				}

				this.selectedUnit = {
					Unit_SlNo: service.Unit_ID,
					Unit_Name: service.Unit_Name
				}
			},
			deleteService(serviceId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_service', {serviceId: serviceId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getServices();
					}
				})
			},
			clearForm(){
				let keys = Object.keys(this.service);
				keys.forEach(key => {
					if(typeof(this.service[key]) == "string"){
						this.service[key] = '';
					} else if(typeof(this.service[key]) == "number"){
						this.service[key] = 0;
					}
				})
			}
		}
	})
</script>