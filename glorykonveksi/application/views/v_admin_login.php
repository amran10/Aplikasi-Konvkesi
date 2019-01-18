<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets2/img/apple-icon.png">
       <!-- <link rel="icon" type="image/png" href="/assets2/img/favicon.png"> -->
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
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<!-- <script type="text/javascript">
		function validasi(){
			if(document.getElementById('g-recaptcha-response').value==''){
				alert('Pastikan Anda Bukan Robot !');
				return false;
			}else{
				return true;
			}
		}
	</script>	 -->
	<script>

		$(document).ready(function() {
			<?php if($isi_msg<>''){ echo "$('#myModal1').modal('show')"; } ?>
			
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
    <div class="wrapper">
		<div class="section section-navbars" style="padding-top : 100px;">
			<div class="container" id="menu-dropdown">
				<div class="row">
					<div class="col-md-12" align="center">
						<hr>
						<nav class="navbar navbar-expand-lg bg-info">
							<h3 style="width:100%; margin-bottom:0px; color:#ffffff;"><b><u>LOGIN ADMIN</u></b></h3>
						</nav>
					</div>
				</div>
				<div class="container" align="center" style="padding : 0px 0px;">
					<div >
						<!-- Nav tabs -->
						<div class="card">
							<div class="card-body">
								<!-- Tab panes -->
								<div class="tab-content text-center">
								
									<!-- Mini Modal -->
									<div class="modal fade modal-mini modal-primary" id="myModal1" name="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header justify-content-center">
													<div class="modal-profile">
														<i class="now-ui-icons travel_info"></i>
													</div>
												</div>
												<div class="modal-body">
													<p><?php echo $isi_msg;?></p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-link btn-neutral"></button>
													<button type="button" class="btn btn-link btn-neutral" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									<!--  End Modal -->
									
									<div class="row">
										<div class="card card-signup" data-background-color="orange">
											<!--<?php echo form_open('n_login/login'); ?>-->
											<form action='<?php echo base_url(); ?>admin/proses' method='post' onSubmit='return validasi(this);'>
												<div class="header text-center">
												<!--
													<h4 class="title title-up">Sign Up</h4>
													<div class="social-line">
														<a href="#pablo" class="btn btn-neutral btn-facebook btn-icon btn-round">
															<i class="fa fa-facebook-square"></i>
														</a>
														<a href="#pablo" class="btn btn-neutral btn-twitter btn-icon btn-lg btn-round">
															<i class="fa fa-twitter"></i>
														</a>
														<a href="#pablo" class="btn btn-neutral btn-google btn-icon btn-round">
															<i class="fa fa-google-plus"></i>
														</a>
													</div>
												-->
												</div>
												<div class="card-body">
													<div class="input-group form-group-no-border">
														<span class="input-group-addon">
															<i class="now-ui-icons users_circle-08"></i>
														</span>
														<input type="text" name="username" class="form-control" placeholder="Username">
													</div>
													<div class="input-group form-group-no-border">
														<span class="input-group-addon">
															<i class="now-ui-icons ui-1_lock-circle-open"></i>
														</span>
														<input type="password" name="password" class="form-control" placeholder="Password">
													</div>
													<!--<div class="input-group form-group-no-border">
														Belum punya akun?
													</div>
													<div class="input-group form-group-no-border">
														<a href="<?php echo base_url()."n_login/registrasi"; ?>"><u>Klik disini.</a>
													</div>-->
													<div class="g-recaptcha" data-sitekey="6Le6AXEUAAAAAMZh1d4Y1PljrFTXu_MhPBjDO4Sr"></div>												
														<!--If you want to add a checkbox to this form, uncomment this code -->
													<!-- <div class="checkbox">
													<input id="checkboxSignup" type="checkbox">
														<label for="checkboxSignup">
														Unchecked
														</label>
													</div> -->
												</div>
												<div class="footer text-center">
													<button class="btn btn-neutral btn-round btn-lg" type="submit" name="submit">Login</button>
												</div>
												<!--<div class="footer text-center">
													<a style='text-decoration:none;' href='http://webjob.wika.co.id/lama/home.php?act=bHVwYV9wYXNzd29yZA==' onClick=\"window.location.href='$PHP_SELF?act=".base64_encode("lupa_password")."'\">Lupa Password?</a>
												</div>-->
											</form>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</html>