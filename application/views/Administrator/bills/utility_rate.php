<style>
	.inline-radio {
		display: inline;
	}

	#branch .Inactive{
        color: red;
    }
</style>
<div class="row" style="margin-top: 20px;">
	<div class="col-sm-6 col-sm-offset-3">
		<?php if($selected){ ?>
		<form class="form-vertical" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>utility_rate_update">
		
			<div class="form-group">
				<label class="col-sm-5 control-label" for="Electricity_Rate"> Electricity Rate (Per unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Electricity_Rate" type="number" id="Electricity_Rate" value="<?php echo $selected->Electricity_Rate ?>" class="form-control" />
					<input name="iidd" type="hidden" id="iidd" value="" class="txt" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Ac_Rate"> Ac Rate (Per unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Ac_Rate" type="number" id="Ac_Rate" value="<?php echo $selected->Ac_Rate ?>" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="Electricity_Rate"> Generator Rate (Per unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Generator_Rate" type="number" id="Electricity_Rate" value="<?php echo $selected->Electricity_Rate ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Service_Rate"> Service Rate (unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Service_Rate" type="number" id="Service_Rate" value="<?php echo $selected->Electricity_Rate ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Wasa_Rate"> Wasa Rate (unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Wasa_Rate" type="number" id="Wasa_Rate" value="<?php echo $selected->Electricity_Rate ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Mosque_Rate"> Mosque charge (unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Mosque_Rate" type="number" id="Mosque_Rate" value="<?php echo $selected->Electricity_Rate ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group" style="margin-top:15px;">
				<label class="col-sm-4 control-label" for=""> </label>
				<label class="col-sm-1 control-label"></label>
				<div class="col-sm-6">
					<button type="submit" name="btnSubmit" title="Update" class="btn btn-sm btn-info pull-left">
							Update
							<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
					
				</div>
			</div>
		</form>
		<?php 
		}else{
		?>
		
		<form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>utility_rate_insert">
			<div class="form-group">
				<label class="col-sm-5 control-label" for="Electricity_Rate"> Electricity Rate (Per unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Electricity_Rate" type="number" id="Electricity_Rate" value="" class="form-control" />
					<input name="iidd" type="hidden" id="iidd" value="" class="txt" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Ac_Rate"> Ac Rate (Per unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Ac_Rate" type="number" id="Ac_Rate" value="" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="Electricity_Rate"> Generator Rate (Per unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Generator_Rate" type="number" id="Electricity_Rate" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Service_Rate"> Service Rate (unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Service_Rate" type="number" id="Service_Rate" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Wasa_Rate"> Wasa Rate (unit) </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Wasa_Rate" type="number" id="Wasa_Rate" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="Mosque_Rate"> Mosque charge (unit)  </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="Mosque_Rate" type="number" id="Mosque_Rate" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group" style="margin-top:15px;">
				<label class="col-sm-4 control-label" for=""> </label>
				<label class="col-sm-1 control-label"></label>
				<div class="col-sm-6">
					<button type="submit" name="btnSubmit" title="Save" class="btn btn-sm btn-success pull-right">
							Save
							<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
					
				</div>
			</div>
		</form>
		<?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
    function Employee_submit(){
    	var logo = $('#companyLogo').val();
    	alert(logo);
    	if(logo == ""){
           alert('Please Select a logo')
            return false;
        }
        var Company_name = $("#Company_name").val();
		var inpt=$('input[name=inpt]:checked').val();
		//alert(inpt);
        if(Company_name == ""){
            $("#Company_name").css('border-color','red');
            return false;
        }
        var fd = new FormData();
        var Description = CKEDITOR.instances['Description'].getData();
        var Description=encodeURIComponent(Description);
          fd.append('companyLogo', $('#companyLogo')[0].files[0]);
          fd.append('Company_name', $('#Company_name').val());
          fd.append('Description',Description );
          fd.append('inpt',inpt );
          fd.append('iidd', $('#iidd').val());
         

          var x = $.ajax({
            url: "<?php echo base_url();?>Administrator/page/company_profile_Update/",
            type: "POST",
            data: fd,
            enctype: 'multipart/form-data',
            processData: false, 
            contentType: false,
            success:function(data){         
            //$("#Company").html(data);
            alert("Update Success");
            //setTimeout( function() {$.fancybox.close(); },1200);
            location.reload();
            } 
          });
    }



    $(function() {
      $('.froala-editor').froalaEditor()
    });
</script>
   <!-- TextArea editor -->
<script type='text/javascript' src='<?php echo base_url(); ?>ckeditor/ckeditor.js'></script>

<script type="text/javascript">
    CKEDITOR.replace('Description');
    
</script>        
<!-- end TextArea editor -->