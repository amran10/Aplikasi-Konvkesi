<?php 
ob_start();
session_start();
include("$DOCUMENT_ROOT/lama/s/config.php");
//include("header.php");

$debug=0;
$act_api = $_GET['act_a'];

if($act_api=='do_foto'){
    	$values2 = '';
	$columns 	="keterangan,foto,set_foto";
	 if(file_get_contents('php://input')){
        $key = (Array)json_decode(file_get_contents('php://input'));
        
        $keterangan = $key['keterangan'];
        $foto = $key['foto'];
    	$set_foto = $key['set_foto'];
   
    $sql_insert ="insert into tbl_galery values ('".$keterangan."','".$foto."','".$set_foto."')";
     //$f->pre($sql_insert);
    $result=$db->Execute($sql_insert);

    if (!$result){
        print $db->ErrorMsg();
        die($sql_insert);
    }
    
    $f->insert_log("INSERT $title. $primary_key ".($$primary_key));
    
    $sql="
        select foto,keterangan from tbl_galery";
    //echo $sql; die();
    $result = $db->Execute($sql);
    $dt_opt = json_encode($result->getAll());
    exit($dt_opt);
    }
}

?>