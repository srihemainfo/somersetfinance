 @if (isset($user) && $user != '')
 
        <div class="pb-3" style="overflow:hidden;">
            <div class="bg-primary text-light student_label">
                <div style="padding-right:2%;">
                    <span style="margin-right: 10px;"> STAFF NAME: {{ $user->name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile">
                                    <div class="user-avatar">
                                        @if ($user->file_path ?? '' != '')
                                            <img class="uploaded_img" src="{{ asset('partner_images/'.$user->file_path) }}" alt="image">
                                        @else
                                            <img src="{{ asset('adminlogo/user-image.png') }}" alt="">
                                        @endif
                                    </div>
                                    <h5 class="user-name">{{ $user->name ?? 'N/A' }}</h5>
                                    
                                    <h6 class="user-email">{{ $user->roles[0]->title ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12" style="padding-left:0;">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="mb-2 text-primary">Staff Info</h5>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" value="{{ $user->name ?? 'N/A'  }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="staffCode">Staff Code</label>
                                        <input type="text" class="form-control" id="staffCode" value="{{ $user->StaffCode ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                      
                                        <input type="text" class="form-control" id="role" value="{{ $user->roles[0]->title ?? 'N/A' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" value="{{ $user->email ?? 'N/A'  }}" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" value="{{ $user->phone ??  'N/A'  }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('partner_full_view_access')
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card" style="margin-top: 16px;">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Personal Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body view_more" style="display:none;">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gutters">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="name">First Name</label>
                                                <input type="text" class="form-control"  value="{{ $user->name ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control"  value="{{ $user->email ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="staffCode">Staff Code</label>
                                                <input type="text" class="form-control"  value="{{ $user->StaffCode ?? 'N/A'  }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control"  value="{{ $user->phone ??  'N/A'  }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Business Details</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body view_more" style="display:none;">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="company_phone">Company Phone</label>
                                            <input type="text" class="form-control"  value="{{ $user->company_phone ??  'N/A'  }}" readonly>
                                        </div>
                                    </div>
                             
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="company_address_1">Company Address Line 1</label>
                                            <input type="text" class="form-control"  value="{{ $user->company_address_1 ??  'N/A'  }}" readonly>
                                        </div>
                                    </div>
                                
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="company_address_1">Company Address Line 2</label>
                                            <input type="text" class="form-control"  value="{{ $user->company_address_2 ??  'N/A'  }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gutters" style="margin:7.5px 0px 0px 0px;">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding:0;">
                        <div class="card">
                            <div class="card-header">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <h5 style="margin-bottom:0;margin-top:3px;" class="text-primary">Agreement Upload</h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" style="text-align:end;">
                                        <h5 style="margin-bottom: 0;"><i class="right fa fa-fw fa-angle-left add_plus" style="font-size:1.5em;"></i></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body view_more" style="display:none;">
                                <div class="row gutters">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="agreement">agreement Upload</label>
                                            <input type="file" class="form-control" id="agreement" name="agreement"   accept=".jpg, .jpeg, .png, .pdf, .doc, .docx">
                                        </div>
                                    </div>
                                </div>
                                    <div class="row gutters" id='Agreement_folder'>
                                        <?php /*
                                        
                                        @php
                                            $datas = $user->agreements;
                                        
                                        if(isset( $datas) && count( $datas) >0){
                                        $html= '';
                                        foreach($datas as $id =>$data ){
                                        
                                        
                                            $index = $id ;
                                            $index ++ ;
                                            
                                            $fileExtension = pathinfo('fileupload/' .$data ->file_path, PATHINFO_EXTENSION);
                                                 $doc = ['doc', 'docx'];
                                            
                                                if(in_array($fileExtension  ,$doc)){
                                                    $ImagePath = asset('formupload/word_preview.png') ;
                                                }else
                                                if($fileExtension == 'pdf'){
                                                    $ImagePath = asset('formupload/pdf_preview.png') ;
                                
                                                }else{
                                                    $ImagePath =  asset('fileupload/'. $data ->file_path) ;
                                
                                                }
                                            
                                            

                                            $html .= '<div class="col-12 col-md-3 col-xl-3 col-sm-3 my-2 all_file_data">';
                                            $html .= '<form>';
                                            $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                                            $html .= '<input type="checkbox" class="delete-checkbox" data-image-id="'.$data ->id.'" data-file-id="uploaded_file_'.$index.'">';
                                            $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                                            $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                                            $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                                            $html .= '<button id="upload_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$data->id .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$data ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                                            $html .= '<button id="upload_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$data->id .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$data ->fileExtension.'"><i class="far fa-edit"></i></button>';
                                            $html .= '<button id="upload_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$data->id .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$data ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                                            $html .= '</div>';
                                            $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                                            $html .= '<div class="form-group">';
                                            $html .= '</div>';
                                            $html .= '</div></div></form></div>';
                                            //end
                                        
                    
                                            }
                                            $data = $html;
                                        }
                                        
                                        @endphp
                                        
                                        
                                        {!! $data !!}
                                        
                                        */ ?>
                                        
                                    
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"  style="display:none;" id="delete_selected">Delete Selected</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                </div>
               
            @endcan
        </div>
    @endif