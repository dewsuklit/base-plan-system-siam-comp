<? 
session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

require_once( '../DBconfig/dbConfig2.php' );
// require_once( '../../include/config.php' );
// require_once( '../function/function.php' );
// require("../../include/class.phpmailer.php");
// require("../../include/class.smtp.php");

$id = $_REQUEST["id"];
$BOM = $_REQUEST["myBOM"];
$Query2="select comp_id from sr where id = '$id' ";
$Result2= $db2->query($Query2);
$line=mysqli_fetch_array($Result2);
$comp_id=$line['comp_id'];
$npr_id=$line['model_npr_id'];
mysqli_free_result($Result2);
if($comp_id != '0'){
	$Query2="select *,d.model as model,d.four_digit as four_digit 
	,a.remark as remark_sr
	from sr a 
	left outer join customer b on a.customer_id = b.customer_id 
	left outer join department c on a.department_id = c.id 
	left outer join comp_data d on a.comp_id=d.id 
	where a.id = '$id' ";
}else if($npr_id != '0' ){
	$Query2="select *,d.target_model_name as thismodel,d.fourdigit as four_digit
	,a.remark as remark_sr
	from sr a 
	left outer join customer b on a.customer_id = b.customer_id 
	left outer join department c on a.department_id = c.id 
	left outer join npr d on a.model_npr_id=d.id 
	where a.id = '$id' ";
}else{
	$Query2 ="select *,d.model as thismodel from sr a left outer join customer b on a.customer_id = b.customer_id 	left outer join department c on a.department_id = c.id left outer join questionnaire d on a.model_qn_id=d.id where a.id = '$id'";
}
$Result2= $db2->query($Query2);
$line=mysqli_fetch_array($Result2);
if($comp_id != 0 ) $txt_model = $line["model"];
else $txt_model = $line["thismodel"];
$txt_status= $line["status"];
$txt_four_digit = $line["four_digit"];
$txt_orderingcode = $line["orderingcode"];
$txt_qty = $line["qty"];
$txt_rd_take_comp_from = $line["rd_take_comp_from"];
$txt_payment_condition = $line["payment_condition"];
$txt_test_report_require = $line["test_report_require"];
$txt_request_rd_to_sent_to_store_within = $line["request_rd_to_sent_to_store_within"];
$txt_sight_glass_number = $line["sight_glass_number"];


$txt_other_file = $line["other_file"];
$txt_type_of_compressor = $line["type_of_compressor"];

$txt_remark = $line["remark_sr"];
$txt_attach_report_date = $line["file_attach_report_date"];
$txt_file_attach_report = $line["file_attach_report"];
$txt_rd_report_file = $line["rd_report_file"];
$txt_rd_get_part_from_prod = $line["rd_get_part_from_prod"];
$txt_rd_send_to_store = $line["rd_send_to_store"];
$txt_new_date_to_store_rev1 = $line["new_date_to_store_rev1"];
$txt_new_date_to_store_rev2 = $line["new_date_to_store_rev2"];
$txt_serial_no = $line["serial_no"];
$txt_store_date = $line["store_date"];
$txt_mkt_issued_by = $line["mkt_issued_by"];
$txt_mkt_already_sent_to_customer_on_by= $line["mkt_already_sent_to_customer_on_by"];
$txt_sr_cancle_by= $line["sr_cancle_by"];
mysqli_free_result($Result2);

$sr_no = $line["sr_no"];
$Query2="SELECT ondate FROM base_plan_rd where id = (SELECT id_base_plan FROM base_plan_rd_detail where sr_no = '$sr_no' order by id desc limit 1)";
$Result2= $db2->query($Query2);
$line=mysqli_fetch_array($Result2);
$answer_date = $line["ondate"];

$Query2="SELECT storedate FROM base_plan_rd_detail where sr_no = '$sr_no' order by id desc";
$Result2= $db2->query($Query2);
$line=mysqli_fetch_array($Result2);
if($line["storedate"] != "" && $txt_rd_send_to_store == "0000-00-00") $txt_rd_send_to_store = $line["storedate"];
	// $txt_new_date_to_store_rev1 = $line[storedate];

?>
<style>
	.table_sample thead tr th,
	.table_sample tr td{
		border:1px solid gray;
		text-align:center;
	}
</style>
<table width="100%" class="table_sample" border=1 style="border:1px solid gray;" >
	<!-- <tr><td colspan="100%" style="height:5px"></td></tr>  -->
	<thead style="background-color: #c8d0e7!important;">
		<tr>
			
			<th>Model</th>
			<!-- <th>4Digits</th> -->
			<th>Orderingcode</th>
			
			<th>Qty(Set)</th>
			<th>R&D take comp. from</th>
			<th>BOM</th>
			<th>Payment condition</th>
			<th>Test report require</th>
			<th>Type of compressor</th>
			<th>Request R&D to send to store within</th>
			
			
			<th>Answer Date</th>
			<th>
				Send to store 1st commitment
			</th>
			<th>
				R&D informed 2nd schedule to MKT
				<!-- Not finish in time new date to store (Rev1) -->
			</th>
			<th>
				R&D informed 3th schedule to MKT
				<!-- Not finish in time new date to store (Rev2) -->
			</th>
			<th>Serial No.</th>
			<th>Store date.</th>
			<th>Remark</th>
		</tr>
	</thead>
	
	
	<tr class="trCenter" <?if(strtolower($txt_status)=='cancel'){echo "style='color:red;'";}?>>
		
		<td class="sample_lable"><?=$txt_model;?></td>
		<!-- <td class="sample_lable"><?=$txt_four_digit;?></td> -->
		<td class="sample_lable"><?=$txt_orderingcode;?></td>
		
		<td class="sample_lable"><?=$txt_qty?></td>
		<td class="sample_lable"><?=$txt_rd_take_comp_from?></td>
		<td class="sample_lable"><?=$BOM?></td>
		<td class="sample_lable"><?=$txt_payment_condition?></td>
		<td class="sample_lable"><?=$txt_test_report_require?></td>
		<td class="sample_lable"><?=$txt_type_of_compressor?></td>
		<td class="sample_lable"><?=$txt_request_rd_to_sent_to_store_within?></td>
		<?/*
		<td class="sample_lable">
			<?if($txt_other_file != ''){?>
				<a href="javascript:;" style="font-size:11px" onclick="window.open('file.php?faf=<?=parameter_encode("sr_other_file_folder,".$txt_other_file)?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');"><img src="image/search16.png" border='0'></a>
				<?
			}else{?>
				-
				<?
			}?>
		</td>
		*/?>
		<td class="sample_lable"><?=$answer_date?></td>
		<td class="sample_lable"><?=$txt_rd_send_to_store?></td>
		<td class="sample_lable"><?=$txt_new_date_to_store_rev1?></td>
		<td class="sample_lable"><?=$txt_new_date_to_store_rev2?></td>
		<td class="sample_lable"><?=$txt_serial_no?></td>
		<td class="sample_lable" style="width:100px"><?=$txt_store_date?></td>
		<td class="sample_lable"><?=$txt_remark?></td>
	</tr>
	<!-- <tr><th colspan="100%" style="height:5px"></th></tr> -->
</table>	
