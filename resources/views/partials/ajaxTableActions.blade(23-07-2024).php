@if (isset($viewGate))
    @can($viewGate)
        @if ($viewFunct != 'viewExamfee')
            @if (isset($row->id))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->id }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @elseif (isset($row['id']))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row['id'] }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->rack['id'] }})" title="View">
                    <i class="fa-fw nav-icon far fa-eye"></i>
                </button>
            @endif
        @else
            <button class="newViewBtn" onclick="{{ $viewFunct }}({{ $row->regulation_id }})" title="View">
                <i class="fa-fw nav-icon far fa-eye"></i>
            </button>
        @endif
    @endcan

@endif

@if(isset($caseAssignGate))
@can($caseAssignGate)

    @if(isset($viewFunct1) && ($viewFunct1 != null || $viewFunct1 != '') )

    <button class="newViewBtn assignDriver" data-case-id='{{ $row->id }}' data-ref-id='{{ $row->ref_no }}'  title="Assign">
        <i class="fa-fw nav-icon fa fa-user-plus"></i>
    </button>

    @elseif(isset($viewFunct2) &&($viewFunct2 != null || $viewFunct2 != ''))
        <button class="newDeleteBtn removeClient" data-case-id="{{ $row->id }}" data-client-id="{{ $row->assigned_client_id }}" title="Remove">
            <i class="fa-fw nav-icon fa fa-user-times"></i>
        </button>

    @endif
    @endcan
@endif

@if (isset($editGate))
    @can($editGate)

        @if($editFunct == 'editCase')
            <a href="{{ route('admin.application-stage.edit',$row->id) }}" target='_blank' title="Preview"><i class="fa-fw nav-icon far fa-edit"></i></a>
        @elseif ($editFunct != 'editExamfee')
            @if (isset($row->id))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->id }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @elseif (isset($row['id']))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row['id'] }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->rack['id'] }})" title="Edit">
                    <i class="fa-fw nav-icon far fa-edit"></i>
                </button>
            @endif
        @else
            <button class="newEditBtn" onclick="{{ $editFunct }}({{ $row->regulation_id }})" title="Edit">
                <i class="fa-fw nav-icon far fa-edit"></i>
            </button>
        @endif
    @endcan
@endif

@if (isset($deleteGate))
    @can($deleteGate)
        @if ($deleteFunct != 'deleteExamfee')
            @if (isset($row->id))
                <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->id }})" title="Delete">
                    <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                </button>
            @elseif (isset($row->rack['id']))
                <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->rack['id'] }})" title="Delete">
                    <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                </button>
            @endif
        @else
            <button class="newDeleteBtn" onclick="{{ $deleteFunct }}({{ $row->regulation_id }})" title="Delete">
                <i class="fa-fw nav-icon far fa-trash-alt"></i>
            </button>
        @endif
    @endcan
@endif

@if(isset($previewGate))
@can($previewGate)
<a href="{{ $weburl  }}" target='_blank' title="Preview"><i class="fa-fw nav-icon fas fa-globe-europe"></i></a>
@endcan
@endif


@if (isset($uploadGate))
    @can($uploadGate)

        <button class="newViewBtn" onclick="{{ $uploadFunct }}({{ $row->id }})" title="Upload">
            <i class="fa-fw nav-icon fas fa-solid fa-upload"></i>
        </button>

    @endcan
@endif
