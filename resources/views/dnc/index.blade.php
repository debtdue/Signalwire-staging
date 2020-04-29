@extends('layouts.app')

@section('title')
    DNC Numbers
@endsection

@section('page_styles')

    <!-- Datatables-->
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">

@endsection

@section('content')
    <div class="content-wrapper">

        <div class="content-heading">
            <div>Do Not Contact Numbers<small style="margin-top: 5% !important;">Add numbers to black list and manage them</small></div>
        </div>

        <!-- START widgets box-->
        @include('dnc.create')

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">DNC List</div>
                </div>
                <div class="card-body">
                    {{-- <table class="table table-striped table-hover my-4 w-100" id="dnc_numbers">             --}}
                    <table class="table table-striped table-hover my-4 w-100" id="datatable1">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Number</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Added On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($numbers as $number)
                            <tr>
                                <td>{{++$counter}}</td>
                                <td>{{ $number->number }}</td>
                                <td>
                                    @if( $number->type == 'number')
                                    Number
                                    @else
                                    Wild Card
                                    @endif
                                </td>
                                <td style="color:red;">
                                    @if($number->blacklisted === 1)
                                    Blocked
                                    @endif
                                </td>
                                <td>{{ $number->created_at->toDayDateTimeString() }}</td>
                                <td>
                                    <div class="panel-body">

                                        <a href="{{ route('dnc-numbers.destroy',$number->id) }}" data-toggle="tooltip" title="Delete number or wild card!" class="btn btn-square btn-danger swal_warning" data-form-id="{{ $number->id }}">
                                            <em class="icon-trash"></em>
                                        </a>
                                        <form id="del-dnc-form-{{$number->id}}" action="{{ route('dnc-numbers.destroy',$number->id) }}" method="POST" style="display: none;">
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

    <!-- FILESTYLE-->
    <script src="{{ asset('vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script>

    <!-- SWEET ALERT-->
    <script src="{{ asset('vendor/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>

        $(document).ready(function () {

            // Show, hide DNC method types according to method type
            let dncMethod = $('input[name=dnc_method]:checked').val();
            showDncMethodFields(dncMethod);


            $('#dnc_numbers').dataTable({
                'paging': true,  // Table pagination
                'ordering': true,  // Column ordering
                'info': true,  // Bottom left status text
                'responsive': false, // https://datatables.net/extensions/responsive/examples/
            });

            $('.swal_warning').on('click', function (e) {
                e.preventDefault();
                let formId = $(this).attr("data-form-id");

                swal({
                    icon: 'warning',
                    title: "Are you sure?",
                    text: "The number will be deleted from DNC list",
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
                        document.getElementById('del-dnc-form-' + formId).submit();
                    } else {
                        swal("Cancelled", "Action has cancelled!", "error");
                    }
                });

            });

            // Sweet alert delete number warning
            // $('.swal_warning').on('click', function (e) {
            //     e.preventDefault();
            //     let formId = $(this).attr("data-form-id");
            //     swal({
            //         title: "Are you sure?",
            //         text: "You will not be able to recover this number!",
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#DD6B55",
            //         confirmButtonText: "Yes, delete it!",
            //         cancelButtonText: "No, cancel please!",
            //         closeOnConfirm: false,
            //         closeOnCancel: false
            //     }, function (isConfirm) {

            //         if (isConfirm) {
            //             document.getElementById('del-dnc-form-' + formId).submit();
            //         } else {
            //             swal("Cancelled", "Action has cancelled!", "error");
            //         }
            //     });

            // });


        });

        /**
         * Play hide n seek for DNC method types
         *
         * @param method
         */
        function showDncMethodFields(method) {

            if (method === 'add_manually') {
                $('#dnc_manual_block').show('slow');
                $('#dnc_csv_block').hide('slow');
                $('#dnc_wild_card_block').hide('slow');
            }
            else if (method === 'add_wild_card') {
                $('#dnc_wild_card_block').show('slow');
                $('#dnc_manual_block').hide('slow');
                $('#dnc_csv_block').hide('slow');
            }
            else {
                $('#dnc_csv_block').show('slow');
                $('#dnc_manual_block').hide('slow');
                $('#dnc_wild_card_block').hide('slow');
            }
        }


    </script>
@endsection
