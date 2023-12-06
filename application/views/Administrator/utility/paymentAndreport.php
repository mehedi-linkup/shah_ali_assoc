<div id="paymentInvoice">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<payment-invoice v-bind:payment_id="paymentId"></payment-invoice>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/paymentInvoice.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#paymentInvoice',
		components: {
			paymentInvoice
		},
		data(){
			return {
				paymentId: parseInt('<?php echo $paymentId;?>')
			}
		}
	})
</script>

