<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BDM Tool</title>

    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/starter-template.css" rel="stylesheet">
	 
	<link href="assets/datepicker/datepicker3.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
	
	   <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">BDM Tool</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
             
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template">
        <h1>Market Maker Action Analysis Tool.</h1>
		<h6>a simple helper for every bandarmologist</h6>
		<hr/>
        <p>You can import IPOT csv Broker Summary and find out. What action does the market maker a.k.a <em>BANDAR</em> performed</p>
      </div>
	  <form action="#" method="post" id="frmUpload" enctype="multipart/form-data">
		<div class="row center-block">
		<div class="col-md-4 col-md-offset-4">
			<div class="form-group"> 
			<label for="csv_file">Select CSV IPOT Broker Summary to upload:</label>
			<input type="file" name="csv_file" id="fileupload" class="form-control"/> 
			</div>
		</div>
		</div>
	  </form>
	  
	  <div class="row">
		<div class="col-md-8 col-md-offset-2">
					  <div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title">Search</h3>
		  </div>
		  <div class="panel-body">
			<form action="<?php echo base_url() ?>bdmcontroller/get_data" method="post" id="frmSearch">
				<input type="hidden" name="page" value="1"/>
				<div class="row center-block">
					<div class="col-md-4">
						<div class="form-group"> 
						<label for="stock_code">STOCK</label>
						<input type="text" name="stock_code" class="form-control"/> 
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group"> 
						<label for="start_date">Start Date</label>
						<input type="text" name="start_date" class="form-control"/> 
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group"> 
						<label for="end_date">End Date</label>
						<input type="text" name="end_date" class="form-control"/> 
						</div>
					</div>
				</div>
				<div class="row center-block">
					<div class="col-md-4 col-md-offset-4 text-center">
						<button class="btn btn-primary" type="button" id="btnSearch"/>Search</button>
					</div> 
				</div>
		  </form>
		  </div>
		</div>
		</div>
	</div>
	<div id="dataContainer">
	</div> 
    </div><!-- /.container -->
    <script src="assets/jquery.min.js"></script>
	<script src="assets/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/datepicker/bootstrap-datepicker.js"></script>
	<script src="assets/uploader/jquery.fileupload.js"></script>
	<script src="assets/uploader/jquery.iframe-transport.js"></script>
	<script type="text/javascript">
	// semua pemanggilan js ada disini
	function getData(){ 
		$.post($('#frmSearch').attr('action'),$('#frmSearch').serialize()).done(function(htmldata){
			$('#dataContainer').html(htmldata)
		}).fail(function(){
			alert('unable to load data');
		})
	}
	
	$(function(){
		getData();
		
		// input search
		$('input[name=start_date],input[name=end_date]').datepicker({
         format: "dd-mm-yyyy",
         todayBtn:true,
         todayHighlight:true
       });
	   
	   // 
	   $('div#dataContainer').on("click",".btnDetail",function(e){
 		 var id = $(this).parents('td').attr('data-id');
 		 $.get( '<?php echo base_url()?>bdmcontroller/get_detail/'+id, function( data ) {
			if($('div#popupContainer').length>0)$('div#popupContainer').remove();
			$(data).prependTo($('body'));
			$('div#popupContainer').modal('show');
		  });
			 console.log('id - nya : '+id);
		 });
		
		// fileupload handler
		$('#fileupload').fileupload({
		url: '<?php echo base_url()?>bdmcontroller/add_broker_sum',
		dataType: 'html',
		done: function (e, data) {
			var result = $.parseJSON(data.result); 
					alert(result.msg);
					getData();
			}
		}).prop('disabled', !$.support.fileInput) ;
		
		$('#btnSearch').click(function(e){
			getData();
		})
	});
	</script>
  </body>
</html>