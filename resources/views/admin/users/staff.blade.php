@php
    $type_id = auth()->user()->roles[0]->type_id;
    $key = ($type_id == 6) ? 'layouts.admin' : 'layouts.admin';
@endphp

@extends($key)

@section('content')
@include('admin.users.staff_data')
   
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"
        integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const staff = [];

        let loader = document.getElementById("loader");

        let given_data = document.getElementById("given_data");

        let input = document.getElementById("autocomplete-input");


<?php /*
        window.onload = function() {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.staff-edge.geter') }}',
                type: 'POST',
                data: {
                    'data': 'geter'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // console.log(data);
                    let details = data.staff;
                    let staff = {};
                    // console.log(details)
                    for (let i = 0; i < details.length; i++) {
                        staff[details[i]] = null;
                    }
                    // console.log(staff)
                    $('input.autocomplete').autocomplete({
                        data: staff,
                    });
                    $('#loading').hide();

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    $('#loading').hide();
                }
            });

        } */ ?>

        function run(element) {
            if (/[0-9]/.test($(element).val()) && /[a-zA-Z]/.test($(element).val())) {
                var a = $(element).val();
                window.location.href = "{{ url('admin/teaching-staff-edge') }}/" + a;
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.add_plus').each(function(index) {
                $(this).click(function() {
                    $(this).toggleClass('rotated');
                    $('.view_more').eq(index).toggle();
                });
            });
        });
    </script>
@endsection
