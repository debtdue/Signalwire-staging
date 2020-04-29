@extends('layouts.app')

@section('title')
    Businesses
@endsection

@section('page_styles')
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Manage Businesses<small style="margin-top: 5% !important;">see details of each business and manage them by edit and deleted</small></div>
        </div>

        <div class="container-fluid">
        @include('layouts.flash_messages')

        <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">List of All Businesses</div>
                </div>

                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="business_listing">
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Business Title</th>
                            <th>Agents</th>
                            <th>#s Account</th>
                            <th>Greeting</th>
                            <th>Recording</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $businesses as $business)
                            <tr class="gradeC">
                                <td>{{++$counter}}</td>
                                <td>{{ ucwords($business->title) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#myModal-{{$business->id}}">Show Agents</button>
                                </td>
                                <td>{{ $business->account['friendly_name'] }}</td>

                                <td>
                                    @if( $business->greeting_message_type == 'mp3_audio')
                                        <audio controls>
                                            <source
                                                src="{{asset('storage/'.$business->id.'/greetings/greeting.mp3')}}"
                                                type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @else
                                        {{ $business->greeting_text }}
                                    @endif
                                </td>
                                <td>
                                    @if($business->record_calls === 1)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>{{ $business->created_at->toDayDateTimeString() }}</td>
                                <td>
                                    <div class="panel-body">
                                        <a class="btn btn-square btn-info" data-toggle="tooltip"
                                           title="View business details and call flow"
                                           href="{{ route('businesses.show',$business->id) }}"><em
                                                class="icon-magnifier"></em></a>
                                        <a class="btn btn-square btn-success" data-toggle="tooltip"
                                           title="Update business details and call flow"
                                           href="{{ route('businesses.edit',$business->id) }}"><em
                                                class="icon-pencil"></em> </a>

                                        <a href="{{ route('businesses.destroy',$business->id) }}" data-toggle="tooltip"
                                           title="Delete business and free resources"
                                           class="btn btn-square btn-danger sweet_alert_warning"
                                           data-form-id="{{ $business->id }}">
                                            <em class="icon-trash"></em>
                                        </a>
                                        <form id="del-business-form-{{$business->id}}"
                                              action="{{ route('businesses.destroy',$business->id) }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--Looping again to load the modal by using already fetched data--}}
@foreach( $businesses as $business)
    @include('business.modals')
@endforeach

@section('page_script')
    <script src="{{asset('vendor/datatables.net/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>

    <!-- SWEET ALERT-->
    <script src="{{ asset('vendor/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            // Init Data tables
            $('#business_listing').dataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                // 'info': true, // Bottom left status text
                'responsive': false, // https://datatables.net/extensions/responsive/examples/
            });

            $('.agents-details-modal').dataTable();

            $('.sweet_alert_warning').on('click', function (e) {
                // Sweet alert delete number warning
                e.preventDefault();
                let formId = $(this).attr("data-form-id");

                swal({
                    icon: 'warning',
                    title: "Are you sure?",
                    text: "All related Agents, Users and business will be deleted. And SignalWire account become free !",
                    buttons: {
                        cancel: {
                            text: "No, cancel please!",
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: false
                        },
                        confirm: {
                            text: 'Yes, delete it!',
                            value: true,
                            visible: true,
                            className: "bg-danger",
                            closeModal: false
                        }
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        document.getElementById('del-business-form-' + formId).submit();
                    } else {
                        swal("Cancelled", "Action has cancelled!", "error");
                    }
                });
            });



        });
    </script>
@endsection
