@if (isset($accessGate))
    @can($accessGate)
        <div class="toggle-wrapper blue text-center">
            <input class="toggle-checkbox" type="checkbox" id='Leave_value' data-class="{{ $row->id }}" class="toggleData"
                data-id="{{ $row->past_attendance_control }}" {{ $row->past_attendance_control == 0 ? '' : 'checked' }}
                onchange="attControl(this)" />
            <div class="toggle-container">
                <div class="toggle-ball"></div>
            </div>
        </div>
    @endcan
@endif

@if (isset($accessGate2))
    @can($accessGate2)
        <div class="toggle-wrapper blue text-center">
            <input class="toggle-checkbox" type="checkbox" data-class="{{ $row->user_name_id }}" class="toggleData"
                data-id="{{ $row->past_leave_access }}" {{ $row->past_leave_access == 0 ? '' : 'checked' }}
                onchange="attControl(this)" />
            <div class="toggle-container">
                <div class="toggle-ball"></div>
            </div>
        </div>
    @endcan
@endif
