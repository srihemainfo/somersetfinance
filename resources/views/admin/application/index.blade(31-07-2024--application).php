@extends('layouts.admin')

@section('content')
    <form id="bookingForm" name="bookingForm">
        @include('admin.application.partials.create_application_form.client_search')
    </form>

    @include('admin.application.partials.add_customer_modal')
@endsection
@section('scripts')
    @include('admin.application.partials.booking_js')
@endsection



