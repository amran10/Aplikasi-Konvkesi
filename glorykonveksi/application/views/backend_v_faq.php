<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
	<meta name="referrer" content="always" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets2/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/assets2/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Admin Webjob WIKA</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.css" />
    <!-- CSS Files -->
    <link href="/assets2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets2/css/now-ui-kit.css?v=1.1.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="/assets2/css/demo.css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.css">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
	<!-- kalender -->

	<script src="/assets2/jquery/jquery-1.10.2.js"></script>

	<script>
		$(document).ready(function() {
			$.get('http://webjob.wika.co.id/api_she/get_pend/act-o-cek_jns_org', {
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
		function decision(message, url){
			if(confirm('Yakin menghapus detail ini?')) {
				location.href = 'http://localhost/api_she/del_dt_faq/' + url;
			}
		}
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
	#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 10%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
	</style>

<!-- kalender -->
</head>

</br>
</br>
</br>
            <div class="container" id="menu-dropdown">
                <div class="row">
                    <div class="col-md-12" align="left">
                        <nav class="navbar navbar-expand-lg bg-info">
                            <h3 style="width:100%; margin-bottom:0px; color:#ffffff;"><b>Admin, FAQ</b></h3>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="now-ui-icons users_single-02" ></i> ADMIN BACKEND</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="<?php echo base_url()."admin_foto"; ?>">Admin Foto</a>
                            <a class="dropdown-item" href="<?php echo base_url()."admin_faq"; ?>">Admin FAQ</a>
                            <a class="dropdown-item" href="<?php echo base_url()."admin_kalender"; ?>">Admin Kalender</a>
                            <a class="dropdown-item" href="<?php echo base_url()."auth_wj/logout1"; ?>">Logout</a>
                        </div>
                    </li>
                        </nav>
                    </div>
                </div>
                <div class="container" align="left" style="padding : 0px 0px;">           
				<?php echo form_open_multipart('admin_faq/post_faq_backend'); ?>
				<div class="form-group">
					<label><b>Pertanyaan</b></label><br>
					<textarea type="text" id="pertanyaan" name="pertanyaan" class="form-control" cols="40" rows="5" placeholder="Tulis Pertanyaan"></textarea>
					<label><b>Jawaban</b></label><br>
					<textarea type="text" id="pertanyaan" name="jawaban" class="form-control" cols="40" rows="5" placeholder="Tulis Jawaban"></textarea>
					<div class="col-md-12" align="center" style="padding-right:30px;">
						<button class="btn btn-primary btn-round" type="submit" name="submit">SIMPAN</button>
					</div>
				</div>	
			</form>
				
			<?php echo form_open_multipart('post_faq_backend'); ?>
			<div class="containers">
				<table class="table" >
					<thead style="background-color:#B4B7BA;">
					  <tr>
						<th style="line-height: 10px;">NO</th>
						<th>Pertanyaan</th>
						<th>Jawaban</th>
						<th>FUNGSI</th>
					  </tr>
					</thead>
					<tbody>
					 <?php $a=1; foreach ($faq_ as $k) { ?> 
					  <tr>
						<td><?php echo $a; ?></td>
						<td><?php echo $k->pertanyaan; ?></td>
						<td style="width:  10px;"><?php echo $k->jawaban; ?></td>
						<td align="center">
							<a hidden style="color:#79C753;" href="<?php echo base_url().$nav_beranda; ?>">
								<i class="fa fa-edit"></i>
							</a>&nbsp&nbsp
							<a href="<?php echo base_url()."admin_faq/faq_update"; ?>" target="">
								<i class="fa fa-edit" ></i> 
							</a>&nbsp&nbsp
							<a href="javascript:decision('sdsd','<?php $l_dlt = 'act_a-o-del_faq-t-id-o-'.$k->id; echo $l_dlt; ?>');">
								<i class="fa fa-trash"></i>
							</a>&nbsp&nbsp
						</td>
					  </tr>
					<?php $a++; } ?>
					</tbody>
				</table>
			</div>
		</form>

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