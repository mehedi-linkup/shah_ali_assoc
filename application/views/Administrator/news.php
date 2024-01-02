<style>
	.news-panel {
		min-height: 400px; 
		overflow: hidden; 
		border: 1px solid #ddd; 
		position: relative;
	}
	#news-board-ticker ul li {
		list-style: none;
		background: url("/assets/extra/img/bullet_tick.png") no-repeat center left;
	}
	#news-board-ticker ul a {
		margin-left: 20px;
		border-bottom: 1px dotted #666;
	}
</style>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default news-panel">
			<?php  
				$startOfWeek = date('Y-m-d', strtotime('monday this week'));
				$currentDate = date('Y-m-d');
				$news = $this->db->select('*')->get('tbl_news')->result();

				foreach ($news as $item) {
					if ($item->add_date >= $startOfWeek && $item->add_date <= $currentDate) {
						$item->newStatus = 'n';
					}
				}
			?>
			<h2 style="padding:15px">নিউজ বোর্ড</h2>
			<div class="panel-body news-content">
				<?php foreach ($news as $key=>$item) { ?>
				<p style="margin-bottom: 15px;"><span style="color:green;font-style:italic;">Date: <?php echo $item->add_date ?></span><br><a href="<?php echo base_url() . 'news_view/' . $item->news_code ?>" title="<?php echo $item->news_title ?>"><?php echo $item->news_title ?>"</a> <strong  style="color:red"><?php echo @$item->newStatus == 'n'? '(নতুন)' : ''?></strong>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
