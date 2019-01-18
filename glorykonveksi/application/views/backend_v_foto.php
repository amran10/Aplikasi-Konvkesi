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
                location.href = 'http://webjob.wika.co.id/api_she/del_dtl/' + url;
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
    /*      float: right; */
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

<body>
</br>
</br>
</br>
            <div class="container" id="menu-dropdown">
                <div class="row">
                    <div class="col-md-12" align="left">
                        <nav class="navbar navbar-expand-lg bg-info">
                            <h3 style="width:100%; margin-bottom:0px; color:#ffffff;"><b>Admin, Input Foto</b></h3>
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
                <h4>Upload Backend</h4>
        <?php echo form_open_multipart('admin_foto/upload'); ?>
        <div class="form-group">
            <select id="set_foto" name="set_foto" class="form-control"
                onchange="
                if($(this).val()=='1'){
                    $('#beranda').show();
                }else{
                    $('#beranda').hide();
                }if($(this).val()=='2'){
                    $('#regular').show();
                }else{
                    $('#regular').hide();
                }if($(this).val()=='3'){
                    $('#jobfair').show();
                }else{
                    $('#jobfair').hide();
                };">
                <option value="0">- Pilih -</option>
                <option value="1">Backgorund Beranda</option>
                <option value="2">Beranda Lowongan Regular</option>
                <option value="3">Beranda Lowongan Jobfair</option>
                <option value="4">Lowongan Regular</option>
                <option value="5">Lowongan Jobfair</option>
            </select>
        </div>
        <!--<div class="row" style="width:100%; padding-top : 8px; display : none;" id="beranda">
        &nbsp; &nbsp; &nbsp;<div align="left"><b>Format Foto/Spesifikasi Foto</b></div></br>
        <div class="form-group">
            &nbsp;<a>Format Foto : JPG || PNG</a></br>
            &nbsp;<a>Size        : Max 2MB</a>
                <img src="/assets2/img/format_beranda.png" align="left" width="220px" height="120px" />
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" readonly="readonly">
        </div>
        </div>
        <div class="row" style="width:100%; padding-top : 8px; display : none;" id="regular">
        &nbsp; &nbsp; &nbsp;<div align="left"><b>Format Foto/Spesifikasi Foto</b></div></br>
        <div class="form-group">
            &nbsp;<a>Format Foto : JPG || PNG</a></br>
            &nbsp;<a>Size        : Max 2MB</a>
                <img src="/assets2/img/format_regular.png" align="left" width="220px" height="120px" />
             <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" readonly="readonly">
        </div>
        </div> -->
        <!--<div class="row" style="width:100%; padding-top : 8px; display : none;" id="jobfair">
        &nbsp; &nbsp; &nbsp;<div align="left"><b>Format Foto/Spesifikasi Foto</b></div></br>
        <div class="form-group">
            &nbsp;<a>Format Foto : JPG || PNG</a></br>
            &nbsp;<a>Size        : Max 2MB</a>
              <img src="/assets2/img/format_jobfair.png" align="left" width="220px" height="120px" />
            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" readonly="readonly"> 
        </div>
        </div>
        <div class="form-group">
            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
        </div>-->
        <div class="form-group">
            <input type="file" name="foto">
        </div>
        <div class="form-group">
              <style>
    .blink {
      animation: blink-animation 1s steps(5, start) infinite;
      -webkit-animation: blink-animation 1s steps(5, start) infinite;
    }
    @keyframes blink-animation {
      to {
        visibility: hidden;
      }
    }
    @-webkit-keyframes blink-animation {
      to {
        visibility: hidden;
      }
    }
    </style>
    <span class="blink"><b>Perhatikan Format Foto!</b></span><br>
            <a><h7><font color="black">Format minimal Untuk Upload Foto :<br>Dimensions : 400 x 400 <br>Width &nbsp;: 400 Pixels<br>Height : 400 Pixels</font><br>(File Foto : JPG, PNG &nbsp;|| Size        : Max 2MB)</h7></a>
        </div>
        <div class="form-group">
            <button class="btn btn-success" name="submit" type="submit">Upload</button>
        </div>
        <div class="col-md-2"></div>
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