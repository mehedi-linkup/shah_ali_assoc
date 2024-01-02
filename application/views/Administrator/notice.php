<style>
	.news-panel {
		min-height: 400px; 
		overflow: hidden; 
		border: 1px solid #ddd; 
		position: relative;
	}
	#notice-board-ticker ul li {
		list-style: none;
		background: url("/assets/extra/img/bullet_tick.png") no-repeat center left;
		margin-bottom: 5px;
	}
	#notice-board-ticker ul a {
		margin-left: 20px;
		border-bottom: 1px dotted #666;
	}
</style>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default news-panel">
			<h2 style="padding:15px">নোটিশ বোর্ড</h2>
			<div id="notice-board-ticker">
				<ul>
					<?php  
						$startOfWeek = date('Y-m-d', strtotime('monday this week'));
						$currentDate = date('Y-m-d');
						$notices = $this->db->select('*')->get('tbl_notice')->result();

						foreach ($notices as $notice) {
							if ($notice->add_date >= $startOfWeek && $notice->add_date <= $currentDate) {
								$notice->newStatus = 'n';
							}
						}
					?>
					<?php foreach ($notices as $key=>$notice) { ?>
						<li>
							<a href="<?php echo base_url() . 'notice_view/' . $notice->notice_code ?>" title="<?php echo $notice->notice_title ?>"><?php echo $notice->notice_title ?></a> <strong  style="color:red"><?php echo @$notice->newStatus == 'n'? '(নতুন)' : ''?></strong>
						</li>
					<?php } ?>
				</ul>	
			</div>
		</div>
	</div>
</div>
