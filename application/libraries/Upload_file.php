<?php
class Upload_file { 

	public function upload_broker() {
		// function to upload xls, xlsx file
		$ci = &get_instance ();
		$config ['upload_path'] = "./uploads";
		$config ['allowed_types'] = 'xls|xlsx|csv';
		$config ['max_size'] = '15360'; // 1MB = 1024,
		$config ['encrypt_name'] = "TRUE";
		$config ['file_name'] = date ( 'YmdHis' );

		$ci->load->library ( 'upload', $config );

		if ($ci->upload->do_upload ( "csv_file" )) :

			$data = $ci->upload->data ();
			$result = array (
					'err_no' => "0",
					'sys_filename' => $data ['file_name'],
					'real_filename' => $data ['client_name'],
					'full_path' => $data ['full_path']
			);
		 else :

			$result = array (
					'err_no' => "206",
					'err_msg' => $ci->upload->display_errors ( '', '' )
			);


		endif;

		return $result;
	}


}

?>
