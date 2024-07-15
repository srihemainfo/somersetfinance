<!doctype html>
<html lang="en" dir="ltr">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="">
		<meta name="Author" content="">
		<meta name="Keywords" content=""/>


		<title> SOMERSET FINANCIAL:: Edit Doctor </title>


		<link rel="icon" href="{{ asset('theme/assets/img/brand/favicon.png') }}" type="image/x-icon"/>
		<link href="{{ asset('theme/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" id="style"/>
		<link href="{{ asset('theme/assets/css/icons.css') }}" rel="stylesheet">
		<link href="{{ asset('theme/assets/css/style.css') }}" rel="stylesheet">
		<link href="{{ asset('theme/assets/css/plugins.css') }}" rel="stylesheet">
		<link href="{{ asset('theme/assets/css/animate.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr@latest/dist/themes/classic.min.css">

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <style>
            hr {
            color: #f3f70d;
            }
        </style>

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
							<a class="open-toggle"   href="javascript:void(0);"><i class="header-icons" data-eva="menu-outline"></i></a>
							<a class="close-toggle"   href="javascript:void(0);"><i class="header-icons" data-eva="close-outline"></i></a>
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
										<a class="new nav-link full-screen-link"   href="javascript:void(0);"><i class="fe fe-maximize"></i></span></a>
									</div>

									<div class="dropdown main-profile-menu nav nav-item nav-link">

										<?php echo $alldatas['toprightmenu'];?>

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
								<?php echo $alldatas['userinfo'];?>
							</div>
						</div>

						<?php echo $alldatas['sidenavbar']; ?>

						<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>

						<?php echo $alldatas['mainmenu'];?>

						<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
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
								<li class="breadcrumb-item"><a   href="javascript:void(0);">Sell Vehicle List</a></li>
								<li class="breadcrumb-item active" aria-current="page">Sell Vehicle Details Create </li>
							</ol>
						</nav>
					</div>

					{!! $alldatas['rightsidenavbar'] !!}

				</div>


                <div class="row">
                    <div class="col-md-12">
                <form name="Event" id="Event" method="post"  enctype="multipart/form-data" action="{{ route('eventlist.store') }}">
                    @csrf
                    @method('POST')
                    <div class="card">
                        <div class="card-header border-bottom-0">

                            <h3 class="card-title">Create Sell Vehicle Details </h3>
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
                    <h4>Car Brand & Model</h4>
                    <hr>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand"  value="{{ old('brand') }}" required>
                            @error('brand')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>


                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Model</label>
                            <input type="text" class="form-control" id="model" name="model"  value="{{ old('model') }}" required>
                            @error('model')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration Number</label>
                            <input type="text" class="form-control" id="registration_no" name="registration_no"
                                value="{{ old('registration_no') }}" required>
                            @error('registration_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration Year</label>
                            <input type="text" class="form-control" id="year" name="year"  value="{{ old('year') }}" required>
                            @error('year')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration Country</label>
                            <input type="text" class="form-control" id="country" name="country"  value="{{ old('country') }}" required>
                            @error('country')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration City</label>
                            <input type="text" class="form-control" id="city" name="city"  value="{{ old('city') }}" required>
                            @error('city')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>


                    </div>

                    <div class="row">
                        <h4>Other Details</h4>
                        <hr>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Transmission</label>
                            <select id="transmission"name="transmission" class="form-control" value="{{ old('transmission') }}" required>
                              <option label="Choose one"></option>
                              <option  value="Auto"  @if (old('transmission') == 'Auto') selected @endif  >Auto</option>
                              <option  value="Manual" @if (old('transmission') == 'Manual') selected @endif >Manual</option>
                            </select>
                            @error('transmission')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Status</label>
                            <select id="new_old"name="new_old" class="form-control" value="{{ old('new_old') }}" required>
                              <option label="Choose one"></option>
                              <option  value="New"  @if (old('new_old') == 'New') selected @endif >New</option>
                              <option  value="Used Car" @if (old('new_old') == 'Used Car') selected @endif >Used Car</option>
                            </select>
                            @error('new_old')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Engine Capacity (CC)</label>
                            <input type="text" class="form-control" id="engine_cc" name="engine_cc"  value="{{ old('engine_cc') }}" required>
                            @error('engine_cc')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Fuel Type</label>
                            <select id="fuel_type"name="fuel_type" class="form-control" value="{{ old('fuel_type') }}" required>
                              <option  selected  label="Choose one"></option>
                              <option  value="Petrol"  @if (old('fuel_type') == 'Petrol') selected @endif>Petrol</option>
                              <option  value="Diesel" @if (old('fuel_type') == 'Diesel' ) selected @endif >Diesel</option>
                             <option  value="Gas" @if (old('fuel_type') == 'Gas' ) selected @endif>Gas</option>

                            </select>
                            @error('fuel_type')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>



                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Color</label>
                            <input type="text" id="color-picker">
                            <input type="hidden" class="form-control" id="colour" name="colour"  value="{{ old('colour') }}">
                            @error('colour')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Millage(KM)</label>
                            <input type="text" class="form-control" id="millage" name="millage"  value="{{ old('millage') }}" required>
                            @error('millage')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>


                          <div class="form-group col-md-3">
                            <label for="inputAddress">Vehicle image <b>(Size : 220×220)</b></label>
                            <input type="file" class="form-control" id="event_image" name="event_image" placeholder="choose image" >
                            @error('event_image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                    </div>

                    <div class="row">
                        <h4>Car Features</h4>
                        <hr>
                        @foreach( $alldatas['vehicle_features'] as $vehicle_feature)
                            <div class="form-group col-md-3">
                                <input type="checkbox" name="vehicle_features[]" value="{{ $vehicle_feature->id }}"
                                {{ in_array($vehicle_feature->id, old('vehicle_features', [])) ? 'checked' : '' }}>
                         &nbsp;&nbsp;{{ $vehicle_feature->name }}<br>
                         </div>


                        @endforeach
                    </div>


                    <div class="row">
                        <h4>Client Details</h4>
                        <hr>

                        {{-- <div class="form-group col-md-3">
                            <label for="inputAddress2">Status</label>
                            <select id="status"name="status" class="form-control" value="{{ old('status') }}" required>
                              <option label="Choose one"></option>
                              <option  value="Active"  @if (old('status') == 'New') selected @endif >Active</option>
                              <option  value="Inactive" @if (old('status') == 'Inactive') selected @endif>Inactive</option>
                            </select>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div> --}}


                            <div class="form-group col-md-3">
                              <label for="inputEmail4">Client Name</label>
                              <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Title" value="{{ old('customer_name') }}" required>
                              @error('customer_name')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Regsitration Date</label>
                            <input type="date" class="form-control" id="display_date" name="display_date" value="{{ old('display_date') }}" required >
                            @error('display_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Client Contact</label>
                            <input type="text" class="form-control" id="customer_number" name="customer_number" value="{{ old('customer_number') }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            @error('customer_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Price</label>
                            <input type="text" class="form-control" id="vehicle_price" name="vehicle_price" value="{{ old('vehicle_price') }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            @error('vehicle_price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>


                    </div>





                      {{-- <div class="form-group col-md-3">
                        <label for="inputAddress2">Vehicle Status</label>
                        <input type="text" class="form-control" id="new_old" name="new_old"  value="{{ old('new_old') }}">
                      </div> --}}



                      {{-- <div class="form-group col-md-3">
                        <label for="inputAddress2">Vehicle Transmission</label>
                        <input type="text" class="form-control" id="transmission" name="transmission"  value="{{ old('transmission') }}">
                      </div> --}}

                      {{-- <div class="form-group col-md-3">
                        <label for="inputAddress2">Engine Type</label>
                        <input type="text" class="form-control" id="engine_type" name="engine_type"  value="{{ old('engine_type') }}">
                      </div> --}}


                    <div class="form-row">

                    {{-- <div class="form-group col-md-6">
                        <label for="inputPassword4">Sort Order</label>
                        <input type="number" class="form-control" id="shord_order" name="shord_order">
                      </div> --}}
                  </div>
                  <div class="row">
                  <div class="form-group col-md-12">
                    <label for="inputAddress2">Seller Comments </label>



                    <textarea name="content" id="description" class="summernote" placeholder="enter text"  ></textarea>
                    @error('content')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                  </div>

                  <h4>Meta Details</h4>
                  <hr>

                  <div class="row">
                      <div class="form-group col-md-3">
                          <label for="inputAddress2">Brand</label>
                          <input type="text" class="form-control" id="meta_title" name="meta_title"  value="{{ old('meta_title') }}" required>
                          @error('meta_title')
                          <span class="text-danger">{{ $message }}</span>
                          @enderror
                        </div>


                        <div class="form-group col-md-3">
                          <label for="inputAddress2">Meta Keyword</label>
                          <input type="text" class="form-control" id="meta_keyword" name="meta_keyword"  value="{{ old('meta_keyword') }}" required>
                          @error('meta_keyword')
                          <span class="text-danger">{{ $message }}</span>
                          @enderror
                        </div>

                        <div class="form-group ">
                          <label for="inputAddress2">Meta Description</label>

                        <textarea name="meta_description" id="meta_description" class="summernote" placeholder="enter text" {{ old('meta_description') }} ></textarea>
                         @error('meta_description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                          {{-- <input type="text" class="form-control" id="meta_description" name="meta_description"
                              value="{{ old('meta_description') }}" required>
                          @error('meta_description')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror --}}
                      </div>

                  </div>
                </div>
            </div>
                    <div style="text-align: center">
                        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
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
        <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@latest/dist/pickr.min.js"></script>


        <script>
            $(document).ready(function() {
                const pickr = Pickr.create({
                    el: '#color-picker',
                    theme: 'classic', // or 'monolith', 'nano'
                    default: '#FF000012', // Default color
                    components: {
                        preview: true,
                        opacity: true,
                        hue: true,
                        interaction: {
                            hex: true,
                            rgba: false,
                            hsla: false,
                            input: true,
                            save: true,
                        },
                    },
                });

                const selectedColorTextField = $('#colour');

                // You can listen for the 'save' event to get the selected color
                pickr.on('save', function(color,instance) {
                    const selectedColor = color.toHEXA().toString();
                    selectedColorTextField.val(selectedColor); // Update value of another field with selected color and text
                    instance.hide();
                });
            });
        </script>




        <script type="text/javascript">
            $(document).ready(function() {
        //     $('#title').select2({
        //    placeholder: "Select a Catogory",
        //     });
            $('.summernote').summernote();
            });
            </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
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

      $(function() {
    $( "#year" ).datepicker({dateFormat: 'yy'});
});​

</script>

	</body>
</html>
