@extends('layouts.admin')
@section('content')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
@endif
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success"
            href="{{ route('admin.sellvehicle.create') }}">Sell Vehicle List Create</a>
        </div>
    </div>
    <!-- main-content -->
    <div class="main-content app-content">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Latest Event List</h3>
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
                                    <!--wd-30p -->
                                    <th style="width: 10%;"class="border-bottom-0">S.no</th>
                                    <th class="wd-30p border-bottom-0" style="width: 25%;">Title</th>
                                    <!--<th class="wd-10p border-bottom-0">Content</th>-->
                                    <th class="wd-10p border-bottom-0" style="width: 15%;">Date</th>
                                    <th class="wd-10p border-bottom-0 disableFilterBy" style="width: 20%;">Status
                                    </th>
                                    <th class="wd-10p border-bottom-0 disableFilterBy" style="width: 30%;">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($LatestEvent as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event->title ?? '' }} {{ $event->brandd->brand ?? '' }} {{ $event->ModelV->model ?? '' }}
                                            ({{ $event->year }})
                                        </td>
                                        <!--<td>{!! $event->content !!}</td>-->
                                        <td>{{ date('jS F Y', strtotime($event->display_date)) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <select name="status" id="status{{ $event->id }}"
                                                    onchange="update_status({{ $event->id }});"
                                                    style="color: rgba(12, 134, 8, 0.582)">
                                                    <option myid="{{ $event->id }}" value="Active"
                                                        {{ $event->status == 'Active' ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option myid="{{ $event->id }}" value="Inactive"
                                                        {{ $event->status == 'Inactive' ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.sellvehicle.destroy', $event->id) }}"
                                                method="Post">

                                                <a class="btn btn-info"
                                                    href="{{ route('admin.sellvehicle.edit', $event->id) }}">Edit</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger show_confirm"
                                                    data-toggle="tooltip">Delete</button>
                                                <!-- <a href="#" onclick="UploadServiceContentImagesModal('{{ $event->id }}'); return false;" title="Upload Images" class="mb-2 mr-2 btn-sm btn-transition btn btn-outline-secondary uploadservicecontentimagesmodal"><i class="fa fa-arrow-up" aria-hidden="true"></i> Upload Images</a> -->

                                                <!-- <a class="btn btn-info" href="">Upload Image</a> -->
                                                <a class="btn btn-info" href="#"
                                                    onclick="openImageUploadPopup('{{ $event->id }}'); return false;">Upload
                                                    Image</a>
                                            </form>

                                            <a class="btn btn-info"
                                            href="{{ route('admin.sellvehicle.purchase', $event->id) }}">purchase Add</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>






        <div id="service_content_image_upload_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style="width: 75%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title m-auto " style='padding-left:100px;'>Upload Image </h5>
                        <button type="button" style="outline: none;" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <form method="post" id="service_content_image_upload_form" enctype="multipart/form-data">
                            @csrf
                            <div class="md-form mb-5">
                                <label for="">Content Image <b>(Size : 840×470)</b>: </label>
                                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.web" multiple
                                    name="service_content_image[]" id="service_content_image">
                                <input type="hidden" name="eventslug" id="eventslug">
                                <p id="service_content_image_error" style="color: red;font-size: 12px;margin-left: 2px;">
                                </p>

                        </form>
                        <!--<button class="btn btn-primary float-end" onclick="UploadServiceContentImages()" >Upload</button>-->
                        <button class="btn btn-primary float-end" onclick="UploadServiceContentImages()">Upload</button>

                    </div>
                    <div class="md-form mb-5">
                        <label for="">Uploaded Images: </label>
                        <div class="row showservicecontentimages"></div>
                    </div>
                </div>
                <div class="modal-footer mx-auto">
                    <p class='w-100'>Note:</p>
                    <p class='text-warning'> * If you wish to select multiple images, first choose your desired
                        file and then hold down the Ctrl key (Command on Mac) while selecting the images.</p>
                    <p class='text-warning'> * If you want to delete multiple images after selecting their
                        checkboxes and then clicking the delete icon.</p>
                    <!--<button class="btn btn-primary" onclick="UploadServiceContentImages()">Submit</button>-->
                </div>
            </div>
        </div> <!-- modal-bialog .// -->
    </div> <!-- modal.// -->



    <a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
@endsection



@section('scripts')
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
                url: '{{ route('admin.get-images.upload-image') }}',
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
                        // for (var i = 0; i < response['data'].length; i++) {
                        // 	html += '<div class="col-3 text-center delete_'+ response['data'][i].id  + '"><img src="http://cmsadmin.chettinadhospital.com/public/Event/' + response['data'][i].image + '">' +
                        // 		'<i onclick="DeleteImage(' + response['data'][i].id + ')" style="cursor: pointer;" class="fa fa-trash"></i></div>';
                        // }

                        for (var i = 0; i < response['data'].length; i++) {
                            html += '<div class="col-3 text-center delete_' + response['data'][i].id + '">';
                            html += '<img src="/Event/' + response['data'][i].image +
                                '" style="width: 85px; height:70px;">';
                            html += '<input type="checkbox" class="imageCheckbox" value="' + response['data'][i]
                                .id + '">';
                            html += '<i onclick="DeleteImage(' + response['data'][i].id +
                                ')" style="cursor: pointer; padding-left: 16px" class="fa fa-trash"></i>';
                            html += '</div>';
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
                url: "{{ route('admin.sellvehicle.StatusUpdate', ':id') }}".replace(':id', myid),
                data: {
                    status: status
                },
                success: function(data) {
                    alert(data.message);
                    location.reload();
                }
            });

        };
        <?php /*
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
        // 		url: "{{ route('admin.image-upload.upload-image') }}",
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
        // 			url: "{{ route('admin.image-upload.upload-image') }}",
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

        */
        ?>

        $(document).ready(function() {
            $('#service_content_image_upload_form').submit(function(e) {
                e.preventDefault();

            });
        });

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
                    // if (this.width !== 840 || this.height !== 470) {
                    // if (!(this.width >= 800 && this.width <= 840 ) || !(this.height >= 450 &&  this.height <= 470)) {
                    //     invalidImages.push(file.name + ' (invalid dimensions)');
                    // }
                    filesProcessed++;
                    if (filesProcessed === inputFiles.length) {
                        handleInvalidImages();
                    }
                };
            }

            function handleInvalidImages() {
                if (invalidImages.length > 0) {
                    $('#service_content_image_error').html('Invalid images: ' + invalidImages.join(', ') +
                        '<br>Maximum 15MB file size is supported<br>Acceptable image format is jpg, jpeg, png, web only.'
                    );
                    // '<br>Maximum 15MB file size is supported<br>Acceptable image format is jpg, jpeg, png, web only.<br>Image dimensions should be 840x470 pixels.');
                    return false;
                }

                var formdata = new FormData(document.getElementById('service_content_image_upload_form'));
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $('#service_content_image_upload_modal').modal('hide')

                $.ajax({
                    type: 'post',
                    url: "{{ route('admin.image-upload.upload-image') }}",
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
                                // location.reload();
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
                                // location.reload();
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

            const zIndexValue = 9999;

            const checkboxes = document.querySelectorAll('.imageCheckbox');
            const selectedValues = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedValues.push(checkbox.value); // Push value to array if checked
                    // checkbox.nextElementSibling.remove(); // Remove the image
                    // checkbox.remove(); // Remove the checkbox
                }
            })
            // selectedValues.push(checkbox.value);
            // console.log(selectedValues);
            // var id =

            if (selectedValues.length > 0) {

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // 	$('#service_content_image_upload_modal').modal('hide')
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete the image!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No',
                }).then((result) => {
                    // console.log(result);
                    if (result.value == true) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.delete-image.upload-image') }}",
                            data: {
                                id: selectedValues
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
                                        showConfirmButton: false,
                                        didOpen: () => {
                                            Swal.getPopup().style.zIndex = zIndexValue;
                                        }
                                    }).then(function() {

                                        $.each(selectedValues, function(index, value) {

                                            $('.delete_' + value).remove();
                                        });




                                        // 			location.reload();

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

            } else {

                Swal.fire({
                    // 		title: 'Are you sure?',
                    text: 'Atleast Click the one checkbox',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    // 		cancelButtonText: 'No',
                })

            }
        }
    </script>
@endsection
