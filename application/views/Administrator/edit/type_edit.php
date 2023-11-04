<div class="row">
<div class="col-xs-12">
	<!-- PAGE CONTENT BEGINS -->
	<div class="form-horizontal">
		
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Type Name  </label>
			<label class="col-sm-1 control-label no-padding-right">:</label>
			<div class="col-sm-8">
				<input type="text" id="typename" name="typename" placeholder="Type Name"  value="<?php echo $selected->ProductType_Name; ?>" class="col-xs-10 col-sm-4" />
				<input name="id" id="id" type="hidden" value="<?php echo $selected->ProductType_SlNo; ?>"/>
				<span id="msg"></span>
				<span style="color:red;font-size:15px;">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="description">Description </label>
			<label class="col-sm-1 control-label no-padding-right">:</label>
			<div class="col-sm-8">
				<textarea class="col-xs-10 col-sm-4" name="typedescrip" id="typedescrip"><?php echo $selected->ProductType_Description; ?></textarea>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
			<label class="col-sm-1 control-label no-padding-right"></label>
			<div class="col-sm-8">
				    <button type="button" class="btn btn-sm btn-success" onclick="submit()" name="btnSubmit">
						Submit
						<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
			</div>
		</div>
		
</div>
</div>
</div>



<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			Type Information
		</div>

		<!-- div.table-responsive -->

		<!-- div.dataTables_borderWrap -->
		<div id="saveResult">
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center" style="display:none;">
							<label class="pos-rel">
								<input type="checkbox" class="ace" />
								<span class="lbl"></span>
							</label>
						</th>
						<th>SL No</th>
						<th>Type Name</th>
						<th class="hidden-480">Description</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$BRANCHid=$this->session->userdata('BRANCHid');
					$query = $this->db->query("SELECT * FROM tbl_producttype where status='a' AND type_branchid = '$BRANCHid' order by ProductType_Name asc");
					$row = $query->result();
					//while($row as $row){ ?>
					<?php $i=1; foreach($row as $row){ ?>
					<tr>
						<td class="center" style="display:none;">
							<label class="pos-rel">
								<input type="checkbox" class="ace" />
								<span class="lbl"></span>
							</label>
						</td>

						<td><?php echo $i++; ?></td>
						<td><a href="#"><?php echo $row->ProductType_Name; ?></a></td>
						<td class="hidden-480"><?php echo $row->ProductType_Description; ?></td>
						<td>
						<div class="hidden-sm hidden-xs action-buttons">
								<a class="blue" href="#">
									<i class="ace-icon fa fa-search-plus bigger-130"></i>
								</a>

								<a class="green" href="<?php echo base_url() ?>Administrator/page/typeedit/<?php echo $row->ProductType_SlNo; ?>" title="Eidt" onclick="return confirm('Are you sure you want to Edit this item?');">
									<i class="ace-icon fa fa-pencil bigger-130"></i>
								</a>

								<a class="red" href="#" onclick="deleted(<?php echo $row->ProductType_SlNo; ?>)">
									<i class="ace-icon fa fa-trash-o bigger-130"></i>
								</a>
							</div>
						</td>
					</tr>
					
					<?php } ?>
				</tbody>
			</table>
		</div>
<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
			
<script type="text/javascript">
    function submit(){
        var typename= $("#typename").val();
        var typedescrip= $("#typedescrip").val();
        var id= $("#id").val();
        if(typename==""){
            $("#msg").html("Required Filed").css("color","red");
            return false;
        }
        var typename=encodeURIComponent(typename);
        var inputdata = 'typename='+typename+'&typedescrip='+typedescrip+'&id='+id;
        var urldata = "<?php echo base_url();?>Administrator/page/typeupdate";
        $.ajax({
            type: "POST",
            url: urldata,
            data: inputdata,
            success:function(data){
				alert("Update Success");
				window.location = '/type';
            }
        });
    }
</script>
<script type="text/javascript">
    function deleted(id){
        var deletedd= id;
        var inputdata = 'deleted='+deletedd;
		if(confirm("Are You Sure Want to delete This?")) {
			var urldata = "<?php echo base_url();?>Administrator/page/typedelete";
			$.ajax({
				type: "POST",
				url: urldata,
				data: inputdata,
				success:function(data){
					alert(data);
					window.location.href='<?php echo base_url(); ?>type';
				}
			});
		};
    }
</script>
