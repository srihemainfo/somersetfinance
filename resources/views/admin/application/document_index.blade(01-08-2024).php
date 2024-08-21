@extends('layouts.admin')
@section('content')


@if(isset($isdocument) && $isdocument == true )

@php
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        if ($user) {
            $assignedRole = $user->roles->first();

            if ($assignedRole) {
                $roleTitle = $assignedRole->id;
            } else {
                $roleTitle = 0;
            }
        }
        // echo $roleTitle ;
    @endphp

<style>

input[type=checkbox] {
    width: 15px;
    height: 15px;
}

</style>

    <section class="content" style="padding-top: 20px;padding-bottom:20px;">

        <div class="container">
            <div class="row my-3">
                <div class="col-sm-12">
                    <div class="stage_view d-flex justify-content-evenly table-responsive customized_scrollbar">
                        <div class="stage completed enquiry"></div>
                        <div class="stage completed underwriting"></div>
                        <div class="stage current processing"></div>
                        <div class="stage upcoming completion"></div>
                        <div class="stage upcoming complete"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row my-3">
                <div class="col-sm-6">
                    <div class="profile_view">
                        <h1>{{ $data->customerdetail->name ?? 'N/a' }}</h1>
                        <p>
                            <i class="far fa-envelope"></i>
                            {{ $data->customerdetail->email ?? 'N/a' }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="profile_view text-right">
                        <h1>Case No: <span id="case_number" class="text-uppercase" title="Click to copy">{{ $data->ref_no ?? 'N/A' }}</span></h1>
                        <p>
                            <i class="far fa-file"></i>
                            {{ $data->loanType->title ?? 'N/a' }}
                            {{-- Second Charge --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">

            <!--<div class="card">-->
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 text-left row">
                                        <div class="col-sm-6">
                                            Profile Details
                                        </div>
                                        @if($roleTitle == 1)
                                        <div class="col-sm-6 text-right p-0">
                                             <button type="button" title="Edit Customer Details" id='applicant_edit' class="btn_borderless " data-applicant-id="{{ $data->customerdetail->id ?? 'N/a' }}" data-applicant-name="{{ $data->customerdetail->name ?? 'N/a' }}">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            {{--
                                            <!--<a href="{{ route('admin.application-stage.edit',$data->id) }}" title="Edit Details">-->
                                            <!--    <i class="far fa-edit"></i>--> --}}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<h5 class="card-title mb-3">Info</h5>-->
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0 profile_details">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Full Name :</th>
                                                <td class="text-muted">{{ $data->customerdetail->name ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Mobile :</th>
                                                <td class="text-muted">{{ $data->customerdetail->phone ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">E-mail :</th>
                                                <td class="text-muted">{{ $data->customerdetail->email ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Address :</th>
                                                
                                                @php
                                                
                                                $address1 = $data->customerdetail->address1 ?? '';
                                                $address2 = $data->customerdetail->address2 ?? '';
                                                
                                                // Remove trailing comma from address1 if it exists
                                                $address1 = rtrim($address1, ',');
                                                
                                                // Determine the appropriate separator
                                                $comma = ($address1 && $address2) ? ',<br>' : '';
                                                
                                                // Concatenate the addresses with the separator
                                                $full_address = $address1 . $comma . $address2;
                                                
                                                @endphp
                                                
                                                <td class="text-muted">{!! $full_address !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Creating Date :</th>
                                                <td class="text-muted">
                                                    {{ $data->created_at instanceof \Carbon\Carbon
                                                        ? $data->created_at->format('Y-m-d H:i:s')
                                                        : ($data->created_at ? date('d-m-Y H:i:s', strtotime($data->created_at)) : 'N/a')
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 text-left row">
                                        <div class="col-sm-6">
                                            Loan Details
                                        </div>
                                        @if($roleTitle == 1)
                                        <div class="col-sm-6 text-right p-0">
                                            <a href="{{ route('admin.application-stage.edit',$data->id) }}" title="Edit Details">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<h5 class="card-title mb-3">Info</h5>-->
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0 profile_details">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Loan Type :</th>
                                                <td class="text-muted">{{$data->loanType->title ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Loan Amount :</th>
                                                <td class="text-muted">{{$data->loan_amount ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Term :</th>
                                                <td class="text-muted">{{$data->term ?? 'N/a' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Rate :</th>
                                                <td class="text-muted">{{$data->rate ?? 'N/a' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Proc Fee :</th>
                                                <td class="text-muted">{{$data->proc_fee ?? 'N/a' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!--</div>-->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="documents_view">

                        @if((isset($data->applicationLoanDocument2)  && count($data->applicationLoanDocument2) > 0) || ( isset($data->applicantDocument1)  && count($data->applicantDocument1) > 0 ))
                        <div class="card">
                            <div class="card-header">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 text-left">
                                        Document Uploading Details
                                    </div>
                                </div>

                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:10%;">S.NO</th>
                                            <th scope="col">Name</th>
                                            <th scope="col" style="width:10%;" class="text-center">Status</th>
                                            <th scope="col" style="width:20%;" class="text-center">Upload</th>
                                             @if($roleTitle ==1)
                                             <th scope="col" style="width:20%;" class="text-center">Delete</th>
                                             @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $si = 1;
                                        @endphp
                                        @if((isset($data->applicationLoanDocument2) && !empty($data->applicationLoanDocument2) ))
                                        @foreach($data->applicationLoanDocument2 as $document)

                                        @php
                                        
                               
                                            // Fetch the related image for this document

                                            // $documentImage = $documentImages->where('document_id', $document->id)->where('status', 'rejected');

                                            $documentImagesForDocument = $documentImages->where('document_id', $document->id);

                                                // Count the images based on their status
                                                // $pendingCount = $documentImagesForDocument->where('status', 'Pending')->count();
                                                $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                                $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                                $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                                                $reuploadCount = $documentImagesForDocument->where('status', 'Reupload')->count();
                                            @endphp
                                                <tr>
                                                    <th scope="row">{{ $si }}</th>
                                                    <td>{{ $document->title ?? N/A }}</td>
                                                    <td class="text-center image_container_0_{{ $data->id }}_{{$document->id }}">
                                                        @if($documentImagesForDocument)
                                                            @if(  $reuploadCount || $rejectedCount)
                                                            
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    
                                                                     @if($rejectedCount)
                                                                         <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                            <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            
                                                            @elseif($uploadCount)
                                                                <img title="Uploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                        @else
                                                            <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                        @endif

                                                    </td>
                                                    {{-- <td class="text-center image_container_0_{{ $data->id }}_{{$document->id }}">
                                                        <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                    </td> --}}
                                                    <td class="text-center">
                                                        <button type="button" title="Upload Document" class="btn_borderless upload_document_modal text-primary" data-toggle="modal"
                                                        data-target="#upload_document_modal1"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                        <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                    </button>


                                                        {{-- <button type="button" class="btn btn-success upload_document_modal" data-toggle="modal"
                                                            data-target="#upload_document_modal1"
                                                                data-application-id="{{ $data->id }}"
                                                                data-document-id="{{ $document->id }}"
                                                            >Upload</button> --}}
                                                    </td>
                                                     @if($roleTitle ==1)
                                                    <td class="text-center">
                                                        <button type="button" title="Deleted Document" class="btn_borderless upload_document_delete text-danger" data-toggle="modal"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                       <i class="fas fa-trash"></i>
                                                    </button>


                                                    </td> 
                                                    @endif
                                                </tr>

                                                @php
                                                $si++;

                                                @endphp


                                        @endforeach
                                        @endif

                                        @if (isset($data->applicantDocument1) && !empty($data->applicantDocument1))

                                        @php

                                        // $si = 1;
                                        @endphp
                                        @foreach($data->applicantDocument1 as $document)

                                        @php

                                            // Fetch the related image for this document

                                            // $documentImage = $documentImages->where('document_id', $document->id)->where('status', 'rejected');

                                            $documentImagesForDocument = $documentImages2->where('document_id', $document->id);

                                            // dd($documentImagesForDocument  );
                                                // Count the images based on their status
                                                // $pendingCount = $documentImagesForDocument->where('status', 'Pending')->count();
                                                $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                                $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                                $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                                                 $reuploadCount = $documentImagesForDocument->where('status', 'Reupload')->count();
                                            @endphp
                                                <tr>
                                                    <th scope="row">{{ $si }}</th>
                                                    <td>{{ $document->title ?? N/A }}</td>
                                                    <td class="text-center image_container_0_{{ $data->id }}_{{$document->id }}">
                                                          @if($documentImagesForDocument)
                                                            @if(  $reuploadCount || $rejectedCount)
                                                            
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    
                                                                     @if($rejectedCount)
                                                                         <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                            <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            
                                                            @elseif($uploadCount)
                                                                <img title="Uploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                        @else
                                                            <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                        @endif
                                                    </td>
                                                    {{-- <td class="text-center">
                                                        <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                    </td> --}}
                                                    <td class="text-center">

                                                        <button type="button" title="Upload Document" class="btn_borderless upload_document_modal text-primary " data-toggle="modal"
                                                        data-target="#upload_document_modal11"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                        <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                    </button>


                                                        {{-- <button type="button" class="btn btn-success upload_document_modal" data-toggle="modal"
                                                            data-target="#upload_document_modal"
                                                                data-application-id="{{ $data->id }}"
                                                                data-document-id="{{ $document->id }}"
                                                            >Upload</button> --}}
                                                    </td>
                                                   @if($roleTitle ==1)
                                                      <td class="text-center">
                                                        <button type="button" title="Deleted Document" class="btn_borderless upload_document_delete text-danger " data-toggle="modal"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                       <i class=" fa-fw nav-icon fas fa-trash"></i>
                                                    </button>


                                                    </td> 
                                                    @endif
                                                </tr>

                                                @php
                                                $si++;
                                                @endphp
                                        @endforeach

                                        @endif

                                    </tbody>
                                    
                                      <tbody>
                                    @php
                                    $si =  $si ?? 1;
                                    @endphp
                                    @if((isset($data->additionalDocument2) && count($data->additionalDocument2) >0 ))

                                 
                                    @foreach($data->additionalDocument2 as $document)

                                    @php
                                       
                                        $documentImagesForDocument = $documentImages5->where('additional_id', $document->id);
                                        $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                        $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                        $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                                         $reuploadCount = $documentImagesForDocument->where('status', 'Reupload')->count();
                                    @endphp
                                            <tr>
                                                <th scope="row">{{ $si }}</th>
                                                <td>{{ $document->title ?? N/A }}</td>
                                                <td class="text-center image_container_2_{{ $data->id }}_{{$document->id }}">
                                                    @if($documentImagesForDocument)
                                                            @if(  $reuploadCount || $rejectedCount)
                                                            
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    
                                                                     @if($rejectedCount)
                                                                         <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                            <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            
                                                            @elseif($uploadCount)
                                                                <img title="Uploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                        @else
                                                            <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                        @endif
                                                </td>
                                                {{-- <td class="text-center">
                                                    <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                </td> --}}
                                                <td class="text-center">
                                                    <button type="button" title="Upload Document" class="btn_borderless upload_additional_document_modal text-primary" data-toggle="modal"
                                                    data-target="#upload_document_modal1"
                                                        data-application-id="{{ $data->id }}"
                                                        data-document-id="{{ $document->id }}"
                                                    >
                                                    <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                </button>
                                                </td>
                                                 @if($roleTitle ==1)
                                                 <td class="text-center">
                                                        <button type="button" title="Deleted Document" class="btn_borderless upload_additional_document_delete text-danger" data-toggle="modal"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                       <i class=" fa-fw nav-icon fas fa-trash"></i>
                                                    </button>


                                                    </td> 
                                                    @endif
                                            </tr>

                                            @php
                                            $si++;

                                            @endphp


                                    @endforeach
                                    @endif

                                </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                        <div class="documents_view">
                            <div class="card">
                                @if((isset($data->applicantformUpload2)  && count($data->applicantformUpload2) > 0 )|| ( isset($data->applicationLoanFormUpload2)  && count($data->applicationLoanFormUpload2) > 0 ))
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 text-left">
                                            Form Uploading Details
                                        </div>

                                    </div>

                                </div>

                                {{-- <div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 text-left">
                                            Form Uploading Details
                                        </div>

                                    </div>
                                </div> --}}

                                <div class="card-body table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:10%;">S.NO</th>
                                                <th scope="col">Name</th>
                                                <th scope="col" style="width:10%;" class="text-center">Status</th>
                                                <th scope="col" style="width:10%;" class="text-center">Link</th>
                                                <th scope="col" style="width:20%;" class="text-center">Upload</th>
                                                 @if($roleTitle ==1)
                                                  <th scope="col" style="width:20%;" class="text-center">Delete</th>
                                                  @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $si = 1;
                                            @endphp
                                            @if(isset($data->applicantformUpload2))
                                            @foreach($data->applicantformUpload2 as $document)
                                            @php
                                                // Fetch the related image for this document

                                                // $documentImage = $documentImages->where('document_id', $document->id)->where('status', 'rejected');

                                                $documentImagesForDocument = $documentImages3->where('form_id', $document->id);

                                                // dd($documentImagesForDocument  );
                                                    // Count the images based on their status
                                                    // $pendingCount = $documentImagesForDocument->where('status', 'Pending')->count();
                                                    $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                                    $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                                    $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                                                    $reuploadCount = $documentImagesForDocument->where('status', 'Reupload')->count();
                                                @endphp
                                                    <tr>
                                                        <th scope="row">{{ $si }}</th>
                                                        <td>{{ $document->title ?? N/A }}</td>
                                                        <td class="text-center image_container_1_{{ $data->id }}_{{$document->id }}">
                                                            @if($documentImagesForDocument)
                                                            @if(  $reuploadCount || $rejectedCount)
                                                            
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    
                                                                     @if($rejectedCount)
                                                                         <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                            <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            
                                                            @elseif($uploadCount)
                                                                <img title="Uploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                        @else
                                                            <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                        @endif
                                                        </td>
                                                        @php
                                                        $fileExtension = pathinfo($document->file_name, PATHINFO_EXTENSION);

                                                        @endphp
                                                        <td class="text-center">
                                                            <a class="btn_borderless download_btn" title="Document Download" href="{{ asset('formupload/'. $document->file_name) }}" target='_blank'>
                                                                <i class="fa-fw nav-icon fas fa-solid fa-download"></i>
                                                            </a>
                                                            {{-- <a href="{{ asset('formupload/'. $document->file_name) }}" target='_blank'>download</a> --}}
                                                            {{-- <img src="{{ asset('formupload/'. $document->file_name) }}" class="document_status_img"> --}}
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" title="Upload Document" class="btn_borderless upload_form_modal text-primary "
                                                            data-toggle="modal"

                                                                    data-application-id="{{ $data->id }}"
                                                                    data-document-id="{{ $document->id }}">
                                                                <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                            </button>

                                                            {{-- <button type="button" class="btn btn-success upload_form_modal" data-toggle="modal"

                                                                    data-application-id="{{ $data->id }}"
                                                                    data-document-id="{{ $document->id }}"
                                                                >Upload</button> --}}
                                                        </td>
                                                         @if($roleTitle ==1)
                                                         <td class="text-center">
                                                        <button type="button" title="Deleted Document" class="btn_borderless upload_form_delete text-danger " data-toggle="modal"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                       <i class=" fa-fw nav-icon fas fa-trash"></i>
                                                    </button>


                                                    </td> 
                                                    @endif
                                                    </tr>

                                                    @php
                                                    $si++;

                                                    @endphp


                                            @endforeach

                                            @endif

                                            @if (isset($data->applicationLoanFormUpload2))

                                            @php
                                            // $si = 1;
                                            @endphp
                                            @foreach($data->applicationLoanFormUpload2 as $document)

                                            @php
                                                // Fetch the related image for this document

                                                // $documentImage = $documentImages->where('document_id', $document->id)->where('status', 'rejected');

                                                $documentImagesForDocument = $documentImages4->where('form_id', $document->id);

                                                    // Count the images based on their status
                                                    // $pendingCount = $documentImagesForDocument->where('status', 'Pending')->count();
                                                    $acceptedCount = $documentImagesForDocument->where('status', 'Accepted')->count();
                                                    $rejectedCount = $documentImagesForDocument->where('status', 'Rejected')->count();
                                                    $uploadCount = $documentImagesForDocument->where('status', 'Upload')->count();
                                                      $reuploadCount = $documentImagesForDocument->where('status', 'Reupload')->count();
                                                @endphp
                                                    <tr>
                                                        <th scope="row">{{ $si }}</th>
                                                        <td>{{ $document->title ?? N/A }}</td>
                                                        <td class="text-center image_container_1_{{ $data->id }}_{{$document->id }}">
                                                            @if($documentImagesForDocument)
                                                            @if(  $reuploadCount || $rejectedCount)
                                                            
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    
                                                                     @if($rejectedCount)
                                                                         <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                            <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            
                                                            @elseif($uploadCount)
                                                                <img title="Reuploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                        @else
                                                            <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                        @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn_borderless download_btn" title="Document Download" href="{{ asset('formupload/'. $document->file_name) }}" target='_blank'>
                                                                <i class="fa-fw nav-icon fas fa-solid fa-download"></i>
                                                            </a>
                                                            {{-- <a href="{{ asset('formupload/'. $document->file_name) }}" target='_blank'>download</a> --}}
                                                            {{-- <img src="{{ asset('formupload/file_success.png') }}" class="document_status_img"> --}}
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" title="Upload Document" class="btn_borderless upload_form_modal text-primary"
                                                            data-toggle="modal"

                                                                    data-application-id="{{ $data->id }}"
                                                                    data-document-id="{{ $document->id }}">
                                                                <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                            </button>

                                                            {{-- <button type="button" class="btn btn-success upload_document_modal" data-toggle="modal"
                                                                data-target="#upload_document_modal"
                                                                    data-application-id="{{ $data->id }}"
                                                                    data-document-id="{{ $document->id }}"
                                                                >Upload</button> --}}
                                                        </td>
                                                         @if($roleTitle ==1)
                                                         <td class="text-center">
                                                        <button type="button" title="Deleted Document" class="btn_borderless upload_form_delete text-danger" data-toggle="modal"
                                                            data-application-id="{{ $data->id }}"
                                                            data-document-id="{{ $document->id }}"
                                                        >
                                                       <i class=" fa-fw nav-icon fas fa-trash"></i>
                                                    </button>
                                                    @endif


                                                    </td> 
                                                    </tr>

                                                    @php
                                                    $si++;
                                                    @endphp
                                            @endforeach

                                            @endif
                                            
                                            
                                             {{-- today --}}
                                            @if (isset($data->additionFormUploads) && count($data->additionFormUploads) > 0 )
                                                @php
                                                    $si = $si ?? 1; // Initialize the counter
                                                @endphp
                                                @foreach($data->additionFormUploads as $document)
                                                    @php
                                                    
                                                        // Count the images based on their status
                                                        $acceptedCount = $data->ApplicationAdditionFormUploads->where('status', 'Accepted')->count();
                                                        $rejectedCount = $data->ApplicationAdditionFormUploads->where('status', 'Rejected')->count();
                                                        $uploadCount =$data->ApplicationAdditionFormUploads->where('status', 'Upload')->count();
                                                        $reuploadCount = $data->ApplicationAdditionFormUploads->where('status', 'Reupload')->count();
                                                    @endphp
                                                    <tr>
                                                        <th scope="row">{{ $si }}</th>
                                                        <td>{{ $document->title ?? 'N/A' }}</td>
                                                        <td class="text-center image_container_3_{{ $data->id }}_{{$document->id }}">
                                                          
                                                            @if($reuploadCount || $rejectedCount)
                                                                @if($roleTitle == 1)
                                                                    @if($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @elseif($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @endif
                                                                @else
                                                                    @if($rejectedCount)
                                                                        <img title="Canceled" src="{{ asset('formupload/file_cancel.png') }}" class="document_status_img">
                                                                    @elseif($reuploadCount)
                                                                        <img title="Reuploaded" src="{{ asset('formupload/stage_reupload.png') }}" class="document_status_img">
                                                                    @endif
                                                                @endif
                                                            @elseif($uploadCount)
                                                                <img title="Uploaded" src="{{ asset('formupload/file_uploaded.png') }}" class="document_status_img">
                                                            @elseif($acceptedCount)
                                                                <img title="Succeed" src="{{ asset('formupload/file_success.png') }}" class="document_status_img">
                                                            @else
                                                                <img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">
                                                            @endif
                                                           
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn_borderless download_btn" title="Document Download" href="{{ asset('formupload/'. $document->file_path) }}" target='_blank'>
                                                                <i class="fa-fw nav-icon fas fa-solid fa-download"></i>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" title="Upload Document" class="btn_borderless upload_additional_form_modal text-primary"
                                                                    data-toggle="modal"
                                                                    data-application-id="{{ $data->id }}"
                                                                    data-document-id="{{ $document->id }}">
                                                                <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
                                                            </button>
                                                        </td>
                                                        @if($roleTitle == 1)
                                                            <td class="text-center">
                                                                <button type="button" title="Delete Document" class="btn_borderless upload_additional_form_delete text-danger" data-toggle="modal"
                                                                        data-application-id="{{ $data->id }}"
                                                                        data-document-id="{{ $document->id }}">
                                                                    <i class="fa-fw nav-icon fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @php
                                                        $si++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        {{-- today end --}}

                                            
                                        </tbody>
                                    </table>
                                </div>

                                @endif
                            </div>
                        </div>
            </div>
        
            </div>

        </div>


        <!-- Document Upload Modal -->
        <div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" id='form_data'>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Document Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <label for="file_upload" class="labelFile">
                                    <span>
                                        <svg style="left:50%;" xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                            id="Capa_1" version="1.1" width="60px" height="60px">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813
                                                C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834
                                                C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538
                                                c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392
                                                c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094
                                                c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168
                                                c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391
                                                v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill:#010002;"></path>
                                                    </g>
                                                    <g>
                                                        <path d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078
                                                c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045
                                                L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227
                                                c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036
                                                C104.91,91.608,107.183,91.608,108.586,90.201z" style="fill:#010002;">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>drag and drop your file here or click to select a file!</p>
                                </label>
                                <input class="input" name="text[]" id="file_upload" type="file"
                                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                            </div>
                        </div>

                        <div class="row my-4" id="uploaded_images_container">
                            <input type="hidden" name="application_id" id="modal_application_id">
                            <input type="hidden" name="document_id" id="modal_document_id">
                            <!-- Uploaded images will be displayed here -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="delete_selected">Delete Selected</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle !=1)
                        <button type="button" class="btn btn-primary" id='submit_button'>Save changes</button>
                        @endif
                    </div>
                </form>
                </div>
            </div>
        </div>

        {{-- additional Document --}}
           <!-- Document Upload Modal -->
           <div class="modal fade" id="additional_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" id='form_data'>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Additioanl Document Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <label for="file_upload_two" class="labelFile">
                                    <span>
                                        <svg style="left:50%;" xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                            id="Capa_1" version="1.1" width="60px" height="60px">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813
                                                C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834
                                                C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538
                                                c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392
                                                c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094
                                                c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168
                                                c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391
                                                v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill:#010002;"></path>
                                                    </g>
                                                    <g>
                                                        <path d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078
                                                c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045
                                                L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227
                                                c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036
                                                C104.91,91.608,107.183,91.608,108.586,90.201z" style="fill:#010002;">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>drag and drop your file here or click to select a file!</p>
                                </label>
                                <input class="input" name="text[]" id="file_upload_two" type="file"
                                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                            </div>
                        </div>

                        <div class="row my-4" id="uploaded_images_container2">
                            <input type="hidden" name="application_id2" id="modal_application_id2">
                            <input type="hidden" name="document_id2" id="modal_document_id2">
                            <!-- Uploaded images will be displayed here -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="delete_selected2">Delete Selected</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle !=1)
                        <button type="button" class="btn btn-primary" id='submit_button2'>Save changes</button>
                        @endif
                    </div>
                </form>
                </div>
            </div>
        </div>
        
         <div class="modal fade" id="upload_document_modal_1" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" id='form_data'>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <label for="file_upload1" class="labelFile">
                                    <span>
                                        <svg style="left:50%;" xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                            id="Capa_1" version="1.1" width="60px" height="60px">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813
                                                C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834
                                                C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538
                                                c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392
                                                c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094
                                                c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168
                                                c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391
                                                v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill:#010002;"></path>
                                                    </g>
                                                    <g>
                                                        <path d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078
                                                c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045
                                                L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227
                                                c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036
                                                C104.91,91.608,107.183,91.608,108.586,90.201z" style="fill:#010002;">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>drag and drop your file here or click to select a file!</p>
                                </label>
                                <input class="input" name="text[]" id="file_upload1" type="file"
                                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                            </div>
                        </div>

                        <div class="row my-4" id="uploaded_images_container1">
                            <input type="hidden" name="application_id1" id="modal_application_id1">
                            <input type="hidden" name="document_id1" id="modal_document_id1">
                            <!-- Uploaded images will be displayed here -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" style='display:none' id="delete_selected1">Delete Selected</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle !=1)
                        <button type="button" class="btn btn-primary" id='submit_button1'>Save changes</button>
                        @endif
                    </div>
                </form>
                </div>
            </div>
        </div>

         <div class="modal fade" id="upload_additional_form_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" id='form_data'>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Additional Form Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <label for="file_upload3" class="labelFile">
                                    <span>
                                        <svg style="left:50%;" xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                            id="Capa_1" version="1.1" width="60px" height="60px">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813
                                                C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834
                                                C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538
                                                c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392
                                                c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094
                                                c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168
                                                c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391
                                                v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill:#010002;"></path>
                                                    </g>
                                                    <g>
                                                        <path d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078
                                                c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045
                                                L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227
                                                c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036
                                                C104.91,91.608,107.183,91.608,108.586,90.201z" style="fill:#010002;">
                                                        </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>drag and drop your file here or click to select a file!</p>
                                </label>
                                <input class="input" name="text[]" id="file_upload3" type="file"
                                    accept=".jpg, .jpeg, .png, .pdf" multiple>
                            </div>
                        </div>

                        <div class="row my-4" id="uploaded_additional_images_container">
                            <input type="hidden" name="application_id3" id="modal_additional_application_id">
                            <input type="hidden" name="document_id3" id="modal_additional_document_id">
                            <!-- Uploaded images will be displayed here -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" style='display:none' id="delete_selected3">Delete Selected</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle !=1)
                        <button type="button" class="btn btn-primary" id='submit_button3'>Save changes</button>
                        @endif
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Fullscreen Preview Modal -->
        <div class="modal fade" id="fullscreenPreviewModal" tabindex="-1" role="dialog"
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
                        <!-- Fullscreen content will be injected here -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle ==1)
                            <button type="button" class="btn btn-primary remark_submit">Save changes</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

          <!-- Fullscreen additional Preview Modal -->
          <div class="modal fade" id="fullscreenPreviewModal_2" tabindex="-1" role="dialog"
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
                      <!-- Fullscreen content will be injected here -->
                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      @if($roleTitle ==1)
                      <button type="button" class="btn btn-primary remark_submit2">Save changes</button>
                      @endif
                  </div>
              </div>
          </div>
      </div>


        <div class="modal fade" id="fullscreenPreviewModal1" tabindex="-1" role="dialog"
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
                    <!-- Fullscreen content will be injected here -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if($roleTitle ==1)
                    <button type="button" class="btn btn-primary remark_submit1">Save changes</button>
                    @endif
                </div>
            </div>
        </div>


    </div>
    
         <div class="modal fade" id="fullscreenPreviewModal_3" tabindex="-1" role="dialog"
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
                        <!-- Fullscreen content will be injected here -->
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($roleTitle ==1)
                        <button type="button" class="btn btn-primary remark_submit_3">Save changes</button>
                        @endif
                    </div>
                </div>
            </div>


    </div>

    </section>
    
      @include('admin.application.partials.add_customer_modal')
    
    @else
    <div class="card">

        <div class="card-body table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                        <th colspan='4' class='text-center' >NO Data Available</th>

                    </tr>
                </thead>

            </table>
        </div>
    </div>
    
    

    @endif
    
    
    
    

@endsection
@if(isset($isdocument) && $isdocument == true)
@section('scripts')
<script>

    let loader = $('.loading-overlay') ;


    $(document).ready(function() {
        
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let count = 1;

        let uploadimage = 0;
        let selectedFiles = [];


        function addOrUpdateFileInSelectedFiles(file, remark, uploaded_file) {
            const existingFileIndex = selectedFiles.findIndex(item => item.uploaded_file === uploaded_file);
            if (existingFileIndex !== -1) {
                // Update the existing entry
                selectedFiles[existingFileIndex] = { file: file, remark: remark, uploaded_file: uploaded_file };
            } else {
                // Add a new entry
                selectedFiles.push({ file: file, remark: remark, uploaded_file: uploaded_file });
            }
        }



        
        function handleRemarkSubmit(buttonSelector, ajaxUrl, applicationIdKey, documentIdKey) {
        $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();
        loader.show();
        let number;
        if(buttonSelector == '.remark_submit'){
            number = 0 ;
        
        }else if(buttonSelector == '.remark_submit2'){
             number = 2 ;
            
        }
        else if(buttonSelector == '.remark_submit1'){
             number = 1 ;
        }
        else if(buttonSelector == '.remark_submit_3'){
             number = 3 ;
        }

        var applicationId = window[applicationIdKey];
        var documentId = window[documentIdKey];
        var imageId = window['imageId'];
        var radio = $('input[name="radio"]:checked').val();
        var admin_remark = $('input[name="admin_remark"]').val();

        let formData = new FormData();
        formData.append('applicationId', applicationId);
        formData.append('documentId', documentId);
        formData.append('imageId', imageId);
        formData.append('radio', radio);
        formData.append('admin_remark', admin_remark);

        $('.secondLoader').show();
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
                $('.secondLoader').hide();
                let status = response.status;
                if (status === true) {
                    
                    if(response.image_path){
                         $('.image_container_' + number + '_' + applicationId +  '_'    + documentId ).empty().append(response.image_path)
                    }
                    
                    Swal.fire('', response.message, 'success');
                    
                    
                } else {
                    Swal.fire('', response.message, 'error');
                }
                loader.hide();
                if (buttonSelector === '.remark_submit1') {
                    window['imageId'] = ''; // Clear imageId only for .remark_submit1
                }
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
    });
        }

        // Initialize submit button handlers
        handleRemarkSubmit('.remark_submit', "{{ route('admin.document.remarkUpdatedadmin') }}", 'applicationId1', 'documentId1');
        handleRemarkSubmit('.remark_submit2', "{{ route('admin.document.remarkUpdatedadmin2') }}", 'applicationId2', 'documentId2');
        handleRemarkSubmit('.remark_submit1', "{{ route('admin.document.formremarkUpdatedadmin') }}", 'applicationId1', 'documentId1');
        handleRemarkSubmit('.remark_submit_3', "{{ route('admin.document.additionalformremarkUpdatedadmin') }}", 'applicationId3', 'documentId3');
        
        // remark_submit_3

        
        function handleSubmitButtonClick(buttonSelector, ajaxUrl, applicationIdKey, documentIdKey, containerSelector) {
            $(document).on('click', buttonSelector, function(e) {
                e.preventDefault();
                loader.show();
        
                var applicationId = window[applicationIdKey];
                var documentId = window[documentIdKey];
                let inputNames = [];
        
                $(containerSelector + ' form').each(function() {
                    inputNames.push($(this).serialize());
                });
        
                let formData = new FormData();
                formData.append('applicationId', applicationId);
                formData.append('documentId', documentId);
                formData.append('inputNames', JSON.stringify(inputNames));
        
                $('.secondLoader').show();
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
                        $('.secondLoader').hide();
                        if (response.status === true) {
                            Swal.fire('', response.message, 'success');
                        } else {
                            Swal.fire('', response.message, 'error');
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
            });
        }

        // Initialize submit button handlers
        handleSubmitButtonClick('#submit_button', "{{ route('admin.document.remarkUpdated') }}", 'applicationId1', 'documentId1', '#uploaded_images_container');
        handleSubmitButtonClick('#submit_button1', "{{ route('admin.document.formremarkUpdated') }}", 'applicationId1', 'documentId1', '#uploaded_images_container1');
        handleSubmitButtonClick('#submit_button2', "{{ route('admin.document.additionalremarkUpdated') }}", 'applicationId2', 'documentId2', '#uploaded_images_container2');
        handleSubmitButtonClick('#submit_button3', "{{ route('admin.document.additionalFormremarkUpdated') }}", 'applicationId3', 'documentId3', '#uploaded_additional_images_container');





    function handleModalUploadClick(buttonSelector, ajaxUrl, modalId, containerSelector, applicationIdKey, documentIdKey, modalApplicationIdField, modalDocumentIdField) {
        $(document).on('click', buttonSelector, function() {
            $('.loading-overlay').show();
            loader.show();
    
            var applicationId = $(this).data('application-id');
            var documentId = $(this).data('document-id');
    
            if (applicationId === undefined || documentId === undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
                return;
            }
    
            window[applicationIdKey] = applicationId;
            window[documentIdKey] = documentId;
    
            let formData = new FormData();
            formData.append('applicationId', applicationId);
            formData.append('documentId', documentId);
    
            $('.secondLoader').show();
            const container = $(containerSelector);
            container.empty();
           
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
                    $('.secondLoader').hide();
                    if (response.status === true) {
                        $(modalApplicationIdField).val(applicationId);
                        $(modalDocumentIdField).val(documentId);
                        $(modalId).modal('show');
                       
                        container.append(response.data);
    
                        let currentImageCount = container.children().length;
                        console.log(currentImageCount);
                            uploadimage = currentImageCount;
                             
                    } else {
                        Swal.fire('', response.data, 'error');
                    }
                    $('.loading-overlay').hide();
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
                    $('.loading-overlay').hide();
                    loader.hide();
                }
            });
        });
    }
    
    
        // Initialize modal handlers
    handleModalUploadClick('.upload_document_modal', "{{ route('admin.document.upload_image_get') }}", '#upload_document_modal', '#uploaded_images_container', 'applicationId1', 'documentId1', '#modal_application_id', '#modal_document_id');
    handleModalUploadClick('.upload_additional_document_modal', "{{ route('admin.document.upload_addition_image_get') }}", '#additional_modal', '#uploaded_images_container2', 'applicationId2', 'documentId2', '#modal_application_id2', '#modal_document_id2');
    handleModalUploadClick('.upload_form_modal', "{{ route('admin.document.upload_image_get1') }}", '#upload_document_modal_1', '#uploaded_images_container1', 'applicationId1', 'documentId1', '#modal_application_id1', '#modal_document_id1');
    handleModalUploadClick('.upload_additional_form_modal', "{{ route('admin.document.upload_additional_image_get') }}", '#upload_additional_form_modal', '#uploaded_additional_images_container', 'applicationId3', 'documentId3', '#modal_additional_application_id', '#modal_additional_document_id');


    //upload_additional_form_modal
    
    
    function handleFileUpload(inputSelector, containerSelector, ajaxUrl, applicationIdKey, documentIdKey) {
        $(inputSelector).on('change', function(event) {
            const files = event.target.files;
            const container = $(containerSelector);
            let currentImageCount = uploadimage;
            let number;
            
            if(containerSelector == '#uploaded_images_container'){
                number = 0;
                
            }else if(containerSelector ==  '#uploaded_images_container1') {
                 number = 1;
            }else if(containerSelector == '#uploaded_images_container2'){
                 number = 2;
                
            }
            else if(containerSelector == '#uploaded_additional_images_container'){
                 number = 3;
                
            }
    
            var applicationId = window[applicationIdKey];
            var documentId = window[documentIdKey];
    
            // Display selected files
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('applicationId', applicationId);
                    formData.append('documentId', documentId);
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
                                
                                  if(response.image_path){
                                    $('.image_container_' + number + '_' + applicationId +  '_'    + documentId ).empty().append(response.image_path)
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
    handleFileUpload('#file_upload', '#uploaded_images_container', "{{ route('admin.document.upload_image_get') }}", 'applicationId1', 'documentId1');
    handleFileUpload('#file_upload1', '#uploaded_images_container1', "{{ route('admin.document.upload_form_image_get') }}", 'applicationId1', 'documentId1');
    handleFileUpload('#file_upload_two', '#uploaded_images_container2', "{{ route('admin.document.upload_additional_image_get') }}", 'applicationId2', 'documentId2');
    handleFileUpload('#file_upload3', '#uploaded_additional_images_container', "{{ route('admin.document.upload_additional_image_get') }}", 'applicationId3', 'documentId3');
   




        // Function to create new added content dynamically
        function createNewAddedContent(index,remark) {
           
            const newContent = $('<div>', { class: 'container-fluid', id: `new_added_content_${index}` });

            const pane = $('<div>', { class: 'pane m-3' });
            const idkLabel = $('<label>', { class: 'label' }).html(`<span>Idk</span><input id="idk_${index}" class="input idk" name="radio" type="radio" checked="checked" value="">`);
            const yesLabel = $('<label>', { class: 'label' }).html(`<span>Yes</span><input id="yes_${index}" class="input yes" name="radio" type="radio" value="Verified">`);
            const noLabel = $('<label>', { class: 'label' }).html(`<span>No</span><input id="no_${index}" class="input no" name="radio" type="radio" value="Canceled>`);
            pane.append(idkLabel, yesLabel, noLabel, $('<span>', { class: 'selection' }));
            const extraInput = $('<div>', { class: 'extra-input m-3', id: `extra-input_${index}` });
            const extraText = $('<input>', { type: 'text', name: 'admin_remark', id: `extra-text_${index}`, class: 'form-control', placeholder: 'Reason', value:remark });
            extraInput.append(extraText);

            newContent.append(pane, extraInput);
            return newContent;
        }

        
        // Function to handle fullscreen preview
function handlePreviewClick(buttonSelector, modalSelector, ajaxUrl) {
    $(document).on('click', buttonSelector, function(event) {
        event.preventDefault();

        const fileId = $(this).data('file-id');
        const fileType = $(this).data('file-type');
        const imageId = $(this).data('image-id');
        const appId = $(this).data('app-id');
        const documentId = $(this).data('document-id');
        const modalBody = $(`${modalSelector} .modal-body`);
        modalBody.empty(); // Clear previous content
        window['imageId'] = imageId;
        const index = fileId.split('_').pop(); // Get the index from the fileId

        let formData = new FormData();
        formData.append('fileId', fileId);
        formData.append('fileType', fileType);
        formData.append('appId', appId);
        formData.append('documentId', documentId);
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
                $('.secondLoader').hide();
                if (response.status) {
                    modalBody.html(response.data);
                    $(modalSelector).modal('show');
                } else {
                    Swal.fire('', response.data, 'error');
                }
                $('.loading-overlay').hide();
                loader.hide();
            },
            error: function(jqXHR, textStatus) {
                $('.loading-overlay').hide();
                loader.hide();
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

// Initialize preview handlers
handlePreviewClick('[id^=upload_preview_]', '#fullscreenPreviewModal', "{{ route('admin.document.upload_preview') }}");
handlePreviewClick('[id^=upload1_preview_]', '#fullscreenPreviewModal1', "{{ route('admin.document.form_upload_preview') }}");
handlePreviewClick('[id^=upload2_preview_]', '#fullscreenPreviewModal_2', "{{ route('admin.document.addtioanl_upload_preview') }}");
handlePreviewClick('[id^=upload3_preview_]', '#fullscreenPreviewModal_3', "{{ route('admin.document.addtioanl_form_upload_preview') }}");


$(document).on('click', '[id^=upload_change_], [id^=upload1_change_], [id^=upload2_change_],[id^=upload3_change_]', function(event) {
    event.preventDefault();

    const buttonId = $(this).attr('id');
    const fileId = $(this).data('file-id');
    const imageId = $(this).data('image-id');
    const input = $('<input>', { type: 'file', accept: 'image/*,application/pdf' });

    let applicationId, documentId, ajaxUrl, number;
    
    if (buttonId.startsWith('upload1_change_')) {
        applicationId = window['applicationId1'];
        documentId = window['documentId1'];
        ajaxUrl = "{{ route('admin.document.upload_form_image_get') }}";
        number = 1;
    } else if (buttonId.startsWith('upload2_change_')) {
        applicationId = window['applicationId2'];
        documentId = window['documentId2'];
        ajaxUrl = "{{ route('admin.document.upload_additional_image_get') }}";
        number= 2;
    } else if(buttonId.startsWith('upload_change_')) {
        applicationId = window['applicationId1'];
        documentId = window['documentId1'];
        ajaxUrl = "{{ route('admin.document.upload_image_get') }}";
        number = 0;
    }
    else if(buttonId.startsWith('upload3_change_')) {
        applicationId = window['applicationId3'];
        documentId = window['documentId3'];
        ajaxUrl = "{{ route('admin.document.upload_additional_image_get') }}";
        number = 3;
    }

    let formData = new FormData();
    formData.append('applicationId', applicationId);
    formData.append('documentId', documentId);
    formData.append('imageId', imageId);
    var index = fileId.split('_')[2] || fileId;
    formData.append('index', index);

    input.on('change', function(e) {
        const file = e.target.files[0];
        if (file && (file.type.startsWith('image/') || file.type === 'application/pdf')) {
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
                    
                    if(response.image_path){
                        console.log('image_container_'+applicationId + '_' + documentId );
                         $('.image_container_' + number + '_' + applicationId +  '_'    + documentId ).empty().append(response.image_path)
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


$(document).on('click', '[id^=upload1_delete_], [id^=upload2_delete_], [id^=upload_delete_],[id^=upload3_delete_]', function(event) {
    event.preventDefault();
    
    const tag = $(this);
    const buttonId = $(this).attr('id');
    const image_id = $(this).data('image-id');
    const file_id = $(this).data('file-id');
    let applicationId, documentId, ajaxUrl;
    let container;
    let number;

    if (buttonId.startsWith('upload1_delete_')) {
        applicationId = window['applicationId1'];
        documentId = window['documentId1'];
        ajaxUrl = "{{ route('admin.document.upload_image_get_delete1') }}";
        container  = tag.closest('#uploaded_images_container1') ;
        number = 1;
        
    } else if (buttonId.startsWith('upload2_delete_')) {
        applicationId = window['applicationId2'];
        documentId = window['documentId2'];
        ajaxUrl = "{{ route('admin.document.upload_image_get_delete2') }}";
        container  = tag.closest('#uploaded_images_container2') ;
        number = 2 ;
    } else if(buttonId.startsWith('upload_delete_')) {
        applicationId = window['applicationId1'];
        documentId = window['documentId1'];
        ajaxUrl = "{{ route('admin.document.upload_image_get_delete') }}";
         container  = tag.closest('#uploaded_images_container') ;
         console.log(container);
         number = 0 ;
        
    
    }
    else if(buttonId.startsWith('upload3_delete_')) {
        applicationId = window['applicationId3'];
        documentId = window['documentId3'];
        ajaxUrl = "{{ route('admin.document.upload_image_get_delete3') }}";
         container  = tag.closest('#uploaded_additional_images_container') ;
         console.log(container);
         number = 3;
        
    
    }

    let formData = new FormData();
    formData.append('applicationId', applicationId);
    formData.append('documentId', documentId);
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
                        
                        if(container){
                           var length =  container.children().length
                           console.log(length);
                            if(length === 0){
                                
                                 var imgElement = '<img title="Pending" src="{{ asset('formupload/file_pending.png') }}" class="document_status_img">';
                                  var imageContainerClass = '.image_container_'+ number + '_' + applicationId + '_' + documentId;
                                  $(imageContainerClass).empty().append(imgElement);
                                
                            }
                            
                        }
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

// Function to handle the delete action
function handleDeleteClick(deleteButtonId, checkboxClass, url, applicationId, documentId) {
    $(deleteButtonId).on('click', function(event) {
        event.preventDefault();

        var $checkedBoxes = $(checkboxClass + ':checked');

        if ($checkedBoxes.length === 0) {
            Swal.fire('', 'Please select at least one item to delete.', 'error');
            return;
        }

        var formData = new FormData();
        $checkedBoxes.each(function() {
            var image_id = $(this).data('image-id');
            formData.append('image_ids[]', image_id); // Append multiple image_ids
        });

        formData.append('applicationId', applicationId);
        formData.append('documentId', documentId);

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
                        $(`#${image_id}`).remove(); // Remove the image container
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
    });
}

// Function to toggle the visibility of delete buttons based on checkbox state
function toggleDeleteButtonVisibility(checkboxClass, deleteButtonId) {
    $(document).on('change', checkboxClass, function() {
        if ($(checkboxClass + ':checked').length > 0) {
            $(deleteButtonId).show();
        } else {
            $(deleteButtonId).hide();
        }
    });
}

// Initialize handlers for different delete buttons
handleDeleteClick('#delete_selected', '.delete-checkbox', "{{ route('admin.document.upload_image_get_deletes') }}", window['applicationId1'], window['documentId1']);
handleDeleteClick('#delete_selected1', '.form_delete-checkbox', "{{ route('admin.document.upload_form_image_get_deletes') }}", window['applicationId1'], window['documentId1']);
handleDeleteClick('#delete_selected2', '.addition_delete_checkbox', "{{ route('admin.document.upload_additional_image_get_deletes') }}", window['applicationId2'], window['documentId2']);
handleDeleteClick('#delete_selected3', '.form_additional_delete-checkbox', "{{ route('admin.document.upload_form_additional_image_get_deletes') }}", window['applicationId3'], window['documentId3']);

// Initialize visibility toggling for delete buttons
toggleDeleteButtonVisibility('.delete-checkbox', '#delete_selected');
toggleDeleteButtonVisibility('.form_delete-checkbox', '#delete_selected1');
toggleDeleteButtonVisibility('.addition_delete_checkbox', '#delete_selected2');
toggleDeleteButtonVisibility('.addition_delete_checkbox', '#delete_selected2');
toggleDeleteButtonVisibility('.form_additional_delete-checkbox', '#delete_selected3');

//form_additional_delete-checkbox


// Initially hide the delete selected button
$('#delete_selected').hide();
$('#delete_selected1').hide();
$('#delete_selected2').hide();
$('#delete_selected3').hide();
function resetModalOnClose(modalSelector, targetModalSelector) {
    $(modalSelector).on('hidden.bs.modal', function () {
        // Ensure the target modal scroll is enabled and reset scroll position
        $(targetModalSelector).css('overflow-y', 'auto').find('.modal-body').scrollTop(0);
    });
}

    // Apply to different modals
    resetModalOnClose('#fullscreenPreviewModal', '#upload_document_modal');
    resetModalOnClose('#fullscreenPreviewModal_2', '#additional_modal');
    resetModalOnClose('#fullscreenPreviewModal1', '#upload_document_modal_1');
    resetModalOnClose('#fullscreenPreviewModal_3', '#upload_additional_form_modal');

    });
    
    //23-07-2024 start
    
    $(document).on('click', '#applicant_edit', function(){
        
      var  id = $(this).data('applicant-id');
       ClientModal_ResetErrors();
        
        if(id){
             loader.show();
            
              $.ajax({
            url: "{{route('admin.customerusers.edit')}}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                        'id': id
                },
            success: function(response) {
                $('.secondLoader').hide();
                loader.hide();

                if (response.status) {
                    
                    if(response.data){
                        var data = response.data;
                        
                        $('#name').val(data.name)
                        $('#email').val(data.email)
                        $('#phone').val(data.phone)
                        $('#address1').val(data.address1)
                        $('#address2').val(data.address2)
                        $('#customer_id').val(data.id)
                        $('#form-modal').modal('show')
                     
                    
                    }
                 
                } else {
                    Swal.fire('', response.data, 'error');
                }
            },
            error: function() {
                $('.secondLoader').hide();
                loader.hide();
                Swal.fire('', 'An error occurred. Please try again.', 'error');
            }
        });
            
        }else{
              Swal.fire('', 'An error occurred. Please try again. leater', 'error');
        }
   
       
        
    })
    
    
    $(document).on('click', '#saveBtn', function(e){
    e.preventDefault();

    loader.show();
    $.ajax({
        data: $('#customerForm').serialize(),
        url: "{{ route('admin.customerStore') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function() { // Corrected typo here
            loader.show();
        },
        success: function(response) {
            ClientModal_ResetErrors();

            if (response.status == 400 && response.errors) {
                ClientModal_ShowErrors(response.errors);
            } else if (response.status == 400 && !response.errors) {
                Swal.fire("Error", "Add or Update failed", "error");
            } else if (response.status == 200) {
                $('#customerForm').trigger("reset");
                $('#form-modal').modal('hide');

                if (response.data.is_new) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Added',
                        text: 'New Customer added successfully',
                        showConfirmButton: false,
                        timer: 2000,
                    });
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Updated',
                        text: 'Customer updated successfully',
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }

                // Redirect after success if needed
                // window.location.href = 'https://somerset.senthil.in.net/admin/document/60';
            }

            loader.hide(); // Remove duplicate
        },
        error: function(data) {
            loader.hide(); // Remove duplicate
        }
    });
});

        
        
    function ClientModal_ResetErrors() {
        $('.invalid-name, .invalid-phone-no, .invalid-email').text('');
    }

    function ClientModal_ShowErrors(errors) {
            if (errors.name) {
                $('.invalid-name').text(errors.name);
            }
            if (errors.phone) {
                $('.invalid-phone-no').text(errors.phone);
            }
            if (errors.email) {
                $('.invalid-email').text(errors.email);
            }
        }
        
$(document).on('click', '.upload_document_delete, .upload_form_delete, .upload_additional_document_delete,upload_additional_form_delete ', function(event) {
    event.preventDefault();

    const button = $(this);
    const applicationId = button.data('application-id');
    const documentId = button.data('document-id');
    const buttonId = button.attr('class').split(' ')[0]; // Get the first class name

    let ajaxUrl;

    if (button.hasClass('upload_document_delete')) {
        ajaxUrl = "{{ route('admin.document.upload_document_delete') }}";
    } else if (button.hasClass('upload_form_delete')) {
        ajaxUrl = "{{ route('admin.document.upload_form_delete') }}";
    }else if (button.hasClass('upload_additional_document_delete')) {
        ajaxUrl = "{{ route('admin.document.upload_additional_document_delete') }}";
    }else if (button.hasClass('upload_additional_form_delete')) {
        ajaxUrl = "{{ route('admin.document.upload_form_additional_document_delete') }}";
    }

    if(ajaxUrl){
        let formData = new FormData();
        formData.append('applicationId', applicationId);
        formData.append('documentId', documentId);
    
        Swal.fire({
            title: "Are You Sure?",
            text: "Do You Want to Delete the Document and all its images?",
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
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'The document and all related images have been deleted.',
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            
                             location.reload();
    
                            // Remove the table row
                            button.closest('tr').remove();
                        } else {
                            Swal.fire("Error", response.message || "Failed to delete the document.", "error");
                        }
                        loader.hide();
                    },
                    error: function() {
                        Swal.fire("Error", "An unexpected error occurred.", "error");
                        loader.hide();
                    }
                });
            }
        });
    }else{
        Swal.fire('', 'Please Refresh the page', 'error');
    }
});



        

    
    
    </script>
@endsection
@endif
