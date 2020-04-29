@extends('layouts.app')

@section('title')
    SignalWire Accounts
@endsection

@section('page_styles')
    <!-- DATATABLES-->
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">

@endsection

@section('content')
    <div class="content-wrapper">

        {{-- HEADING --}}
        <div class="content-heading">
            <div>Manage SignalWire Accounts<small style="padding-top: 2%">Manage SignalWire numbers, add and sync app
                    database with SignalWire</small></div>
        </div>

        <div class="container-fluid">
        @include('layouts.flash_messages')

        <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Manage VoIP Accounts and Numbers</div>
                    {{--<a href="{{route('voip-accounts.sync',auth()->id())}}"
                       class="btn btn-sm btn-purple float-right"
                       data-toggle="tooltip" title="Synchronize Accounts"
                       onclick="event.preventDefault();document.getElementById('sync-accounts-form').submit();">
                        Synchronize Accounts
                    </a>
                    <form id="sync-accounts-form"
                          action="{{ route('voip-accounts.sync',auth()->id()) }}" method="POST"
                          style="display: none;">
                        @csrf
                        @method('PUT')
                    </form>--}}
                </div>

                <div class="card-body">
                    <table class="table table-striped table-hover my-4 w-100" id="signal_wire_accounts_listing">
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Total Number</th>
                            <th>Status</th>
                            <th>Assigned Businesses</th>
                            <th>Created At</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $accounts as $account)
                            <tr class="gradeC">
                                <td>{{ ++$counter }}</td>
                                <td>{{ $account->friendly_name }}</td>
                                <td>{{ $account->type }}</td>
                                <td>{{ $account->total_numbers }}</td>
                                @if($account->status === 'active')
                                    <td style="color: green;">Active</td>
                                @else
                                    <td style="color: red;">Disable</td>
                                @endif

                                <td>{{ isset($account->business['title']) ? $account->business['title'] : '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($account->account_created_at)->toDayDateTimeString() }}</td>
                                <td>{{ Carbon\Carbon::parse($account->account_updated_at)->toDayDateTimeString() }}</td>
                                <td>
                                    <div class="panel-body">
                                        @if( $account->status == true)
                                            <a class="btn btn-square btn-info" data-toggle="tooltip"
                                               title="View Numbers"
                                               href="{{ route('voip-numbers.show',$account->id) }}"><em
                                                    class="icon-magnifier"></em></a>
                                        @endif

                                        {{--Update Account--}}
                                        <a class="btn btn-square btn-success" data-toggle="tooltip"
                                           title="Update account"
                                           href="{{ route('voip-accounts.edit',$account->id) }}"><em
                                                class="icon-pencil"></em></a>

                                        {{--Close Account--}}
                                        <a href="{{ route('voip-accounts.destroy', $account->id) }}"
                                           data-toggle="tooltip" title="Delete Account from SignalWire and App"
                                           class="btn btn-square btn-danger sweet_alert_warning"
                                           data-form-id="{{ $account->id }}">
                                            <em class="icon-trash"></em>
                                        </a>
                                        <form id="del-account-form-{{$account->id}}"
                                              action="{{ route('voip-accounts.destroy',$account->id) }}"
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

    <!-- Datatables-->
    <script src="{{asset('vendor/datatables.net/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>


    <!-- SWEET ALERT-->
    <script src="{{ asset('vendor/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>


        $(document).ready(function () {

            $('#signal_wire_accounts_listing').dataTable({
                "pageLength": 50
            });

            $('.sweet_alert_warning').on('click', function (e) {
                // Sweet alert delete number warning
                e.preventDefault();
                let formId = $(this).attr("data-form-id");

                swal({
                    icon: 'warning',
                    title: "Are you sure?",
                    text: "All numbers associated with this account will be deleted from application and SignalWire too!",
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
                        document.getElementById('del-account-form-' + formId).submit();
                    } else {
                        swal("Cancelled", "Action has cancelled!", "error");
                    }
                });
            });

        });
    </script>
@endsection
