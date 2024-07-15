@extends('layouts.admin')
@section('content')
@can('workshop_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-outline-success" href="{{ route('admin.workshops.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.workshop.title_singular') }}
            </a>
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Workshop', 'route' => 'admin.workshops.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.workshop.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Workshop">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.user_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.topic') }}
                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.from_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.workshop.fields.to_date') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('workshop_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.workshops.massDestroy') }}",
    className: 'btn-outline-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.workshops.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name_name', name: 'user_name.name' },
{ data: 'topic', name: 'topic' },
{ data: 'remarks', name: 'remarks' },
{ data: 'from_date', name: 'from_date' },
{ data: 'to_date', name: 'to_date' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-Workshop').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
