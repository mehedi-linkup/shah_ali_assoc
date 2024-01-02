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
					<div id="news">
						<div class="row" style="margin-top: 15px;">
							<div class="col-md-9 col-md-offset-1">
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
												<a href="" title="Deactive News" v-if="news.status == 'a'" @click.prevent="changeNewsStatus(news.news_sl)"><i class="fa fa-close"></i></a>
												<a href="" title="Active News" v-else><i class="fa fa-check" @click.prevent="changeNewsStatus(news.news_sl)"></i></a>
												<a href="" title="Delete News"><i style="color:red" class="fa fa-trash" @click.prevent="deleteNews(news.news_sl)"></i></a>
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
				selectedNewsFile: '',
            }
        },
        created(){
            this.getNews();
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
            saveNews(){
                let url = "/add_news";
                if(this.news.newsId != 0){
                    url = "/update_news";
                }
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

			deleteNews(newsId){
				let deleteConf = confirm('Are you sure?');
				if(deleteConf == false){
					return;
				}
				axios.post('/delete_news', {newsId: newsId})
				.then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getNews();
					}
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
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
            }
        }
    })
</script>