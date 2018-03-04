<?php
set_time_limit ( 300 );
class BdmModel extends CI_Model{
	/**
	PD, YP, - a
	NI, KK -b
	- c -
	DH GR
	DR OD
	*/
	public function addBrokerSum($headinfo ){
		// insert ke headinfo
		return $this->mgeneral->save ( $headinfo, "stock_info" );
	}

	public function deleteStock($stockId){
		return $this->mgeneral->delete(array('id' =>$stockId),"stock_info");
	}

	public function addBrokerSumDetail($arr_buyer, $arr_seller){
		$this->db->insert_batch("buyer_details", $arr_buyer);
		$this->db->insert_batch("seller_details", $arr_seller);
	}

	public function doAnalyze($stockId){
		// execute sp to generate analysis
		$this->db->query("select analyze_bandarmology($stockId)");
	}

	public function getListData($arr_search, $pageNum){
		$where = "";
		if ( strlen($arr_search['stockcode'])>0 ) {
			$where = " lower(stockcode) like lower('%" . $this->db->escape_like_str ( $arr_search['stockcode'] ) . "%') ";
		}

		if ( strlen($arr_search['startdate'])>0 ) {
			$start_date = date("Y-m-j",strtotime($arr_search['startdate']));
			if ($where != "") $where.= " and ";
			$where.= " startdate = '$start_date' ";
		}

		if ( strlen($arr_search['enddate'])>0 ) {
			//$end_date = date("Y-m-j",strtotime($arr_search['enddate']. " +1 day"));
			$end_date = date("Y-m-j",strtotime($arr_search['enddate'] ));

			if ($where != "") $where.= " and ";
			$where.= " enddate ='$end_date'";
		}


		if ($where != "")
			$where = " where " . $where;

		$query = "select * from stock_info ".$where." order by id desc";
		//echo $query;
		$this->paginator->init ( $query );

		$trData = $this->paginator->getDataOnPage ( $pageNum );
		$navPage = $this->paginator->getPageLinks ( $pageNum, "" );

		$js ["trData"] = $trData;
		$js ["navPage"] = $navPage;
		$js ["startingRowNum"] = (($pageNum - 1) * $this->paginator->dataPerPage) + 1;

		return $js;
	}

	public function getBdmHead($dataId){
		$query = "select
						stockcode,
					  startdate ,
					  enddate ,
					  total_vol ,
					  total_val,
					  net_vol ,
					  net_val,
					  total_avg ,
					  buyer_count ,
					  seller_count ,
					  case when is_broker_acc = 1 then 'Acc' when is_broker_acc=0 then 'Neutral' when is_broker_acc=-1 then 'Dist' end as is_broker_acc ,
					  top3_vol ,
					  top3_net_ratio ,
					  top3_val_ratio ,
					  get_acc_dist_name(top3_is_broker_acc) top3_is_broker_acc ,
					  top5_vol ,
					  top5_net_ratio ,
					  top5_val_ratio ,
					  get_acc_dist_name(top5_is_broker_acc) top5_is_broker_acc ,
					  avg10_vol ,
					  avg10_net_ratio ,
					  avg10_val_ratio ,
					  get_acc_dist_name(avg10_is_broker_acc) avg10_is_broker_acc ,
					  alltop_vol ,
					  alltop_net_ratio ,
					  alltop_val_ratio ,
					  get_acc_dist_name(alltop_is_broker_acc) alltop_is_broker_acc,
					  total_buyer_seller_count
					  from stock_info where id=$dataId;";
		return $this->mgeneral->selectOneFieldRecord($query);
	}

	public function getBdmDetails($dataId){
		$query = "select * from bdm_analysis_details where fk_stock_info=$dataId order by id asc";
		return $this->mgeneral->selectRecord($query);
	}
}
?>
