@extends('layouts.app')

@section('title')
    Account Numbers
@endsection

@section('page_styles')
    <!-- DATATABLES-->
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">

    <!-- SWEET ALERT-->
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/dist/sweetalert.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>VoIP Account Numbers<small style="padding-top: 5%">Manage SignalWire numbers, add and sycn appsss database with SignalWire</small></div>
        </div>

        <!-- START widgets box-->
        <div class="container-fluid">

            @include('layouts.flash_messages')

            <!-- START panel-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$account->title}} Numbers</div>

                    <div class="col-sm-3  float-right">

                        <a href="{{ route('voip-numbers.store.update',$account->id) }}"
                            class="btn btn-lg btn-primary"
                            data-toggle="tooltip" title="Synchronize Account Numbers"
                            onclick="event.preventDefault();document.getElementById('sync-account-numbers-form').submit();">
                            Synchronize Account Numbers
                        </a>
                        <form id="sync-account-numbers-form"
                                action="{{  route('voip-numbers.store.update',$account->id)  }}" method="POST"
                                style="display: none;">
                            @csrf
                            @method('PUT')
                        </form>

                        <a class="btn btn-lg btn-success float-right" href="{{ route('voip-accounts.index') }}" type="button">Back
                            to Accounts</a>

                    </div>

                </div>

                <div class="card-body">

                    {{-- <table class="table table-striped my-4 w-100" id="datatable1"> --}}
                    <table class="table table-striped table-hover my-4 w-100" id="datatable_numbers">
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Friendly Name</th>
                            <th>Capabilities</th>
                            <th>Assigned Business</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($numbers as $number)
                            <tr class="gradeC">
                                <td>{{ ++$counter }}</td>
                                <td>@if($number->friendly_name) {{ $number->friendly_name }}
                                @else {{$number->phone_number}}
                                @endif</td>
                                <td>
                                    @foreach($number->capabilities as $key => $value)
                                        @if($key == 'voice' && $value == 1)
                                            <em class="icon-phone"></em> &nbsp;
                                        @elseif($key == 'sms' && $value == 1)
                                            <em class="icon-envelope"></em> &nbsp;
                                        @elseif($key == 'mms' && $value == 1)
                                            <em class="icon-picture"></em> &nbsp;
                                        @elseif($key == 'fax'  && $value == 1)
                                            <em class="fa fa-fax"></em>
                                        @endif
                                    @endforeach
                                </td>
                                @if( isset($account->business->title))
                                    <td>{{$account->business->title}}</td>
                                @else
                                    <td>No Business assigned yet</td>
                                @endif
                                @if( $number->status == 1 )
                                    <td style="color: green;">Active</td>
                                @else
                                    <td style="color: red;">Disable</td>
                                @endif
                                <td>
                                    <div class="panel-body">

                                        <a class="btn btn-square btn-success" data-toggle="tooltip"
                                            title="Edit number"
                                            href="{{ route('voip-numbers.edit',$number->id) }}"><em
                                                    class="icon-pencil"></em> </a>

                                        <a href="{{ route('voip-numbers.destroy',$number->id) }}"
                                            data-toggle="tooltip"
                                            title="Delete this number from SignalWire Account and App "
                                            class="btn btn-square btn-danger sweet_alert_warning"
                                            data-form-id="{{ $number->id }}">
                                            {{--onclick="event.preventDefault();document.getElementById('del-number-form-{{$number->id}}').submit();">--}}
                                            <em class="icon-trash"></em>
                                        </a>
                                        <form id="del-number-form-{{$number->id}}"
                                                action="{{ route('voip-numbers.destroy',$number->id) }}"
                                                method="POST"
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


@section('page_script')
    {{-- DATATABLES --}}
    <script src="{{asset('vendor/datatables.net/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>


    <!-- SWEET ALERT-->
    <script src="{{ asset('vendor/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>

        $(document).ready(function () {
            // +1 (502) 203-1295

            // Sweet alert delete number warning
            $('.sweet_alert_warning').on('click', function (e) {
                // Sweet alert delete number warning
                e.preventDefault();
                let formId = $(this).attr("data-form-id");

                swal({
                    icon: 'warning',
                    title: "Are you sure?",
                    text: "Number will be deleted from application and released from associated SignalWire account!",
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
                        document.getElementById('del-number-form-' + formId).submit();
                    } else {
                        swal("Cancelled", "Action has cancelled!", "error");
                    }
                });
            });

            $('#datatable_numbers').dataTable();
        });
    </script>
@endsection
