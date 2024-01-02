<div id="billInvoice">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<bill-invoice v-bind:bill_id="billId"></bill-invoice>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/zamindarbillInvoice.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#billInvoice',
		components: {
			billInvoice
		},
		data(){
			return {
				billId: parseInt('<?php echo $billId;?>')
			}
		}
	})
</script>

