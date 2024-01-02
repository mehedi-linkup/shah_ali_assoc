<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css">
<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select.open .dropdown-toggle {
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
	.form-group textarea {
		height: 40px;
	}
</style>

<div id="customers">
		<form @submit.prevent="saveOwner">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Owner Id:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_Code" required readonly>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Owner Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_Name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Father Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_FName">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Present Address:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="owner.Owner_PreAddress"></textarea>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Permanent Address:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="owner.Owner_PerAddress"></textarea>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">User Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_UserName">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Birth Date:</label>
					<div class="col-md-7">
						<input type="date" class="form-control" v-model="owner.Owner_BirthDate" required>
					</div>
				</div>

				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Area:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="districts.length == 0"></select>
						<v-select v-bind:options="districts" v-model="selectedDistrict" label="District_Name" v-if="districts.length > 0"></v-select>
					</div>
					<div class="col-md-1" style="padding:0;margin-left: -15px;"><a href="/area" target="_blank" class="add-button"><i class="fa fa-plus"></i></a></div>
				</div> -->
			</div>	

			<div class="col-md-5">

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Nid:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_NID" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Mobile:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_Mobile" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Office Phone:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Owner_OfficePhone">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Email:</label>
					<div class="col-md-7">
						<input type="email" class="form-control" v-model="owner.Owner_Email">	
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Nominee's Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Nominee_Name">	
					</div>
				</div>
				
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Nominee's NID:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="owner.Nominee_NID">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Previous Due:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="owner.previous_due" required>
					</div>
				</div>

				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Membership Shop:</label>
					<div class="col-md-7">
						<select class="form-control" v-if="stores.length == 0"></select>
						<v-select v-bind:options="stores" v-model="selectedStore" label="display_name" v-if="stores.length > 0"></v-select>
					</div>
				</div> -->

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Is Member:</label>
					<div class="col-md-3">
						<input type="checkbox" v-model="owner.is_member">
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
							Select Image
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
					<datatable :columns="columns" :data="owners" :filter-by="filter" style="margin-bottom: 5px;">
						<template scope="{ row }">
							<tr>
								<td>{{ row.AddTime | dateOnly('DD-MM-YYYY') }}</td>
								<td>{{ row.Owner_Code }}</td>
								<td>{{ row.Owner_UserName }}</td>
								<td>{{ row.Owner_Name }}</td>
								<td>{{ row.Owner_Mobile }}</td>
								<td>{{ row.Owner_OfficePhone }}</td>
								<td>{{ row.Owner_PreAddress }}</td>
								<td>{{ row.Nominee_Name }}</td>
								<td>
									<label for="store_id">Choose a store:</label>
									<select name="store_id" v-model="row.store_id" id="store_id" @change="onChangeOwner(row)">
										<option v-for="store in stores" :value="store.Store_SlNo" :selected="store.Store_SlNo == row.store_id">{{ store.Store_Name }}</option>
									</select>
								</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editOwner(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteOwner(row.Owner_SlNo)">
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
<script src="https://unpkg.com/vue-multiselect@2.1.6"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	Vue.component('multiselect', window.VueMultiselect.default); 

	new Vue({
		el: '#customers',
		data(){
			return {
				owner: {
					Owner_SlNo: 0,
					Owner_Code: '<?php echo $customerCode;?>',
					Owner_Name: '',
					Owner_FName: '',
					Owner_Type: 'retail',
					Owner_Phone: '',
					Owner_Mobile: '',
					Owner_Email: '',
					Owner_BirthDate: moment().format('YYYY-MM-DD'),
					Owner_OfficePhone: '',
					Owner_PreAddress: '',
					Owner_PerAddress: '',
					Owner_UserName: '',
					Owner_NID: '',
					Nominee_Name: '',
					Nominee_NID: '',
					area_ID: '',
					is_member: false,
					previous_due: 0
				},
				owners: [],
				districts: [],
				selectedDistrict: null,
				stores: [],
				imageUrl: '',
				selectedFile: null,
				
				columns: [
                    { label: 'Added Date', field: 'AddTime', align: 'center', filterable: false },
                    { label: 'Owner Id', field: 'Owner_Code', align: 'center', filterable: false },
                    { label: 'Owner Username', field: 'Owner_UserName', align: 'center', filterable: false },
                    { label: 'Owner Name', field: 'Owner_Name', align: 'center' },
                    { label: 'Contact Number', field: 'Owner_Mobile', align: 'center' },
                    { label: 'Office Phone', field: 'Owner_OfficePhone', align: 'center' },
                    { label: 'Address', field: 'Owner_PreAddress', align: 'center' },
                    { label: 'Nominee Name', field: 'Nominee_Name', align: 'center' },
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: '',
				searchKeyword: '',
			}
		},
		filters: {
			dateOnly(datetime, format){
				return moment(datetime).format(format);
			}
		},
		created(){
			this.getDistricts();
			this.getOwners();
			this.getStores();
		},
		computed: {
			filteredOptions() {
				return this.options.filter(option =>
					option.name.toLowerCase().includes(this.searchKeyword.toLowerCase())
				);
			},
		},
		methods: {
			customLabel(store) {
				return store.display_name;
			},
			getStores() {
				axios.get('/get_stores').then(res => {
					this.stores = res.data;
					this.stores.unshift({
						Store_SlNo: "",
						Store_Name: "Select Store"
					})
				})
			},
			getDistricts(){
				axios.get('/get_districts').then(res => {
					this.districts = res.data;
				})
			},
			getOwners(){
				axios.get('/get_owners').then(res => {
					this.owners = res.data;
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
			async onChangeOwner(row) {
				console.log(row)
				let filter = {
					owner_id: row.Owner_SlNo,
					store_id: row.store_id
				}
				let fd = new FormData();
				fd.append('data', JSON.stringify(filter));

				await axios.post('/update_owner_storeid', fd).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success) {
						this.getOwners();
					}
				})

			},
			// generateUserName() {
			// 	const initials = this.getInitials(this.owner.Owner_Name);
			// 	const uniqueIdentifier = initials != '' ? Math.floor(Math.random() * 1000) : ''; //
			// 	this.owner.Owner_UserName = `${initials}${uniqueIdentifier}`;
			// },
			getInitials(name) {
				const words = name.split(' ');
				const initials = words.map(word => word.charAt(0).toLowerCase()).join('');
				return initials;
			},
			saveOwner(){

				let url = '/add_owner';
				if(this.owner.Owner_SlNo != 0){
					url = '/update_owner';
				}

				let fd = new FormData();
				fd.append('image', this.selectedFile);
				fd.append('data', JSON.stringify(this.owner));

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
						this.owner.Owner_Code = r.ownerCode;
						this.getOwners();
					}
				})
			},
			editOwner(owner){
				let keys = Object.keys(this.owner);
				keys.forEach(key => {
					this.owner[key] = owner[key];
				})

				this.owner.is_member = owner.is_member == 'true' ? true : false;

				this.selectedDistrict = {
					District_SlNo: owner.area_ID,
					District_Name: owner.District_Name
				}

				if(owner.image_name == null || owner.image_name == ''){
					this.imageUrl = null;
				} else {
					this.imageUrl = '/uploads/owners/'+owner.image_name;
				}
			},
			deleteOwner(ownerId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_owner', {ownerId: ownerId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getOwners();
					}
				})
			},
			resetForm(){
				let keys = Object.keys(this.owner);
				keys = keys.filter(key => key != "Owner_Type");
				keys.forEach(key => {
					if(typeof(this.owner[key]) == 'string'){
						this.owner[key] = '';
					} else if(typeof(this.owner[key]) == 'number'){
						this.owner[key] = 0;
					}
				})
				this.owner.is_member = false;
				this.imageUrl = '';
				this.selectedFile = null;
			}
		}
	})
</script>