<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BdmController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('main_view');
	}

	public function get_data(){
		/*
			accepting post parameters :
			page
			stock
			startdate
			enddate
		*/
		$stock_code = $this->input->post ( "stock_code" );
		$start_date = $this->input->post ( "start_date" );
		$end_date = $this->input->post ( "end_date" );

		if(strlen($start_date)>0)$start_date = date("Y-m-j",strtotime($start_date));
		if(strlen($end_date)>0)$end_date = date("Y-m-j",strtotime($end_date));

		$arrSearch = array(
			'stockcode'=>$stock_code,
			'startdate'=>$start_date,
			'enddate'=>$end_date
		);


		$strpage = $this->input->post ( "page" );
		$pageNum = (is_numeric ( $strpage ) == false ? 1 : $strpage);

			// kudu disiapin buat order by

		$data = $this->bdmmodel->getListData ( $arrSearch, $pageNum );
		$this->load->view('data_container',$data);
	}

	public function delete_data($stockId){
		echo $this->bdmmodel->deleteStock($stockId);
	}

	public function add_broker_sum(){
		$csv_file = $_FILES ['csv_file'];

		$upload = $this->upload_file->upload_broker();

		if ($upload ['err_no'] == 0) {
			$msg="";
			// process the data
			$file = new SplFileObject($upload['full_path']);
			// Loop until we reach the end of the file.
			$lineCount=1;
			$buyerCount=0;
			$sellerCount=0;
			while (!$file->eof()) {
				// Echo one line from the file.
				$line = $file->fgets();
				$arr_line = preg_split("/[\t]/", $line);

				if($lineCount==1){
					$headinfo = array(
						'stockcode'=>$arr_line[1],
						'startdate'=>$arr_line[3],
						'enddate'=>$arr_line[5]
					);

					$lastId = $this->bdmmodel->addBrokerSum($headinfo);
				}else if($lineCount>3){
					// detect buyer
					// isi ke arr buyer
					if(count($arr_line>1)){
						if(isset($arr_line[0])>0 && strlen($arr_line[0])==2){
							$arr_buyer[$buyerCount]['fk_stock'] = $lastId;
							$arr_buyer[$buyerCount]['broker']=$arr_line[0];
							$arr_buyer[$buyerCount]['total_lot']= str_replace(",","",$arr_line[1]) ;
							$arr_buyer[$buyerCount]['total_value']=str_replace(",","",$arr_line[2]);
							$arr_buyer[$buyerCount]['total_avg']=str_replace(",","",$arr_line[3]);
							$buyerCount++;
						}

						// detect seller
						// isi ke arr seller
						if(isset($arr_line[5]) && strlen($arr_line[5])==2){
							$arr_seller[$sellerCount]['fk_stock'] = $lastId;
							$arr_seller[$sellerCount]['broker']=$arr_line[5];
							$arr_seller[$sellerCount]['total_lot']= str_replace(",","",$arr_line[6]) ;
							$arr_seller[$sellerCount]['total_value']=str_replace(",","",$arr_line[7]);
							$arr_seller[$sellerCount]['total_avg']=str_replace(",","",$arr_line[8]);
							$sellerCount++;
						}

					}
				}
				$lineCount++;
			}
			// Unset the file to call __destruct(), closing the file handle.
			$file = null;
			$this->bdmmodel->addBrokerSumDetail($arr_buyer, $arr_seller );
			$this->bdmmodel->doAnalyze($lastId);

			$result = array (
					'status' => 1,
					'msg' => "CSV File has been successfully uploaded"
			);
		} else {
			$result ['status'] = 0;
			$result ['msg'] = 'Uploading Broker Summary has failed. '.$upload['err_msg'];
		}

		echo json_encode ( $result );
	}

	public function get_detail($data_id){
		$this->load->library ( 'utilities' );
		$data['head'] = $this->bdmmodel->getBdmHead($data_id);
		$data['detail'] = $this->bdmmodel->getBdmDetails($data_id);
		$this->load->view ( 'modal_detail' , $data);
	}
}
?>
