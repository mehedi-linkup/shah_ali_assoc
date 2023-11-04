<form class="form-horizontal" action="<?php echo base_url();?>updateMonth" method="post">
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Month Name  </label>
		<label class="col-sm-1 control-label no-padding-right">:</label>
		<div class="col-sm-8">
			<?php 
				$date = DateTime::createFromFormat('F Y', $row->month_name);
				$outputDate = $date->format('Y-m');
			?>
			<input type="month" id="month" name="month" placeholder="Month Name" value="<?php echo $outputDate; ?>" class="col-xs-10 col-sm-4" />
			<input type="hidden" id="month_id" name="month_id" value="<?php echo $row->month_id; ?>" class="col-xs-10 col-sm-4" />
			<span id="msc"></span>
		</div>
	</div>
	 
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
		<label class="col-sm-1 control-label no-padding-right"></label>
		<div class="col-sm-8">
				<button type="submit" class="btn btn-sm btn-info" name="btnSubmit" onclick="updatedata(event)">
					Update
					<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
				</button>
		</div>
	</div>
</form>
