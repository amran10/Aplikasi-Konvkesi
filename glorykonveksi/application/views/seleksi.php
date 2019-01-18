<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets2/img/apple-icon.png">
    <!--  <link rel="icon" type="image/png" href="/assets2/img/favicon.png"> -->
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
			nowuiKit.initSliders();
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			
			/*  className colors
			
			className: default(transparent), important(red), chill(pink), success(green), info(blue)
			
			*/		
			
			  
			/* initialize the external events
			-----------------------------------------------------------------*/
		
			$('#external-events div.external-event').each(function() {
			
				// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
				// it doesn't need to have a start or end
				var eventObject = {
					title: $.trim($(this).text()) // use the element's text as the event title
				};
				
				// store the Event Object in the DOM element so we can get to it later
				$(this).data('eventObject', eventObject);
				
				// make the event draggable using jQuery UI
				$(this).draggable({
					zIndex: 999,
					revert: true,      // will cause the event to go back to its
					revertDuration: 0  //  original position after the drag
				});
				
			});
		
		
			/* initialize the calendar
			-----------------------------------------------------------------*/
			
			var calendar =  $('#calendar').fullCalendar({
				header: {
					left: 'prev,next',
					//center: 'agendaDay,agendaWeek,month',
					center: 'today',
					right: 'title'
				},
				editable: true,
				firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
				selectable: true,
				defaultView: 'month',
				
				axisFormat: 'h:mm',
				columnFormat: {
					month: 'ddd',    // Mon
					week: 'ddd d', // Mon 7
					day: 'dddd M/d',  // Monday 9/7
					agendaDay: 'dddd d'
				},
				titleFormat: {
					month: 'MMMM yyyy', // September 2009
					week: "MMMM yyyy", // September 2009
					day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
				},
				allDaySlot: false,
				selectHelper: true,
				select: function(start, end, allDay) {
					var title = prompt('Event Title:');
					if (title) {
						calendar.fullCalendar('renderEvent',
							{
								title: title,
								start: start,
								end: end,
								allDay: allDay
							},
							true // make the event "stick"
						);
					}
					calendar.fullCalendar('unselect');
				},
				droppable: true, // this allows things to be dropped onto the calendar !!!
				drop: function(date, allDay) { // this function is called when something is dropped
				
					// retrieve the dropped element's stored Event Object
					var originalEventObject = $(this).data('eventObject');
					
					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);
					
					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = allDay;
					
					// render the event on the calendar
					// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
					$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
					
					// is the "remove after drop" checkbox checked?
					if ($('#drop-remove').is(':checked')) {
						// if so, remove the element from the "Draggable Events" list
						$(this).remove();
					}
					
				},
				
				events: [
					{
						title: 'All Day Event',
						start: new Date(y, m, 1)
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d-3, 16, 0),
						allDay: false,
						className: 'info'
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d+4, 16, 0),
						allDay: false,
						className: 'info'
					},
					{
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false,
						className: 'important'
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false,
						className: 'important'
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d+1, 19, 0),
						end: new Date(y, m, d+1, 22, 30),
						allDay: false,
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/',
						className: 'success'
					}
				],			
			});
			
			
		});

	</script>
	<style>
			
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
                        <a class="nav-link" href="<?php echo base_url().$nav_lowongan; ?>">
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
							<i class="now-ui-icons users_single-02" aria-hidden="true"></i> <?php echo substr($this->session->userdata('nm_user'),0,8); ?> 
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
                            <p><b>LOGIN/SIGNUP</b></p>
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
							<h3 style="width:100%; margin-bottom:0px; color:#ffffff;"><b><u><?php echo $des_menu; ?></u></b></h3>
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
									<table class="table table-bordered">
										<thead style="background-color:#B4B7BA;">
										  <tr>
											<th>NO</th>
											<th>POSISI</th>
											<th>LOKASI</th><th>STATUS</th>
											<th>TANGGAL APPLY</th>
										  </tr>
										</thead>
										<tbody>
										<?php $a=1; foreach ($dt_seleksi as $k) { ?>
										  <tr>
											<td><?php echo $a; ?></td><td><?php echo $k->posisi; ?></td>
											<td><?php echo $k->loc_test; ?></td>
											<td><?php echo $k->status_pelamar; ?></td>
											<td><?php echo $k->TANGGAL_APLIKASI; ?></td>
										  </tr>
										<?php $a++; } ?>
										</tbody>
									</table>
								</div>
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
<script src="/assets2/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="/assets2/js/now-ui-kit.js?v=1.1.0" type="text/javascript"></script>

<link href="/assets2/fullcalendar.css" rel="stylesheet" />
<link href="/assets2/fullcalendar.print.css" rel="stylesheet" media="print" />

<script src="/assets2/jquery/jquery-ui.custom.min.js"></script>

<script src="/assets2/fullcalendar.js"></script>
<script type="text/javascript">

    function scrollToDownload() {

        if ($('.section-download').length != 0) {
            $("html, body").animate({
                scrollTop: $('.section-download').offset().top
            }, 1000);
        }
    }
</script>

</html>