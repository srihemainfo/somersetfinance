@php
    $type_id = auth()->user()->roles[0]->type_id;
    $key = ($type_id == 6) ? 'layouts.admin' : 'layouts.admin';
@endphp

  @php
        $userId = auth()->user()->id;
        $partner = \App\Models\User::find($userId);
        if ($partner) {
            $assignedRole = $partner->roles->first();

            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            } else {
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp

@extends($key)

@section('content')

    <div class="row gutters">
        <link href="{{ asset('css/materialize.css') }}" rel="stylesheet" />
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
            <div class="card">

                <div class="row">
                    <div class="col-11">
                        <div class="input-field" style="padding-left: 0.50rem;">
                            <input type="text" name="name" id="autocomplete-input"
                                style="margin:0;padding-left:0.50rem;" placeholder="Enter Staff Name   ( Staff Code )"
                                class="autocomplete" autocomplete="off"
                                @if ($name != '') value="{{ $name }}" @else value="" @endif required
                                onchange="run(this)">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
@include('admin.users.staff_data')
@if (isset($user) && $user != '')
@include('partials.homecontent')
@endif
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const staff = [];

        let loader = $('.loading-overlay');

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");



        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.partner-edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // console.log(data);
                    let details = data.staff;
                    let staff = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        staff[details[i]] = null;
                    }
                    // console.log(staff)
                    $('input.autocomplete').autocomplete({
                        data: staff,
                    });
                    $('#loading').hide();

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    $('#loading').hide();
                }
            });

        }

        function run(element) {
            if ($(element).val()!= '') {
                var a = $(element).val();
                window.location.href = "{{ url('admin/partner-edge') }}/" + a;
            }
            
        }

        $(document).ready(function() {
            $('.add_plus').each(function(index) {
                $(this).click(function() {
                    $(this).toggleClass('rotated');
                    $('.view_more').eq(index).toggle();
                });
            });
        });
        
    @if (isset($user) && $user != '')
        
    function callAjax() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList')) {
            $('.datatable-caseList').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.edge.index') }}",
                 data : { 
                            id : {{ $user->id ?? ''}}
                            
                        }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        // Call callAjax2() directly after callAjax() completes
        callAjax2();
    }
    
    function callAjax2() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList2')) {
            $('.datatable-caseList2').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.edge.CompletedJob') }}",
                 data : { 
                            id : {{ $user->id ?? ''}}
                            
                        }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList2').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        
         callAjax3();
    }
    
    function callAjax3() {
        if ($.fn.DataTable.isDataTable('.datatable-caseList3')) {
            $('.datatable-caseList3').DataTable().destroy();
        }

        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        dtButtons.splice(0, 10);
        let dtOverrideGlobals = {
            buttons: dtButtons,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.home.edge.CanceledJob') }}",
                 data : { 
                            id : {{ $user->id ?? ''}}
                            
                        }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'ref_no', name: 'ref_no' },
                { data: 'customer_id', name: 'customer_id' },
                // { data: 'loan_type_id', name: 'loan_type_id' },
                @if( $roleTitle == 1)
                { data: 'assigned_client_id', name: 'assigned_client_id' },
                @endif
                { data: 'actions', name: 'actions' }
            ],
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,
        };

        $('.datatable-caseList3').DataTable(dtOverrideGlobals);

        // Adjust columns on tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    }
    
        // Call callAjax() on document ready
    callAjax();
    
    function callAjax4() {
                if ($.fn.DataTable.isDataTable('.datatable-caseList4')) {
                    $('.datatable-caseList4').DataTable().destroy();
                }
                
                let dtOverrideGlobals = {
                    retrieve: true,
                    aaSorting: [],
                    ajax: {
                        url: "{{ route('admin.home.edge.EnquiryJob') }}",
                        data : { 
                            id : {{ $user->id ?? ''}}
                            
                        }
                    },
                    columns: [
                        { data: 'placeholder', name: 'placeholder' },
                        { data: 'id', name: 'id' },
                        // { data: 'loan_category_type', name: 'loan_category_type' },
                        // { data: 'client_loan_amount', name: 'client_loan_amount' },
                        { data: 'client_first_name', name: 'client_first_name' },
                        // { data: 'client_email', name: 'client_email' },
                        // { data: 'client_phone', name: 'client_phone' },
                         @if($roleTitle == 1)
                            { data: 'created_by', name: 'created_by' },
                         @endif
                        // { data: 'created_date', name: 'created_date' },
                        // { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions' }
                    ],
                    orderCellsTop: true,
                    order: [[1, 'desc']],
                    pageLength: 10,
                };
                
                let table = $('.datatable-caseList4').DataTable(dtOverrideGlobals);
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                });
            }
   
    callAjax4();
    
    function agreements() {
    let id = "{{ $user->id ?? '' }}";
    
    let formData = new FormData();
    formData.append('id', id);
    
    $.ajax({
        data: formData,
        url: "{{ route('admin.partner-edge.agreements') }}",
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        dataType: 'json',
        contentType: false,   
        processData: false,   
        beforeSend: function() {
            loader.show();
        },
        success: function(response) {
            if (response.status && response.data) {
                $('#Agreement_folder').html(response.data);
            } else {
                $('#Agreement_folder').html('');
            }
            loader.hide();
        },
        error: function(data) {
            loader.hide();
        }
    });
}

      
      agreements();
      
     

    @endif
    
    $(document).ready(function(){
        
        function handleDeleteClick(deleteButtonId, checkboxClass, url, applicationIdKey) {
            $(deleteButtonId).on('click', function(event) {
             event.preventDefault();

        var $checkedBoxes = $(checkboxClass + ':checked');

        if ($checkedBoxes.length === 0) {
            Swal.fire('', 'Please select at least one item to delete.', 'error');
            return;
        }
      

            // Confirmation dialog
            Swal.fire({
                title: "Are You Sure?",
                text: "Do You Want to Delete the Image?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    var formData = new FormData();
                    $checkedBoxes.each(function() {
                        var image_id = $(this).data('image-id');
                        formData.append('image_ids[]', image_id); // Append multiple image_ids
                    });
                
                

                // Fetching the values from the window object using the provided keys
                var id = "{{$user->id ?? ''}}";
             

                formData.append('id', id);
              
                $('.secondLoader').show();
                loader.show();

                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('.secondLoader').hide();
                        loader.hide();

                        if (response.status) {
                            $checkedBoxes.each(function() {
                                var image_id = $(this).data('file-id');
                                $(`#${image_id}`).closest('.all_file_data').remove(); // Remove the image container
                            });
                            Swal.fire('', response.message, 'success');
                            $(deleteButtonId).hide();
                        } else {
                            Swal.fire('', response.message, 'error');
                        }
                    },
                    error: function() {
                        $('.secondLoader').hide();
                        loader.hide();
                        Swal.fire('', 'An error occurred. Please try again.', 'error');
                    }
                });

                // Hide the delete button if no checkboxes are left
                if ($(checkboxClass + ':checked').length === 0) {
                    $(deleteButtonId).hide();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire('Cancelled', 'Your data is safe :)', 'error');
            }
        });
    });
    }
        handleDeleteClick('#delete_selected', '.delete-checkbox', "{{ route('admin.partner-edge.filedelete') }}", 'applicationId');
    
    
        function toggleDeleteButtonVisibility(checkboxClass, deleteButtonId) {
    $(document).on('change', checkboxClass, function() {
        if ($(checkboxClass + ':checked').length > 0) {
            $(deleteButtonId).show();
        } else {
            $(deleteButtonId).hide();
        }
    });
    
    }
        toggleDeleteButtonVisibility('.delete-checkbox', '#delete_selected');



        
    })
        
        
    </script>
     @if (isset($user) && $user != '')
        @include('admin.application_stage.partials.caseList')
    @endif
@endsection