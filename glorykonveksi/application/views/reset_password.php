<?
ob_start();
session_start();
include("$DOCUMENT_ROOT/s/config.php");
// $f->pre($_SESSION);
#echo $template->basicheader();
include("header.php");
#$template->navmenu();
#echo"<link href=\"http://$HTTP_HOST/css/frame.css\" rel=\"stylesheet\" type=\"text/css\">";
#   echo base64_encode("REQ-001367");
function status_pelamar(){
	global $db;
	$status_pelamar_array=array();
	$sql="select kd_status_pelamar,status_pelamar from rec_status_pelamar";
	$result=$db->Execute($sql);
	if(!$result) print $db->ErrorMsg();
	while($row=$result->FetchRow()){
		foreach($row as $key=>$val){
			$key=strtolower($key);
			$$key=$val;
		}
		$status_pelamar_array[$kd_status_pelamar]=$status_pelamar;
	}
	return $status_pelamar_array;
}
$status_pelamar_array=status_pelamar();
?>
<script type="text/javascript">
    function openFrame(base_url){   
       window.location.href="index.php?act=login1&param="+base_url;
    }   

    function resizeIframe(obj){
        {obj.style.height = 0;};
        {obj.style.height = (obj.contentWindow.document.body.scrollHeight+30) + 'px';}
    }
    function open_index(){
        window.open('index.php','_blank');
        //window.location.href="index.php";
    }
 
</script>
<?

//get berita dari knowledge base HCMS
/*$sql="  select a.judul as judul_berita,a.artikel as artikel_berita,file_1,filename_1,keterangan_1 from $dbname_hris.sup_berita a
                    left join $dbname_hris.sup_berita_category b on a.bca_id=b.bca_id
                    left join $dbname_hris.sup_berita_sub_category c on a.bsa_id=c.bsa_id
                    where lower(b.kategori)='portal' and lower(c.sub_kategori)='berita'
                    order by a.ber_id desc";
                    echo $sql; 
$result=$f->get_last_record($sql);
foreach($result as $key => $val)$$key=trim($val);
$file_1=str_replace("kb_file/berita","banner",$file_1);*/

