@extends('layouts.admin')
@section('content')
<div class="table-responsive">


<table class="table table-hover table-light">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Ref</th>
      <th scope="col">Name</th>
      <th scope="col">Security</th>
      <th scope="col">Post Code</th>
      <th scope="col">Stage</th>
      <th scope="col">Type</th>
      <th>
        Action
      </th>
      

    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">2</th>
      <td>105294</td>
      <td>Paskaralingam Rasu, Kokila Paskaralingam</td>
      <td>27 Burwell Avenue</td>
      <td>UB6 ONU</td>
      <td>Processing</td>
      <td>Second Change</td>
      <td> 
        @php 
        $data = 1;
        @endphp
      <a href="http://127.0.0.1:8000/admin/application-stage/edit/1" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; open</a>


      <!-- <button type="button" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; open</button> -->
      </td>
    </tr>
    <tr>
    <th scope="row">1</th>
      <td>105876</td>
      <td>JEYARATHAN JEGANATHAN, THARMIKA JEYARATHAN</td>
      <td>6 Glebe Road, 464 Stoneferry Road</td>
      <td>HU7 0DX, HU7 0BG</td>
      <td>Processing</td>
      <td>Bridging Finance</td>
      <td>
      <a href="http://127.0.0.1:8000/admin/application-stage/edit/2" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; open</a>
      <!-- <button type="button" class="btn btn-primary" id="saveBtn"><i class="fa fa-save"></i>&nbsp; open</button> -->
      </td>
    </tr>
  </tbody>
</table>

</div>

    @include('admin.application.partials.add_customer_modal')
@endsection



