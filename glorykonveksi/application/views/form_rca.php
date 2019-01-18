<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets2/img/apple-icon.png">
     <!-- <link rel="icon" type="image/png" href="/assets2/img/favicon.png">  -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Webjob WIKA</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.css" />
    <!-- CSS Files -->
    <link href="/assets2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets2/css/now-ui-kit.css?v=1.1.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="/assets2/css/demo.css" rel="stylesheet" />
	
	<!-- kalender -->

	<script src="/assets2/jquery/jquery-1.10.2.js"></script>

	<script>
		$(document).ready(function() {
			$.get('http://webjob.wika.co.id/lama/wj_api3.php?act=cek_jns_org', {
				maxResults:10,
				q:$('#jenis_o').val()
			}, function(resp_data){
				var $el = $('#jenis_o');
				$el.empty(); // remove old options
				$el.append($('<option></option>')
					.attr('value', '').text(''));
				var item = resp_data;
				//console.log(item);
				data = $.parseJSON(resp_data);
				$.each(data, function (i,v)
				{
					$el.append($('<option></option>')
					.attr('value', v.jns_org).text(v.ket_org));
					console.log(v.jns_org);
				});
				console.log(resp_data);
			});
			nowuiKit.initSliders();
		});
	</script>
	
	<style>
		.containers {
			overflow-x: auto;
			white-space: nowrap;
		}
			
		#external-events {
			float: left;
			width: 150px;
			padding: 0 10px;
			text-align: left;
			}
			
		#external-events h4 {
			font-size: 16px;
			margin-top: 0;
			padding-top: 1em;
			}
			
		.external-event { /* try to mimick the look of a real event */
			margin: 10px 0;
			padding: 2px 4px;
			background: #3366CC;
			color: #fff;
			font-size: .85em;
			cursor: pointer;
			}
			
		#external-events p {
			margin: 1.5em 0;
			font-size: 11px;
			color: #666;
			}
			
		#external-events p input {
			margin: 0;
			vertical-align: middle;
			}

		#calendar {
	/* 		float: right; */
			margin: 0 auto;
			width: 100%;
			background-color: #FFFFFF;
			  border-radius: 6px;
			  border-color: blue;
			box-shadow: 0 3px 5px #2CA8FF;
			}

	</style>
<!-- kalender -->
</head>

