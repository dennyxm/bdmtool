<?php
$util = new Utilities();
?>
<div class="modal fade" tabindex="-1" role="dialog" id="popupContainer">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $head->stockcode ?>&nbsp;[ <?php echo date('d-M-Y', strtotime($head->startdate)) ?> - <?php echo date('d-M-Y', strtotime($head->enddate)) ?>]</h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td></td>
							<td class="text-center">Total Volume</td>
							<td class="text-center">Net Volume</td>
						</tr>
						<tr>
							<td>Lot</td>
							<td class="text-right"><?php echo $util->formatNumber($head->total_vol)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->net_vol)?> </td>
						</tr>
						<tr>
							<td>Juta</td>
							<td class="text-right"><?php echo $util->formatNumber($head->total_val/1000000)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->net_val/1000000)?> </td>
						</tr>
						<tr>
							<td>AVERAGE</td>
							<td colspan="2"  class="text-center"><?php echo $util->formatNumber($head->total_avg)?> </td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td></td>
							<td class="text-center">Buyer</td>
							<td class="text-center">Seller</td>
							<td class="text-center">#</td>
							<td class="text-center">Acc/Dist</td>
						</tr>
						<tr>
							<td>Broker</td>
							<td class="text-right"><?php echo $util->formatNumber($head->buyer_count)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->seller_count)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->total_buyer_seller_count)?> </td>
							<td class="text-right"><?php echo $head->is_broker_acc?> </td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td></td>
							<td class="text-center">Vol</td>
							<td class="text-center">Net Ratio</td>
							<td class="text-center">Acc/Dist</td>
							<td class="text-center">Volume Ratio</td>
						</tr>
						<tr>
							<td>Top 3</td>
							<td class="text-right"><?php echo $util->formatNumber($head->top3_vol)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->top3_net_ratio) ?> </td>
							<td class="text-center"><?php echo $head->top3_is_broker_acc?> </td>
              <?php
                  $vol_ratio = $head->total_vol/$head->net_vol;
                  $bg_vol_ratio="";
                  if($vol_ratio<3){
                    $bg_vol_ratio="bg-primary";
                  }elseif ($vol_ratio>=3 && $vol_ratio<6) {
                    # code...
                    $bg_vol_ratio="bg-success";
                  }elseif ($vol_ratio>=6 && $vol_ratio<9) {
                    # code...
                    $bg_vol_ratio="bg-warning";
                  }elseif ($vol_ratio>=9) {
                    # code...
                    $bg_vol_ratio="bg-danger";
                  }
              ?>
							<td class="text-center <?php echo $bg_vol_ratio ?>" rowspan="4"> <h1><?php echo $util->formatNumber($vol_ratio)?></h1> </td>
						</tr>
						<tr>
							<td>Top 5</td>
							<td class="text-right"><?php echo $util->formatNumber($head->top5_vol)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->top5_net_ratio)?> </td>
							<td class="text-center"><?php echo $head->top5_is_broker_acc?> </td>
							<!-- <td class="text-right"><?php echo $util->formatNumber($head->top5_val_ratio)?> </td> -->
						</tr>
						<tr>
							<td>Avg 10</td>
							<td class="text-right"><?php echo $util->formatNumber($head->avg10_vol)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->avg10_net_ratio)?> </td>
							<td class="text-center"><?php echo $head->avg10_is_broker_acc?> </td>
							<!-- <td class="text-right"><?php echo $util->formatNumber($head->avg10_val_ratio)?> </td> -->
						</tr>
						<tr>
							<td>#</td>
							<td class="text-right"><?php echo $util->formatNumber($head->alltop_vol)?> </td>
							<td class="text-right"><?php echo $util->formatNumber($head->alltop_net_ratio)?> </td>
							<td class="text-center"><?php echo $head->alltop_is_broker_acc?> </td>
							<!-- <td class="text-right"><?php echo $util->formatNumber($head->alltop_val_ratio)?> </td> -->
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<td colspan="4" class="text-center bg-green">BUYER</td>
						<td rowspan="2" class="text-center"  width="5%">#</td>
						<td colspan="4" class="text-center bg-red">SELLER</td>
						<td class="text-center">Acc/Dist</td>
					</tr>
					<tr>
						<td class="text-center"><strong>Broker</strong></td>
						<td class="text-center"><strong>Vol</strong></td>
						<td class="text-center"><strong>Avg</strong></td>
						<td class="text-center"><strong>Value (jt)</strong></td>
						<td class="text-center"><strong>Broker</strong></td>
						<td class="text-center"><strong>Vol</strong></td>
						<td class="text-center"><strong>Avg</strong></td>
						<td class="text-center"><strong>Value (jt)</strong></td>
						<td class="text-center"><strong>Vol</strong></td>
					</tr>
				</thead>
				<tbody>
				<?php
					if (count ( $detail ) > 0){
						for($i = 0; $i < count ( $detail ); $i ++) {
							$jo = $detail [$i];
              // label for YP, PD
              $buyer_lbl_color="";
              $seller_lbl_color="";
              // grade a
              if($jo->buyer_broker=="YP" || $jo->buyer_broker=="PD") $buyer_lbl_color="bg-primary";
              if($jo->seller_broker=="YP" || $jo->seller_broker=="PD") $seller_lbl_color="bg-primary";
              // grade b
              if($jo->buyer_broker=="NI" || $jo->buyer_broker=="KK") $buyer_lbl_color="bg-info";
              if($jo->seller_broker=="NI" || $jo->seller_broker=="KK") $seller_lbl_color="bg-info";

					?>
					<tr>
						<td class="text-center <?php echo $buyer_lbl_color ?>"><?php echo $jo->buyer_broker?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->buyer_vol)?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->buyer_avg)?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->buyer_val/1000000)?></td>
						<td class="text-center"><?php echo $jo->row_number?> </td>
						<td class="text-center <?php echo $seller_lbl_color ?>"><?php echo $jo->seller_broker?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->seller_vol)?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->seller_avg)?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->seller_val/1000000)?></td>
						<td class="text-center"><?php echo $util->formatNumber($jo->acc_dist_vol)?></td>
					</tr>
				<?php
						}
					}
				?>
				</tbody>
			</table>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
