
@if(isset($viewGate))
@can($viewGate)
    <a class="newViewBtn" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}" target="_blank" title="View">
        <i class="fa-fw nav-icon far fa-eye"></i>
    </a>

@endcan
@endif

@if(isset($editGate))
@can($editGate)
    @if ($editGate == 'certificate_provision')
        <a class="newPrintBtn" href="{{ route('admin.' . $crudRoutePart . '.print', $row->id) }}" target="_blank" title="Print">
            <i class="fa-fw nav-icon fas fa-print"></i>
        </a>
        <button class="newReadyBtn" onclick="certificateReady({{ $row->id }})" title="Ready">
            <i class="fa-fw nav-icon far fa-check-circle"></i>
        </button>
    @elseif ($editGate == '')
    @else
        <a class="newEditBtn" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}" target="_blank" title="Edit">
            <i class="fa-fw nav-icon far fa-edit"></i>
        </a>
    @endif
@endcan
@endif

@if(isset($deleteGate))
@can($deleteGate)
    @if ($deleteGate == 'student_delete')
        <button type="submit" class="newDeleteBtn" data-id="{{ $row->id }}" title="Delete"
            id="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" onclick="deleteStudent(this)"><i class="fa-fw nav-icon fas fa-trash-alt"></i></button>
    @else
        <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST"
            onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="newDeleteBtn" type="submit" title="Delete">
                <i class="fa-fw nav-icon fas fa-trash-alt"></i>
            </button>
        </form>
    @endif
@endcan
@endif
