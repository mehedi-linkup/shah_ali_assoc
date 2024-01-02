<div id="billInvoice">
	<div class="row">
		Hello
		<div class="col-md-8 col-md-offset-2">
			<bill-invoice v-bind:payment_id="paymentId"></bill-invoice>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/components/zamindariPaymentInvoice.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#billInvoice',
		components: {
			billInvoice
		},
		data(){
			return {
				paymentId: parseInt('<?php echo $paymentId;?>')
			}
		}
	})
</script>





