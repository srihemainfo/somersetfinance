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
        
    @endphp

@extends($key)

@section('content')

<style>
    .select2 {
        width: 100% !important;
    }
    .select2-container--default{
        width: 70% !important;
    }
</style>

    <div class="card">
   <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label for="search_clients">Search Partner <span class="required">*</span></label>
                    <div class="input-group">
                        <select class="form-control select2 select2-hidden-accessible" style="width: 70% !important;"
                            tabindex="-1" aria-hidden="true" id="search_clients" name="client_id"
                            data-control="select2" data-placeholder="Search Customer" data-hide-search="true">
                            <option
                                value="{{  $name ? $name : '' }}">
                                {{ $name ? $name : '' }} 
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
</div>
    
          <!--Fullscreen Preview Modal -->
        <div class="modal fade " id="fullscreenPreviewModal" tabindex="-1" role="dialog"
            aria-labelledby="fullscreenPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fullscreenPreviewModalLabel">Fullscreen Preview</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                         
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-close waves-effect waves-green btn-flat " data-dismiss="modal">Close</button>
                        
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


        
    <script>
    

        const staff = [];

        let loader = $('.loading-overlay');

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");



        

        $(document).ready(function() {
            $('.add_plus').each(function(index) {
                $(this).click(function() {
                    $(this).toggleClass('rotated');
                    $('.view_more').eq(index).toggle();
                });
            });
            
            
             $('#search_clients').select2({
            ajax: {
                url: "{{ route('admin.partner-edge.geter') }}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                delay: 400,
                data: function(params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        })
        
          $('#search_clients').change(function() {
              
              if ($(this).val()!= '') {
                var a = $(this).val();
                window.location.href = "{{ url('admin/partner-edge') }}/" + a;
               
            }
              
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
                            id : "{{ $user->id ?? ''}}"
                            
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
                            id : "{{ $user->id ?? ''}}"
                            
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
                            id : "{{ $user->id ?? ''}}"
                            
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
                            id : "{{ $user->id ?? ''}}"
                            
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
        
        let count = 1;

        let uploadimage = 0;
        let selectedFiles = [];
        
        
        
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
        handleDeleteClick('#delete_selected', '.delete-checkbox', "{{ route('admin.partner-edge.filedeletes') }}", 'applicationId');
    
    
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
        
        
        $(document).on('click', '[id^=upload_delete_]', function(event) {
            event.preventDefault();
            
            const tag = $(this);
            const buttonId = $(this).attr('id');
            const image_id = $(this).data('image-id');
            const file_id = $(this).data('file-id');
            let id, ajaxUrl;
            let container;
        
            if (buttonId.startsWith('upload_delete_')) {
                id = "{{ $user->id ?? ''}}";
                
                ajaxUrl = "{{ route('admin.partner-edge.filedelete') }}";
                container  = tag.closest('#uploaded_images_container1') ;
                
                
            } 
        
            let formData = new FormData();
            formData.append('id', id);
            formData.append('image_id', image_id);
        
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
                    loader.show();
        
                    $.ajax({
                        url: ajaxUrl,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status) {
                                const fileId = $(`[data-file-id='${file_id}']`).data('file-id');
                                const fileDiv = $(`#${fileId}`);
                                fileDiv.closest('.all_file_data').remove();
                            }
                            loader.hide();
                        },
                        error: function() {
                            loader.hide();
                        }
                    });
                }
            });
        });

    $(document).on('click', '[id^=upload_change_]', function(event) {
            event.preventDefault();
        
            const buttonId = $(this).attr('id');
            const fileId = $(this).data('file-id');
            const imageId = $(this).data('image-id');
            const input = $('<input>', { type: 'file', accept: 'image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
        
            let id,  ajaxUrl, number;
            
             if(buttonId.startsWith('upload_change_')) {
                id = "{{ $user -> id ?? ''}}";
                ajaxUrl = "{{ route('admin.partner-edge.agreements') }}";
                
            }
            
        
            let formData = new FormData();
            formData.append('id', id);
            formData.append('imageId', imageId);
            var index = fileId.split('_')[2] || fileId;
            formData.append('index', index);
        
            input.on('change', function(e) {
                const file = e.target.files[0];
                
                const minSizeMB = 10; // Minimum file size in MB
                const minSizeBytes = minSizeMB * 1024 * 1024;
                
                 const allowedTypes = [
                    'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp',
                    'application/pdf',
                    'application/msword', // .doc
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
                ];
                
                
                if (file && (allowedTypes.includes(file.type)) && (file.size <= minSizeBytes)) {
                    formData.append('file', file);
        
                    $('.secondLoader').show();
                    loader.show();
        
                    $.ajax({
                        url: ajaxUrl,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.data) {
                                const fileDiv = $(`#${fileId}`);
                                fileDiv.empty();
                                fileDiv.html(response.data);
                               
                            }
                            
                            loader.hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status) {
                                if (jqXHR.status == 500) {
                                    Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                                } else {
                                    Swal.fire('', jqXHR.status, 'error');
                                }
                            } else if (textStatus) {
                                Swal.fire('', textStatus, 'error');
                            } else {
                                Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText, "error");
                            }
                            loader.hide();
                        }
                    });
                }
            });
        
            input.click();
        });

    function handlePreviewClick(buttonSelector, modalSelector, ajaxUrl) {
        $(document).on('click', buttonSelector, function(event) {
            event.preventDefault();
    
            const fileId = $(this).data('file-id');
            const fileType = $(this).data('file-type');
            const imageId = $(this).data('image-id');
            const id = "{{ $user->id ?? ''}}";
            const modalBody = $(`${modalSelector} .modal-body`);
            modalBody.empty(); // Clear previous content
            window['imageId'] = imageId;
            const index = fileId.split('_').pop(); // Get the index from the fileId
    
            let formData = new FormData();
            formData.append('fileId', fileId);
            formData.append('fileType', fileType);
            formData.append('id', id);
            formData.append('imageId', imageId);
            formData.append('index', index);
    
            loader.show();
    
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loader.hide();
                    $('.secondLoader').hide();
                    if (response.status) {
                        modalBody.html(response.data);
                        
                        //  $('.modal').modal();
                         $('#fullscreenPreviewModal').modal('show');
    
                        // Initialize and open the modal
                        // const modalInstance = M.Modal.getInstance($(modalSelector));
                        // modalInstance.open();
    
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $('.loading-overlay').hide();
                },
                error: function(jqXHR, textStatus) {
                    loader.hide();
                    $('.loading-overlay').hide();
                    let errorMsg = 'Request Failed';
                    if (jqXHR.status) {
                        errorMsg = jqXHR.status === 500 ? 'Request Timeout / Internal Server Error' : jqXHR.status;
                    } else if (textStatus) {
                        errorMsg = textStatus;
                    }
                    Swal.fire('', errorMsg, 'error');
                }
            });
        });
    }
    
        // Assuming you're passing user ID dynamically
    handlePreviewClick('[id^=upload_preview_]', '#fullscreenPreviewModal', "{{ route('admin.partner-edge.upload_preview') }}");
    
    function handleFileUpload(inputSelector, containerSelector, ajaxUrl) {
            $(inputSelector).on('change', function(event) {
                const files = event.target.files;
                const container = $(containerSelector);
                let currentImageCount = uploadimage;
                
                var id = "{{ $user-> id ?? ''}}";
    
                // Display selected files
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                        const minSizeMB = 10; // Minimum file size in MB
                        const minSizeBytes = minSizeMB * 1024 * 1024;
                    
                     const allowedTypes = [
                        'image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp',
                        'application/pdf',
                        'application/msword', // .doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
                    ];
                    
                    
                    
                    if (file && (allowedTypes.includes(file.type)) && (file.size <= minSizeBytes) ) {
                        let formData = new FormData();
                        formData.append('file', file);
                        formData.append('id', id);
                        formData.append('currentImageCount', i === 0 ? currentImageCount : ++currentImageCount);
        
                        $('.secondLoader').show();
                        loader.show();
        
                        // Perform AJAX request
                        (function(fileData) {
                            $.ajax({
                                url: ajaxUrl,
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: fileData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.data) {
                                        container.append(response.data);
                                    }
                                    
                                    
                                    loader.hide();
                                },
                                error: function(jqXHR, textStatus) {
                                    let errorMsg = 'Request Failed';
                                    if (jqXHR.status) {
                                        errorMsg = jqXHR.status === 500 ? 'Request Timeout / Internal Server Error' : jqXHR.status;
                                    } else if (textStatus) {
                                        errorMsg = textStatus;
                                    }
                                    Swal.fire('', errorMsg, 'error');
                                    loader.hide();
                                }
                            });
                        })(formData);
                    }
                }
        
                // Reset the input value to allow re-upload of the same file
                $(this).val('');
            });
        }
        
            // Initialize file upload handlers
        handleFileUpload('#agreement', '#Agreement_folder', "{{ route('admin.partner-edge.agreements') }}");
    
    })
        
        
    </script>
     @if (isset($user) && $user != '')
        @include('admin.application_stage.partials.caseList')
    @endif
@endsection