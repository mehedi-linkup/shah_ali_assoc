<div id="ownerListReport">
  <div style="display:none;" v-bind:style="{display: owners.length > 0 ? '' : 'none'}">
    <div class="row">
      <div class="col-md-12">
        <a href="" @click.prevent="printownerList"><i class="fa fa-print"></i> Print</a>
      </div>
    </div>

    <div class="row" style="margin-top:15px;">
      <div class="col-md-12">
        <div class="table-responsive" id="printContent">
          <table class="table table-bordered table-condensed">
            <thead>
              <th>Sl</th>
              <th>Owner Id</th>
              <th>Owner Name</th>
              <th>Address</th>
              <th>Contact No.</th>
              <th>NID</th>
            </thead>
            <tbody>
              <tr v-for="(owner, sl) in owners">
                <td>{{ sl + 1 }}</td>
                <td>{{ owner.Owner_Code }}</td>
                <td>{{ owner.Owner_Name }}</td>
                <td>{{ owner.Owner_PerAddress }}</td>
                <td>{{ owner.Owner_Mobile }}</td>
                <td>{{ owner.Owner_NID }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div style="display:none;text-align:center;" v-bind:style="{display: owners.length > 0 ? 'none' : ''}">
    No records found
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>

<script>
  new Vue({
    el: '#ownerListReport',
    data() {
      return {
        owners: []
      }
    },
    created() {
      this.getOwners();
    },
    methods: {
      getOwners() {
        axios.get('/get_owners').then(res => {
          this.owners = res.data;
        })
      },

      async printOwnerList() {
        let printContent = `
                    <div class="container">
                        <h4 style="text-align:center">Owner List</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#printContent').innerHTML}
							</div>
						</div>
                    </div>
                `;

        let printWindow = window.open('', '', `width=${screen.width}, height=${screen.height}`);
        printWindow.document.write(`
                    <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
                `);

        printWindow.document.body.innerHTML += printContent;
        printWindow.focus();
        await new Promise(r => setTimeout(r, 1000));
        printWindow.print();
        printWindow.close();
      }
    }
  })
</script>