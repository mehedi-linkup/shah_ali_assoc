<style>

	.form-group textarea {
		height: 40px;
	}
</style>
<div id="customers">
		<form @submit.prevent="saveGrade">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
			<div class="col-md-5 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-md-4">Grade:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="grade.Grade_Name" placeholder="Grade" required>
					</div>
				</div>
				<!-- <div class="form-group clearfix">
					<label class="control-label col-md-4">Total Store:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="floor.Floor_Store" placeholder="total store">
					</div>
				</div>-->

				<div class="form-group clearfix">
					<label class="control-label col-md-4">Description:</label>
					<div class="col-md-7">
						<textarea type="text" class="form-control" v-model="grade.Grade_Description" placeholder="description here..."></textarea>
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
					<datatable :columns="columns" :data="grades" :filter-by="filter" style="margin-bottom: 5px;">
						<template scope="{ row }">
							<tr>
								<td>{{ row.Grade_Name }}</td>
								<td>{{ row.Grade_Description }}</td>
								<td>
									<?php if($this->session->userdata('accountType') != 'u'){?>
									<button type="button" class="button edit" @click="editGrade(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteGrade(row.Grade_SlNo)">
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
				grade: {
					Grade_SlNo: 0,
					Grade_Name: '',
					Grade_Description: '',
				},
				grades: [],
				columns: [
                    { label: 'Grade Name', field: 'Grade_Name', align: 'center' },
                    { label: 'Grade Description', field: 'Grade_Description', align: 'center' },
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
			this.getGrades();
		},
		methods: {
			getGrades(){
				axios.get('/get_grades').then(res => {
					this.grades = res.data;
				})
			},
			saveGrade(){
				let url = '/add_grade';
				if(this.grade.Grade_SlNo != 0){
					url = '/update_grade';
				}

				let fd = new FormData();
				fd.append('data', JSON.stringify(this.grade));

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
					}
				}).then(res=>{
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
						this.getGrades();
					}
				})
			},
			editGrade(grade){
				let keys = Object.keys(this.grade);
				keys.forEach(key => {
					this.grade[key] = grade[key];
				})
			},
			deleteGrade(gradeId){
				let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_grade', {gradeId: gradeId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getGrades();
					}
				})
			},
			resetForm(){
				let keys = Object.keys(this.grade);
				keys = keys.filter(key => key != "Grade_Type");
				keys.forEach(key => {
					if(typeof(this.grade[key]) == 'string'){
						this.grade[key] = '';
					} else if(typeof(this.grade[key]) == 'number'){
						this.grade[key] = 0;
					}
				})
			}
		}
	})
</script>