@extends('layouts.admin')
@section('content')



    <div class="row">
        <div class="col-md-12">
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.sellvehicle.purchaseget', $Event->id) }}">
                @csrf
                {{-- @method('put') --}}
                <div class="card">
                    <div class="card-header border-bottom-0">

                        <h3 class="card-title">Purchase Details</h3>
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


                    <div class="table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="LatestEvent_table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;" class="border-bottom-0">Vehicle Purchase Price</th>
                                    <th style="width: 30%;" >£{{ number_format($Event->vehicle_price, 2) }}</th>
                                    <th style="width: 30%;" ></th>
                                </tr>
                                <tr>
                                    <th style="width: 30%;" class="border-bottom-0">Vehicle Expense Title</th>
                                    <td style="width: 30%;" ></td>
                                    <td style="width: 30%; text-align:center" >Action</td>
                                </tr>
                                @php 
                                $total = $Event->vehicle_price ?? 0;
                                @endphp
                                @foreach($Event->PurchaseDetails as $purchase)
                                <tr>
                                    <th style="width: 30%;" >
                                        <div class="form-group ">
                                            <input type="text" style="width: 100%;" class="myInput" name="purchase_titles[]" value="{{ $purchase->purchase_title }}" required>
                                            <input type="hidden" class="form-control" name="purchase_id[]" value="{{ $purchase->id }}" required>
                                        </div>
                                    </th>
                                    <th style="width: 30%;" >
                                        <div class="form-group ">
                                            <input type="text" class="myInput" name="purchase_price[]" value="{{ old('purchase_price') ?? $purchase->purchase_price }}" required oninput="this.value = this.value.replace(/[^\d.]/g, '');">

                                            {{-- <input type="text" class="myInput" name="purchase_price[]" value="{{ old('purchase_price') ?? $EvetList->purchase_price }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '');"> --}}
                                        </div>
                                    </th style="width: 30%;" >
                                    <td class="text-center"><button class="newDeleteBtn" type="button" onclick="removeRow(this)" title="Remove">
                                        <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                                    </button></td>
                                </tr>
                                @php
                                $total += $purchase->purchase_price ?? 0;
                                @endphp
                                @endforeach
                                
                              
                            </tbody>

                            <tbody style = border;0;>

                                <tr>
                                    <th class='text-center'>Total Cost</th>
                                    <th>
                                        

                                        {{-- <button class="btn btn-primary m-4 w-75" id="total_price">£{{  $total }}</button> --}}
                                        <span  id="total_price">£{{  $total }}</span>
                                    </th>
                                    <th>
                                        <button class="btn btn-primary m-4 w-75" id="addRowBtn">Add Row</button>
                                    </th>
                                </tr>

                            </tbody>
{{-- 

                            <tbody style = border;0;>

                                <tr>
                                    <th></th>
                                    <th>
                                        

                                        <button class="btn btn-primary m-4 w-75" id="addRowBtn">Add Row</button>
                                    </th>
                                    <th>

                                    </th>
                                </tr>

                            </tbody> --}}


                        </table>
                    </div>
                    
                    <div style="text-align: center; padding:10px;">
                        <button class="btn btn-secondary" onclick="window.history.back();">close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>

        </div>
    @endsection
    @section('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $('.summernote').summernote();
                //     $('#title').select2({
                //    placeholder: "Select a Catogory",
                //     });
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
  
       
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("addRowBtn").addEventListener("click", function () {
            var tableBody = document.querySelector("#LatestEvent_table tbody");
            var newRowHtml = `
            <tr>
                <td style="width: 30%;" >
                    <div class="form-group" >
                        <input type="text" class="myInput" name="purchase_titles[]" value="" required>
                        <input type="hidden" class="myInput" name="purchase_id[]" value="" required>
                    </div>
                </td>
                <td style="width: 30%;" >
                    <div class="form-group">
                        <input type="text" class="myInput" name="purchase_price[]" value="" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </td>
                <td style="width: 30%;text-align:center;" ><button class="newDeleteBtn" type="button" onclick="removeRow(this)" title="Remove">
                                        <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                                    </button></td>
            </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRowHtml);
        });
    });

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        purchase_expanse();
    }


//     $(document).ready(function(){
//         $("input[name*='purchase_price']").keydown(function(){

//             var vehicle_price = {{ $Event->vehicle_price ?? 0 }} ;
//            var  additionalprice = $("input[name*='purchase_price']" );
//            var total = 0 + vehicle_price  ;

//             additionalprice.each(function( index ) {
//                 console.log( total += $( this ).val() ) ;
//             });
//         });
//         $("input[name*='purchase_price']").keyup(function(){
//             var vehicle_price = {{ $Event->vehicle_price ?? 0 }}  ;
//            var  additionalprice = $("input[name*='purchase_price']");
//            var total = 0 + vehicle_price ;

//             additionalprice.each(function( index ) {
//                 console.log( total += $( this ).val() ) ;
//             });


//         });
// });

$(document).ready(function(){
    $(document).on('input', "input[name*='purchase_price']", function(){

        purchase_expanse();

    });
});

function purchase_expanse (){

    
    var vehicle_price = {{ $Event->vehicle_price ?? 0 }};
        var additionalprice = $("input[name*='purchase_price']");
        var total = vehicle_price;

        additionalprice.each(function() {
            var value = parseFloat($(this).val()) || 0;
            total += value;
        });

        $('#total_price').html('£'+ total.toFixed(2)); 

}




</script>

    @endsection