if($act=='aktivasi'){
    $status='1';

    $email=$_GET['email'];
    if(isset($_GET['req_id'])){
        $url="user.php?req_id=".$_GET['req_id'];
    }else{
        $url="user.php";
    }
    #echo $url;
    #$f->pre($_GET);
    $sql="update rec_user_web set status='$status' where email='$email' ";
    $result=$db->Execute($sql);
    if (!$result){
        print $db->ErrorMsg();
        die($sql);
    } 
    echo"
        <div class=clear style='margin-top:25px;'></div>
        <div class='warp'>
        <center>
            <table style='width:300px;border:2px solid #52db05;' >
                <tr>
                    <td valign=top ><img src=/i/button_correct.png></td>
                    <td>
                        SUKSES:<ul><li>Akun Anda Telah Aktif Silakan Login <a href='javascript:void(0)' onClick='open_index()' >disini</a></li></ul>
                    </td>
                </tr>
            </table>
        </center>
        </div>
    ";  
    #die();
}elseif($act=='login1'){
    //src=\"user.php?act=register\" 
    #$f->pre($_POST);
    if(!empty($_GET['param'])){
        $base_url=base64_decode($_GET['param']);    
    }else{
        $base_url=base64_decode($_POST['param'])."&email=".base64_encode($_POST['email'])."&password=".base64_encode($_POST['password']);
    }
    
    #unset($_GET['param']);
    #echo $base_url;
    #die();
    echo"<iframe name='myframe' id='myframe' onload='javascript:resizeIframe(this);' frameborder=\"0\"  style=\"width:100%;height:100%;\" scrolling=\"no\"  src=".$base_url."></iframe>";   
}elseif($act=='do_login'){
    #$f->pre($_POST,1);
    if(empty($_POST)){
        #$f->pre($_GET);
        $email=base64_decode($_GET['email']);
        $password=base64_decode($_GET['password']);
    }
    $sql="select id as id_user,password as xpassword,status,nama_depan,nama_tengah,nama_belakang, email from rec_user_web where email='$email'";    
    $result=$f->get_last_record($sql);
    foreach($result as $key=>$val) $$key=$val;
    if(empty($id_user)){
        $error .="<li>Email Anda tidak cocok.</li>";
    }elseif(md5($password) != $xpassword){
        $error .="<li>Password Anda tidak cocok.</li>";
    }elseif($status=='0'){
        $error .="<li>Status User belum aktif.</li>";       
    }/*else{
        echo"LOGIN SUKSES";
    }*/
    
    if($error){
        echo"
            <div class=clear style='margin-top:25px;'></div>
            <div class='warp'>
            <center>
                <table style='width:300px;border:2px solid #C00000;' >
                    <tr>
                        <td valign=top ><img src=/i/stop.png></td>
                        <td>
                            ERROR:<ul>$error</ul>
                            <a href=javascript:void(0); onClick=window.history.go(-1);>&larr; Kembali</a>
                        </td>
                    </tr>
                </table>
            </center>
            </div>
        ";
        die();
    }else{  
        //Create SESSION

        $nama_depan=$db->getOne("select nama_depan from rec_user_web where email='$email'");
        if($nama_depan ==""){
            $_SESSION['login_nip']=$email;
            $exp = explode('@',$email);
            $_SESSION['nama_user']=strtoupper($exp[0]);
        }else{
            $_SESSION['login_nip']=$email;
            $_SESSION['nama_user']=strtoupper($nama_depan);
        }
        
        //cek apakah data pribadinya sudah diupdate/diisi belum?
        $sql_cek = "select email,nama_depan,tempat_lahir, tanggal_lahir, jns_kelamin_peg, kd_agama, kd_negara, no_identitas, tanggal_identitas from rec_pelamar where email='".$_SESSION['login_nip']."'";
        $result_cek = $f->get_last_record($sql_cek);
        #$f->pre($sql_cek,1);
        foreach($result_cek as $key_cek=>$val_cek) $$key_cek=$val_cek;
        if($email =="" or $nama_depan =="" or $tempat_lahir =="" or $tanggal_lahir =="" or $jns_kelamin_peg =="" or $kd_agama =="" or $no_identitas =="" or $tanggal_identitas ==""){
            /*header("location:user_info.php?act=update&s=".base64_encode('user_baru')."");
            ob_end_flush();
            exit;*/
            // echo '<script type="text/javascript">window.location.href = "user_info.php?act=update&s='.base64_encode('user_baru').'; </script>';
            header("location:user_info.php?act=update&s=".base64_encode('user_baru'));
            exit();
        }elseif($_POST['req_id'] !=''){
            $req_id = $_POST['req_id'];
            /*header("location:vacant.php?req_id=$encod_param");
            ob_end_flush();
            exit;*/
            #echo '<script type="text/javascript">window.location.href = "vacant.php?req_id=$req_id"; </script>';
            echo '<script type="text/javascript">window.parent.location.href = "index.php?act=login1&p='.base64_encode("vacant.php?req_id=$req_id").'"; </script>';
            #echo '<script type="text/javascript">window.parent.location.href = "index.php?act=login11"; </script>';
            exit();
        }else{
            /*header("location:/home.php");
            ob_end_flush();
            exit;*/
            #$f->pre($_SESSION);
            #echo '<script type="text/javascript">window.parent.location.href = "index.php?act=login1&param='.base64_encode("apply.php").'"; </script>';
            echo '<script type="text/javascript">window.parent.location.href = "index.php?act=login1"; </script>';
            exit();
        }
    }
}elseif($act=='do_reset_password'){
    include("header.php");
    $sql_check="select email from rec_user_web where email='$email' and status='1'";    
    if($f->check_exist_value($sql_check)==false){
        $error .="<li>Email Anda tidak terdaftar di Portal Karir.</li>";
    }
    
    if($error){
        echo"
            <center>
                <table style='width:300px;border:2px solid #C00000;margin-top:50px;' >
                    <tr>
                        <td valign=top ><img src=/i/stop.png></td>
                        <td>
                            ERROR:<ul>$error</ul>
                            <a href=javascript:void(0); onClick=window.location.href='index.php?act=".base64_encode("lupa_password")."'>&larr; Kembali</a>
                        </td>
                    </tr>
                </table>
            </center>
        ";
        die();
    }else{
        $nama_depan=$db->getOne("select nama_depan from rec_user_web where email='$email' and status='1'");
        $new_password=substr(md5(rand()),0,7);
        $content="
                <body style=\"margin: 10px;\">
                <div style=\"width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">
                    Yth. Sdr/i $nama_depan<br>
                    Di Tempat<br><br>
                    Terima kasih Anda telah mendaftar di Portal Karir PT Wijaya Karya (Persero) Tbk.<br>
                    Login Anda adalah : $email<br>
                    Password Anda adalah : $new_password<br><br>
                    <b>Hormat kami,</b><br>
                    <b>Recruitment Team </b><br>
                </div>
                </body>
            ";

            $name="Recruitmen";
            $subject="Reset Password Portal Karir WIKA";
            $success_msg="<li>Password Baru Anda Sudah di Kirim.</li><li>Silakan Periksa Email Anda untuk melihat Password baru Anda.</li>";
            $error_msg="<li>Tidak Berhasil Kirim Email!</li>";
            $rs_mail=$m->sendmail($email,$name,$subject,$content,$success_msg,$error_msg);

            if($rs_mail['flag_mail']=="0"){
                $sql="update rec_user_web set password='".md5($new_password)."' where email='$email'";
                if($debug)$f->pre($sql);
                $result=$db->Execute($sql);
                if(!$result)print $db->ErrorMsg();
            }   
            echo $rs_mail['output_mail'];
    }
}elseif($act=='do_change_password'){
    include("header.php");
    $sql_check="select password from rec_user_web where email='".$_SESSION['login_nip']."'";
    $password_old=$db->getOne($sql_check);
    if($password_old!=md5(trim($password))){
        $error="<li>Password Lama Anda tidak Cocok.</li>";
    }

    if($error){
        echo"
            <center>
                <table style='width:300px;border:2px solid #C00000;margin-top:50px;' >
                    <tr>
                        <td valign=top ><img src=/i/stop.png></td>
                        <td>
                            ERROR:<ul>$error</ul>
                            <a href=javascript:void(0); onClick=window.history.back();>&larr; Kembali</a>
                        </td>
                    </tr>
                </table>
            </center>
        ";
        die();
    }else{
        $sql="update rec_user_web set password='".md5($password_baru)."' where email='".$_SESSION['login_nip']."'";
        if($debug)$f->pre($sql);
        $result=$db->Execute($sql);
        if(!$result)print $db->ErrorMsg();
        echo"
            <center>
                <table style='width:300px;border:2px solid #52db05;margin-top:50px;' >
                    <tr>
                        <td valign=top ><img src=/i/button_correct.png></td>
                        <td>
                            SUKSES:<ul><li>Password Berhasil di ubah.</li></ul><a href=\"javascript:void(0);\" onClick=\"window.history.go(-2);\">&larr; Kembali</a>
                        </td>
                    </tr>
                </table>
            </center>
        ";  
    }

}elseif(base64_decode($act)=='detail'){
    $req_id=base64_decode($q);
    $curr_date=date('Y-m-d',time());

    $sql="select * from rec_permintaan_adv_portal_karir where req_id='$req_id' and tanggal_selesai >= '$curr_date'";
    // $f->pre($sql);
    $result=$f->get_last_record($sql);
    $sql_ket="select convert(text,keterangan) from rec_permintaan_adv_portal_karir where req_id='$req_id' and tanggal_selesai >= '$curr_date'";
    $keterangans = $db->getOne($sql_ket);
    // var_dump($keterangans);
    // $f->pre($result);
    foreach($result as $key=>$val){
        $key=strtolower($key);
        if(eregi("tanggal|tgl",$key)){
            $val=$f->convert_date($val,1);
        }
        $$key=trim($val);
    }
    $job_description = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($job_description,"\n")).'</li>';
    $requirement = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($requirement,"\n")).'</li>';

    echo"
    <html>
    <head>
        <title>PORTAL KARIR PT WIJAYA KARYA (PERSERO) TBK.</title>
        <!---
		<link rel='shortcut icon' href='/favicon.png'/>
        <link rel=\"icon\" href= \"/animated_favicon.gif\" type=\"image/gif\">
		--->
        <style>

            /*Map Detail created by Dicky 02/03/2017*/

            /* Always set the map height explicitly to define the size of the div
               * element that contains the map. */
              #map {
                height: 60%;
                width:95%;
              }
              /* Optional: Makes the sample page fill the window. */
              html, body {
                height: 100%;
                margin: 0;
                padding: 0;
              }

            input[type='button'] {
                cursor: pointer; padding: 3px 7px; border-radius: 3px; border: 0px currentColor; border-image: none; height: 26px; color: #FFFFFF; background: linear-gradient(#71bf44 0%, #00ac4f 100%) 100% 100%; font-family: Myriad-CnWeb !important;
            }
            input[type='button']:hover {
                background: url('/i/phiro/nav_bg.png'); color:#FFF;
            }
        </style>
    </head>
    <body>
        <div class=clear style='margin-top:25px;'></div>
        <div class='warp'>
            <div class=subtitle style=margin-bottom:5px;><strong>$nm_alias_posisi</strong></div>
            <div class=clear style='margin-top:25px;'></div>
            <div class=row>

                <div class=col>
                    <P>
                    <SPAN style=\"color: rgb(255, 255, 255);\">
                        <STRONG>Informasi Posisi</STRONG><br>
                        <small><STRONG><i>Application Deadline ".$f->convert_date_id($tanggal_selesai,1)."</i></STRONG></small>
                        <ul style='padding-left:15px;margin-top:-25px;'>
                            <li style='padding:10px;'>
                                <p><strong>Deskripsi Pekerjaan</strong></p>
                                <ul style='padding-left:20px;' >$job_description</ul>
                            </li>
                            <li style='padding:10px;margin-top:-15px;'>
                                <p><strong>Persyaratan</strong></p>
                                <ul style='padding-left:20px;' >$requirement</ul>
                            </li>
                        </ul>
                    </SPAN>
                </P>
                <p>".nl2br($keterangans)."</p>
            </div>
            <div class=row>
            <p><strong>Peta Lokasi</strong></p>
                <div id='map'></div>
                <script>
                  var customLabel = {
                    restaurant: {
                      label: 'R'
                    },
                    bar: {
                      label: 'B'
                    }
                  };

                    function initMap() {
                    var map = new google.maps.Map(document.getElementById('map'), {
                      center: new google.maps.LatLng($lat, $lon),
                      zoom: 14
                    });
                    var infoWindow = new google.maps.InfoWindow;

                      // Change this depending on the name of your PHP or XML file
                      downloadUrl('http://$HTTP_HOST/wsmap.php?req_id=$req_id', function(data) {
                        var xml = data.responseXML;
                        var markers = xml.documentElement.getElementsByTagName('marker');
                        Array.prototype.forEach.call(markers, function(markerElem) {
                          var name = markerElem.getAttribute('name');
                          var address = markerElem.getAttribute('address');
                          var type = markerElem.getAttribute('type');
                          var point = new google.maps.LatLng(
                              parseFloat(markerElem.getAttribute('lat')),
                              parseFloat(markerElem.getAttribute('lng')));

                          var infowincontent = document.createElement('div');
                          var strong = document.createElement('strong');
                          strong.textContent = name
                          infowincontent.appendChild(strong);
                          infowincontent.appendChild(document.createElement('br'));

                          var text = document.createElement('text');
                          text.textContent = address
                          infowincontent.appendChild(text);
                          var icon = customLabel[type] || {};
                          var marker = new google.maps.Marker({
                            map: map,
                            position: point,
                            label: icon.label
                          });
                        });
                      });
                    }



                  function downloadUrl(url, callback) {
                    var request = window.ActiveXObject ?
                        new ActiveXObject('Microsoft.XMLHTTP') :
                        new XMLHttpRequest;

                    request.onreadystatechange = function() {
                      if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                      }
                    };

                    request.open('GET', url, true);
                    request.send(null);
                  }

                  function doNothing() {}
                </script>
                <script async defer
                src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAXrjsHxeN1_LqSlDAprBsI84XxnCTJdng&callback=initMap'>
                </script>
            </div>
        </div><br>";
        echo"       
    </body>
    </html>";
}elseif(base64_decode($act)=='lupa_password'){
    include("header.php");
    echo"
    <script>
        function validasi_lupa(){
            if(document.f1.email.value==''){
                alert('Isi Email !');
                document.forms['f1'].email.focus();
                return false;
            }else{
                return true;
            }
        }
    </script>
    <table id=wrapper cellspacing=0 width=100%>
        <tr>
            <td valign=top colspan=4>
                <div id=dim><img src='$file_1' alt='$filename_1' title='$keterangan_1' width=100%></div><br>
                <div class='warp'>
                    <p style='width:100%;text-align:center;'><strong>$judul_berita</strong></p><br>
                    <p style='text-align:justify;'>$artikel_berita</p>
                </div><br>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td valign=top >
                <div class=warp>
                    <div class=subtitle style=margin-bottom:5px;><strong>LUPA PASSWORD</strong></div>
                    <table cellspacing=0 cellpadding=0 width=100%>
                        <tr>
                            <td align=center><h1>Silakkan masukkan Email yang Anda gunakan.<br>Kami Akan mengirimkan Password baru ke Email Anda.</h1></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td align=center>
                                <form name=f1 id=f1 method=post onSubmit='return validasi_lupa(this);'>
                                    <table>
                                        <input type=hidden name=act value='do_reset_password'>
                                        <tr>
                                            <td>Email</td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name=email value='$email'  placeholder='user@domain.com' onBlur=check_email(this.value,'email'); size=33></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
										 <tr>
											<td colspan=2><div class=\"g-recaptcha\" data-sitekey=\"6LcWjz8UAAAAAJ2a3wcGsMCv_k9v1c9kTqh9ETA4\"></div></td>
										</tr>
                                        <tr>
                                            <td align=center colspan=2>
                                                <input type=button onClick='window.history.go(-1)' value='Kembali'>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type=submit value='Submit'>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
								<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'async defer>
								</script>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        
    </table>";
}elseif($act=='change_password'){
    include("header.php");
    echo"
    <script>
        function validasi(){
            if(document.f1.password.value==''){
                alert('Isi Password Lama Anda !');
                document.forms['f1'].password.focus();
                return false;
            }else if(document.f1.password_baru.value==''){
                alert('Isi Password Baru Anda !');
                document.forms['f1'].password_baru.focus();
                return false;
            }else if(document.f1.conf_password_baru.value==''){
                alert('Isi Konfirmasi Password Baru Anda !');
                document.forms['f1'].conf_password_baru.focus();
                return false;
            }else if(document.f1.password_baru.value != document.f1.conf_password_baru.value){
                alert('Password Baru dan Konfirmasi Password Baru Tidak Sama !');
                document.forms['f1'].conf_password_baru.focus();
                return false;
            }else{
                return true;
            }
        }
    </script>
    <table id=wrapper cellspacing=0 width=100%>
        <tr>
            <td valign=top colspan=4>
                <div id=dim><img src='$file_1' alt='$filename_1' title='$keterangan_1' width=100%></div><br>
                <div class='warp'>
                    <p style='width:100%;text-align:center;'><strong>$judul_berita</strong></p><br>
                    <p style='text-align:justify;'>$artikel_berita</p>
                </div><br>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td valign=top>
                <div class=warp>
                    <div class=subtitle style=margin-bottom:5px;><strong>UBAH PASSWORD</strong></div>
                    <table cellspacing=0 cellpadding=0 width=100%>
                        <tr>
                            <td align=center><h1>Silakkan masukkan Password Lama dan Password Baru Anda.</h1></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td align=center>
                                <form name=f1 id=f1 method=post onSubmit='return validasi(this);'>
                                    <table style='border:1px solid #ccc;padding:10px;'>
                                        <input type=hidden name=act value='do_change_password'>
                                        <tr>
                                            <td>Password Lama</td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type=password name=password placeholder='Password Lama'  size=33></td>
                                        </tr>
                                        <tr>
                                            <td>Password Baru</td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type=password name=password_baru placeholder='Password Baru'  size=33></td>
                                        </tr>
                                        <tr>
                                            <td>Konfirmasi Password Baru</td>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type=password name=conf_password_baru placeholder='Konfirmasi Password Baru'  size=33></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td align=center colspan=2>
                                                <input type=button onClick='window.history.go(-1)' value='Kembali'>
                                                &nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value='Submit'>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>";
}else{
    //echo "path dokumen :".$_document_root_hris;
    echo "
        <script type=\"text/javascript\">
            function validasi(){
                if(document.f1.email.value==''){
                    alert('Isi Email !');
                    document.forms['f1'].email.focus();
                    return false;
                }else if(document.f1.password.value==''){
                    alert('Isi Password !');
                    document.forms['f1'].password.focus();
                    return false;
                }else{
                    return true;
                }
            }
        </script>
    ";

    if(empty($_SESSION['login_nip'])){
        if($act=='next'){
            $req_id=$_GET['param'];
        }
        $content_login="
            <form method=post name=f1 id=f1 onSubmit='return validasi(this);'>
                <table cellpadding=0 cellspacing=0 width=100%>
                    <input type=hidden name=act value=do_login>
                    <input type=hidden name=req_id value='$req_id'>
                    <input type=hidden name=challange value='$challenge'>                                           
                    <tr>
                        <td colspan=3><div class='subtitle'><strong>LOGIN</strong></div></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td style=\"vertical-align:top;\">Email </td>
                        <td style=\"vertical-align:top;\">:&nbsp;&nbsp;</td>
                        <td><input style='color:#000' type=text name=email value='$email' onBlur=check_email(this.value,'email');></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td style=\"vertical-align:top;\">Password</td>
                        <td style=\"vertical-align:top;\">:&nbsp;&nbsp;</td>
                        <td><input style='color:#000' type=password name=password value='$password'></td>
                    </tr>
                    <tr> 
                        <td style=\"vertical-align:top;padding-top:5px;padding-bottom:5px;\" colspan=3>
                            <a style='text-decoration:none;' href='javascript:void(0);' onClick=\"window.location.href='$PHP_SELF?act=".base64_encode("lupa_password")."'\">Lupa Password?</a>
                        </td>
                    </tr>
                    <tr>
                    <td colspan=3><div class=\"g-recaptcha\" data-sitekey=\"6LcWjz8UAAAAAJ2a3wcGsMCv_k9v1c9kTqh9ETA4\"></div></td>
                    </tr>
                    <tr> 
                        <td style=\"vertical-align:top;\" colspan=3>
                            <input style=\"width:100%;\" type=submit value='Login'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=3 style='padding-top:5px;'><small>Jika Anda belum memiliki Akun silakan daftar <a href=\"javascript:void(0);\" onClick=window.location.href='user.php?act=register'>di sini</a><small></td>
                    </tr>
                </table>
            </form>
        ";
        #$welcome="Hi, ".$_SESSION['nama_user'];
        //$control= $f->btn_link_blue('user.php?act=list','Update Profile')." | ".$f->btn_link_blue('logout.php','Logout');
        #$control= "<a href='apply.php' class=link_style>Apply List</a> &nbsp; <a href='user.php?act=list' class=link_style>Update Profile</a> &nbsp; <a href='logout.php' class=link_style>Logout</a>";
    }else{
        $sql_user="SELECT
                    a.no_identitas,
                    a.tanggal_identitas,
                    a.tempat_lahir,
                    a.tanggal_lahir,
                    a.jns_kelamin_peg,
                    a.kd_agama,
                    a.kd_negara,
                    a.alamat_tempat_tinggal,
                    a.kd_propinsi_tempat_tinggal,
                    a.kd_kabupaten_tempat_tinggal,
                    a.kd_kecamatan_tempat_tinggal,
                    a.kd_kelurahan_tempat_tinggal,
                    a.rw_tempat_tinggal,
                    a.rt_tempat_tinggal,
                    a.kd_sts_kawin,
                    a.hp,
                    a.ekspektasi_gaji,
                    a.kesiapan_kerja,
                    b.kd_hubungan_keluarga,
                    b.nm_keluarga,
                    b.no_induk_kependudukan,
                    b.jns_kelamin_peg AS jns_kelamin_keluarga,
                    b.kd_agama AS kd_agama_keluarga,
                    c.kd_pend_umum,
                    c.nm_lemb_pend_form,
                    d.tmt_masa_kerja,
                    d.nama_perusahaan,
                    d.jabatan_perusahaan_lama,
                    e.response,
                    f.response as response_b
                FROM
                    REC_PELAMAR a
                    LEFT JOIN SPG_RIWAYAT_KELUARGA b ON a.email = b.email
                    LEFT JOIN REC_RIWAYAT_PEND_FORMAL c ON b.email = c.email
                    LEFT JOIN rec_masa_kerja d ON a.email = d.email
                    LEFT JOIN rec_question_response e ON a.email = e.email and e.id_question='intern_perusahaan_4' 
                    LEFT JOIN rec_question_response f ON a.email = f.email and f.id_question='lain_lain_1' 
                where a.email ='".$_SESSION['login_nip']."'";
        // $f->pre($sql_user);
        $res_user = $db->Execute($sql_user);
        $row_user = $res_user->FetchRow();
        // $f->pre($row_user);
        foreach ($row_user as $key=>$val){
            // $f->pre($key);
            $key=strtolower($key);
            if(eregi("tanggal|tgl",$key)){
                $val=$f->convert_date($val,1);
            }
            $$key=trim($val);
        }
        // echo $rep_id;
        // $f->pre($tempat_lahir);
        // $f->pre($no_identitas);
        // $f->pre($tanggal_identitas);
        // $f->pre($tanggal_lahir);
        // $f->pre($tanggal_lahir);
        // $f->pre($jns_kelamin_peg);
        // $f->pre($kd_agama);
        // $f->pre($alamat_tempat_tinggal);
        // $f->pre($kd_propinsi_tempat_tinggal);
        // $f->pre($kd_kabupaten_tempat_tinggal);
        // $f->pre($kd_kecamatan_tempat_tinggal);
        // $f->pre($kd_kelurahan_tempat_tinggal);
        // $f->pre($rw_tempat_tinggal);
        // $f->pre($rt_tempat_tinggal);
        // $f->pre($kd_sts_kawin);
        // $f->pre($hp);
        // $f->pre($ekspektasi_gaji);
        // $f->pre($kesiapan_kerja);
        // $f->pre($kesiapan_kerja);
        // $f->pre($kd_hubungan_keluarga);
        // $f->pre($nm_keluarga);
        // $f->pre($no_induk_kependudukan);
        // $f->pre($jns_kelamin_keluarga);
        // $f->pre($kd_agama_keluarga);
        // $f->pre($kd_pend_umum);
        // $f->pre($nm_lemb_pend_form);

        
        $sql = "select path picture from rec_pelamar where email = '".$_SESSION['login_nip']."' ";

        $result = $db->Execute($sql);


        $row = $result->FetchRow();
        $picture = trim($row['picture']);

        if(!empty($picture) || $picture!=''){
           $img_picture = "<img src=\"$picture\" style=\"width:120px;border:1px dotted #000;\" >";
        }else{
           $img_picture= "<img src=\"i/phiro/default_user.gif\" style=\"width:120px;border:1px dotted #000;\" >";
        }

        $content_login="
            <table cellpadding=0 cellspacing=0 width=100%>                                          
                <tr>
                    <td><div class='subtitle'><strong>WELCOME</strong></div></td>
                </tr>         
                <tr>
                    <td >
                        <p style='padding:5px;text-align:center;'>$img_picture</p>
                        <p style='margin-top:5px;padding:5px;text-align:center;'>".$_SESSION['nama_user']."</p>
                        <p style='margin-top:5px;padding:5px;text-align:center;'><input type=button style='width:80%;' value='Lihat Resume Saya' onClick=\"window.location.href='cetak_resume.php?flag=1'\"></p>
                        <p style='margin-top:5px;padding:5px;text-align:center;'><input type=button style='width:80%;' value='Ubah Password' onClick=\"window.location.href='$PHP_SELF?act=change_password'\"></p>
                    </td>
                </tr>
            </table><br>
        ";
        //$control= $f->btn_link_blue('user.php','Login');
        #$control= "<a href='user.php' class=link_style>Login</a>";
    }
    
    echo"
        <table id=wrapper cellspacing=0 width=100%>
            <tr>
                <td valign=top colspan=3 width=100%>
                    <!--<div class=clear style='margin-top:25px;'></div>
                    <div id=dim><img src='$file_1' alt='$filename_1' title='$keterangan_1' width=100%></div><br>
                    <div class='warp'>
                        <p style='width:100%;text-align:center;'><strong>$judul_berita</strong></p><br>
                        <p style='text-align:justify;'>$artikel_berita</p>
                    </div><br>-->";

                    if(!empty($query)) {
                        $query  = urldecode($query);
                        $query  = strtolower(trim($query));
                        $query  = htmlspecialchars($query,ENT_QUOTES);
                        $search = "and a.judul like '%$query%'";  
                    }

                    $sql="select a.file_1,a.file_2,a.file_3,a.file_4,a.file_5,a.file_6,
							a.filename_1,a.filename_2,a.filename_3,a.filename_4,a.filename_5,a.filename_6,
							a.keterangan_1,a.keterangan_2,a.keterangan_3,a.keterangan_4,a.keterangan_5,a.keterangan_6,
							a.judul,a.artikel,a.tanggal_buat,c.sub_kategori
							from hris_wika.dbo.sup_berita a
							left join hris_wika.dbo.sup_berita_category b on a.bca_id=b.bca_id
							left join hris_wika.dbo.sup_berita_sub_category c on a.bsa_id=c.bsa_id
							where lower(b.kategori)='portal karir' and (lower(c.sub_kategori)='berita' or lower(c.sub_kategori)='pengumuman test')
                            $search
							order by a.ber_id desc";
                   	// $f->pre($sql);

                    $total = $f->count_total("hris_wika.dbo.sup_berita a
                            left join hris_wika.dbo.sup_berita_category b on a.bca_id=b.bca_id
                            left join hris_wika.dbo.sup_berita_sub_category c on a.bsa_id=c.bsa_id
                            where lower(b.kategori)='portal karir' and (lower(c.sub_kategori)='berita' or lower(c.sub_kategori)='pengumuman test')",
                            $search);
                    $sort  = (!$sort)?"desc":$sort;
                    $page  = (!$page)?"0":$page;
                    $start  = (!$start)?"0":$start;
                    $num  = (!$num)?"6":$num;
                    $order  = (!$order)?"ber_id":$order;
                    $start=($page-1)*$num;
                    if($start < 0) $start='0';
                    $result=$db->SelectLimit($sql,$num,$start);
                    // $f->pre("num=>".$num." start=>".$start);
                    // $f->pre(array("link"=>$PHP_SELF."?order=$order&sort=$sort&type=$type&act=","page"=>$page,"total"=>$total,"num"=>"$num","show_total"=>1));
                    echo"<br>
					<div class='warp'><h2><marquee behavior='scroll' direction='left' onmouseover='this.stop();' onmouseout='this.start();'>Waspada Terhadap Penipuan yang mengatasnamakan PT Wijaya Karya (Persero) Tbk. WIKA tidak pernah melakukan kerjasama dengan biro perjalanan manapun, dan meminta peserta untuk membayar biaya akomodasi untuk kegiatan rekrutmen.&nbsp;Pengumuman rekrutmen resmi WIKA hanya melalui <a href='http://www.wika.co.id/category/career-news' target='_blank'>http://www.wika.co.id/category/career-news</a> atau <a href='http://webjob.wika.co.id' >http://webjob.wika.co.id.</a>&nbsp;Informasi lebih lanjut dapat menghubungi 021 80679200 ext. 50648. Biro Rekrutmen dan Penempatan - Departemen Human Capital WIKA.</marquee></h2></div>
                    <div class='warp'>
                        <p style='width:100%;text-align:center;font-size:16px;background:url(/i/phiro/nav_bg.png) repeat-x 100% 100%;color:#fff;padding-top:10px;padding-bottom:10px;'><strong><u>BERITA REKRUITMEN DAN PENGUMUMAN TEST</u></strong></p><br>
                        <p style='text-align:justify;'>";
                        $f->search_box1($query);
                            while($row=$result->FetchRow()){
                                // $f->pre($row);
                                foreach($row as $key=>$val){
                                    $$key=trim($val);
                                }
                                #$f->pre($file_1);
                                $file_1=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_1);
                                $file_2=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_2);
                                $file_3=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_3);
                                $file_4=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_4);
                                $file_5=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_5);
                                $file_6=str_replace("C:\inetpub\wwwroot\hris_wika","https://hcis.wika.co.id",$file_6);
                                echo "<div class=M8_link>
                                		<strong style='font-size:14px;'>$judul</strong><br>
                                        $artikel<br>
                                        Lampiran : <br><li>".$f->check_file_type($filename_1,$file_1,"_blank")."&nbsp;&nbsp;<a href='$file_1' target='_blank'>$filename_1</a>
                                        ".((!empty($file_2))?"<br><li>".$f->check_file_type($filename_2,$file_2,"_blank")."&nbsp;&nbsp;<a href='$file_2' target='_blank'>$filename_2</a>":"")."
                                        ".((!empty($file_3))?"<br><li>".$f->check_file_type($filename_3,$file_3,"_blank")."&nbsp;&nbsp;<a href='$file_3' target='_blank'>$filename_3</a>":"")."
                                        ".((!empty($file_4))?"<br><li>".$f->check_file_type($filename_4,$file_4,"_blank")."&nbsp;&nbsp;<a href='$file_4' target='_blank'>$filename_4</a>":"")."
                                        ".((!empty($file_5))?"<br><li>".$f->check_file_type($filename_5,$file_5,"_blank")."&nbsp;&nbsp;<a href='$file_5' target='_blank'>$filename_5</a>":"")."
                                        ".((!empty($file_6))?"<br><li>".$f->check_file_type($filename_6,$file_6,"_blank")."&nbsp;&nbsp;<a href='$file_6' target='_blank'>$filename_6</a>":"")."
                                        <br>
                                        <!-- <small><i>Tanggal ".$f->convert_date_id($tanggal_buat,1)."</i></small><hr> -->
                                    </div>";
                            }
                            $f->paging(array("link"=>$PHP_SELF."?order=$order&sort=$sort&type=$type&act=","page"=>$page,"total"=>$total,"num"=>"$num","show_total"=>1));
                        echo"
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td valign=top width=80%>
                    <div class='warp'>
                        <div class=subtitle style=margin-bottom:5px;><strong>KARIR</strong></div>
                        <table cellspacing=0 cellpadding=0 class=table-gray>
                            <tr>
                                <th>#</th>
                                <th>Posisi</th>
                                <th width=75px>Lokasi Test</th>
                                <!--<th>Kota</th>-->
                                <th>Tanggal Posting</th>
                                <th>Tanggal Berakhir</th>
                                <th style='text-align:center;'>Fungsi</th>
                            </tr>";
                            $curr_date=date('Y-m-d');
                            // $f->pre($curr_date);
                            
                                    
                            if($login_nip=='addli_165@yahoo.com'){
                                $sql="  select distinct b.ptk_id,a.req_id,b.kd_jabatan,a.tanggal_publish,a.tanggal_selesai, a.posisi,a.nm_alias_posisi as alias,a.lokasi,a.rpk_id
                                    from rec_permintaan_adv_portal_karir a 
                                    left join rec_ptk b on a.req_id=b.ptk_id
                                    left join spg_08_kantor c on a.kd_kantor=c.kd_kantor
                                    -- where a.tanggal_selesai >= '$curr_date'
                                   -- where a.tanggal_publish<= '$curr_date'
                                   -- AND a.tanggal_selesai >= '$curr_date'
                                    order by b.ptk_id,a.tanggal_publish desc";
                            }else{
                                    $sql="  select distinct b.ptk_id,a.req_id,b.kd_jabatan,a.tanggal_publish,a.tanggal_selesai, a.posisi,a.nm_alias_posisi as alias,a.lokasi,a.rpk_id
                                    from rec_permintaan_adv_portal_karir a 
                                    left join rec_ptk b on a.req_id=b.ptk_id
                                    left join spg_08_kantor c on a.kd_kantor=c.kd_kantor
                                    -- where a.tanggal_selesai >= '$curr_date'
                                    where a.tanggal_publish<= '$curr_date'
                                    AND a.tanggal_selesai >= '$curr_date'
                                    order by b.ptk_id,a.tanggal_publish desc";
                            }
                            #$result=$db->Execute($sql);
                            $result=$db->SelectLimit($sql,20);
                            if(!$result) print $db->ErrorMsg();
                            $no=0;
                            while($row=$result->FetchRow()){
                                $no++;
                                // $f->pre($row);
                                //$rpk_lokasi = $row['rpk_id'];
                                //$sql_lokasi = "select lokasi from rec_lokasi_test where rpk_id = '$rpk_lokasi' ";
                                //$result = $db->Execute($sql_lokasi);
                                //$f->pre($result);
                                foreach ($row as $key=>$val){
                                    $key=strtolower($key);
                                    if(eregi("tanggal|tgl",$key)){
                                        $val=$f->convert_date($val,1);
                                    }
                                    $$key=trim($val);
                                }
                                $sql_lokasi = "select lokasi,rpk_id from REC_LOKASI_TEST where rpk_id ='$rpk_id'";
                                // $f->pre($sql_lokasi);
                                $result_lokasi = $db->SelectLimit($sql_lokasi,20,0);
                                $user_id=$db->getOne("select id from rec_user_web where email='".$_SESSION['login_nip']."'");
                                #echo "select req_id from rec_apply_list where req_id='".$req_id."' and user_id='$user_id' ";
                                $cek_apply = $db->GetOne("select req_id from rec_apply_list where req_id='".$req_id."' and user_id='$user_id' ");
                                // $f->pre("select req_id from rec_apply_list where req_id='".$req_id."' and user_id='$user_id'");
                                if(empty($_SESSION['login_nip'])){
                                    //vacant.php?req_id=".base64_encode($req_id)."
                                    $apply_url="user.php?req_id=".base64_encode($req_id);
                                    $apply="<input type=button value='Apply' onClick='return alert(\"Silahkan login terlebih dahulu!\");'>";
                                }else{

                                    /* $f->pre("tempat_lahir=>".$tempat_lahir." no_identitas=>".$no_identitas." tanggal_lahir=>".$tanggal_lahir." jns_kelamin_peg=>".$jns_kelamin_peg." kd_agama=>".$kd_agama." alamat_tempat_tinggal=>".$alamat_tempat_tinggal." kd_propinsi_tempat_tinggal=>".$kd_propinsi_tempat_tinggal." kd_kabupaten_tempat_tinggal=>".$kd_kabupaten_tempat_tinggal." kd_kecamatan_tempat_tinggal=>".$kd_kecamatan_tempat_tinggal." kd_kelurahan_tempat_tinggal=>".$kd_kelurahan_tempat_tinggal." rw_tempat_tinggal=>".$rw_tempat_tinggal." rt_tempat_tinggal=>".$rt_tempat_tinggal." kd_sts_kawin=>".$kd_sts_kawin." hp=>".$hp." ekspektasi_gaji=>".$ekspektasi_gaji." kesiapan_kerja=>".$kesiapan_kerja." kd_hubungan_keluarga=>".$kd_hubungan_keluarga." nm_keluarga=>".$nm_keluarga." jns_kelamin_keluarga=>".$jns_kelamin_keluarga." kd_agama_keluarga=>".$kd_agama_keluarga." kd_pend_umum=>".$kd_pend_umum." nm_lemb_pend_form=>".$nm_lemb_pend_form." tmt_masa_kerja=>".$tmt_masa_kerja." nama_perusahaan=>".$nama_perusahaan." jabatan_perusahaan_lama=>".$jabatan_perusahaan_lama." response=>".$response); */

                                    if($cek_apply==""){
                                        if ($tempat_lahir == "" || $no_identitas == "" || $tanggal_lahir == "" || $jns_kelamin_peg == "" || $kd_agama == "" || $alamat_tempat_tinggal == "" || 
                                            	$kd_propinsi_tempat_tinggal == "" || $kd_kabupaten_tempat_tinggal == "" || 
                                            	$kd_kecamatan_tempat_tinggal == "" || $kd_kelurahan_tempat_tinggal == "" || 
                                            	$rw_tempat_tinggal == "" || $rt_tempat_tinggal == "" || 
                                            	$kd_sts_kawin == "" || $hp == "" || $ekspektasi_gaji == "" || $kesiapan_kerja == "" || 
                                            	$kesiapan_kerja == "" || $kd_hubungan_keluarga == "" || $nm_keluarga == "" || 
                                                $jns_kelamin_keluarga == "" || $kd_agama_keluarga == "" || 
                                            	$kd_pend_umum == "" || $nm_lemb_pend_form == "" || $response == "" || $response_b == ""
                                            ) {
                                            
                                            $msg = "user_baru";
                                            $apply_url="user_info.php?act=update&s=".base64_encode($msg);    
                                        }else {
                                            $apply_url="apply.php?_act=".base64_encode("apply")."&req_id=".base64_encode($req_id);
                                        }
                                        $apply="<input type=button value='Apply' onClick=window.location.href='$apply_url'>";
                                    }else{
                                        $apply_url="";
                                        // $cek_seleksi_adm = $db->GetOne("select COUNT(1) from $dbname_hris.rec_proses_seleksi a left join REC_PELAMAR as b on a.REP_ID=b.REP_ID where email='$login_nip' and a.RES_ID='RES-000012' and a.REQ_ID='$req_id' and a.STATUS = '4'");
                                        // if ($cek_seleksi_adm>0) {
                                        //     $apply="<small><i>Ditolak</i></small>";
                                        // }else {
                                            $status_seleksi = $db->GetOne("select a.STATUS_SELEKSI from rec_apply_list a left join rec_pelamar b on a.rep_id=b.rep_id where email='$login_nip' and a.req_id='$req_id'");
                                            $apply="<small><i>$status_pelamar_array[$status_seleksi]</i></small>";
                                        // }
                                    }
                                }
                                echo"
                                <tr>
                                    <td>$no</td>
                                    <td>$alias</td>
                                    <td>
                                    ";
                                    while ($rows_ = $result_lokasi->FetchRow()) {
                                        // $f->pre($rows_);
                                        foreach ($rows_ as $key => $val) {
                                            $key=strtolower($key);
                                            $$key=trim($val);
                                            // $f->pre($value);
                                            // echo $key['lokasi']; 
                                        }
                                        echo "- $lokasi<br>";
                                    }
                                    echo"</td>
                                    <!--<td>".(!empty($kota)?$kota:"-")."</td>-->
                                    <td align=center>".$f->convert_date_id($tanggal_publish,1)."</td>
                                    <td align=center>".$f->convert_date_id($tanggal_selesai,1)."</td>
                                    <td align=left width=120px>
                                        <input type=button value='Detail' onClick=\"openNewWindows('home.php?act=".base64_encode("detail")."&q=".base64_encode($req_id)."','detail_lowongan','800','600','yes');\">
                                        
                                        $apply
                                    </td>
                                </tr>";
                            }
                            if($no=="0"){
                                echo"<tr><td colspan=6 align=center>**TIDAK ADA DATA**</td></tr>";
                            }
                            echo"   
                        </table>
                    </div>
                </td>
                <td>&nbsp;</td>
                <td valign=top width=20% >
                    <div class='warp'>
                        $content_login
                    </div>
                </td>
            </tr>
        </table>";
}
#echo $template->basicfooter();
?>