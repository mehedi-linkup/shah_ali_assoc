<style>
	.inline-radio{
		display: inline;
	}

	#branch .Inactive{
        color: red;
    }
</style>
<div id="newsNotice">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-sm-12">
					<div id="notice">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-9 col-md-offset-1">
								<form class="form-horizontal" @submit.prevent="saveNotice">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> Notice Code </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="text" placeholder="Notice Code" class="form-control" v-model="notice.notice_code" readonly/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> Notice Title </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="text" placeholder="Notice title" class="form-control" v-model="notice.notice_title" required/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> Notice File </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="file" style="padding: 1px;" class="form-control" v-model="notice.notice_file" @change="processNoticeFile"/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> Description </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<textarea class="form-control" placeholder="Description" v-model="notice.notice_description" rows="8"></textarea>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-8 control-label no-padding-right"></label>
										<div class="col-sm-4" style="text-align:right">
											<button type="submit" class="btn btn-sm btn-success">
												Submit
												<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
		
						<div class="row" style="margin-top: 20px;display:none;" v-bind:style="{display: notices.length > 0 ? '' : 'none'}">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Sl</th>
												<th>Notice Code</th>
												<th>Notice Title</th>
												<th>Notice File</th>
												<th>Notice Description</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(notice, sl) in notices">
												<td>{{ sl + 1 }}</td>
												<td>{{ notice.notice_code }}</td>
												<td>{{ notice.notice_title }}</td>
												<td>{{ notice.notice_file }}</td>
												<td>{{ notice.notice_description }}</td>
												<td><span v-bind:class="notice.active_status">{{ notice.active_status }}</span></td>
												<td>
													<?php if($this->session->userdata('accountType') != 'u'){?>
													<a href="" title="Edit Notice" @click.prevent="editNotice(notice)"><i class="fa fa-pencil"></i></a>&nbsp;
													<a href="" title="Deactive Notice" v-if="notice.status == 'a'" @click.prevent="changeNoticeStatus(notice.notice_sl)"><i class="fa fa-close"></i></a>
													<a href="" title="Active Notice" v-else><i class="fa fa-check" @click.prevent="changeNoticeStatus(notice.notice_sl)"></i></a>
													<a href="" title="Delete Notice"><i style="color:red" class="fa fa-trash" @click.prevent="deleteNotice(notice.notice_sl)"></i></a>
													<?php }?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
<div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>

<script>
    new Vue({
        el: '#newsNotice',
        data(){
            return {
                notice: {
					noticeId: 0,
					notice_code: '<?php echo $noticeCode?>',
                    notice_title: '',
                    notice_file: '',
                    notice_description: ''
                },
                notices: [],
				selectedNoticeFile: ''
            }
        },
        created(){
            this.getNotice();
        },
        methods: {
			processNoticeFile() {
				if(event.target.files.length > 0){
					this.selectedNoticeFile = event.target.files[0];
				} else {
					this.selectedNoticeFile = null;
				}
			},

            getNotice(){
                axios.get('/get_notice').then(res => {
                    this.notices = res.data;
                })
            },

            saveNotice(){
                let url = "/add_notice";
                if(this.notice.noticeId != 0){
                    url = "/update_notice";
                }
				let fd = new FormData();
				fd.append('notice_file', this.selectedNoticeFile);
				fd.append('data', JSON.stringify(this.notice));


                axios.post(url, fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success){
                        this.clearNoticeForm();
						this.notice.notice_code = r.noticeCode;
						this.getNotice();
                    }
                })
            },

            editNotice(notice){
                this.notice.noticeId = notice.notice_sl;
                this.notice.notice_code = notice.notice_code;
                this.notice.notice_title = notice.notice_title;
                this.notice.notice_description = notice.notice_description;
            },

			changeNoticeStatus(noticeId){
                let changeConfirm = confirm('Are you sure?');
                if(changeConfirm == false){
                    return;
                }
                axios.post('/change_notice_status', {noticeId: noticeId}).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success){
                        this.getNotice();
                    }
                })
            },
			deleteNotice(noticeId){
				let deleteConf = confirm('Are you sure?');
				if(deleteConf == false){
					return;
				}
				axios.post('/delete_notices', {noticeId: noticeId})
				.then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getNotice();
					}
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},

            clearNoticeForm(){
                this.notice = {
					noticeId: 0,
					notice_code: 0,
                    notice_title: '',
                    notice_file: '',
                    notice_description: ''
                }
            }
        }
    })
</script>