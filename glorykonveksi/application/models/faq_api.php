<?php 
ob_start();
session_start();
include("$DOCUMENT_ROOT/s/config.php");

$act_api = $_GET['act_a'];

if($act_api=='do_faq_api'){
    $values2 = '';
    $columns    ="pertanyaan,jawaban";
    if(file_get_contents('php://input')){
        $key = (Array)json_decode(file_get_contents('php://input'));
        
        $pertanyaan = $key['pertanyaan'];
        $jawaban = $key['jawaban'];
    
    $sql_insert ="insert into sup_faq (pertanyaan,jawaban) values ('".$pertanyaan."','".$jawaban."')";
     //$f->pre($sql_insert);
    $result=$db->Execute($sql_insert);

    if (!$result){
        print $db->ErrorMsg();
        die($sql_insert);
    }
    
    $f->insert_log("INSERT $title. $primary_key ".($$primary_key));
    
    $sql="
        select pertanyaan, jawaban from $dbname_hris.sup_faq";
    //echo $sql; die();
    $result = $db->Execute($sql);
    $dt_opt = json_encode($result->getAll());
    exit($dt_opt);
    }
}


?>

