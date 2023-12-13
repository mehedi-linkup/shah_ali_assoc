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

<div id="renterDue">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
			<!-- <div class="form-group">
				<label class="col-sm-1 control-label no-padding-right" for="searchType"> Search Type </label>
				<div class="col-sm-2">
					<select id="searchType" class="form-control" style="padding: 0px 3px" v-model="searchType" v-on:change="onChangeSearchType">
						<option value="all"> All </option>
						<option value="renter"> By Renter </option>
					</select>
				</div>
			</div> -->

			<!-- <div class="form-group" style="display:none" v-bind:style="{display: searchType == 'renter' ? '' : 'none'}">
				<label class="col-sm-1 control-label no-padding-right" for="searchType"> Renter </label>
				<div class="col-sm-2">
					<v-select v-bind:options="renters" v-model="selectedRenter" label="display_name"></v-select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2">
					<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getDues" style="margin-top:0px;border:0px;height:28px;">
				</div>
			</div> -->
		</div>
	</div>
	<div class="row" style="display:none;" v-bind:style="{display: dueList.length > 0 ? '' : 'none'}">
		<div class="col-md-12">
			<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
				<i class="fa fa-print"></i> Print
			</a>
			<div v-if="accountType == 'r'" class="table-responsive" id="reportTable">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Renter Code</th>
							<th>Renter Name</th>
							<th>Address</th>
							<th>Mobile</th>
							<th>Due</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="due in dueList">
							<td>{{ due.Renter_Code }}</td>
							<td>{{ due.Renter_Name }}</td>
							<td>{{ due.Renter_PreAddress }}</td>
							<td>{{ due.Renter_Mobile }}</td>
							<td>{{ due.due }}</td>
						</tr>
					</tbody>
					<tbody>
						<tr style="font-weight:bold">
							<td colspan="4" style="text-align:right">Total due</td>
							<td>{{ total.toFixed(2) }}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div v-if="accountType == 'o'" class="table-responsive" id="reportTable">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Owner Code</th>
							<th>Owner Name</th>
							<th>Address</th>
							<th>Mobile</th>
							<th>Due</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="due in dueList">
							<td>{{ due.Owner_Code }}</td>
							<td>{{ due.Owner_Name }}</td>
							<td>{{ due.Owner_PreAddress }}</td>
							<td>{{ due.Owner_Mobile }}</td>
							<td>{{ due.due }}</td>
						</tr>
					</tbody>
					<tbody>
						<tr style="font-weight:bold">
							<td colspan="4" style="text-align:right">Total due</td>
							<td>{{ total.toFixed(2) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#renterDue',
		data() {
			return {
				searchType: 'all',
				renters: [],
				selectedRenter: {
					Renter_SlNo: '',
                    Renter_Name: '',
                    Renter_Mobile: '',
                    Renter_PreAddress: '',
					display_name: 'Select Renter'
                },
				owners: [],
				selectedOwner: {
					Owner_SlNo: '',
                    Owner_Name: '',
                    Owner_Mobile: '',
                    Owner_PreAddress: '',
					display_name: 'Select Owner'
                },
				dueList: [],
				total: 0.00,
				accountType: '<?php echo $accountType; ?>',
				userId: '<?php echo $userId; ?>'
			}
		},
		created() {
			this.getDues();
		},
		methods: {
            getRenters(){
				axios.get('/get_renters').then(res => {
					this.renters = res.data;
				})
			},
			onChangeSearchType() {
				if (this.searchType == 'renter' && this.renters.length == 0) {
					this.getRenters();
				} else if (this.searchType == 'all') {
					this.selectedRenter.Renter_SlNo = null;
				}
			},
			getDues() {
				// if (this.searchType == 'renter' && this.selectedRenter.Renter_SlNo == null) {
				// 	alert('Select renter');
				// 	return;
				// }

				let data = {};
				let url = '';
				if(this.accountType == 'o') {
					data.ownerId = this.userId;
					url = '/get_owner_due';
				} else {
					data.ownerId = '';
				}

				if(this.accountType == 'r') {
					data.renterId = this.userId;
					url = '/get_renter_due';
				} else {
					data.renterId = '';
				}

				

				axios.post(url, data).then(res => {
					if(this.searchType == 'renter'){
						this.dueList = res.data;
					} else {
						this.dueList = res.data.filter(d => parseFloat(d.due) != 0);
					}
					this.total = this.dueList.reduce((prev, curr) => {return prev + parseFloat(curr.due)}, 0);
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Renter due report</h4 style="text-align:center">
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