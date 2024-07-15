<!doctype html>
<html lang="en" dir="ltr">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="">
	<meta name="Author" content="">
	<meta name="Keywords" content="" />


	<title> SOMERSET FINANCIAL:: Edit Event </title>


	<link rel="icon" href="{{ asset('theme/assets/img/brand/favicon.png') }}" type="image/x-icon" />
	<link href="{{ asset('theme/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" id="style" />
	<link href="{{ asset('theme/assets/css/icons.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/plugins.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/animate.css') }}" rel="stylesheet">

</head>

<body class="main-body app sidebar-mini ltr" oncontextmenu="return false;">

	<!-- Loader -->
	<div id="global-loader">
		<img src="{{ asset('theme/assets/img/loaders/loader-4.svg') }}" class="loader-img" alt="Loader">
	</div>
	<!-- /Loader -->

	<!-- page -->
	<div class="page custom-index">

		<!-- main-header -->
		<div class="main-header side-header sticky nav nav-item">
			<div class="container-fluid main-container ">
				<div class="main-header-left ">
					<div class="app-sidebar__toggle mobile-toggle" data-bs-toggle="sidebar">
						<a class="open-toggle" href="javascript:void(0);"><i class="header-icons" data-eva="menu-outline"></i></a>
						<a class="close-toggle" href="javascript:void(0);"><i class="header-icons" data-eva="close-outline"></i></a>
					</div>
					<div class="responsive-logo">
						<a href="index.html" class="header-logo"><img src="{{ asset('theme/assets/img/brand/logo.png') }}" class="logo-11"></a>
						<a href="index.html" class="header-logo"><img src="{{ asset('theme/assets/img/brand/logo-white.png') }}" class="logo-1"></a>
					</div>
				</div>
				<button class="navbar-toggler nav-link icon navresponsive-toggler vertical-icon ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
				</button>
				<div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0  mg-lg-s-auto">
					<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
						<div class="main-header-right">



							<div class="nav nav-item  navbar-nav-right mg-lg-s-auto">
								<div class="nav-item full-screen fullscreen-button">
									<a class="new nav-link full-screen-link" href="javascript:void(0);"><i class="fe fe-maximize"></i></span></a>
								</div>

								<div class="dropdown main-profile-menu nav nav-item nav-link">

									<?php echo $alldatas['toprightmenu']; ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /main-header -->

		<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
		<div class="sticky">
			<aside class="app-sidebar sidebar-scroll">
				<div class="main-sidebar-header active">
					<a class="desktop-logo logo-light active" href="index.html"><img src="{{ asset('theme/assets/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
					<a class="desktop-logo logo-dark active" href="index.html"><img src="{{ asset('theme/assets/img/brand/logo-white.png') }}" class="main-logo" alt="logo"></a>
					<a class="logo-icon mobile-logo icon-light active" href="index.html"><img src="{{ asset('theme/assets/img/brand/favicon.png') }}" alt="logo"></a>
					<a class="logo-icon mobile-logo icon-dark active" href="index.html"><img src="{{ asset('theme/assets/img/brand/favicon-white.png') }}" alt="logo"></a>
				</div>
				<div class="main-sidemenu">
					<div class="main-sidebar-loggedin">
						<div class="app-sidebar__user">
							<?php echo $alldatas['userinfo']; ?>
						</div>
					</div>

					<?php echo $alldatas['sidenavbar']; ?>

					<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
							<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
						</svg></div>

					<?php echo $alldatas['mainmenu']; ?>

					<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
							<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
						</svg></div>
				</div>
			</aside>
		</div>
		<!-- main-sidebar -->

		<!-- main-content -->
		<div class="main-content app-content">

			<!-- container -->
			<div class="main-container container-fluid">

				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div>
						<h4 class="content-title mb-2">Hi, welcome back!</h4>
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="javascript:void(0);">Event</a></li>
								<li class="breadcrumb-item active" aria-current="page">Event</li>
							</ol>
						</nav>
					</div>

					{!! $alldatas['rightsidenavbar'] !!}

				</div>


				<div class="row">
					<div class="col-md-12">
						<form name="Event" id="Event" method="post" enctype="multipart/form-data" action="{{ route('eventlist.update',$EvetList->id) }}">
							@csrf
							@method('put')
							<div class="card">
								<div class="card-header border-bottom-0">

									<h3 class="card-title">Edit Event</h3>
								</div>
								@if ($message = Session::get('success'))
								<div class="alert alert-success">
									<p>{{ $message }}</p>
								</div>
								@endif
								@if ($errors->any())
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
								@endif
								<div class="card-body">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="inputEmail4">Title</label>
											<input type="text" class="form-control" id="name" name="title" placeholder="Name" value="{{ $EvetList->title }}">
										</div>

										<div class="form-group col-md-3 ">
											<label for="inputAddress2">Status</label>
											<select id="status" name="status" class="form-control">
												<option label="Choose one"></option>
												<option value="Active" {{ "Active" == $EvetList->status  ? 'selected' : '' }}>Active</option>
												<option value="Inactive" {{ "Inactive" == $EvetList->status  ? 'selected' : '' }}>Inactive</option>
											</select>
										</div>

										<div class="form-group col-md-3">
											<label for="inputEmail4">Date</label>
											<input type="date" class="form-control" id="display_date" name="display_date" value="{{ $EvetList->display_date }}">
										</div>



									</div>


									<div class="form-row">

										<div class="form-group">
											<label for="inputAddress">Choose image<b>(Size : 700×300)</b></label>
											<input type="file" class="form-control" id="event_image" value="{{$EvetList->image}}" name="event_image" placeholder="choose image">
										</div>
										<div class="form-group col-md-2">
											<img src="{{URL::asset('Event/'.$EvetList->image)}}" alt="Event" max-width="100px" height="100px">
										</div>

										<div class="form-group col-md-6">
											<label for="inputPassword4">Short Order</label>
											<input type="number" class="form-control" id="shord_order" value="{{$EvetList->shord_order}}" name="shord_order">
										</div>
									</div>
								</div>



								<div class="form-group">
									<label for="inputAddress2">Content</label>
									<textarea name="content" id="description" class="summernote" value='{!! $EvetList->content !!}'>{!! $EvetList->content !!}</textarea>
									{{-- <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor"> --}}
								</div>
								<div style="text-align: center">
									<button class="btn btn-secondary" onclick="window.history.back();">close</button>
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
						</form>

					</div>


				</div>

			</div>



			{!! $alldatas['footer'] !!}


		</div>



		<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>


		<script src="{{ asset('theme/assets/plugins/jquery/jquery.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/bootstrap/popper.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/ionicons/ionicons.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/moment/moment.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/summernote-editor/summernote1.js') }}"></script>
		<script src="{{ asset('theme/assets/js/summernote.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/select2/js/select2.min.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/side-menu/sidemenu.js') }}"></script>
		<script src="{{ asset('theme/assets/js/sticky.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/sidebar/sidebar.js') }}"></script>
		<script src="{{ asset('theme/assets/plugins/sidebar/sidebar-custom.js') }}"></script>
		<script src="{{ asset('theme/assets/js/eva-icons.min.js') }}"></script>
		<script src="{{ asset('theme/assets/js/script.js') }}"></script>
		<script src="{{ asset('theme/assets/js/themecolor.js') }}"></script>
		<script src="{{ asset('theme/assets/js/swither-styles.js') }}"></script>
		<script src="{{ asset('theme/assets/js/custom.js') }}"></script>
		<script src="{{ asset('theme/assets/jsscript/createnewfaqjs.js') }}"></script>



		<script type="text/javascript">
			$(document).ready(function() {
				$('.summernote').summernote();
				//     $('#title').select2({
				//    placeholder: "Select a Catogory",
				//     });
			});
		</script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
		<script type="text/javascript">
			$('.show_confirm').click(function(event) {
				var form = $(this).closest("form");
				var name = $(this).data("name");
				event.preventDefault();
				swal({
						title: `Are you sure you want to delete this record?`,
						text: "If you delete this, it will be gone forever.",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							form.submit();
						}
					});
			});
		</script>

</body>

</html>
