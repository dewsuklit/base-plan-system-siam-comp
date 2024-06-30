<?php
session_start();
if($_SESSION["session_name"] == ''){
 echo "<script>location.href='../index.php';</script>";
}
require_once( '../../../../include/dbfunction.php' );
require_once( '../../../function/function.php' );


$room = $_REQUEST["room"];
$srno = $_REQUEST["srno"];
$roomtoarr = explode(",", $room);

$date_time = date("Y-m-d H:i:s");



// $this_year = date(Y);
$Querysr = "SELECT a.id,a.qty,a.mkt_issued_by,a.mkt_issued_date,a.purpose_of_sr,b.model FROM sr a inner join comp_data b on a.comp_id = b.id where a.sr_no = '$srno' ";
$Resultsr = mysql_query($Querysr) or die(mysql_error());
$linesr = mysql_fetch_assoc($Resultsr);
$sr_id = $linesr["id"]; 
$sr_model = $linesr["model"]; 
$sr_qty = (int)$linesr["qty"];
$txt_purpose_of_sr = $linesr["purpose_of_sr"]; 
$mkt_issued_by = $linesr["mkt_issued_by"]; 
$date_issued = $linesr["mkt_issued_date"]; 
mysql_free_result($Resultsr);

if($sr_id == ''){

$Querysr = "SELECT a.id,a.qty,a.mkt_issued_by,a.mkt_issued_date,a.purpose_of_sr,b.target_model_name FROM sr a inner join npr b on a.model_npr_id = b.id where a.sr_no = '$srno' ";
$Resultsr = mysql_query($Querysr) or die(mysql_error());
$linesr = mysql_fetch_assoc($Resultsr);
$sr_id = $linesr["id"]; 
$sr_model = $linesr["target_model_name"]; 
$sr_qty = (int)$linesr["qty"];
$txt_purpose_of_sr = $linesr["purpose_of_sr"]; 
$mkt_issued_by = $linesr["mkt_issued_by"]; 
$date_issued = $linesr["mkt_issued_date"]; 
mysql_free_result($Resultsr);
}


$this_year = date(Y);
$Query = "select topic_doc_no from rd_request where revise = '0'  order by id desc limit 1";
$Result = mysql_query($Query) or die(mysql_error());
$line = mysql_fetch_assoc($Result);
$last_doc_no_now = $line["topic_doc_no"]; //RDC19O0001
mysql_free_result($Result);
$last_doc_no = substr($last_doc_no_now, 5, 4);


$last_doc_no_year =  substr($last_doc_no_now, 2, 4);
$last_doc_no_year = "20". substr($last_doc_no_year,1,2);
if($last_doc_no == '' || ($last_doc_no_year != $this_year)){ 
	$cutyear = substr($this_year,2,2);
	$txt_topic_doc_no = 'RDC'.$cutyear.'0001';//1629
}else{ 
	//Kornpatm20190104 change running doc function before modify is wrong
	$int_last_doc_no = (int)$last_doc_no + 1;
	$str_last_doc_no = (string)$int_last_doc_no;
	switch(strlen($str_last_doc_no)){
	case 1 : $new_doc_no = '000'.$str_last_doc_no; break;
	case 2 : $new_doc_no = '00'.$str_last_doc_no;break;
	case 3 : $new_doc_no = '0'.$str_last_doc_no;break;
	case 4 : $new_doc_no = $str_last_doc_no;break;
	}
	//$new_doc_no = str_pad($new_doc_no,5, "", STR_PAD_LEFT);
	$cutyear = substr($last_doc_no_year,2,2);
	$txt_topic_doc_no = 'RDC'.$cutyear.$new_doc_no;
	//echo $txt_topic_doc_no;//debug

}


for($m=0;$m<$sr_qty;$m++){

	$sr_serial = $m+1;
	$txt_serial_no = $txt_topic_doc_no."-".$sr_serial;
	
	$Queryrd = "insert into rd_request 
	(	
		id_sr,
		topic_doc_no,
		serial_no,
		topic_for_rd,
		detail1_model,
		detail1_special_request,
		topic_amount,
		topic_write_by,
		topic_write_date,
		topic_check_by,
		topic_check_date,
		topic_approve_by,
		topic_approve_date,
		send_approve_by,
		send_approve_date,
		lastversion


	) values (
	
		'$sr_id',
		'$txt_topic_doc_no',
		'$txt_serial_no',
		'$txt_purpose_of_sr',
		'$sr_model',
		'From SR',
		'$sr_qty',
		'$mkt_issued_by',
		'$date_issued',
		'$mkt_issued_by',
		'$date_issued',
		'$mkt_issued_by',
		'$date_issued',
		'$mkt_issued_by',
		'$date_issued',
		'1'
	)";


	$Resultrd = mysql_query($Queryrd) or die(mysql_error());

	
	// find for rd_situation
	$Query2="select id from rd_request where topic_doc_no = '$txt_topic_doc_no' and serial_no = '$txt_serial_no' ";
	$Result2= mysql_query($Query2) or die(mysql_error());
	$line=mysql_fetch_array($Result2);
	$id_rd_request_data = $line["id"];
	mysql_free_result($Result2);

	if (in_array("1", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','1')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("2", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','2')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("5", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','5')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("3", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','3')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("8", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','8')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("9", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','9')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("10", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','10')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("4", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','4')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("6", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','6')";
		$Result = mysql_query($Query) or die(mysql_error());
	}

	if (in_array("11", $roomtoarr)) {
		$Query = "insert into rd_request_situation (id_rd_request,id_rd_request_status) values ('$id_rd_request_data','11')";
		$Result = mysql_query($Query) or die(mysql_error());
	}


} 
				
                    //rdrequest
                    
                   
echo "<script>alert('Insert Complete'); location.href = 'index.php';</script>";

	

?>



