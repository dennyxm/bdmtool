 <div class="row">
		<div class="col-md-8 col-md-offset-2">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
					<td class="text-center" width="5%">#</td>
					<td class="text-center"><strong>STOCK CODE</strong></td>
					<td class="text-center"><strong>Processing Time</strong></td>
					<td class="text-center"><strong>Start Date</strong></td>
					<td class="text-center"><strong>End Date</strong></td>
					<td class="text-center">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?php
	if (count ( $trData ) > 0){
		$numRow = $startingRowNum;
		for($i = 0; $i < count ( $trData ); $i ++) {
			$jo = $trData [$i];
?>
<tr>
						<td class="text-center"><?php echo $numRow?></td>
						<td class="text-center"><?php echo $jo->stockcode?></td>
						<td class="text-center"><?php echo date('d-M-Y H:i:s', strtotime($jo->processtime))?></td>
						<td class="text-center"><?php echo date('d-M-Y', strtotime($jo->startdate)) ?></td>
						<td class="text-center"><?php echo date('d-M-Y', strtotime($jo->enddate)) ?></td>
						<td class="text-center" data-id="<?php echo $jo->id?>" >
							<button class="btn btn-success btn-sm btnDetail">
								View
							</button>
              <button class="btn btn-danger btn-sm btnDelete">
								Delete
							</button>
						</td>
					</tr>
<?php
			$numRow ++;
		}
	}else{
		?>
		<tr>
			<td colspan="7" class="text-center">No Data</td>
		</tr>
		<?php
	}
?>
</tbody>
	</table>
</div>
</div>
 <div class="row">
		<div class="col-md-8 col-md-offset-2">
		<?php echo $navPage?>
		</div>
</div>
