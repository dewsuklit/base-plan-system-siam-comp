<? 
session_start();


if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

require_once( '../DBconfig/dbConfig2.php' );

$id_sr = $_REQUEST['id_sr'];
$del = $_REQUEST['del'];
if($del==''){$del=-1;}
if($del != -1){
	$Querydel = "select engineer_rd_file from sr where id = '$id_sr'"; 
	$Resultdel = $db2->query($Querydel);
	$linedel=mysqli_fetch_array($Resultdel);
	$all_file = $linedel['engineer_rd_file'];
	$separate_del = explode("/",$all_file);
	mysqli_free_result($Resultdel);
	$new_update = "";
	if($del == 0){ // check == delete first file
		if(count($separate_del) <= 1){ // have more 1 file
			$deleteName = $separate_del[$del];
			$new_update = "";
		}else{
			$deleteName = $separate_del[$del]."/";
			$new_update = str_replace($deleteName,"",$all_file);
		}
	}else{
		$deleteName = "/".$separate_del[$del];
		$new_update = str_replace($deleteName,"",$all_file);
	}
	$Queryup = "update sr set engineer_rd_file = '$new_update' where id = '$id_sr'"; 
	$Resultup = $db2->query($Queryup);
}

?>
<table cellspacing="0" cellpadding="0">
<?$numrow = 0;
	$Query = "select * from sr where id = '$id_sr'"; 
	$Result = $db2->query($Query);
	$line=mysqli_fetch_array($Result);
	$separate_file = explode("/",$line['engineer_rd_file']);
	if($separate_file[0] != ""){
		for($i=0;$i<count($separate_file);$i++){?>
			<tr>
				<td class="name"><?=$i+1?>.&nbsp;&nbsp;</td>
				<td class="name" style="cursor:pointer" onclick="window.open('uploadfile/sr_engineer_rd_folder/<?echo $separate_file[$i];?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes')"><?echo $separate_file[$i];?></td>
				<td class="name" style="cursor:pointer">&nbsp;<img class="delete-icon" src="../../../image/deletetodolist.png" onclick="if(confirm('Do you want to delete this file?')){startRequest(<?=$i?>);}"></td>
			</tr>
		<?$numrow++;
		}
	}
	if($numrow == 0){?>
		<tr>
			<td colspan="100%">No File</td>
		</tr>
<?}mysqli_free_result($Result);?>
</table>




