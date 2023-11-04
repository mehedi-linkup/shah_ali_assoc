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
	.form-group textarea {
		height: 40px;
	}
</style>
<div id="customers">
		<form @submit.prevent="saveRenter">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Renter Id:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_Code" required readonly>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Renter Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_Name" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Father Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_FName">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Present Address:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="renter.Renter_PreAddress"></textarea>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Permanent Address:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="renter.Renter_PerAddress"></textarea>
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
					<label class="control-label col-md-4">Birth Date:</label>
					<div class="col-md-7">
						<input type="date" class="form-control" v-model="renter.Renter_BirthDate" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Nid:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_NID" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Mobile:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_Mobile" required>
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Office Phone:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="renter.Renter_OfficePhone">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Email:</label>
					<div class="col-md-7">
						<input type="email" class="form-control" v-model="renter.Renter_Email">	
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Previous Due:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="renter.previous_due" required>
					</div>
				</div>

				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Credit Limit:</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="renter.Renter_Credit_Limit" required>
					</div>
				</div> -->

				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Renter Type:</label>
					<div class="col-md-7">
						<input type="radio" name="renterType" value="retail" v-model="renter.Renter_Type"> Retail
						<input type="radio" name="renterType" value="wholesale" v-model="renter.Renter_Type"> Wholesale
					</div>
				</div> -->
				
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
					<datatable :columns="columns" :data="renters" :filter-by="filter" style="margin-bottom: 5px;">
						<template scope="{ row }">
							<tr>
								<td>{{ row.AddTime | dateOnly('DD-MM-YYYY') }}</td>
								<td>{{ row.Renter_Code }}</td>
								<td>{{ row.Renter_Name }}</td>
								<td>{{ row.Renter_Mobile }}</td>
								<td>{{ row.Renter_OfficePhone }}</td>
								<td>{{ row.Owner_PreAddress }}</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editRenter(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteRenter(row.Renter_SlNo)">
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
				renter: {
					Renter_SlNo: 0,
					Renter_Code: '<?php echo $customerCode;?>',
					Renter_Name: '',
					Renter_FName: '',
					Renter_Type: 'retail',
					Renter_Phone: '',
					Renter_Mobile: '',
					Renter_Email: '',
					Renter_BirthDate: moment().format('YYYY-MM-DD'),
					Renter_OfficePhone: '',
					Renter_PreAddress: '',
					Renter_PerAddress: '',
					Renter_NID: '',
					area_ID: '',
					previous_due: 0
				},
				renters: [],
				districts: [],
				selectedDistrict: null,
				imageUrl: '',
				selectedFile: null,
				
				columns: [
                    { label: 'Added Date', field: 'AddTime', align: 'center', filterable: false },
                    { label: 'Renter Id', field: 'Renter_Code', align: 'center', filterable: false },
                    { label: 'Renter Name', field: 'Renter_Name', align: 'center' },
                    { label: 'Contact Number', field: 'Renter_Mobile', align: 'center' },
                    { label: 'Office Phone', field: 'Renter_OfficePhone', align: 'center' },
                    { label: 'Address', field: 'Owner_PreAddress', align: 'center' },
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
			this.getDistricts();
			this.getRenters();
		},
		methods: {
			getDistricts(){
				axios.get('/get_districts').then(res => {
					this.districts = res.data;
				})
			},
			getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
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
			saveRenter(){
				// if(this.selectedDistrict == null){
				// 	alert('Select area');
				// 	return;
				// }

				// this.renter.area_ID = this.selectedDistrict.District_SlNo;
				
				let url = '/add_renter';
				if(this.renter.Renter_SlNo != 0){
					url = '/update_renter';
				}

				let fd = new FormData();
				fd.append('image', this.selectedFile);
				fd.append('data', JSON.stringify(this.renter));

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
						this.renter.Renter_Code = r.renterCode;
						this.getRenters();
					}
				})
			},
			editRenter(renter){
				let keys = Object.keys(this.renter);
				keys.forEach(key => {
					this.renter[key] = renter[key];
				})

				this.selectedDistrict = {
					District_SlNo: renter.area_ID,
					District_Name: renter.District_Name
				}

				if(renter.image_name == null || renter.image_name == ''){
					this.imageUrl = null;
				} else {
					this.imageUrl = '/uploads/renters/'+renter.image_name;
				}
			},
			deleteRenter(renterId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_renter', {renterId: renterId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getRenters();
					}
				})
			},
			resetForm(){
				let keys = Object.keys(this.renter);
				keys = keys.filter(key => key != "Renter_Type");
				keys.forEach(key => {
					if(typeof(this.renter[key]) == 'string'){
						this.renter[key] = '';
					} else if(typeof(this.renter[key]) == 'number'){
						this.renter[key] = 0;
					}
				})
				this.imageUrl = '';
				this.selectedFile = null;
			}
		}
	})
</script>