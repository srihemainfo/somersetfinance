@extends('layouts.admin')


@section('content')

<style>
    hr {
        border-top: 1px solid #b5b5b5 !important;
    }
    h4 label {
        margin-bottom : 2.10rem !important ;
    }
   
</style>

    <div class="col-md-12">
        <form name="Event" id="Event" method="post" enctype="multipart/form-data"
            action="{{ route('admin.sellvehicle.store') }}">
            @csrf
            @method('POST')
            <div class="card">
                <div class="card-header border-bottom-0">

                    <h1 class="card-title text-center"><label>Create Vehicle Details</label> </h1>
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
                    <h4><label >Car Brand & Model</label> </h4>
                   

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="brand">Brand</label>
                            <select id="brand"name="brand" class="form-control" value="{{ old('brand') }}" required>

                                @foreach ($alldatas['brand'] as $id => $value)
                                    <option value="{{ $id == 0 ? '': $id }}" @if (old('brand') == $id) selected @endif>
                                        {{  $value }}</option>
                                @endforeach
                            </select>

                            {{-- <input type="text" class="form-control" id="brand" name="brand"
                                value="{{ old('brand') }}" required> --}}
                            @error('brand')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Model</label>
                            <select id="model"name="model" class="form-control" value="{{ old('model') }}" required>


                            </select>
                            {{-- <input type="text" class="form-control" id="model" name="model"
                                value="{{ old('model') }}" required> --}}
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
                            <input type="text" class="form-control" id="year" name="year"
                                value="{{ old('year') }}" required>
                            @error('year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                value="{{ old('country') }}" required>
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Registration City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ old('city') }}" required>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>

                    <hr>

                    <h4 class='mt-1'> <label >Other Details </label></h4>
                  
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Transmission</label>
                            <select id="transmission"name="transmission" class="form-control"
                                value="{{ old('transmission') }}" required>
                                <option label="Choose one"></option>
                                <option value="Auto" @if (old('transmission') == 'Auto') selected @endif>Auto</option>
                                <option value="Manual" @if (old('transmission') == 'Manual') selected @endif>Manual</option>
                                <option value="Semi-Auto" @if (old('transmission') == 'Semi-Auto') selected @endif>Semi-Auto</option>
                            </select>
                            @error('transmission')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Status</label>
                            <select id="new_old"name="new_old" class="form-control" value="{{ old('new_old') }}"
                                required>
                                <option label="Choose one"></option>
                                <option value="New" @if (old('new_old') == 'New') selected @endif>New</option>
                                <option value="Used Car" @if (old('new_old') == 'Used Car') selected @endif>Used Car</option>
                            </select>
                            @error('new_old')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Engine Capacity (CC)</label>
                            <input type="text" class="form-control" id="engine_cc" name="engine_cc"
                                value="{{ old('engine_cc') }}" required>
                            @error('engine_cc')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Fuel Type</label>
                            <select id="fuel_type"name="fuel_type" class="form-control" value="{{ old('fuel_type') }}"
                                required>
                                <option selected label="Choose one"></option>
                                <option value="Petrol" @if (old('fuel_type') == 'Petrol') selected @endif>Petrol</option>
                                <option value="Diesel" @if (old('fuel_type') == 'Diesel') selected @endif>Diesel</option>
                                <option value="Gas" @if (old('fuel_type') == 'Gas') selected @endif>Gas</option>

                            </select>
                            @error('fuel_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Color</label>
                            <input type="text" id="color-picker">
                            <input type="hidden" class="form-control" id="colour" name="colour"
                                value="{{ old('colour') }}">
                            @error('colour')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Mileage(Miles)</label>
                            <input type="text" class="form-control" id="millage" name="millage"
                                value="{{ old('millage') }}" required>
                            @error('millage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group col-md-3">
                            <label for="inputAddress">Vehicle image <b>(Size : 220×220)</b></label>
                            <input type="file" class="form-control" id="event_image" name="event_image"
                                placeholder="choose image">
                            @error('event_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <hr>


                    <h4> <label>Car Features</label></h4>
                    
                    <div class="row">
                        @foreach ($alldatas['vehicle_features'] as $vehicle_feature)
                            <div class="form-group col-md-3">
                                <input type="checkbox" name="vehicle_features[]" value="{{ $vehicle_feature->id }}"
                                    {{ in_array($vehicle_feature->id, old('vehicle_features', [])) ? 'checked' : '' }}>
                                &nbsp;&nbsp;{{ $vehicle_feature->name }}<br>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    <h4> <label>Client Details </label></h4>
                    
                    <div class="row">

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
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                placeholder="Title" value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Regsitration Date</label>
                            <input type="date" class="form-control" id="display_date" name="display_date"
                                value="{{ old('display_date') }}" required>
                            @error('display_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Client Contact</label>
                            <input type="text" class="form-control" id="customer_number" name="customer_number"
                                value="{{ old('customer_number') }}" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            @error('customer_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Vehicle Price</label>
                            <input type="text" class="form-control" id="vehicle_price" name="vehicle_price"
                                value="{{ old('vehicle_price') }}" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            @error('vehicle_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>

                    <hr>





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
                                            
                    <h4><label >Description </label></h4>
                  
                    <div class="row">
                        <div class="form-group col-md-12">

                            <textarea name="content" id="description" class="summernote" style='display:none;' placeholder="enter text"></textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <hr>

                    <h4><label>Meta Details (SEO) </label></h4>
                   
  
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Meta Title</label>
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
  
                          {{-- <div class="form-group ">
                            <label for="inputAddress2">Meta Description</label>
                            
                          <textarea name="meta_description" id="meta_description" class="summernote" placeholder="enter text" {{ old('meta_description') }} ></textarea>
                           @error('meta_description')
                                <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    
                        </div> --}}
  
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="inputAddress2">Meta Description</label>

                            <textarea name="meta_description" id="meta_description" class="summernote" style='display:none;' placeholder="enter text"></textarea>
                            @error('meta_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    
                    
                </div>
                <div class="d-flex justify-content-center">
                    <div class="m-3">
                        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                        <button type="submit" name="submit_button" value="button1" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="m-3">
                        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                        <button type="submit" name="submit_button" value="button2" name='submit' class="btn btn-primary">Submit & Continue</button>
                    </div>

                </div>

        </form>

    </div>


@endsection



@section('scripts')
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
            pickr.on('save', function(color, instance) {
                const selectedColor = color.toHEXA().toString();
                selectedColorTextField.val(
                    selectedColor); // Update value of another field with selected color and text
                instance.hide();
            });
        });
    </script>




    <script type="text/javascript">
        $(document).ready(function() {
            //     $('#title').select2({
            //    placeholder: "Select a Catogory",
            //     });
            $('.summernote').summernote({ height: 300,

              
                callbacks: {
                    onInit: function() {
                        // Remove the full-screen button after Summernote has initialized
                        $('.note-btn.btn-fullscreen').remove();
                        $('.note-table').remove();
                        $('.note-insert').remove();
                    }
                }

            });
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

        // $(function() {
        //     $("#year").datepicker({
        //         dateFormat: 'yy'
        //     });
        // });


        // $(document).on("change", '#brand', function () {
        //     var stateUrl = "{{ route('admin.model_get')}}";
        //     // console.log($(this).val());

        //     $.get(stateUrl,
        //             {option: $(this).val()},
        //             function (data) {
        //                 var model = $('#model');
        //                 model.empty();
        //                 model.append("<option>Select a Model</option>");
        //                 $.each(data, function (index, element) {
        //                     model.append("<option value='" + element.id + "'>" + element.model + "</option>");
        //                 });
        //             }
        //     );
        // })

        $(document).ready(function () {
            var oldModel = "{{ old('model') }}";
            var oldBrand = "{{ old('brand') }}";

            if (oldBrand) {
                $('#brand').val(oldBrand).trigger('change');
            }

            $(document).on("change", '#brand', function () {
                var stateUrl = "{{ route('admin.model_get') }}";

                $.get(stateUrl,
                    { option: $(this).val() },
                    function (data) {
                        var model = $('#model');
                        model.empty();
                        model.append("<option value=''>Select a Model</option>");
                        $.each(data, function (index, element) {
                            var selected = element.id == oldModel ? "selected" : "";
                            model.append("<option value='" + element.id + "' " + selected + ">" + element.model + "</option>");
                        });
                    }
                );
            });
        });

        






    </script>
@endsection
