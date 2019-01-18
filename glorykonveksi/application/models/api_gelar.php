<?
ob_start();

//session_start();
include("$DOCUMENT_ROOT/lama/s/config.php");
// $f->pre($_SESSION);
#echo $template->basicheader();
//include("header.php");
#$template->navmenu();
#echo"<link href=\"http://$HTTP_HOST/css/frame.css\" rel=\"stylesheet\" type=\"text/css\">";
#   echo base64_encode("REQ-001367");

?>

<?

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

if($act=='lowongan_dtl'){
	
	$curr_date=date('Y-m-d');
	
	switch($pos){
		case 'MTS':
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
		case 'PPCP':
				$posisi = 'PROGRAM PELATIHAN CALON PEGAWAI (PPCP)';
			break;
		default :
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
	}
	
	$sql="select * from rec_permintaan_adv_portal_karir where req_id='$req_id' and tanggal_selesai >= '$curr_date'";
    // $f->pre($sql);
    $result1=$f->get_last_record($sql);
	
    // var_dump($keterangans);
    // $f->pre($result);
    foreach($result1 as $key=>$val){
        $key=strtolower($key);
        if(eregi("tanggal|tgl",$key)){
            $val=$f->convert_date($val,1);
        }
        $$key=trim($val);
    }
    $job_description = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($job_description,"\n")).'</li>';
    $requirement = '<li style=padding:3px;>'.str_replace("\n","</li>\n<li style=padding:3px;>",trim($requirement,"\n")).'</li>';
	
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$data = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($data));
	//header('Content-Type: application/json';
//echo $data;
	exit('-%-'.$data.'-%-');
	
}elseif($act=='lowongan_kota'){
	$curr_date=date('Y-m-d');
	
	switch($pos){
		case 'MTS':
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
		case 'PPCP':
				$posisi = 'PROGRAM PELATIHAN CALON PEGAWAI (PPCP)';
			break;
		default :
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
	}
	
	//$sql="  select lokasi, kd_kantor from REC_LOKASI_TEST where rpk_id ='$rpk'";

	$sql = "select lokasi, kd_kantor from REC_LOKASI_TEST where rpk_id in (select distinct rpk_id from rec_permintaan_adv_portal_karir 
			where (tanggal_publish<= '$curr_date' AND tanggal_selesai >= '$curr_date') AND req_id= '$rpk')";
			
	//echo $sql; die();
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$data = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($data));
	//header('Content-Type: application/json';
//echo $data;
	exit('-%-'.$data.'-%-');

}elseif($act=='pengumuman'){
	
	$curr_date=date('Y-m-d');

	if($search <> ''){
		$search = str_replace('-o-',' ', $search); 
		
		$search = "and a.judul like '%$search%'";
	}
	
	$sql="select top 30 a.file_1,a.file_2,a.file_3,a.file_4,a.file_5,a.file_6,
			a.filename_1,a.filename_2,a.filename_3,a.filename_4,a.filename_5,a.filename_6,
			a.keterangan_1,a.keterangan_2,a.keterangan_3,a.keterangan_4,a.keterangan_5,a.keterangan_6,
			a.judul,a.artikel,a.tanggal_buat,c.sub_kategori
			from hris_wika.dbo.sup_berita a
			left join hris_wika.dbo.sup_berita_category b on a.bca_id=b.bca_id
			left join hris_wika.dbo.sup_berita_sub_category c on a.bsa_id=c.bsa_id
			where lower(b.kategori)='portal karir' and (lower(c.sub_kategori)='berita' or lower(c.sub_kategori)='pengumuman test')
			$search
			order by a.ber_id desc";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$data = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($data));
	//header('Content-Type: application/json';
//echo $data;
	exit('-%-'.$data.'-%-');
	
}elseif($act=='get_nav'){
	$sql="select * from tbl_nav_bar";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$data = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($data));
	//header('Content-Type: application/json';
