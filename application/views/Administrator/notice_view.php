<div class="row">
	<div class="col-md-12 col-xs-12">
		<div id="printable_area">
			<u>নোটিশ</u>
			
			<h3><?php echo $notice->notice_title ?></h3>

			<a href="<?php echo base_url() . $notice->notice_file ?>" title="<?php echo $notice->notice_title ?>"> <i class="fa fa-file" style="color:#8f0000;"></i> <?php echo $notice->notice_title ?></a>
			<div>
				<div class="viewer" style="background-color: rgb(255, 255, 255); width: 720px;"><iframe width="720" height="600" src="<?php echo base_url() . $notice->notice_file ?>"></iframe></div>
			</div>
		</div>
	</div>
</div>