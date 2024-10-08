@extends('layouts.admin')
@section('content')

    <style>
        hr {
            border-top: 1px solid #b5b5b5 !important;
        }

        h4 label {
            margin-bottom: 2.10rem !important;
        }
    </style>



    <div class="row">
        <div class="col-md-12">
            <form name="Event" id="Event" method="post" enctype="multipart/form-data"
                action="{{ route('admin.sellvehicle.update', $EvetList->id) }}">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header border-bottom-0">

                        <h1 class="card-title text-center"><label>Edit Sell Vehilcle Details </label></h1>
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

                        <h4><label>Car Brand & Model</label> </h4>

                        <div class="row">


                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Brand</label>

                                {{-- <select id="brand"name="brand" class="form-control" value="{{ old('brand') }}" required>

                                        @foreach ($alldatas['brand'] as $id => $value)
                                        <option value="{{ $id }}" @if (old('brand') == $id || $EvetList->brand == $id) selected @endif>{{ $value }}
                                        </option>
                                        @endforeach
                                    </select> --}}

                                <select id="brand" name="brand" class="form-control" required>
                                    @foreach ($alldatas['brand'] as $id => $value)
                                        <option value="{{ $id }}"
                                            @if (old('brand') == $id || (isset($EvetList) && $EvetList->brand == $id)) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>



                                {{-- <input type="text" class="form-control" id="brand" name="brand"
                                        value="{{ old('brand') ?? $EvetList->brand }}" required> --}}
                                @error('brand')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Model</label>

                                <select id="model"name="model" class="form-control" value="{{ old('model') }}"
                                    required>


                                </select>


                                {{-- <input type="text" class="form-control" id="model" name="model"
                                        value="{{ old('model') ?? $EvetList->model }}" required> --}}
                                @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Registration Number</label>
                                <input type="text" class="form-control" id="registration_no" name="registration_no"
                                    value="{{ old('registration_no') ?? $EvetList->registration_no }}" required>
                                @error('registration_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Registration Year</label>
                                <input type="text" class="form-control" id="year" name="year"
                                    value="{{ old('year') ?? $EvetList->year }}" required>
                                @error('year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Registration Country</label>
                                <input type="text" class="form-control" id="country" name="country"
                                    value="{{ old('country') ?? $EvetList->country }}" required>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Registration City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ old('city') ?? $EvetList->city }}" required>
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                        <hr>

                        <h4 class='mt-1'> <label>Other Details </label></h4>
                        <div class="row">




                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Transmission</label>
                                <select id="transmission"name="transmission" class="form-control"
                                    value="{{ old('transmission') ?? $EvetList->transmission }}" required>
                                    <option label="Choose one"></option>
                                    <option value="Auto" @if (old('transmission') == 'Auto' || $EvetList->transmission == 'Auto') selected @endif>Auto
                                    </option>
                                    <option value="Manual" @if (old('transmission') == 'Manual' || $EvetList->transmission == 'Manual') selected @endif>Manual
                                    </option>
                                </select>
                                @error('transmission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Vehicle Status</label>
                                <select id="new_old"name="new_old" class="form-control"
                                    value="{{ old('new_old') ?? $EvetList->new_old }}" required>
                                    <option label="Choose one"></option>
                                    <option value="New" @if (old('new_old') == 'New' || $EvetList->new_old == 'New') selected @endif>New
                                    </option>
                                    <option value="Used Car" @if (old('new_old') == 'Used Car' || $EvetList->new_old == 'Used Car') selected @endif>Used Car
                                    </option>
                                </select>
                                @error('new_old')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Engine Capacity (CC)</label>
                                <input type="text" class="form-control" id="engine_cc" name="engine_cc"
                                    value="{{ old('engine_cc') ?? $EvetList->engine_cc }}" required>
                                @error('engine_cc')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Fuel Type</label>
                                <select id="fuel_type"name="fuel_type" class="form-control"
                                    value="{{ old('fuel_type') ?? $EvetList->fuel_type }}" required>
                                    <option selected label="Choose one"></option>
                                    <option value="Petrol" @if (old('fuel_type') == 'Petrol' || $EvetList->fuel_type == 'Petrol') selected @endif>Petrol
                                    </option>
                                    <option value="Diesel" @if (old('fuel_type') == 'Diesel' || $EvetList->fuel_type == 'Diesel') selected @endif>Diesel
                                    </option>
                                    <option value="Gas" @if (old('fuel_type') == 'Gas' || $EvetList->fuel_type == 'Gas') selected @endif>Gas
                                    </option>

                                </select>
                                @error('fuel_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Vehicle Color</label>
                                <input type="text" id="color-picker">
                                <input type="hidden" class="form-control" id="colour" name="colour"
                                    value="{{ old('colour') ?? $EvetList->colour }}">
                                @error('colour')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Millage(KM)</label>
                                <input type="text" class="form-control" id="millage" name="millage"
                                    value="{{ old('millage') ?? $EvetList->millage }}" required>
                                @error('millage')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress">Choose image<b>(Size : 200×200)</b></label>
                                <input type="file" class="form-control" id="event_image"
                                    value="{{ $EvetList->image }}" name="event_image" placeholder="choose image">
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <img src="{{ URL::asset('Event/' . $EvetList->image) }}" alt="Event"
                                    max-width="100px" height="100px">
                            </div>



                        </div>
                        <h4> <label>Car Features</label></h4>

                        <div class="row">
                            {{-- @foreach ($alldatas['vehicle_features'] as $vehicle_feature)
                                <div class="form-group col-md-3">
                                    <input type="checkbox" name="vehicle_features[]" value="{{ $vehicle_feature->id }}"
                                    {{ in_array($vehicle_feature->id, old('vehicle_features', [])) ? 'checked' : '' }}>
                             &nbsp;&nbsp;{{ $vehicle_feature->name }}<br>
                             </div>


                            @endforeach --}}
                            @foreach ($alldatas['vehicle_features'] as $vehicle_feature)
                                <div class="form-group col-md-3">
                                    <input type="checkbox" name="vehicle_features[]" value="{{ $vehicle_feature->id }}"
                                        {{ (is_array(old('vehicle_features')) && in_array($vehicle_feature->id, old('vehicle_features'))) || optional($EvetList->SellVehicleFeatures)->contains($vehicle_feature->id) ? 'checked' : '' }}>
                                    &nbsp;&nbsp;{{ $vehicle_feature->name }}<br>
                                </div>
                            @endforeach


                        </div>
                        <hr>
                        <h4> <label>Client Details </label></h4>

                        <div class="row">


                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Client Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    placeholder="Title" value="{{ old('customer_name') ?? $EvetList->customer_name }}"
                                    required>
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Regsitration Date</label>
                                <input type="date" class="form-control" id="display_date" name="display_date"
                                    value="{{ old('display_date') ?? $EvetList->display_date }}" required>
                                @error('display_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Client Contact</label>
                                <input type="text" class="form-control" id="customer_number" name="customer_number"
                                    value="{{ old('customer_number') ?? $EvetList->customer_number }}" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                @error('customer_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Vehicle Price</label>
                                <input type="text" class="form-control" id="vehicle_price" name="vehicle_price"
                                    value="{{ old('vehicle_price') ?? $EvetList->vehicle_price }}" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                @error('vehicle_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                     
                        <hr>
                        <h4> <label> Comments </label></h4>

                        <div class="row">

                            <div class="form-group col-md-12">
                                <textarea name="content" id="description" class="summernote" style='display:none;' value='{!! $EvetList->content !!}'
                                    required>{!! $EvetList->content !!}</textarea>

                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>

                        <h4><label>Meta Details (SEO) </label></h4>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title"
                                    value="{{ old('meta_title') ?? $EvetList->meta_title }}" required>
                                @error('meta_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group col-md-3">
                                <label for="inputAddress2">Meta Keyword</label>
                                <input type="text" class="form-control" id="meta_keyword" name="meta_keyword"
                                    value="{{ old('meta_keyword') ?? $EvetList->meta_keyword }}" required>
                                @error('meta_keyword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="form-group "> --}}
                                {{-- <label for="inputAddress2">Meta Description </label>
                                <textarea name="meta_description" id="description" class="summernote" style='display:none;'
                                    value='{!! old('meta_description') ?? $EvetList->meta_description !!}' required>{!! $EvetList->meta_description !!}</textarea>

                                @error('meta_description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                                {{-- <label for="inputAddress2">Meta Description</label>
                                <input type="text" class="form-control" id="meta_description" name="meta_description"
                                    value="{{ old('meta_description') ?? $EvetList->meta_description }}" required>
                                @error('meta_description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            {{-- </div> --}}

                     
                            
                        </div>
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label for="inputAddress2">Meta Description </label>
                                    <textarea name="meta_description" id="description" class="summernote" style='display:none;'
                                        value='{!! old('meta_description') ?? $EvetList->meta_description !!}' required>{!! $EvetList->meta_description !!}</textarea>
    
                                    @error('meta_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
    
                                </div>

                        </div>

                        <?php
                        /*

                                <div class="form-row">
                                {{-- <div class="form-group col-md-6">
                                                                <label for="inputEmail4">Car Name</label>
                                                                <input type="text" class="form-control" id="name" name="title" placeholder="Name" value="{{ $EvetList->title }}" required>
                                                                @error('title')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div> --}}

                                {{-- <div class="form-group col-md-3 ">
                                                                <label for="inputAddress2">Status</label>
                                                                <select id="status" name="status" class="form-control" required>
                                                                    <option label="Choose one"></option>
                                                                    <option value="Active" {{ "Active" == $EvetList->status  ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ "Inactive" == $EvetList->status  ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                                @error('status')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div> --}}

                                {{-- <div class="form-group col-md-3">
                                                                <label for="inputEmail4">Date</label>
                                                                <input type="date" class="form-control" id="display_date" name="display_date" value="{{ old('display_date') ?? $EvetList->display_date }}" required>
                                                                @error('display_date')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div> --}}
                                                                {{-- @error('status')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror --}}

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Brand</label>
                                                                <input type="text" class="form-control" id="brand" name="brand"  value="{{ old('brand') ?? $EvetList->brand }}" required>
                                                                @error('brand')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Model</label>
                                                                <input type="text" class="form-control" id="model" name="model"  value="{{ old('model') ?? $EvetList->model }}" required>
                                                                @error('model')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle Year</label>
                                                                <input type="text" class="form-control" id="year" name="year"  value="{{ old('year') ?? $EvetList->year }}" required>
                                                                @error('year')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle Color</label>
                                                                <input type="text" id="color-picker">
                                                                <input type="hidden" class="form-control" id="colour" name="colour"  value="{{ old('colour') ?? $EvetList->colour }}" required>
                                                                @error('colour')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle Color</label>
                                                                <input type="text" class="form-control" id="colour" name="colour"  value="{{ old('colour') ?? $EvetList->colour }}" required>
                                                                @error('colour')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle Status</label>
                                                                <select id="new_old" name="new_old" class="form-control" required>
                                                                    <option value="" @if (old('new_old') == '' && !$EvetList->new_old) selected @endif disabled>Choose one</option>
                                                                    <option value="New" @if (old('new_old') == 'New' || $EvetList->new_old == 'New') selected @endif>New</option>
                                                                    <option value="Used Car" @if (old('new_old') == 'Used Car' || $EvetList->new_old == 'Used Car') selected @endif>Used Car</option>
                                                                </select>
                                                                @error('new_old')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Transmission</label>
                                                                <select id="transmission"name="transmission" class="form-control" value="{{ old('transmission') }}" required>
                                                                <option  @if (old('transmission') == '' && !$EvetList->transmission) selected @endif disabled label="Choose one"></option>
                                                                <option  value="Auto" @if (old('new_old') == 'Auto' || $EvetList->new_old == 'Auto') selected @endif>Auto</option>
                                                                <option  value="Manual" @if (old('new_old') == 'Manual' || $EvetList->new_old == 'Manual') selected @endif>Manual</option>
                                                                </select>
                                                                @error('transmission')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle Country</label>
                                                                <input type="text" class="form-control" id="country" name="country"  value="{{ old('country') ?? $EvetList->country }}" required>
                                                                @error('country')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Vehicle City</label>
                                                                <input type="text" class="form-control" id="city" name="city"  value="{{ old('city') ?? $EvetList->city }}" required>
                                                                @error('city')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Fuel Type</label>
                                                                <select id="fuel_type"name="fuel_type" class="form-control" value="{{ old('fuel_type') }}" required>
                                                                <option  @if (old('fuel_type') == '' && !$EvetList->fuel_type) selected @endif disabled label="Choose one"></option>
                                                                <option  value="Petrol"  @if (old('fuel_type') == 'Petrol' || $EvetList->fuel_type == 'Petrol') selected @endif>Petrol</option>
                                                                <option  value="Diesel" @if (old('fuel_type') == 'Diesel' || $EvetList->fuel_type == 'Diesel') selected @endif >Diesel</option>
                                                                <option  value="Gas" @if (old('fuel_type') == 'Gas' || $EvetList->fuel_type == 'Gas') selected @endif>Gas</option>

                                                                </select>
                                                                @error('fuel_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label for="inputAddress2">Engine Capacity (CC) </label>
                                                                <input type="text" class="form-control" id="engine_cc" name="engine_cc"  value="{{ old('engine_cc') ?? $EvetList->engine_cc }}" required>
                                                                @error('engine_cc')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                                            </div>




                                </div>

                       */
                        ?>
                        <?php
                                                        /*

                                <div class="form-row">

                                <div class="form-group">
                                <label for="inputAddress">Choose image<b>(Size : 200×200)</b></label>
                                <input type="file" class="form-control" id="event_image" value="{{ $EvetList->image }}" name="event_image" placeholder="choose image">
                                                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>
                                <div class="form-group col-md-2">
                                <img src="{{ URL::asset('Event/' . $EvetList->image) }}" alt="Event" max-width="100px" height="100px">
                                </div>

                                {{-- <div class="form-group col-md-6">
                                                                <label for="inputPassword4">Sort Order</label>
                                                                <input type="number" class="form-control" id="shord_order" value="{{$EvetList->shord_order}}" name="shord_order">
                                                            </div> --}}




                                </div>

                        */
                        ?>


                    </div>


                    <?php
                                        /*
                    <div class="form-group">
                    <label for="inputAddress2">Content</label>
                    <textarea name="content" id="description" class="summernote" value='{!! $EvetList->content !!}' required>{!! $EvetList->content !!}</textarea>
                                            @error('content')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    {{-- <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor"> --}}
                    </div>
                                        */
                    ?>
                    {{-- <div style="text-align: center">
                        <button class="btn btn-secondary" onclick="window.history.back();">close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div> --}}

                    <div class="d-flex justify-content-center">
                        <div class="m-3">
                            <div class="row">
                                <button class="btn btn-secondary m-3" onclick="window.history.back();">close</button>
                                <button type="submit" name="submit_button" value="button1" class="btn btn-primary m-3">Submit</button>

                            </div>
                            {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                        </div>
                        <div class="m-3">
                            {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                            <button type="submit" name="submit_button" value="button2" name='submit' class="btn btn-primary m-3">Submit & Continue</button>
                        </div>
    
                    </div>
            </form>

        </div>
    @endsection
    @section('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $('.summernote').summernote({ height: 300});

                var content = <?php echo json_encode($EvetList->content); ?>;
                $('.summernote').summernote('code', content);
                $('[data-toggle="tooltip"]').tooltip();
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

        <script>
            $(document).ready(function() {
                const storedColor =
                    '{{ $EvetList->colour }}'; // Assuming you have stored the color value in a PHP variable

                const pickr = Pickr.create({
                    el: '#color-picker',
                    theme: 'classic', // or 'monolith', 'nano'
                    default: storedColor, // Set default color to stored color
                    components: {
                        preview: true,
                        opacity: true,
                        hue: true,
                        interaction: {
                            hex: true,
                            rgba: true,
                            hsla: true,
                            input: true,
                            save: true,
                        },
                    },
                });

                // Another field to display the selected color and text
                const colorPreview = $('#color-preview');
                const selectedColorTextField = $('#colour');

                // Set initial values based on the stored color
                colorPreview.css('backgroundColor', storedColor); // Set background color of preview
                // selectedColorTextField.val('Selected Color: ' +
                //     storedColor); // Set value of another field with selected color and text

                // You can listen for the 'save' event to get the selected color
                pickr.on('save', function(color, instance) {
                    const selectedColor = color.toHEXA().toString();
                    colorPreview.css('backgroundColor', selectedColor); // Update background color of preview
                    selectedColorTextField.val(
                    selectedColor); // Update value of another field with selected color and text
                    instance.hide(); // Close the color picker
                });
            });

            // $(document).ready(function () {
            //     var oldModel = "{{ old('model') }}";
            //     var oldBrand = "{{ old('brand') }}";
            //     var livebrand = "{{ $EvetList->brand }}"

            //     if (oldBrand) {
            //         $('#brand').val(oldBrand).trigger('change');
            //     }else if(livebrand ){
            //         $('#brand').val(livebrand).trigger('change');
            //         oldModel = livebrand ;
            //     }

            //     $(document).on("change", '#brand', function () {
            //         var stateUrl = "{{ route('admin.model_get') }}";
            //         var model = $('#model');
            //         model.empty();

            //         $.get(stateUrl,
            //             { option: $(this).val() },
            //             function (data) {

            //                 if(data.length > 0){

            //                     model.append("<option value=''>Select a Model</option>");
            //                     $.each(data, function (index, element) {
            //                         var selected = element.id == oldModel ? "selected" : "";
            //                         model.append("<option value='" + element.id + "' " + selected + ">" + element.model + "</option>");
            //                     });
            //                 }else{
            //                     model.append('No Data Found') ;
            //                 }
            //             }
            //         );
            //     });
            // });

            $(document).ready(function() {
                var oldModel = "{{ old('model') }}";
                var oldBrand = "{{ old('brand') }}";
                var livebrand = "{{ isset($EvetList) ? $EvetList->brand : '' }}";
                var model_val = "{{ isset($EvetList) ? $EvetList->model : '' }}";

                function populateModels(brandId) {
                    var stateUrl = "{{ route('admin.model_get') }}";
                    var model = $('#model');
                    model.empty();
                    model.append('Loading...')

                    $.get(stateUrl, {
                        option: brandId
                    }, function(data) {
                        if (data.length > 0) {
                            model.empty();
                            model.append("<option value=''>Select a Model</option>");
                            $.each(data, function(index, element) {
                                var selected = element.id == oldModel || element.id == model_val ?
                                    "selected" : "";
                                model.append("<option value='" + element.id + "' " + selected + ">" +
                                    element.model + "</option>");
                            });
                        } else {
                            model.append('<option>No Data Found</option>');
                        }
                    });
                }

                if (oldBrand) {
                    $('#brand').val(oldBrand).trigger('change');
                    populateModels(oldBrand);
                } else if (livebrand) {
                    $('#brand').val(livebrand).trigger('change');
                    oldModel = livebrand;
                    populateModels(livebrand);
                }

                $(document).on("change", '#brand', function() {
                    populateModels($(this).val());
                });
            });
        </script>
    @endsection