//echo $data;
	exit('-%-'.$data.'-%-');	
	
}elseif($act=='cek_fakultas'){
	$dt_opt = '{';
	$sql="select kd_grup_pend_formal as kd_grup,ket_grup_pend_formal as ket_grup from $dbname_hris.spg_pendidikan_formal_grup where kd_pend_umum='".$_GET['kd_pend']."' order by ket_grup_pend_formal";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
		
}elseif($act=='cek_jurusan'){
	$dt_opt = '';
	$sql="select kd_pend_formal as kd_pend,ket_pend_formal as ket_pend from $dbname_hris.spg_pendidikan_formal 
		where jns_pend_formal='".$_GET['jns_pend']."' and kd_grup_pend_formal='".$_GET['kd_grup']."' order by ket_pend_formal";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);

}elseif($act=='cek_prodi'){
	$dt_opt = '';
	$sql="select kd_studi_pend_formal as kd_pend,ket_studi_pend_formal as ket_pend from $dbname_hris.spg_pend_formal_studi 
		where kd_pend_umum='".$_GET['jns_pend']."' and kd_grup_pend_formal='".$_GET['kd_grup']."' and kd_pend_formal='".$_GET['kd_jurusan']."' order by ket_studi_pend_formal";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='cek_geglar'){
	$dt_opt = '';
	$sql="select gelar from $dbname_hris.spg_pend_formal_studi 
		where kd_studi_pend_formal='".$_GET['kd_prodi']."'";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='cek_jns_org'){
	$dt_opt = '';
	$sql="select jns_org, ket_org from SPG_JNS_ORGANISASI order by jns_org asc";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);

}elseif($act=='dt_diri'){
	$dt_opt = '';
	$sql=" select rep_id, nama_depan as nama, no_identitas as no_ktp, tempat_lahir as tem_lahir, tanggal_lahir as tgl_lahir, jns_kelamin_peg as j_kelamin, 
		kd_agama as agama, alamat_tempat_tinggal as alamat_ktp, 'option1' as sesuai_ktp, alamat_domisili as alamat_dom, hp as handphone, toefl,path as foto, email
		from rec_pelamar where email='".$_GET['email']."'";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='dt_pend'){
	$dt_opt = '';
	$sql="
		select 
			p.id, p.email as e_pend,
			p.nm_lemb_pend_form as sekolah,
			p.tmp_lemb_pend_form as kota,
			p.tahun_mulai as thn_masuk,
			p.tahun_selesai as thn_lulus,
			p.nilai as ipk,
			CONCAT(g.ket_grup_pend_formal,'/',f.ket_pend_formal,'/',h.ket_studi_pend_formal) as jurusan,
                        CONCAT(p.fakultas_lain,'/',p.jurusan_lain,'/',p.prodi_lain) as jurusan_lain,
			j.nm_pend_umum as pendidikan
		from rec_riwayat_pend_formal p
		left join spg_jns_pend_umum j on j.kd_pend_umum=p.kd_pend_umum
		left join $dbname_hris.spg_pendidikan_formal f on p.kd_jurusan=f.kd_pend_formal
		left join $dbname_hris.spg_pendidikan_formal_grup g on p.kd_fakultas=g.kd_grup_pend_formal
		left join $dbname_hris.spg_pend_formal_studi h on p.kd_prodi=h.kd_studi_pend_formal
		where p.email='".$_GET['email']."'
		order by no_urut_pend_formal";
	//echo $sql;
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='dt_org'){
	$dt_opt = '';
	$sql="select a.id,a.tanggal_buat,a.email as e_pend,a.rep_id,
		a.nm_organisasi as organisasi,
		a.jns_org as jenis_o,
		a.kd_kedudukan_org,
		year(a.tgl_mulai) as thn_masuk_o,
		year(a.tgl_akhir) as thn_keluar_o,
		a.jabatan as jabatan_o,
		a.keterangan,
		b.nm_kedudukan_org,
		c.ket_org 
		from rec_referensi_pelamar_org a 
		left join SPG_JNS_KEDUDUKAN_ORG b on a.kd_kedudukan_org=b.kd_kedudukan_org
		left join SPG_JNS_ORGANISASI c on a.jns_org=c.jns_org
		where email='".$_GET['email']."' 
		order by tgl_mulai";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//var_dump($result);die();//=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='dt_ker'){
	$dt_opt = '';
	$sql ="	select id,tanggal_buat,industri_lainnya, email as e_pend, rep_id, 
		tmt_masa_kerja as thn_masuk_k, 
		tmt_masa_kerja_berakhir as thn_keluar_k, 
		nama_perusahaan as perusahaan, 
		masa_kerja_thn, 
		masa_kerja_bln, 
		jabatan_perusahaan_lama as jabatan, 
		gaji_pokok_lama, 
		keterangan as uraian_k
		from rec_masa_kerja 
		where email='".$_GET['email']."' ";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//var_dump($result);die();//=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='dt_pres'){
	$dt_opt = '';
	$sql ="select id, email as e_pend, nm_lembaga as lembaga,tahun as tahun_pr,tingkatan as tingkatan,deskripsi as desk_pr,nm_prestasi as prestasi from rec_penghargaan 
		where email='".$_GET['email']."' order by tahun";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//var_dump($result);die();//=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
	
}elseif($act=='list_faq'){
	$dt_opt = '{';
	$sql="select pertanyaan, jawaban from $dbname_hris.sup_faq where fca_id='FCA-000001' and fsa_id='SAR-000005'";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
		
}elseif($act=='list_seleksi'){
	$dt_opt = '{';
	/*$sql="select distinct a.*,d.posisi,b.status, b.status_pelamar
		from rec_apply_list a
		left join (select a.email, a.rep_id, a.status, b.status_pelamar from rec_pelamar a left join rec_status_pelamar b on a.status=b.kd_status_pelamar) b on a.rep_id=b.rep_id
		left join rec_ptk c on a.req_id=c.ptk_id	
		left join rec_permintaan_adv_portal_karir d on a.req_id=d.req_id
		where b.email='".$_GET['email']."' and update_status='1' order by a.rep_id";*/

	$sql="select distinct a.*,d.posisi,b.status, b.status_pelamar, d.loc_test
		from rec_apply_list a
		left join (select a.email, a.rep_id, a.status, b.status_pelamar from rec_pelamar a 
			left join rec_status_pelamar b on a.status=b.kd_status_pelamar) b on a.rep_id=b.rep_id
		left join rec_ptk c on a.req_id=c.ptk_id	
		left join (select a.kd_kantor, d.req_id, a.LOKASI as loc_test, d.posisi from rec_permintaan_adv_portal_karir d
			left join hris_wika_rec.dbo.rec_lokasi_test a on d.rpk_id=a.rpk_id) d on a.req_id=d.req_id and a.kd_kantor=d.kd_kantor
		where b.email='".$_GET['email']."' and update_status='1' order by a.rep_id";

	//echo $sql; die();
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
		
}elseif($act=='list_gambar'){
	$dt_opt = '{';
	$sql="select top 1 * from tbl_galery where set_foto='$set_foto' order by id desc";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
		
}elseif($act=='list_faq_api'){
	$dt_opt = '{';
	$sql="select * from tbl_faq order by id desc";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//$result=$db->SelectLimit($sql,20);
	$dt_opt = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($dt_opt));
	exit($dt_opt);
		
}else{
	$curr_date=date('Y-m-d');
	
	switch($pos){
		case 'MTS':
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
		case 'PPCP':
				$posisi = 'PROGRAM PELATIHAN CALON PEGAWAI (PPCP)';
			break;
		default :
				$posisi = 'MANAGEMENT TRAINEE SHE';
			break;
	}
	
	$sql = "select distinct req_id, posisi from rec_permintaan_adv_portal_karir 
			where (tanggal_publish<= '$curr_date' AND tanggal_selesai >= '$curr_date') AND jenis='$jenis'";
	//echo $sql; die();
	$result=$db->Execute($sql);
	//var_dump($result); die();
	//$result=$db->SelectLimit($sql,20);
	$data = json_encode($result->getAll());
ob_clean();

	header("Access-Control-Allow-Origin: *");
	//header('HTTP/1.1: 200');
	header('Status: 200');
	header('Content-Length: '.strlen($data));
	//header('Content-Type: application/json';
//echo $data;
	exit('-%-'.$data.'-%-');
}

#echo $template->basicfooter();
?>