

<div class="col-sm-12" style="width:500px;height:200px;">
	<div class="col-sm-12 text-center" style="border-bottom:2px #ccc solid;margin-bottom:30px;"> 
		<h4 style="margin-top:20px;"> Bill reports: </h4>
	</div>
	<?php 
		 $today = date('Y-m-d');
		 $lastDay = date('Y-m-d', strtotime('+15 days'));

		 $bill = $this->db->query("
			SELECT  
				concat(
					'ইনভয়েস: ', bd.invoice,
					', তলা: ', f.Floor_Name,
					', ষ্টোর: ', s.Store_Code, ' - ', s.Store_Name,
					', বিল: ', bd.net_payable,
					', পরিশোধের শেষ দিন: ', bd.last_date,
					', দিন বাকি: ', ( select ifnull(datediff(bd.last_date, '$today'), 0) )
				) as bill_text,
				( 
					select ifnull(sum(upd.payment), 0) from tbl_utility_payment_details upd
					where upd.bill_detail_id = bd.id
				) as bill_paid
			from tbl_bill_sheet_details bd
			join tbl_store s on s.Store_SlNo = bd.store_id
			join tbl_bill_sheet bs on bs.id = bd.bill_id
			left join tbl_floor f on f.Floor_SlNo = s.floor_id
			where 1 = 1 and bd.id = '$bill_detail_id'
			order by bd.id desc
		 ", $this->session->userdata('BRANCHid') )->row();
				
		 echo $bill->bill_text ;
	?>



</div>
