<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Utilities {
	protected $ci;
	public function __construct() {
		$this->ci = & get_instance ();
	}

	public function addDay($date, $day){
		date_add(strtotime($date), $interval);
	}

	public function formatNumber($number){
		return number_format($number, 2, ',', '.');
	}

	public function unmask($value){
		//ilangin titik separator ribuan. jadiin (,) yg terdeteksi sebagai titik desimal
		$value = str_replace(".","","$value");
		$value = str_replace(",",".",$value);
		return $value;
	}

	public function remask($value){
		return str_replace(".",",",$value);
	}
}
