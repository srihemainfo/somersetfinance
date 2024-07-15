<!doctype html>
<html lang="en" dir="ltr">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="">
	<meta name="Author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="Keywords" content="" />


	<title> SOMERSET FINANCIAL::LatestEvent Details </title>


	<link rel="icon" href="{{ asset('theme/assets/img/brand/favicon.png') }}" type="image/x-icon" />
	<link href="{{ asset('theme/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" id="style" />
	<link href="{{ asset('theme/assets/css/icons.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/plugins.css') }}" rel="stylesheet">
	<link href="{{ asset('theme/assets/css/animate.css') }}" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/datetime/1.3.0/css/dataTables.dateTime.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
								<li class="breadcrumb-item"><a href="javascript:void(0);">LatestEvent</a></li>
								<li class="breadcrumb-item active" aria-current="page"> LatestEvent Details</li>
							</ol>
						</nav>
					</div>

					{!! $alldatas['rightsidenavbar'] !!}

				</div>






				<div class="row row-sm">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">LatestEvent List</h3>
							</div>
							<div class="card-body">
								<!-- <h6>Date Range Filter</h6>

                                <div class="table-responsive">
                                <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="basic-datatable">
                                    <tr>
                                      <td style="color:rgb(0, 0, 0); ">
                                        From date *:  <input type='text'  id='search_fromdate' class="datepicker" placeholder='From date'>
                                      </td>
                                      <td  style="color:rgb(0, 0, 0); ">
                                        To date *:  <input type='text'  id='search_todate' class="datepicker" placeholder='To date'>
                                      </td>
                                      <td>
                                         <input type='button' id="btn_search" class="btn btn-secondary" value="Search">
                                      </td>
                                    </tr>
                                  </table>
                                </div> -->



								<div class="table-responsive">
									<table class="table border-top-0 table-bordered text-nowrap border-bottom" id="LatestEvent_table">
										<thead>
											<tr>
												<th class="wd-30p border-bottom-0">S.no</th>
												<th class="wd-30p border-bottom-0">Title</th>
												<th class="wd-10p border-bottom-0">Content</th>
												<th class="wd-10p border-bottom-0">Date</th>
												<th class="wd-10p border-bottom-0 disableFilterBy">Status</th>
												<th class="wd-10p border-bottom-0 disableFilterBy">Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($LatestEvent as $event)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $event->title }}</td>
												<td>{!! $event->content !!}</td>
												<td>{{ date('jS F Y', strtotime($event->display_date)) }}</td>
												<td>
													<div class="btn-group">
														<select name="status" id="status{{ $event->id }}" onchange="update_status({{ $event->id }});" style="color: rgba(12, 134, 8, 0.582)">
															<option myid="{{ $event->id }}" value="Active" {{ $event->status == 'Active' ? 'selected' : '' }}>Active</option>
															<option myid="{{ $event->id }}" value="Inactive" {{ $event->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
														</select>
													</div>
												</td>
												<td>
													<form action="{{ route('eventlist.destroy',$event ->id) }}" method="Post">
														<a class="btn btn-info" href="{{ route('eventlist.edit',$event ->id)  }}">Edit</a>
														@csrf
														@method('DELETE')
														<button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
														<!-- <a href="#" onclick="UploadServiceContentImagesModal('{{ $event->event_slug }}'); return false;" title="Upload Images" class="mb-2 mr-2 btn-sm btn-transition btn btn-outline-secondary uploadservicecontentimagesmodal"><i class="fa fa-arrow-up" aria-hidden="true"></i> Upload Images</a> -->

														<!-- <a class="btn btn-info" href="">Upload Image</a> -->
														<a class="btn btn-info" href="#" onclick="openImageUploadPopup('{{ $event->event_slug }}'); return false;">Upload Image</a>
													</form>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>


								</div>
							</div>
						</div>
					</div>
				</div>





				{!! $alldatas['footer'] !!}


			</div>


			<div id="service_content_image_upload_modal" class="modal fade" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document" style="width: 75%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Create Image </h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="border: none;background: transparent;">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body mx-3">
							<form method="post" id="service_content_image_upload_form" enctype="multipart/form-data">
								@csrf
								<div class="md-form mb-5">
									<label for="">Content Image <b>(Size : 840×470)</b>: </label>
									<input type="file" class="form-control" accept=".jpg,.jpeg,.png,.web" multiple name="service_content_image[]" id="service_content_image">
									<input type="hidden" name="eventslug" id="eventslug">
									<p id="service_content_image_error" style="color: red;font-size: 12px;margin-left: 2px;"></p>
								</div>
							</form>
							<div class="md-form mb-5">
								<label for="">Uploaded Images: </label>
								<div class="row showservicecontentimages"></div>
							</div>
						</div>
						<div class="modal-footer mx-auto">
							<button class="btn btn-secondary" onclick="UploadServiceContentImages()">Submit</button>
						</div>
					</div>
				</div> <!-- modal-bialog .// -->
			</div> <!-- modal.// -->



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
					//     $('#title').select2({
					//    placeholder: "Select a Catogory",
					//     });
					$('.summernote').summernote();
				});
			</script>

			<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
			<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
			<script src="https://cdn.datatables.net/datetime/1.3.0/js/dataTables.dateTime.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>





			<script>
				function openImageUploadPopup(eventSlug) {
					// Set the event slug value in a hidden input field
					document.getElementById("eventslug").value = eventSlug;

					// Get the CSRF token value
					var token = $('meta[name="csrf-token"]').attr('content');

					// Clear existing images
					$('.showservicecontentimages').html('');

					// Get the image for the event
					$.ajax({
						url: '{{ route("get-images.upload-image") }}',
						type: 'POST',
						headers: {
							'X-CSRF-TOKEN': token
						},
						data: {
							eventslug: eventSlug
						},
						success: function(response) {
							if (response['status'] == 200) {
								console.log(response['data'].length);
								var html = '';
								for (var i = 0; i < response['data'].length; i++) {
									html += '<div class="col-3 text-center"><img src="http://cmsadmin.chettinadhospital.com/public/Event/' + response['data'][i].image + '">' +
										'<i onclick="DeleteImage(' + response['data'][i].id + ')" style="cursor: pointer;" class="fa fa-trash"></i></div>';
								}
								$('.showservicecontentimages').html(html);
							}
						},
						error: function(xhr, status, error) {
							// Handle error
							console.error('Error fetching image');
						}
					});

					// Open the modal
					$('#service_content_image_upload_modal').modal('show');
				}

				var minDate, maxDate;

				var $fileName = 'LatestEvent Details';
				// Custom filtering function which will search data in column four between two values
				//  $.fn.dataTable.ext.search.push(
				//      function( settings, data, dataIndex ) {
				//          var min = minDate.val();

				//          var max = maxDate.val();
				//          var date =  new Date( data[3] );

				//          if (
				//              ( min === null && max === null ) ||
				//              ( min === null && date <= max ) ||
				//              ( min <= date   && max === null ) ||
				//              ( min <= date   && date <= max )
				//          )



				//          {
				//              return true;
				//          }
				//          return false;
				//      }
				//  );

				$.fn.dataTable.ext.search.push(
					function(settings, data, dataIndex) {
						var min = minDate.val();
						var max = maxDate.val();
						var dateStr = data[3]; // 3rd column
						var date = new Date(dateStr.split("/").reverse().join("-"));

						if (
							(min === null && max === null) ||
							(min === null && date <= max) ||
							(min <= date && max === null) ||
							(min <= date && date <= max)
						) {
							return true;
						}
						return false;
					}
				);
				$(document).ready(function() {
					// Create date inputs
					minDate = new DateTime($('#search_fromdate'), {
						//  format: 'MMMM Do YYYY'
						format: 'DD/MM/YYYY'
						//  format: 'D/M/YYYY'

					});
					maxDate = new DateTime($('#search_todate'), {
						format: 'DD/MM/YYYY'
						//  format: 'D/M/YYYY'
					});

					// DataTables initialisation
					var table = $('#LatestEvent_table').DataTable({


						dom: 'Bfrtip',
						buttons: {

							dom: {
								button: {
									className: 'form control-btn btn-info text center', //Primary class for all buttons
								},

							},
							buttons: [
								// {
								//     //EXCEL
								//     extend: 'excelHtml5', //extend the buttons that u wanna use
								//     text:' <i class="mdi mdi-file-excel"></i> Export to Excel ',
								//     filename: $fileName,
								//     exportOptions: {
								//         columns: ':visible:not(.disableFilterBy)'
								//         }

								// }
							]
						},



					});
					// Refilter the table

					$('#btn_search').on('click', function() {
						table.draw();
					});
					//  $('#search_fromdate, #search_todate').on('change', function () {
					//      table.draw();
					//  });
				});

				// } );
			</script>




			<script>
				//     jQuery(document).ready(function($) {
				//   var colour=$("input[name=status]").val();


				//     });

				// $("#status").on('change', function() {


				function update_status(id) {
					// var myid = $('option:selected', this).attr('myid');
					// var status = $(this).val();
					var status = $('#status' + id).val();

					var myid = id;
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});


					$.ajax({
						type: 'POST',
						url: ' eventlist/' + myid,
						data: {
							status: status
						},
						success: function(data) {
							alert(data.message);
							location.reload();
						}
					});

				};
				// });

				// function UploadServiceContentImages() {
				// 	$('#service_content_image_error').text('');
				// 	var inputFiles = $('#service_content_image')[0].files;

				// 	if (inputFiles.length === 0) {
				// 		$('#service_content_image_error').text('Please select at least one image.');
				// 		return;
				// 	}

				// 	var invalidImages = [];

				// 	for (var i = 0; i < inputFiles.length; i++) {
				// 		var inputFile = inputFiles[i];

				// 		if (!inputFile.type.match('image.*')) {
				// 			invalidImages.push(inputFile.name);
				// 		}

				// 		var maxSize = 1 * 1024 * 1024; // 5MB
				// 		if (inputFile.size > maxSize) {
				// 			invalidImages.push(inputFile.name);
				// 		}
				// 	}

				// 	if (invalidImages.length > 0) {
				// 		$('#service_content_image_error').html('Invalid images: ' + invalidImages.join(', ') +
				// 			'<br>Maximum 1MB file size is supported<br>Acceptable image format is jpg, jpeg, png, web only.');
				// 		return false;
				// 	}

				// 	var formdata = new FormData(document.getElementById('service_content_image_upload_form'));
				// 	var csrfToken = $('meta[name="csrf-token"]').attr('content');
				// 	$('#service_content_image_upload_modal').modal('hide')

				// 	$.ajax({
				// 		type: 'post',
				// 		url: "{{ route('image-upload.upload-image') }}",
				// 		data: formdata,
				// 		headers: {
				// 			'X-CSRF-TOKEN': csrfToken
				// 		},
				// 		processData: false, // Important: tell jQuery not to process the data
				// 		contentType: false, // Important: tell jQuery not to set contentType
				// 		success: function(response) {
				// 			$("#service_content_image_upload_form")[0].reset();
				// 			if (response['status'] == 200) {
				// 				Swal.fire({
				// 					position: "top-right",
				// 					icon: "success",
				// 					title: 'Success...!',
				// 					text: response['message'],
				// 					timer: 2500,
				// 					showConfirmButton: false
				// 				}).then(function() {
				// 					location.reload();
				// 				});
				// 			}
				// 			if (response['status'] == 400) {
				// 				Swal.fire({
				// 					position: "top-right",
				// 					icon: "danger",
				// 					title: response['message'],
				// 					timer: 2500,
				// 					showConfirmButton: false
				// 				}).then(function() {
				// 					location.reload();
				// 				});
				// 			}
				// 		},
				// 		error: function(data) {
				// 			console.log('Error:', data);
				// 		}
				// 	});
				// }

				// function UploadServiceContentImages() {
				// 	$('#service_content_image_error').text('');
				// 	var inputFiles = $('#service_content_image')[0].files;

				// 	if (inputFiles.length === 0) {
				// 		$('#service_content_image_error').text('Please select at least one image.');
				// 		return;
				// 	}

				// 	var invalidImages = [];
				// 	var filesProcessed = 0;

				// 	for (var i = 0; i < inputFiles.length; i++) {
				// 		var inputFile = inputFiles[i];

				// 		// Check if the file size exceeds 10MB
				// 		var maxSize = 10 * 1024 * 1024; // 10MB
				// 		if (inputFile.size > maxSize) {
				// 			invalidImages.push(inputFile.name + ' (exceeds 10MB)');
				// 		}

				// 		// Check if the file type is not an image
				// 		if (!inputFile.type.match('image.*')) {
				// 			invalidImages.push(inputFile.name + ' (invalid format)');
				// 		}

				// 		// Check image dimensions
				// 		checkImageDimensions(inputFile);
				// 	}

				// 	function checkImageDimensions(file) {
				// 		var image = new Image();
				// 		image.src = URL.createObjectURL(file);
				// 		image.onload = function() {
				// 			if (this.width !== 840 || this.height !== 470) {
				// 				invalidImages.push(file.name + ' (invalid dimensions)');
				// 			}
				// 			filesProcessed++;
				// 			if (filesProcessed === inputFiles.length) {
				// 				handleInvalidImages();
				// 			}
				// 		};
				// 	}

				// 	function handleInvalidImages() {
				// 		if (invalidImages.length > 0) {
				// 			$('#service_content_image_error').html('Invalid images: ' + invalidImages.join(', ') +
				// 				'<br>Maximum 10MB file size is supported<br>Acceptable image format is jpg, jpeg, png, web only.<br>Image dimensions should be 160x90 pixels.');
				// 			return false;
				// 		}

				// 		var formdata = new FormData(document.getElementById('service_content_image_upload_form'));
				// 		var csrfToken = $('meta[name="csrf-token"]').attr('content');
				// 		$('#service_content_image_upload_modal').modal('hide')

				// 		$.ajax({
				// 			type: 'post',
				// 			url: "{{ route('image-upload.upload-image') }}",
				// 			data: formdata,
				// 			headers: {
				// 				'X-CSRF-TOKEN': csrfToken
				// 			},
				// 			processData: false, // Important: tell jQuery not to process the data
				// 			contentType: false, // Important: tell jQuery not to set contentType
				// 			success: function(response) {
				// 				$("#service_content_image_upload_form")[0].reset();
				// 				if (response['status'] == 200) {
				// 					Swal.fire({
				// 						position: "top-right",
				// 						icon: "success",
				// 						title: 'Success...!',
				// 						text: response['message'],
				// 						timer: 2500,
				// 						showConfirmButton: false
				// 					}).then(function() {
				// 						location.reload();
				// 					});
				// 				}
				// 				if (response['status'] == 400) {
				// 					Swal.fire({
				// 						position: "top-right",
				// 						icon: "danger",
				// 						title: response['message'],
				// 						timer: 2500,
				// 						showConfirmButton: false
				// 					}).then(function() {
				// 						location.reload();
				// 					});
				// 				}
				// 			},
				// 			error: function(data) {
				// 				console.log('Error:', data);
				// 			}
				// 		});
				// 	}
				// }

