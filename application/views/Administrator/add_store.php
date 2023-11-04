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
	#customers label{
		font-size:13px;
	}
	#customers select{
		border-radius: 3px;
	}
	#customers .add-button{
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display:block;
		text-align: center;
		color: white;
	}
	#customers .add-button:hover{
		background-color: #41add6;
		color: white;
	}
	#customers input[type="file"] {
		display: none;
	}
	#customers .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}
	#customers .custom-file-upload:hover{
		background-color: #41add6;
	}

	#customerImage{
		height: 100%;
	}
</style>
<div id="customers">
		<form @submit.prevent="saveStore">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Store ID:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_Code" required readonly>
					</div>
				</div>

                <div class="form-group clearfix">
					<label class="control-label col-md-4">Store No:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_No" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Store Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_Name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">TIN:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_TIN" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">No of Employee:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.employee_number" required>
					</div>
				</div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4">Store Type:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="types.length == 0"></select>
						<v-select v-bind:options="types" v-model="selectedType" label="ProductType_Name" v-if="types.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/type" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Level:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="floors.length == 0"></select>
						<v-select v-bind:options="floors" v-model="selectedFloor" label="Floor_Name" v-if="floors.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/floor" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Grade:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="grades.length == 0"></select>
						<v-select v-bind:options="grades" v-model="selectedGrade" label="Grade_Name" v-if="grades.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/grade" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Description:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="store.Store_Description"></textarea>
					</div>
				</div>
			</div>	

			<div class="col-md-5">

                <div class="form-group clearfix">
					<label class="control-label col-md-4">Shop Owner:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="owners.length == 0"></select>
						<v-select v-bind:options="owners" v-model="selectedOwner" label="Owner_Name" v-if="owners.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/owner" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>


				<div class="form-group clearfix">
					<label class="control-label col-md-4">Shop Renter:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="renters.length == 0"></select>
						<v-select v-bind:options="renters" v-model="selectedRenter" label="Renter_Name" v-if="renters.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/renter" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Start Date:</label>
					<div class="col-md-7">
						<input type="date" class="form-control" v-model="store.start_date" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Website:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_Web" placeholder="www.example.com" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Mobile:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_Mobile" placeholder="+880" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Office Phone:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="store.Store_OfficePhone" placeholder="+880">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Email:</label>
					<div class="col-md-7">
						<input type="email" class="form-control" v-model="store.Store_Email" placeholder="info@email.com">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Dimension:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="store.square_feet" placeholder="Square Feet" required>
					</div>
				</div>

           

                <div class="form-group clearfix">
					<label class="control-label col-md-4">Previous Due:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="store.previous_due" required>
					</div>
				</div>

                <div class="form-group clearfix">
					<label class="control-label col-md-4">Is member:</label>
					<div class="col-md-7">
						<input type="checkbox" class="form-control-inline" v-model="store.is_member">
					</div>
				</div>

				<div class="form-group clearfix">
					<div class="col-md-7 col-md-offset-4">
						<input type="submit" class="btn btn-success btn-sm" value="Save">
					</div>
				</div>
			</div>	
			<div class="col-md-2 text-center;">
				<div class="form-group clearfix">
					<div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
						<img id="customerImage" v-if="imageUrl == '' || imageUrl == null" src="/assets/no_image.gif">
                        <img id="customerImage" v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl">
					</div>
					<div style="text-align:center;">
						<label class="custom-file-upload">
							<input type="file" @change="previewImage"/>
							Select Logo
						</label>
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
					<datatable :columns="columns" :data="stores" :filter-by="filter" style="margin-bottom: 5px;">
						<template scope="{ row }">
							<tr>
								<td>{{ row.Store_Code }}</td>
								<td>{{ row.Store_Name }}</td>
								<td>{{ row.Store_Mobile }}</td>
								<td>{{ row.Store_OfficePhone }}</td>
								<td>{{ row.Store_TIN }}</td>
								<td>{{ row.ProductType_Name }}</td>
								<td>{{ row.Floor_Name }}</td>
								<td>{{ row.Grade_Name }}</td>
								<td>{{ row.Renter_Name }}</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editStore(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteStore(row.Store_SlNo)">
										<i class="fa fa-trash"></i>
									</button>
									<?php }?>
								</td>
							</tr>
						</template>
					</datatable>
					<datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
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
		el: '#customers',
		data(){
			return {
				store: {
					Store_SlNo: 0,
					Store_Code: '<?php echo $storeCode;?>',
                    Store_No: '',
					Store_Name: '',
                    Store_TIN: '',
					employee_number: '',
					Store_Description: '',
					Store_Mobile: '',
					Store_Email: '',
					start_date: moment().format('YYYY-MM-DD'),
					Store_OfficePhone: '',
					Store_Web: '',
					square_feet: '',
                    is_member: false,
					previous_due: 0
				},
				stores: [],
                types: [],
                selectedType: null,
				grades: [],
				selectedGrade: null,
				floors: [],
				selectedFloor: null,
				owners: [],
				selectedOwner: null,
				renters: [],
				selectedRenter: null,
				imageUrl: '',
				selectedFile: null,
				
				columns: [
                    { label: 'Store Id', field: 'Store_Code', align: 'center', filterable: false },
                    { label: 'Store Name', field: 'Store_Name', align: 'center' },
                    { label: 'Contact Number', field: 'Store_Mobile', align: 'center' },
                    { label: 'Office Phone', field: 'Store_OfficePhone', align: 'center' },
                    { label: 'TIN', field: 'Store_TIN', align: 'center' },
                    { label: 'Type', field: 'ProductType_Name', align: 'center' },
                    { label: 'Floor', field: 'Floor_Name', align: 'center' },
                    { label: 'Grade', field: 'Grade_Name', align: 'center' },
                    { label: 'Renter', field: 'Renter_Name', align: 'center' },
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: ''
			}
		},
		filters: {
			dateOnly(datetime, format){
				return moment(datetime).format(format);
			}
		},
		created(){
            this.getTypes();
			this.getGrades();
            this.getFloors();
            this.getOwners();
            this.getRenters();
			this.getStores();
		},
		methods: {
            getTypes() {
                axios.get('/get_types').then(res => {
					this.types = res.data;
				})
            },
			getGrades(){
				axios.get('/get_grades').then(res => {
					this.grades = res.data;
				})
			},
			getFloors(){
				axios.get('/get_floors').then(res => {
					this.floors = res.data;
				})
			},
            getOwners(){
				axios.get('/get_owners').then(res => {
					this.owners = res.data;
				})
			},
            getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
				})
			},
			getStores(){
				axios.get('/get_stores').then(res => {
					this.stores = res.data;
				})
			},
			previewImage(){
				if(event.target.files.length > 0){
					this.selectedFile = event.target.files[0];
					this.imageUrl = URL.createObjectURL(this.selectedFile);
				} else {
					this.selectedFile = null;
					this.imageUrl = null;
				}
			},
			saveStore(){
                if(this.selectedType == null){
					alert('Select type');
					return;
				}
                if(this.selectedGrade == null){
					alert('Select grade');
					return;
				}
                if(this.selectedFloor == null){
					alert('Select floor');
					return;
				}
                if(this.selectedOwner == null){
					alert('Select owner');
					return;
				}
                if(this.selectedRenter == null){
					alert('Select renter');
					return;
				}

                this.store.Store_Type = this.selectedType.ProductType_SlNo ;
                this.store.Store_Grade = this.selectedGrade.Grade_SlNo;
                this.store.Store_Floor = this.selectedFloor.Floor_SlNo;
                this.store.owner_id = this.selectedOwner.Owner_SlNo;
                this.store.renter_id = this.selectedRenter.Renter_SlNo;

                // if(this.selectedType != null && this.selectedType.ProductType_SlNo != null){
				// 	this.store.employeeId = this.selectedType.ProductType_SlNo;
				// } else {
				// 	this.store.ProductType_SlNo = null;
				// }

				// this.store.owner_id = this.selectedOwner.Owner_SlNo;
				// this.store.salesFrom = this.selectedBranch.brunch_id;

				let url = '/add_store';
				if(this.store.Store_SlNo != 0){
					url = '/update_store';
				}

				let fd = new FormData();
				fd.append('image', this.selectedFile);
				fd.append('data', JSON.stringify(this.store));

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
						console.log(progress);
					}
				}).then(res=>{
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
						this.store.Store_Code = r.storeCode;
						this.getStores();
					}
				})
			},
			editStore(store){
				let keys = Object.keys(this.store);
				keys.forEach(key => {
					this.store[key] = store[key];
				})
                this.store.is_member = store.is_member == '1' ? true : false; 
				this.selectedType = {
					ProductType_SlNo: store.Store_Type,
					ProductType_Name: store.ProductType_Name
				}
				this.selectedGrade = {
					Grade_SlNo: store.Store_Grade,
					Grade_Name: store.Grade_Name
				}
				this.selectedFloor = {
					Floor_SlNo: store.Store_Floor,
					Floor_Name: store.Floor_Name
				}
				this.selectedOwner = {
					Owner_SlNo: store.owner_id,
					Owner_Name: store.Owner_Name
				}
				this.selectedRenter = {
					Renter_SlNo: store.renter_id,
					Renter_Name: store.Renter_Name
				}
			
				if(store.image_name == null || store.image_name == ''){
					this.imageUrl = null;
				} else {
					this.imageUrl = '/uploads/stores/'+store.image_name;
				}
			},
			deleteStore(storeId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_store', {storeId: storeId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getStores();
					}
				})
			},
			resetForm(){
				let keys = Object.keys(this.store);
				keys = keys.filter(key => key != "Store_Type");
				keys.forEach(key => {
					if(typeof(this.store[key]) == 'string'){
						this.store[key] = '';
					} else if(typeof(this.store[key]) == 'number'){
						this.store[key] = 0;
					}
				})
                this.store.is_member = false;
				this.imageUrl = '';
				this.selectedFile = null;
                this.selectedType = null;
                this.selectedGrade = null;
                this.selectedFloor = null;
                this.selectedOwner = null;
                this.selectedRenter = null;

			}
		}
	})
</script>