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
				<div class="col-sm-6">
					<div id="news">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-12">
								<form class="form-horizontal" @submit.prevent="saveNews">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> News Code </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" placeholder="News Code" v-model="news.news_code" readonly/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> News Title </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="text" placeholder="New title" class="form-control" v-model="news.news_title" required/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> News File </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<input type="file" style="padding: 1px;" class="form-control" v-model="news.news_file" @change="processNewsFile"/>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> Description </label>
										<label class="col-sm-1 control-label no-padding-right">:</label>
										<div class="col-sm-8">
											<textarea class="form-control" placeholder="Description" v-model="news.news_description" rows="8"></textarea>
										</div>
									</div>
		
									<div class="form-group">
										<label class="col-sm-8 control-label no-padding-right"></label>
										<div class="col-sm-4">
											<button type="submit" class="btn btn-sm btn-success">
												Submit
												<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
		
						<div class="row" style="margin-top: 20px;display:none;" v-bind:style="{display: all_news.length > 0 ? '' : 'none'}">
							<div class="col-md-12">
								<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Sl</th>
											<th>News Code</th>
											<th>News Title</th>
											<th>News File</th>
											<th>Description</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(news, sl) in all_news">
											<td>{{ sl + 1 }}</td>
											<td>{{ news.news_code }}</td>
											<td>{{ news.news_title }}</td>
											<td>{{ news.news_file }}</td>
											<td>{{ news.news_description }}</td>
											<td><span v-bind:class="news.active_status">{{ news.active_status }}</span></td>
											<td>
												<?php if($this->session->userdata('accountType') != 'u'){?>
												<a href="" title="Edit News" @click.prevent="editNews(news)"><i class="fa fa-pencil"></i></a>&nbsp;
												<a href="" title="Deactive News" v-if="news.status == 'a'" @click.prevent="changeNewsStatus(news.news_sl)"><i class="fa fa-trash"></i></a>
												<a href="" title="Active News" v-else><i class="fa fa-check" @click.prevent="changeNewsStatus(news.news_sl)"></i></a>
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
				
				<div class="col-sm-6">
					<div id="notice">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-12">
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
										<div class="col-sm-4">
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
													<a href="" title="Deactive Notice" v-if="notice.status == 'a'" @click.prevent="changeNoticeStatus(notice.notice_sl)"><i class="fa fa-trash"></i></a>
													<a href="" title="Active Notice" v-else><i class="fa fa-check" @click.prevent="changeNoticeStatus(notice.notice_sl)"></i></a>
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
				news: {
					newsId: 0,
					news_code: '<?php echo $newsCode;?>',
                    news_title: '',
                    news_file: '',
                    news_description: ''
				},
				all_news: [],
                notice: {
					noticeId: 0,
					notice_code: '<?php echo $noticeCode?>',
                    notice_title: '',
                    notice_file: '',
                    notice_description: ''
                },
                notices: [],
				selectedNewsFile: '',
				selectedNoticeFile: ''
            }
        },
        created(){
            this.getNews();
            this.getNotice();
        },
        methods: {
            getNews(){
                axios.get('/get_news').then(res => {
                    this.all_news = res.data;
                })
            },
			processNewsFile(){
				if(event.target.files.length > 0){
					this.selectedNewsFile = event.target.files[0];
				} else {
					this.selectedNewsFile = null;
				}
			},
			processNoticeFile() {
				if(event.target.files.length > 0){
					this.selectedNoticeFile = event.target.files[0];
				} else {
					this.selectedNoticeFile = null;
				}
			},
            saveNews(){
                let url = "/add_news";
                if(this.news.newsId != 0){
                    url = "/update_news";
                }
				console.log(this.news)

				let fd = new FormData();
				fd.append('news_file', this.selectedNewsFile);
				fd.append('data', JSON.stringify(this.news));

                axios.post(url, fd).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success){
                        this.clearNewsForm();
						this.news.news_code = r.newsCode;
                        this.getNews();
                    }
                })
            },
            editNews(news){
                this.news.newsId = news.news_sl;
                this.news.news_code = news.news_code;
                this.news.news_title = news.news_title;
                this.news.news_description = news.news_description;
            },
            changeNewsStatus(newsId){
                let changeConfirm = confirm('Are you sure?');
                if(changeConfirm == false){
                    return;
                }
                axios.post('/change_news_status', {newsId: newsId}).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success){
                        this.getNews();
                    }
                })
            },
          
            clearNewsForm(){
                this.news = {
					newsId: 0,
					news_code: 0,
                    news_title: '',
                    news_file: '',
                    news_description: ''
                }
            },

            getNotice(){
                axios.get('/get_notice').then(res => {
                    this.notices = res.data;
					console.log(this.notices)
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