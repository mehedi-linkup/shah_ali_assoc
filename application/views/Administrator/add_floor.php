<style>

	.form-group textarea {
		height: 40px;
	}
</style>
<div id="customers">
		<form @submit.prevent="saveFloor">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Floor Name:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="floor.Floor_Name" placeholder="level name" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Ranking :</label>
					<div class="col-md-7">
						<input type="number" class="form-control" v-model="floor.Floor_Ranking" placeholder="" required>
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Total Store:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="floor.Floor_Store" placeholder="total store">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Ac Rate%:</label>
					<div class="col-md-7">
						<input type="number" step="0.000001" class="form-control" v-model="floor.ac_rate" placeholder="">
					</div>
				</div>

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Description Address:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="floor.Floor_Description" placeholder="description here..."></textarea>
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
					<datatable :columns="columns" :data="floors" :filter-by="filter" style="margin-bottom: 5px;">
						<template scope="{ row }">
							<tr>
								<td>{{ row.Floor_Name }}</td>
								<td>{{ row.Floor_Ranking }}</td>
								<td>{{ row.Floor_Store }}</td>
								<td>{{ row.ac_rate }}</td>
								<td>{{ row.Floor_Description }}</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editFloor(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteFloor(row.Floor_SlNo)">
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
				floor: {
					Floor_SlNo: 0,
					Floor_Name: '',
					Floor_Ranking: '',
					ac_rate: '',
					Floor_Store: '',
					Floor_Description: ''
				},
				floors: [],
				columns: [
                    { label: 'Floor Name', field: 'Floor_Name', align: 'center' },
                    { label: 'Ranking', field: 'Floor_Ranking', align: 'center' },
                    { label: 'Total Store', field: 'Floor_Store', align: 'center' },
                    { label: 'AC Rate', field: 'ac_rate', align: 'center' },
                    { label: 'Description', field: 'Floor_Description', align: 'center' },
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
			this.getFloors();
		},
		methods: {
			getFloors(){
				axios.get('/get_floors').then(res => {
					this.floors = res.data;
				})
			},
			saveFloor(){
				if(this.floor.ac_rate == 0 || this.floor.ac_rate == '') {
					alert("Floor rate empty");
					return;
				}
				
				
				let url = '/add_floor';
				if(this.floor.Floor_SlNo != 0){
					url = '/update_floor';
				}

				let fd = new FormData();
				fd.append('data', JSON.stringify(this.floor));

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
					}
				}).then(res=>{
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
						this.getFloors();
					}
				})
			},
			editFloor(floor){
				let keys = Object.keys(this.floor);
				keys.forEach(key => {
					this.floor[key] = floor[key];
				})
			},
			deleteFloor(floorId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_floor', {floorId: floorId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getFloors();
					}
				})
			},
			resetForm(){
				let keys = Object.keys(this.floor);
				keys = keys.filter(key => key != "Floor_Type");
				keys.forEach(key => {
					if(typeof(this.floor[key]) == 'string'){
						this.floor[key] = '';
					} else if(typeof(this.floor[key]) == 'number'){
						this.floor[key] = 0;
					}
				})
			}
		}
	})
</script>