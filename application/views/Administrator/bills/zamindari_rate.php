<style>
	.inline-radio {
		display: inline;
	}

	#branch .Inactive{
        color: red;
    }
</style>
<div class="row" style="margin-top: 20px;">
 <h2 class="text-center">Zamidari Rate Form</h2>
	<div class="col-sm-6 col-sm-offset-3">
		<?php if($selected){ ?>
		<form class="form-vertical" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>zamindari_rate_update">
		
			<div class="form-group">
				<label class="col-sm-5 control-label" for="savings_deposit"> Saving Diposit </label>
				
				<div class="col-sm-5">
					<input name="savings_deposit" type="number" id="savings_deposit" value="<?php echo $selected->savings_deposit ?>" class="form-control" />
					<input name="iidd" type="hidden" id="iidd" value="" class="txt" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="membership_fee"> Membership Fee </label>
				
				<div class="col-sm-5">
					<input name="membership_fee" type="number" id="membership_fee" value="<?php echo $selected->membership_fee ?>" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="shop_rent"> Shop Rent  </label>
				
				<div class="col-sm-5">
					<input name="shop_rent" type="number" id="shop_rent" value="<?php echo $selected->shop_rent ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="tax_surcharge"> Tax Surcharge  </label>
				
				<div class="col-sm-5">
					<input name="tax_surcharge" type="number" id="tax_surcharge" value="<?php echo $selected->tax_surcharge ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="service_charge"> Service Charge </label>
				
				<div class="col-sm-5">
					<input name="service_charge" type="number" id="service_charge" value="<?php echo $selected->service_charge ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12"><hr></div>
			</div>

			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="savings_deposit_late"> Saving Deposit Late  </label>
				<div class="col-sm-5">
					<input name="savings_deposit_late" type="number" id="savings_deposit_late" value="<?php echo $selected->savings_deposit_late ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="membership_fee_late"> Membership Fee Late</label>
				<div class="col-sm-5">
					<input name="membership_fee_late" type="number" id="membership_fee_late" value="<?php echo $selected->membership_fee_late ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="shop_rent_late"> Shop Rent lates </label>
				<div class="col-sm-5">
					<input name="shop_rent_late" type="number" id="shop_rent_late" value="<?php echo $selected->shop_rent_late ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="tax_surcharge_late"> Tax Surcharge Late </label>
				
				<div class="col-sm-5">
					<input name="tax_surcharge_late" type="number" id="tax_surcharge_late" value="<?php echo $selected->tax_surcharge_late ?>" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="service_charge_late"> Service Charge Late</label>
				
				<div class="col-sm-5">
					<input name="service_charge_late" type="number" id="service_charge_late" value="<?php echo $selected->service_charge_late ?>" class="form-control" />
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
		
		<form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>zamindari_rate_insert">
			<div class="form-group">
				<label class="col-sm-5 control-label" for="savings_deposit"> Saving Diposit </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="savings_deposit" type="number" id="savings_deposit" value="" class="form-control" />
					<input name="iidd" type="hidden" id="iidd" value="" class="txt" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="membership_fee"> Membership Fee </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="membership_fee" type="number" id="membership_fee" value="" class="form-control" />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="shop_rent"> Shop Rent </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="shop_rent" type="number" id="shop_rent" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="tax_surcharge"> Tax Surcharge </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="tax_surcharge" type="number" id="tax_surcharge" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="service_charge"> Service Charge </label>
				<div class="col-sm-1">: </div>
				<div class="col-sm-5">
					<input name="service_charge" type="number" id="service_charge" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12"><hr></div>
			</div>

			
			<div class="form-group">
				<label class="col-sm-5 control-label" for="savings_deposit_late"> Saving Deposit Late </label>
				<div class="col-sm-5">
					<input name="savings_deposit_late" type="number" id="savings_deposit_late" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="membership_fee_late"> Membership Fee Late  </label>
				<div class="col-sm-5">
					<input name="membership_fee_late" type="number" id="membership_fee_late" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="shop_rent_late"> Shop Rent lates </label>
				<div class="col-sm-5">
					<input name="shop_rent_late" type="number" id="shop_rent_late" value="" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="tax_surcharge_late"> Tax Surcharge Late </label>
				
				<div class="col-sm-5">
					<input name="tax_surcharge_late" type="number" id="tax_surcharge_late" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label" for="service_charge_late"> Service Charge Late</label>
				
				<div class="col-sm-5">
					<input name="service_charge_late" type="number" id="service_charge_late" class="form-control" />
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

   <!-- TextArea editor -->
<script type='text/javascript' src='<?php echo base_url(); ?>ckeditor/ckeditor.js'></script>

<script type="text/javascript">
    CKEDITOR.replace('Description');
    
</script>        
<!-- end TextArea editor -->