function UploadServiceContentImages() {
    $('#service_content_image_error').text('');
    var inputFiles = $('#service_content_image')[0].files;

    if (inputFiles.length === 0) {
        $('#service_content_image_error').text('Please select at least one image.');
        return;
    }

    var invalidImages = [];
    var filesProcessed = 0;

    for (var i = 0; i < inputFiles.length; i++) {
        var inputFile = inputFiles[i];

        // Check if the file size exceeds 15MB
        var maxSize = 15 * 1024 * 1024; // 15MB
        if (inputFile.size > maxSize) {
            invalidImages.push(inputFile.name + ' (exceeds 15MB)');
        }

        // Check if the file type is not an image
        if (!inputFile.type.match('image.*')) {
            invalidImages.push(inputFile.name + ' (invalid format)');
        }

        // Check image dimensions
        checkImageDimensions(inputFile);
    }

    function checkImageDimensions(file) {
        var image = new Image();
        image.src = URL.createObjectURL(file);
        image.onload = function() {
            if (this.width !== 840 || this.height !== 470) {
                invalidImages.push(file.name + ' (invalid dimensions)');
            }
            filesProcessed++;
            if (filesProcessed === inputFiles.length) {
                handleInvalidImages();
            }
        };
    }

    function handleInvalidImages() {
        if (invalidImages.length > 0) {
            $('#service_content_image_error').html('Invalid images: ' + invalidImages.join(', ') +
                '<br>Maximum 15MB file size is supported<br>Acceptable image format is jpg, jpeg, png, web only.<br>Image dimensions should be 840x470 pixels.');
            return false;
        }

        var formdata = new FormData(document.getElementById('service_content_image_upload_form'));
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('#service_content_image_upload_modal').modal('hide')

        $.ajax({
            type: 'post',
            url: "{{ route('image-upload.upload-image') }}",
            data: formdata,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            processData: false, // Important: tell jQuery not to process the data
            contentType: false, // Important: tell jQuery not to set contentType
            success: function(response) {
                $("#service_content_image_upload_form")[0].reset();
                if (response['status'] == 200) {
                    Swal.fire({
                        position: "top-right",
                        icon: "success",
                        title: 'Success...!',
                        text: response['message'],
                        timer: 2500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                }
                if (response['status'] == 400) {
                    Swal.fire({
                        position: "top-right",
                        icon: "danger",
                        title: response['message'],
                        timer: 2500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                }
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }
}


				function DeleteImage(id) {
					var csrfToken = $('meta[name="csrf-token"]').attr('content');
					$('#service_content_image_upload_modal').modal('hide')
					Swal.fire({
						title: 'Are you sure?',
						text: 'You want to delete the image!',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Yes!',
						cancelButtonText: 'No',
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
								type: "POST",
								url: "{{ route('delete-image.upload-image') }}",
								data: {
									id: id
								},
								headers: {
									'X-CSRF-TOKEN': csrfToken
								},
								success: function(response) {
									if (response['status'] == 200) {
										Swal.fire({
											position: "top-right",
											icon: "success",
											title: 'Success',
											text: response['message'],
											timer: 2500,
											showConfirmButton: false
										}).then(function() {
											location.reload();
										});
									}
									if (response['status'] == 400) {
										Swal.fire({
											position: "top-right",
											icon: "danger",
											title: response['message'],
											showConfirmButton: false,
											timer: 2500
										}).then(function() {
											location.reload();
										});
									}
								},
								error: function(data) {
									console.log('Error:', data);
								}
							});
						} else {
							console.log('failed');
						}
					});
				}
			</script>

</body>

</html>
