@extends('layouts.admin')
@section('content')



    <div class="row">
        <div class="col-md-12">
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.sellvehicle.purchaseget', $Event->id) }}">
                @csrf
                {{-- @method('put') --}}
                <div class="card">
                    <div class="card-header border-bottom-0">

                        <h3 class="card-title text-center"> <label>Purchase Details </label></h3>
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

                   
                    <input type="hidden" name="purchase_price[]" id="purchase_price">
                    <input type="hidden" name="purchase_titles[]" id="purchase_titles">
                    <input type="hidden" name="purchase_id[]" id="purchase_id">


                    <div class="table-responsive">

                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="LatestEvent_table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;" class="border-bottom-0">Vehicle Purchase Price : <small>£ {{ number_format($Event->vehicle_price, 2) }}</small> </th>
                                    <th style="width: 30%;" ></th>
                                    <th style="width: 30%;" ></th>
                                </tr>
                                <tr>
                                    <th style="width: 30%;" class="border-bottom-0"> <small>Vehicle Purchase/Expense Title</small></th>
                                    <td style="width: 30%;" ></td>
                                    <td style="width: 30%; text-align:center" ><small>Action</small></td>
                                </tr>
                                @php 
                                $total = 0;
                                @endphp

                                @if($Event->PurchaseDetails->count())
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
                                    <td class="text-center">
                                        <button class="newDeleteBtn" type="button" onclick="removeRow(this)" title="Remove">
                                            <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                $total += $purchase->purchase_price ?? 0;
                                @endphp
                                @endforeach
                                @else

                                <tr id='row_remove'>
                                    <th colspan='3' class="border-bottom-0 text-center">No Data Found</th>
                                    
                                </tr>
                                @endif
                                
                              
                            </tbody>

                            <tbody style = border;0;>


                                <tr>
                                    <th class='d-flex justify-content-center'>
                                        <div style="width:min-content; text-align-start">
                                           <p> Total Expensive</p>
                                           <p> Total Income</p>
                                        </div>
                                    </th>
                                    <th>
                                        

                                        {{-- <button class="btn btn-primary m-4 w-75" id="total_price">£{{  $total }}</button> --}}
                                        <p  id="total_price">£ {{  number_format($total,2) }}</p>
                                        @php 
                                            $total_income =  $Event->vehicle_price - $total ;
                                           


                                        @endphp
                                        <p  id="total_income" class='@if($total_income < 0) bg-danger  @else bg-success @endif'>£ {{  number_format($total_income, 2)}}</p>
                                    </th>
                                    <th class='text-center'>
                                        <button class="newViewBtn m-4" id="addRowBtn">
                                            <i class="fa-fw nav-icon fas fa-plus-circle"></i>
                                        </button>
                                    </th>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="m-3">
                            <div class="row">
                                <button class="btn btn-secondary m-3" onclick="window.history.back();">close</button>
                                <button type="submit" name="submit_button" value="button1" class="btn btn-primary m-3">Submit</button>

                            </div>
                            {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                        </div>
                        <div class="m-3">
                            {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                            <button type="submit" name="submit_button" value="button2" name='submit' class="btn btn-primary m-3">Submit & Continue</button>
                        </div>
    
                    </div>
                    
                    {{-- <div style="text-align: center; padding:10px;">
                        <button class="btn btn-secondary" onclick="window.history.back();">close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div> --}}
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
                <td style="width: 30%;text-align:center;" >
                    <button class="newDeleteBtn" type="button" onclick="removeRow(this)" title="Remove">
                        <i class="fa-fw nav-icon fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRowHtml);
            $('#row_remove').remove();
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

        var total = 0;
        var total_income = 0 ;

        additionalprice.each(function() {
            var value = parseFloat($(this).val()) || 0;
            total += value;
            vehicle_price -= value ;
        });

        $('#total_price').html('£'+ total.toFixed(2)); 
        $('#total_income').html('£'+  vehicle_price.toFixed(2)); 
        if(vehicle_price < 0){
            $('#total_income').addClass('bg-danger');
            $('#total_income').removeClass('bg-success');
        }else{
            $('#total_income').addClass('bg-success');
            $('#total_income').removeClass('bg-danger');

        }

}




</script>

    @endsection
