<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Paginator {
	private $adjacent = 3;
	private $total_row = 0;
	public $dataPerPage = 10; // 10 data default for cmnp project
	public $jsPagingFunction = "fPageGoTo";
	private $css_fw; // default css framework setting, 0 for bootstrap, 1 for foundation
	private $query = "";
	public $navWrapper = "pull-right";
	public $pagingLimit = "";

	/*
	 * private variable declaration which related to css fw
	 * - prev btn
	 * - page btn
	 * - next btn
	 */

	// disabled prev & next
	private $activePrevNextClass;
	private $disabledPrevNextClass;
	// page
	private $currentPageClass;
	private $unavailablePageClass;
	protected $ci;
	public function __construct() {
		$this->ci = & get_instance ();
		$this->ci->load->model ( 'mgeneral' );
	}

	// abis di declare.. jalanin init
	public function init($query, $css_fw = 0) {
		$this->total_row = $this->ci->mgeneral->countDataFromQuery ( $query );
		$this->query = $query;
		$this->css_fw = $css_fw;
	}

	public function getTotalRows(){
		return $this->total_row;
	}

	public function setDefaultJsPagingFunction() {
		$this->jsPagingFunction = "fPageGoTo";
	}
	private function generateCssClass() {
		// bootstrap
		if ($this->css_fw == 0) {
			// prev
			$this->activePrevNextClass = ""; // emang kosong classnya
			$this->disabledPrevNextClass = "disabled";
			// page
			$this->currentPageClass = "active";
			$this->unavailablePageClass = "disabled";
		} else {
			// foundation

			// prev
			$this->activePrevNextClass = "arrow";
			$this->disabledPrevNextClass = "arrow unavailable";
			// page
			$this->currentPageClass = "current";
			$this->unavailablePageClass = "unavailable";
		}
	}
	public function getDataOnPage($page) {
		// hitung offset baris tempat dimulainya data display berdasarkan nomor halaman

		// update 2 jan.. kalo data on page nya <= 0 .. maka dia bakal nampilin semua data , tanpa itung limit & offset
		if($this->dataPerPage>0){
			$startCol = ($page - 1) * $this->dataPerPage;
			$query = $this->query . " limit " . $this->dataPerPage . " offset " . $startCol;
			// $this->pagingLimit = " limit " . $this->dataPerPage . " offset " . $startCol;
		}else $query = $this->query;

		return $this->ci->mgeneral->selectRecord ( $query );
	}

	public function getLimitOnPage($page){
		if($this->dataPerPage>0){
			$startCol = ($page - 1) * $this->dataPerPage;
			return " limit " . $this->dataPerPage . " offset " . $startCol;
		}
	}

	public function getPageLinks($page, $wrapperClass) {
		// current page position also affects the generated links.. so we should also take it as a parameter
		// by default this will generate link that would work beautifully in bootstrap ui environment
		// if you have any additional class beside the default pagination class which is definitely going to be used in this module, please fill the wrapper class

		if($this->dataPerPage>0){
			$prevPage = $page - 1;
			$nextPage = $page + 1;
			$lastPage = ceil ( $this->total_row / $this->dataPerPage );
			$lpm1 = $lastPage - 1;

			// set css class
			$this->generateCssClass ();

			$generatedLink = "";
			if ($lastPage > 1) {
				// beginning wrapper

				$counter = 0;
				$generatedLink = "<nav class=\"" . $this->navWrapper . "\">" . "<ul class=\"pagination " . $wrapperClass . " \" >";

				// prev button
				if ($page > 1)
					$generatedLink .= "<li" . ($this->css_fw == 0 && $this->activePrevNextClass == "" ? "" : " class=\"" . $this->activePrevNextClass . "\"") . "><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $prevPage . ")\">&laquo; Previous</a></li>";
				else
					$generatedLink .= "<li class=\"" . $this->disabledPrevNextClass . "\"><a href=\"#\">&laquo;</a></li>";

					// pages
				if ($lastPage < 7 + ($this->adjacent * 2)) // not enough pages to bother breaking it up
					{
					// System.out.println("masuk kalo page kurang dari "+(7 + (adjacent * 2)));
					for($counter = 1; $counter <= $lastPage; $counter ++) {
						if ($counter == $page)
							$generatedLink .= "<li class=\"" . $this->currentPageClass . "\"><a href=\"#\">" . $counter . ($this->css_fw == 0 ? "<span class=\"sr-only\">(current)</span>" : "") . "</a></li>"; // display current active page
						else
							$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $counter . ")\">" . $counter . "</a></li>"; // display other page num
					}
				} else if ($lastPage > 5 + ($this->adjacent * 2)) // enough pages to hide some
				{
					// System.out.println("kalo page > "+(5 + (adjacent * 2)));
					// close to beginning; only hide later pages
					if ($page < 1 + ($this->adjacent * 2)) {
						for($counter = 1; $counter < 4 + ($this->adjacent * 2); $counter ++) {
							if ($counter == $page)
								$generatedLink .= "<li class=\"" . $this->currentPageClass . "\"><a href=\"#\">" . $counter . "</a></li>";
							else
								$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $counter . ")\">" . $counter . "</a></li>";
						}
						$generatedLink .= "<li class=\"" . $this->unavailablePageClass . "\"><a href=\"#\">&hellip;</a></li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $lpm1 . ")\">" . $lpm1 . "</a></li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $lastPage . ")\">" . $lastPage . "</a></li>";
					} 				// in middle; hide some front and some back
					else if ($lastPage - ($this->adjacent * 2) > $page && $page > ($this->adjacent * 2)) {
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(1)\">1</a></li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(2)\">2</a></li>";
						$generatedLink .= "<li class=\"" . $this->unavailablePageClass . "\"><a href=\"#\">&hellip;</a></li>";
						for($counter = $page - $this->adjacent; $counter <= $page + $this->adjacent; $counter ++) {
							if ($counter == $page)
								$generatedLink .= "<li class=\"" . $this->currentPageClass . "\"><a href=\"#\">" . $counter . "</a></li>";
							else
								$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $counter . ")\">" . $counter . "</a></li>";
						}
						$generatedLink .= "<li class=\"" . $this->unavailablePageClass . "\"><a href=\"#\">&hellip;</a></li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $lpm1 . ")\">" . $lpm1 . "</a></li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $lastPage . ")\">" . $lastPage . "</a></li>";
					} 				// close to end; only hide early pages
					else {
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(1)\">1</a><li>";
						$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(2)\">2</a><li>";
						$generatedLink .= "<li class=\"" . $this->unavailablePageClass . "\"><a href=\"#\">&hellip;</a></li>";
						for($counter = $lastPage - (2 + ($this->adjacent * 2)); $counter <= $lastPage; $counter ++) {
							if ($counter == $page)
								$generatedLink .= "<li class=\"" . $this->currentPageClass . "\"><a href=\"#\">" . $counter . "</a></li>";
							else
								$generatedLink .= "<li><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $counter . ")\">" . $counter . "</a></li>";
						}
					}
				}

				// next button
				if ($page < $counter - 1)
					$generatedLink .= "<li" . ($this->css_fw == 0 && $this->activePrevNextClass == "" ? "" : " class=\"" . $this->activePrevNextClass . "\"") . "><a href=\"#\" onClick=\"" . $this->jsPagingFunction . "(" . $nextPage . ")\">Next &raquo;</a></li>";
				else
					$generatedLink .= "<li class=\"" . $this->disabledPrevNextClass . "\"><a href=\"#\">&raquo;</a></li>";

					// wrapper closure
				$generatedLink .= "</ul>" . "</nav>";
			}
			return $generatedLink;
		} else return "";
	}
}

/* End of file paginator.php */
/* Location: ./application/libraries/Paginator.php */
