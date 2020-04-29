@extends('layouts.app')

@section('title')
    Available SignalWire Numbers
@endsection

@section('page_styles')
    <!-- DATATABLES-->
    <link rel="stylesheet" href="{{ asset('vendor/datatables-colvis/css/dataTables.colVis.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/media/css/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dataTables.fontAwesome/index.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-heading">
            Available SignalWire numbers to purchase
        </div>
        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
            @include('layouts.flash_messages')

            <!-- START panel-->
                <div class="panel panel-default">

                    <div class="panel-heading">

                        <div class="row">
                            <div class="col-lg-8">
                                Purchase Available SignalWire Numbers
                            </div>
                            <div class="col-lg-4">
                                <button class="btn btn-sm btn-primary float-right" onclick="purchaseNumbers();">
                                    Purchase Selected Numbers
                                </button>
                            </div>
                        </div>


                    </div>
                    <div class="panel-body">
                        <form id="purchase_selected_numbers" action="{{ route('voip-numbers.purchase') }}" method="post">
                            @csrf
                            <input type="hidden" id="numbers_to_purchase" name="numbers_to_purchase" value=""/>
                            <input type="hidden" name="account_id" value="{{$accountId}}"/>
                        </form>

                        <!-- START table-responsive-->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="datatable1">
                                <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Friendly Name</th>
                                    <th>Phone Number</th>
                                    <th>Postal Code</th>
                                    <th>Locality</th>
                                    <th>Capabilities</th>
                                    <th data-check-all>
                                        <div class="checkbox c-checkbox" data-toggle="tooltip"
                                             data-title="Check all to purchase all numbers">
                                            <label>
                                                <input type="checkbox">
                                                <span class="fa fa-check"></span>
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $availableNumbers as $number)
                                    <tr>
                                        <td>{{ ++$counter }}</td>
                                        <td>{{ $number['friendly_name'] }}</td>
                                        <td>{{ $number['phone_number'] }}</td>
                                        <td>{{ $number['postal_code'] }}</td>
                                        <td>{{ $number['locality'] }}</td>
                                        <td>
                                            @foreach($number['capabilities'] as $key => $value)
                                                @if($key == 'voice' && $value == 1)
                                                    <em class="icon-phone"></em> &nbsp;
                                                @elseif($key == 'SMS' && $value == 1)
                                                    <em class="icon-envelope"></em> &nbsp;
                                                @elseif($key == 'MMS' && $value == 1)
                                                    <em class="icon-picture"></em> &nbsp;
                                                @elseif($key == 'fax'  && $value == 1)
                                                    <em class="fa fa-fax"></em>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="checkbox c-checkbox">
                                                <label>
                                                    <input type="checkbox" class="numbers_to_purchase"
                                                           value="{{ $number['phone_number'] }}">
                                                    <span class="fa fa-check"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- END table-responsive-->
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-lg-2">
                                </div>
                                <div class="col-lg-8"></div>
                                <div class="col-lg-2">
                                    <button class="btn btn-sm btn-primary float-right" onclick="purchaseNumbers();">
                                        Purchase Selected Numbers
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>


    </div>






@endsection


@section('page_script')




    <!-- DATATABLES-->
    <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-colvis/js/dataTables.colVis.js') }}"></script>
    <script src="{{ asset('vendor/datatables/media/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/buttons.bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/buttons.colVis.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/buttons.flash.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/buttons.html5.js') }}"></script>
    <script src="{{ asset('vendor/datatables-buttons/js/buttons.print.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap.js') }}"></script>


    <script>
        $('#datatable1').dataTable();

        // $('input:checkbox[name=locationthemes[]]:checked').each(function()
        // {
        //     alert( $(this).val());
        // });

        function purchaseNumbers() {

            let numbers = getSelectedNumbers();
            // alert(numbers);
            $('#numbers_to_purchase').val(numbers);
            document.getElementById('purchase_selected_numbers').submit();


        }


        /**
         * Get the selected checkboxes values. They have number that
         * we are going to purchase using.
         *
         * @return boolean | string
         */
        function getSelectedNumbers() {
            /* declare an checkbox array */
            let chkArray = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $(".numbers_to_purchase:checked").each(function () {
                chkArray.push($(this).val());
            });

            /* we join the array separated by the comma */
            let selected;

            selected = chkArray.join(',');

            /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
            if (selected.length > 0) {
                return selected;
            } else {
                alert("Please at least one of the checkbox");
                return false;
            }
        }


    </script>
@endsection
