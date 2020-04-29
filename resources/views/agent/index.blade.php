@extends('layouts.app')

@section('title')
    Agents
@endsection

@section('page_styles')
    <!-- Datatables-->
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- Heading --}}
        <div class="content-heading">
            <div>Manage Agents<small>Edit and delete agents</small></div>
        </div>

        <div class="container-fluid">
        @include('layouts.flash_messages')

        <!-- DATATABLE-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">List of All Agents</div>
                </div>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100" id="agents_dt">
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                            <th>Assigned Businesses</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $agents as $agent)
                            <tr class="gradeC">
                                <td>1</td>
                                <td>{{ $agent->first_name }}</td>
                                <td>{{ $agent->last_name }}</td>
                                <td>{{ $agent->phone_number }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>
                                    @if( ! empty( $agent->businesses) )
                                        @foreach( $agent->businesses as $business )
                                            {{$business->title}}
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <div class="panel-body">
                                        {{-- <a class="btn btn-square btn-info" data-toggle="tooltip"
                                           title="View agent details" href="{{ route('agents.show',$agent->id) }}"><em
                                                class="icon-magnifier"></em>
                                        </a> --}}

                                        <a class="btn btn-square btn-success" data-toggle="tooltip"
                                           title="Update agent details" href="{{ route('agents.edit',$agent->id) }}"><em
                                                class="icon-pencil"></em> 
                                        </a>

                                        <a href="{{ route('agents.destroy',$agent->id) }}" data-toggle="tooltip"
                                           title="Delete agent" class="btn btn-square btn-danger sweet_alert_warning"
                                           data-form-id="{{ $agent->id }}">
                                            <em class="icon-trash"></em>
                                        </a>
                                        
                                        <form id="del-agent-form-{{$agent->id}}"
                                              action="{{ route('agents.destroy',$agent->id) }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
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

            $('#agents_dt').dataTable();

            $('.sweet_alert_warning').on('click', function (e) {
                e.preventDefault();
                let formId = $(this).attr("data-form-id");

                swal({
                    icon: 'warning',
                    title: "Are you sure?",
                    text: "All agent data will be deleted too!",
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
                        document.getElementById('del-agent-form-' + formId).submit();
                    } else {
                        swal("Cancelled", "Action has cancelled!", "error");
                    }
                });

            });

        });
    </script>
@endsection