<body class="index-page sidebar-collapse">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-primary fixed-top navbar-transparent " color-on-scroll="100">
        <div class="container">
            <div class="navbar-translate">
                <a class="navbar-brand" rel="tooltip" title="BUMN" data-placement="bottom" target="_blank">
                    <img src="/assets2/img/bumn1.png" class="creative-tim-logo" />
                </a>
				<a class="navbar-toggler navbar-toggler" rel="tooltip" title="WIKA" data-placement="bottom" target="_blank">
                    <img src="/assets2/img/wika-logo_blue.png" class="creative-tim-logo" style="margin-top:-15px; margin-left: 30%;" />
                </a>
                <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navigation" data-nav-image="/assets2/img/blurred-image-1.jpg">
                <ul class="navbar-nav" style="padding: 0 1%;">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_beranda; ?>">
                            <!--<i class="now-ui-icons arrows-1_cloud-download-93"></i>-->
                            <p><b>BERANDA</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-neutral" href="<?php echo base_url().$nav_lowongan; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>LOWONGAN</b></p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_kalender; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>KALENDER</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_pengumuman; ?>">
                            <!--<i class="now-ui-icons arrows-1_share-66"></i>-->
                            <p><b>PENGUMUMAN</b></p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_kontak; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>KONTAK</b></p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_faq; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>FAQ</b></p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="http://www.wika.co.id" target="_blank">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>ABOUT US</b></p>
                        </a>
                    </li>
					<?php
				if($this->session->userdata('username') <> ''){	
			?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="now-ui-icons users_single-02" aria-hidden="true"></i> <?php echo substr($this->session->userdata('nm_user'),0,5); ?> 
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="<?php echo base_url()."hal_data_diri"; ?>">CURRICULUM VITAE</a>
							<a class="dropdown-item" href="<?php echo base_url()."hal_data_diri/seleksi"; ?>">STATUS SELEKSI</a>
							<a class="dropdown-item" href="<?php echo base_url()."auth_wj/logout"; ?>">LOGOUT</a>
						</div>
					</li>
			<?php }else{ ?>
					<li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_login; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>LOGIN/SIGNUO</b></p>
                        </a>
                    </li>
			<?php } ?>
                </ul>
				<a class="collapse2" href="#" rel="tooltip" title="WIKA" data-placement="bottom" target="_blank">
					<img src="/assets2/img/wika-logo_blue.png" class="creative-tim-logo" />
				</a>
            </div>
			
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper">
		<div class="section section-navbars" style="padding-top : 100px;">
			<div class="container" id="menu-dropdown">
				<div class="row">
					<div class="col-md-12" align="center">
						<hr>
						<nav class="navbar navbar-expand-lg bg-info">
							<h3 style="width:100%; margin-bottom:0px; color:#ffffff;"><b>Selamat Datang, <?php echo ''; ?></b></h3>
						</nav>
					</div>
				</div>
				<div class="container" align="center" style="padding : 0px 0px;">
					<div >
						<!-- Nav tabs -->
						<div class="card">
							<div class="card-body">
								<!-- Tab panes -->
								<div class="tab-content text-left">
									<h5><b>MASUKAN DATA DIRI ANDA</b></h5>
							<?php echo form_open_multipart('lowongan/post_dt_diri'); ?>
									<input id="id" name="id" type="hidden" value="<?php echo ''; ?>" />
									<input id="j_dt" name="j_dt" type="hidden" value="<?php echo $j_dt; ?>" />
									<input id="link1" name="link1" type="hidden" value="<?php echo $link_u; ?>" />

									<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px;">
										<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>DATA PRIBADI</b></h6>
									</nav>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NAMA LENGKAP (*)
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" Required id="nama" name="nama" type="text" value="<?php echo $nama; ?>" placeholder="Isi disini..." class="form-control"  />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											E-MAIL (*)
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" Required id="email" name="email" type="text" value="<?php echo $email; ?>" placeholder="Isi disini..." class="form-control"  />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NOMOR KTP (*)
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" Required id="no_ktp" name="no_ktp" type="text" value="<?php echo $no_ktp; ?>" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TEMPAT LAHIR (*)
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" Required id="tem_lahir" name="tem_lahir" type="text" value="<?php echo $tem_lahir; ?>" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TANGGAL LAHIR (*)
										</div>
										<div class="col-md-4" align="left">
											<div class="datepicker-container">
												<div class="form-group">
													<input readonly style="background-color : white;" Required id="tgl_lahir" name="tgl_lahir" type="text" class="form-control" value="" id="tgl_lahir" name="tgl_lahir">
												</div>
											</div>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											JENIS KELAMIN (*)
										</div>
										<div class="col-md-4" align="left">
											<select readonly style="background-color : white;" Required id="j_kelamin" name="j_kelamin" class="form-control">
												<option value='1' <?php if($j_kelamin=='1') echo 'selected'; ?>>LAKI-LAKI</option>
												<option value='2' <?php if($j_kelamin=='2') echo 'selected'; ?>>PEREMPUAN</option>
											</select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											AGAMA (*)
										</div>
										<div class="col-md-4" align="left">
											<select readonly style="background-color : white;" Required id="agama" name="agama" class="form-control">
												<option value="0" >--</option>
												<option value="4" <?php if($agama=='4') echo 'selected'; ?>>BUDHA</option>
												<option value="5" <?php if($agama=='5') echo 'selected'; ?> >HINDU</option>
												<option value="1" <?php if($agama=='1') echo 'selected'; ?> >ISLAM</option>
												<option value="6" <?php if($agama=='6') echo 'selected'; ?> >KHONGHUCU</option>
												<option value="3" <?php if($agama=='3') echo 'selected'; ?> >KRISTEN KATHOLIK</option>
												<option value="2" <?php if($agama=='2') echo 'selected'; ?> >KRISTEN PROTESTAN</option>
											</select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											ALAMAT SESUAI KTP (*)
										</div>
										<div class="col-md-4" align="left">
											<textarea readonly style="background-color : white;" id="alamat_ktp" name="alamat_ktp" class="form-control" placeholder="Isi disini..."><?php echo $alamat_ktp; ?></textarea>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											ALAMAT DOMISILI (*)
										</div>
										<div class="col-md-4" align="left">
											<div class="radio">
												<input readonly style="background-color : white;" type="radio" name="sesuai_ktp" id="sesuai_ktp1" value="option1" onclick="$('#alamat_dom').val($('#alamat_ktp').val());">
												<label for="sesuai_ktp1">
													Sama dengan alamat KTP
												</label>
											</div>
											<div class="radio">
												<input readonly style="background-color : white;" type="radio" name="sesuai_ktp" id="sesuai_ktp2" value="option2" onclick="$('#alamat_dom').val('');">
												<label for="sesuai_ktp2">
													Tidak Sama dengan alamat KTP
												</label>
											</div>
											<textarea readonly style="background-color : white;" id="alamat_dom" name="alamat_dom" class="form-control" placeholder="Isi disini..."><?php echo $alamat_dom; ?></textarea>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											HANDPHONE (*)
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" Required id="handphone" name="handphone" type="text" value="<?php echo $handphone; ?>" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div <?php echo $i_hiden; ?> class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											UPLOAD FOTO (*)
										</div>
										<div class="col-md-4" align="left">
											<input id="foto" name="foto" <?php echo $i_req; ?> value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div <?php echo $i_hiden; ?> class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											UPLOAD IJAZAH / SURAT KETERANGAN LULUS (*)
										</div>
										<div class="col-md-4" align="left">
											<input id="ijazah" name="ijazah" <?php echo $i_req; ?> value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div <?php echo $i_hiden ?> class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TRANSKRIP NILAI (*)
										</div>
										<div class="col-md-4" align="left">
											<input id="transkrip" name="transkrip" <?php echo $i_req; ?> value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div <?php echo $i_hiden; ?> class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											UPLOAD CV (*)
										</div>
										<div class="col-md-4" align="left">
											<input id="cv" name="cv" <?php echo $i_req; ?> value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TOEFL
										</div>
										<div class="col-md-4" align="left">
											<input readonly style="background-color : white;" id="toefl" name="toefl" type="text" value="<?php echo $toefl; ?>" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="col-md-12" align="right" style="padding-right:30px; margin-bottom: 10px;">
										<button hidden class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
									</div>
							</form>
							
							<?php echo form_open_multipart('lowongan/post_dt_pendidikan'); ?>
									<input id="eml_p" name="eml_p" type="hidden" value="<?php echo $email; ?>" />	
									<input id="link2" name="link2" type="hidden" value="<?php echo $link_u; ?>" />
						
									<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
										<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENDIDIKAN FORMAL</b></h6>
									</nav>
									<div class="row" style="width:100%; padding-left : 30px;" align="left">
										<div class="col-md-8" >Isikan Minimal 2 Jenjang Pendidikan Terakhir Anda</div>
										<div class="col-md-4" align="right">
											<a hidden href="#toefl" target="" 
												onclick="$('#r_pend').show(); $('#r_ker').hide(); $('#r_org').hide(); $('#r_pres').hide();">
												<i class="fa fa-edit" ></i> tambah
											</a>
										</div>
									</div>

									<div id='r_pend' name='r_pend' style="display: none;">
									
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											PENDIDIKAN (*)
										</div>
										<div class="col-md-4" align="left">
											<select Required id="pendidikan" name="pendidikan" class="form-control" 
											onchange="
											$.get('http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend='+$('#pendidikan').val(), {
												maxResults:10,
												q:$('#pendidikan').val()
											}, function(resp_data){
												var $el = $('#fakultas');
												$('#jurusan').empty();
												$('#program').empty();
												$el.empty(); // remove old options
												$el.append($('<option></option>')
													.attr('value', '').text(''));
												var item = resp_data;
												//console.log(item);
												data = $.parseJSON(resp_data);
												$.each(data, function (i,v)
												{
													$el.append($('<option></option>')
														.attr('value', v.kd_grup).text(v.ket_grup));
														console.log(v.kd_grup);
												});
												console.log(resp_data);
											});">
												<option></option>
												<option value="10" >SD</option>
												<option value="20" >SMP</option>
												<option value="30" >SMA</option>
												<option value="41" >D-I</option>
												<option value="42" >D-II</option>
												<option value="43" >D-III</option>
												<option value="44" >D-IV</option>
												<option value="50" >S1</option>
												<option value="60" >S2</option>
												<option value="70" >S3</option>
											</select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											UNIVERSITAS / SEKOLAH (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="sekolah" name="sekolah" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											FAKULTAS (*)
										</div>
										<div class="col-md-4" align="left">
											<select Required id="fakultas" name="fakultas" class="form-control"
											onchange="
											$.get('http://webjob.wika.co.id/lama/wj_api3.php?act=cek_jurusan&jns_pend='+$('#pendidikan').val()+'&kd_grup='+$('#fakultas').val(), {
												maxResults:10,
												q:$('#fakultas').val()
											}, function(resp_data){
												var $el = $('#jurusan');
												$('#program').empty();
												$el.empty(); // remove old options
												$el.append($('<option></option>')
													.attr('value', '').text(''));
												var item = resp_data;
												//console.log(item);
												data = $.parseJSON(resp_data);
												$.each(data, function (i,v)
												{
													$el.append($('<option></option>')
													.attr('value', v.kd_pend).text(v.ket_pend));
													console.log(v.kd_pend);
												});
												console.log(resp_data);
											});">
											<option></option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											JURUSAN (*)
										</div>
										<div class="col-md-4" align="left">
											<select Required id="jurusan" name="jurusan" class="form-control"
											onchange="
											$.get('http://webjob.wika.co.id/lama/wj_api3.php?act=cek_prodi&jns_pend='+$('#pendidikan').val()+'&kd_grup='+$('#fakultas').val()+'&kd_jurusan='+$('#jurusan').val(), {
												maxResults:10,
												q:$('#jurusan').val()
											}, function(resp_data){
												var $el = $('#program');
												$el.empty(); // remove old options
												$el.append($('<option></option>')
													.attr('value', '').text(''));
												var item = resp_data;
												//console.log(item);
												data = $.parseJSON(resp_data);
												$.each(data, function (i,v)
												{
													$el.append($('<option></option>')
													.attr('value', v.kd_pend).text(v.ket_pend));
													console.log(v.kd_pend);
												});
												console.log(resp_data);
											});"><option></option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											PROGRAM STUDI (*)
										</div>
										<div class="col-md-4" align="left">
											<select Required id="program" name="program" class="form-control"><option></option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											GELAR (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="geglar" name="geglar" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											IPK (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="ipk" name="ipk" type="text" value="" placeholder="ex:3.25" class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div><div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											KOTA (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="kota" name="kota" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN MASUK (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="thn_masuk" name="thn_masuk" type="text" value="" placeholder="Isi disini..." class="form-control" />
											<!--<select Required id="thn_masuk" name="thn_masuk" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>-->
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN LULUS (*)
										</div>
										<div class="col-md-4" align="left">
											<input Required id="thn_lulus" name="thn_lulus" type="text" value="" placeholder="Isi disini..." class="form-control" />
											<!--<select Required id="thn_lulus" name="thn_lulus" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>-->
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="col-md-12" align="right" style="padding-right:30px;">
										<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
									</div>
									</div>
								</form>
								
								<div class="containers">
									
									<table class="table table-bordered"  style="margin-top: 10px;">
										<thead style="background-color:#B4B7BA;">
										  <tr>
											<th>NO</th><th>PENDIDIKAN</th><th>UNIVERSITAS</th><th>FAKULTAS/JURUSAN/PROGRAM STUDI</th>
											<th>IPK</th><th>TAHUN MASUK/TAHUN LULUS</th><th>FUNGSI</th>
										  </tr>
										</thead>
										<tbody>
										<?php $a=1; foreach ($pend as $k) { ?>
										  <tr>
											<td><?php echo $a; ?></td><td><?php echo $k->pendidikan; ?></td><td><?php echo $k->sekolah; ?></td>
											<td><?php echo $k->jurusan; ?></td><td><?php echo $k->ipk; ?></td><td><?php echo $k->thn_masuk."/".$k->thn_lulus; ?></td>
											<td align="center">
												<a hidden style="color:#79C753;" href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-edit"></i>
												</a>&nbsp&nbsp
												<a hidden href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-trash"></i>
												</a>
											</td>
										  </tr>
										<?php $a++; } ?>
										</tbody>
									</table>
								
								</div>
								
									<hr>
									
								<?php echo form_open_multipart('lowongan/post_dt_kerja'); ?>
									<input id="eml_k" name="eml_k" type="hidden" value="<?php echo $email; ?>" />
									<input id="link3" name="link3" type="hidden" value="<?php echo $link_u; ?>" />

									<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
										<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENGALAMAN BEKERJA</b></h6>
									</nav>
									<div class="row" style="width:100%; padding-left : 30px;" align="left">
										<div class="col-md-8" >Riwayat Pekerjaan Mulai Dari Pekerjaan Terakhir Anda</div>
										<div class="col-md-4" align="right">
											<a hidden href="#eml_k" target="" 
												onclick="$('#r_pend').hide(); $('#r_ker').show(); $('#r_org').hide(); $('#r_pres').hide();">
												<i class="fa fa-edit" ></i> tambah
											</a>
										</div>
									</div>

									<div id="r_ker" name="r_ker" style="display:none;">

									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NAMA PERUSAHAAN
										</div>
										<div class="col-md-4" align="left">
											<input id="perusahaan" name="perusahaan" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											JABATAN
										</div>
										<div class="col-md-4" align="left">
											<input id="jabatan" name="jabatan" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN MASUK
										</div>
										<div class="col-md-4" align="left">
											<select id="thn_masuk_k" name="thn_masuk_k" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN KELUAR
										</div>
										<div class="col-md-4" align="left">
											<select id="thn_keluar_k" name="thn_keluar_k" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											URAIAN PEKERJAAN 
										</div>
										<div class="col-md-4" align="left">
											<input id="uraian_k" name="uraian_k" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NAMA ATASAN
										</div>
										<div class="col-md-4" align="left">
											<input id="atasan" name="atasan" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NOMOR TELEPON ATASAN
										</div>
										<div class="col-md-4" align="left">
											<input id="tlp_atasan" name="tlp_atasan" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="col-md-12" align="right" style="padding-right:30px;">
										<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
									</div>

								</div>

								</form>
									
								<div class="containers">
								
									<table class="table table-bordered" style="margin-top: 10px;">
										<thead style="background-color:#B4B7BA;">
										  <tr>
											<th>NO</th><th>NAMA PERUSAHAAN</th><th>JABATAN</th><th>TAHUN MASUK/ TAHUN KELUAR</th>
											<th>URAIAN PEKERJAAN</th><th>FUNGSI</th>
										  </tr>
										</thead>
										<tbody>
										<?php $a=1; foreach ($kerja as $k) { ?>
										  <tr>
											<td><?php echo $a; ?></td><td><?php echo $k->perusahaan; ?></td><td><?php echo $k->jabatan; ?></td>
											<td><?php echo $k->thn_masuk_k."/".$k->thn_keluar_k; ?></td><td><?php echo $k->uraian_k; ?></td>
											<td align="center">
												<a hidden style="color:#79C753;" href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-edit"></i>
												</a>&nbsp&nbsp
												<a hidden href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-trash"></i>
												</a>
											</td>
										  </tr>
										<?php $a++; } ?>
										</tbody>
									</table>
									
								</div>
									
									<hr>

								<?php echo form_open_multipart('lowongan/post_dt_organisasi'); ?>
									<input id="eml_o" name="eml_o" type="hidden" value="<?php echo $email; ?>" />
									<input id="link4" name="link4" type="hidden" value="<?php echo $link_u; ?>" />

									<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
										<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENGALAMAN BERORGANISASI</b></h6>
									</nav>
									<div class="row" style="width:100%; padding-left : 30px;" align="left">
										<div class="col-md-8" ></div>
										<div class="col-md-4" align="right">
											<a hidden href="#eml_o" target="" 
												onclick="$('#r_pend').hide(); $('#r_ker').hide(); $('#r_org').show(); $('#r_pres').hide();">
												<i hidden class="fa fa-edit" ></i> tambah
											</a>
										</div>
									</div>

									<div id='r_org' name='r_org' style="display: none;">

									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											PENGALAMAN BERORGANISASI 
										</div>
										<div class="col-md-4" align="left">
											<input id="pil_o" name="pil_o" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NAMA ORGANISASI 
										</div>
										<div class="col-md-4" align="left">
											<input id="organisasi" name="organisasi" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											JENIS ORGANISASI 
										</div>
										<div class="col-md-4" align="left">
											<select Required id="jenis_o" name="jenis_o" class="form-control"><option></option></select>
											<!--<input id="jenis_o" name="jenis_o" type="text" value="" placeholder="Isi disini..." class="form-control" />-->
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN MASUK 
										</div>
										<div class="col-md-4" align="left">
											<select id="thn_masuk_o" name="thn_masuk_o" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN KELUAR 
										</div>
										<div class="col-md-4" align="left">
											<select id="thn_keluar_o" name="thn_keluar_o" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											JABATAN DALAM ORGANISASI 
										</div>
										<div class="col-md-4" align="left">
											<input id="jabatan_o" name="jabatan_o" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="col-md-12" align="right" style="padding-right:30px;">
										<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
									</div>

									</div>

								</form>
								
								<div class="containers">
								
									<table class="table table-bordered" style="margin-top: 10px;">
										<thead style="background-color:#B4B7BA;">
										  <tr>
											<th>NO</th><th>NAMA ORGANISASI</th><th>JENIS ORGANISASI</th><th>TAHUN MASUK/ TAHUN KELUAR</th>
											<th>JABATAN DALAM ORGANISASI</th><th>FUNGSI</th>
										  </tr>
										</thead>
										<tbody>
										<?php $a=1; foreach ($organisasi as $k) { ?>
										  <tr>
											<td><?php echo $a; ?></td><td><?php echo $k->organisasi; ?></td><td><?php echo $k->ket_org; ?></td>
											<td><?php echo $k->thn_masuk_o."/".$k->thn_keluar_o; ?></td><td><?php echo $k->jabatan_o; ?></td>
											<td align="center">
												<a hidden style="color:#79C753;" href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-edit"></i>
												</a>&nbsp&nbsp
												<a hidden href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-trash"></i>
												</a>
											</td>
										  </tr>
										<?php $a++; } ?>
										</tbody>
									</table>
								
								</div>
								
									<hr>
									
								<?php echo form_open_multipart('lowongan/post_dt_prestasi'); ?>
									<input id="eml_pr" name="eml_pr" type="hidden" value="<?php echo $email; ?>" />
									<input id="link5" name="link5" type="hidden" value="<?php echo $link_u; ?>" />

									<nav class="navbar navbar-expand-lg bg-info" style="min-height:0px; margin-bottom:0px;">
										<h6 style="width:100%; margin-bottom:0px; padding-left:10px; color:#ffffff;"><b>PENCAPAIAN / PRESTASI</b></h6>
									</nav>
									<div class="row" style="width:100%; padding-left : 30px;" align="left">
										<div class="col-md-8" ></div>
										<div class="col-md-4" align="right">
											<a hidden href="#eml_pr" target="" 
												onclick="$('#r_pend').hide(); $('#r_ker').hide(); $('#r_org').hide(); $('#r_pres').show();">
												<i class="fa fa-edit" ></i> tambah
											</a>
										</div>
									</div>

									<div id='r_pres' name='r_pres' style="display: none;">

									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											PENCAPAIAN / PRESTASI </br>(Akademis/Organisasi/Seni/Olahraga)
										</div>
										<div class="col-md-4" align="left">
											<input id="prestasi" name="prestasi" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											NAMA LEMBAGA PENYELENGGARA 
										</div>
										<div class="col-md-4" align="left">
											<input id="lembaga" name="lembaga" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TAHUN 
										</div>
										<div class="col-md-4" align="left">
											<select id="tahun_pr" name="tahun_pr" class="form-control"><option>2016</option><option>2017</option><option>2018</option></select>
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											TINGKATAN 
										</div>
										<div class="col-md-4" align="left">
											<input id="tingkatan" name="tingkatan" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="row" style="width:100%; padding-top : 8px;" align="center">
										<div class="col-md-2"></div>
										<div class="col-md-4" align="left">
											DESKRIPSI 
										</div>
										<div class="col-md-4" align="left">
											<input id="desk_pr" name="desk_pr" type="text" value="" placeholder="Isi disini..." class="form-control" />
										</div>
										<div class="col-md-2"></div>
									</div>
									<div class="col-md-12" align="right" style="padding-right:30px;">
										<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
									</div>

									</div>

								</form>
								
								<div class="containers">
								
									<table class="table table-bordered" style="margin-top: 10px;">
										<thead style="background-color:#B4B7BA;">
										  <tr>
											<th>NO</th><th>NAMA LEMBAGA PENYELENGGARA</th><th>TAHUN</th><th>TINGKATAN</th>
											<th>DESKRIPSI</th><th>FUNGSI</th>
										  </tr>
										</thead>
										<tbody>
										<?php $a=1; foreach ($prestasi as $k) { ?>
										  <tr>
											<td><?php echo $a; ?></td><td><?php echo $k->lembaga; ?></td><td><?php echo $k->tahun_pr; ?></td>
											<td><?php echo $k->tingkatan; ?></td><td><?php echo $k->desk_pr; ?></td>
											<td align="center">
												<a hidden style="color:#79C753;" href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-edit"></i>
												</a>&nbsp&nbsp
												<a hidden href="<?php echo base_url().$nav_beranda; ?>">
													<i class="fa fa-trash"></i>
												</a>
											</td>
										  </tr>
										<?php $a++; } ?>
										</tbody>
									</table>
									
								</div>

								<hr>

								<?php echo form_open_multipart('lowongan/post_apply_akhir'); ?>
									<input id="eml_apply" name="eml_apply" type="hidden" value="<?php echo $email; ?>" />
									<input id="link6" name="link6" type="hidden" value="<?php echo $link_u; ?>" />

								<div class="tab-content text-center">
									<h6>Dengan ini saya menyatakan bahwa informasi dan keterangan yang saya berikan adalah benar dan dapat dipertanggungjawabkan.</h6>
									<h6>Saya bersedia menerima konsekuensi dalam bentuk apapun dari PT Wijaya Karya apabila dikemudian hari diketahui bahwa informasi atau keterangan yang saya berikan tidak benar.</h6>
									<div class="row">
										<div class="col-md-6" align="right">
											<a href="<?php echo base_url().$des_menu; ?>" class="btn btn-primary btn-round">KEMBALI</a>
										</div>
										<div class="col-md-6" align="left">
											<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN DAN APPLY</button>
										</div>
									</div>
								</div>
								<form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
       <div class="main">

			<div class="section" data-background-color="blue" style="padding :20px 0px;">
                <div class="container text-center" style="height :80%;">
					<div class="text-center">
						<img style="width:10%;" src="/assets2/img/wika-logo.png" alt="Image">
					</div>
					<div class="text-center">
						<a target="_blank" href="https://www.twitter.com/ptwijayakarya" class="btn btn-neutral btn-icon btn-twitter btn-round " rel="tooltip" title="Follow us">
							<i class="fa fa-twitter"></i>
						</a>
						<a target="_blank" href="https://www.facebook.com/ptwika" class="btn btn-neutral btn-icon btn-facebook btn-round " rel="tooltip" title="Like us">
							<i class="fa fa-facebook-square"></i>
						</a>
						<a target="_blank" href="http://www.instagram.com/ptwijayakarya" class="btn btn-neutral btn-icon btn-linkedin btn-round" rel="tooltip" title="Follow us">
							<i class="fa fa-instagram"></i>
						</a>
						<a target="_blank" href="https://www.youtube.com/channel/UCUF609VvNhSKbuJxSXGfKYQ" class="btn btn-neutral btn-icon btn-github btn-round " rel="tooltip" title="Subscribe us">
							<i class="fa fa-youtube"></i>
						</a>
					</div>
					</br>
					<div class="text-center">
						<h6 style="font-size: 0.8em;">© 2018 PT WIJAYA KARYA (Persero) Tbk. All Rights Reserved.</h6>
					</div>
				</div>
            </div>
                        
			<div hidden class="col-sm-6 col-lg-3">
				<p class="category">Sliders</p>
				<div id="sliderRegular" class="slider"></div>
				<br>
				<div id="sliderDouble" class="slider slider-primary"></div>
			</div>
  
            
        </div>
		
    </div>
</body>
<!--   Core JS Files   -->
<script src="/assets2/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="/assets2/js/core/popper.min.js" type="text/javascript"></script>
<script src="/assets2/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="/assets2/js/plugins/bootstrap-switch.js"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="/assets2/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
<!--<script src="/assets2/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>-->
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="/assets2/js/now-ui-kit.js?v=1.1.0" type="text/javascript"></script>

<link href="/assets2/fullcalendar.css" rel="stylesheet" />
<link href="/assets2/fullcalendar.print.css" rel="stylesheet" media="print" />

<script src="/assets2/jquery/jquery-ui.custom.min.js"></script>

<script src="/assets2/fullcalendar.js"></script>

<script src="/assets2/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
	$("#pendidikan").onchange( function(){
		console.log('ll');
       var input = $("#pendidikan").val();

		//ajax
		$.get("http://webjob.wika.co.id/lama/wj_api3.php?act=cek_fakultas&kd_pend="+input, {
			maxResults:10,
			q:input.val()
		}, function(resp_data){
			var item = resp_data['dt'];
			//data hasil search muncul (cek console)
			console.log(item);
		});
	});
	
    function scrollToDownload() {

        if ($('.section-download').length != 0) {
            $("html, body").animate({
                scrollTop: $('.section-download').offset().top
            }, 1000);
        }
    }
	$("#tgl_lahir").datepicker({autoclose: true,
		format: "dd-M-yyyy",
		//daysOfWeekDisabled: [0,1,3,5,6],
		clearBtn: true
	});
	$("#period2").datepicker({autoclose: true,
		format: "dd-M-yyyy",
		//daysOfWeekDisabled: [0,1,3,5,6],
		clearBtn: true
	});

	

</script>

</html>