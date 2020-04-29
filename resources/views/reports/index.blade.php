@extends('layouts.app')

@section('title')
    Reports
@endsection

@section('page_styles')
    <!-- TAGS INPUT-->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
    <!-- SLIDER CTRL-->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-slider/dist/css/bootstrap-slider.css')}}">
    <!-- CHOSEN-->
    <link rel="stylesheet" href="{{asset('vendor/chosen-js/chosen.css')}}">
    <!-- DATETIMEPICKER-->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}">
    <!-- COLORPICKER-->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css')}}">
    <!-- SELECT2-->
    <link rel="stylesheet" href="{{asset('vendor/select2/dist/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css')}}">
    <!-- WYSIWYG-->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-wysiwyg/css/style.css')}}">

    <!-- Include Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Calls Reports<small style="margin-top: 5% !important;">Generate calls report as per your
                    criteria</small></div>
        </div>

        <div class="container-fluid">
        @include('layouts.flash_messages')

        <!-- DATATABLE DEMO 1-->
            <div class="card card-default">


                <div class="card-body">

                    {{--Add search filters--}}
                    @include('reports.filters')


                    <div class="card-header">
                        <div class="card-title">Searched results</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped my-4 w-100">
                            <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>SignalWire Number</th>
                                <th>Agent Number</th>
                                <th>From Number</th>
                                <th>Business</th>
                                <th>Account</th>
                                <th>Call SID</th>
                                <th>Call Duration</th>
                                <th>direction</th>
                                <th>Recording Type</th>
                                <th>Recording</th>
                                <th>Recording Duration</th>
                                <th>Created At</th>
                                <th>Last Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $calls as $call)
                                <tr class="gradeC">

                                    <td>{{$counter++}}</td>
                                    <td>{{ $call->to }}</td>
                                    <td>{{ $call->agent_number }}</td>
                                    <td>
                                        <button type="button"
                                                @if( is_null($call->is_blacklisted ) )
                                                class="btn btn-primary"
                                                onclick="blackListNumber(this.id,'{{$call->from}}')"
                                                id="from-{{$call->id}}"
                                                data-toggle="tooltip" title="Add this number into DNC List"
                                                @else
                                                class="btn btn-danger"
                                                data-toggle="tooltip" title="The number is in DNC List"
                                            @endif
                                        >{{ $call->from }}</button>
                                    </td>
                                    <td>
                                        @if( isset($call->business->title))
                                            {{ $call->business->title }}
                                        @endif
                                    </td>
                                    <td>
                                        @if( isset($call->account->friendly_name))
                                            {{ $call->account->friendly_name }}
                                        @endif
                                    </td>

                                    <td>{{ $call->call_sid }}</td>
                                    <td>{{ $call->dial_call_duration }}</td>
                                    <td>{{ $call->direction }}</td>
                                    <td>
                                        @if($call->recording_type == 'voice_mail')
                                            Voice-mail
                                        @else
                                            Conversation
                                        @endif
                                    </td>
                                    <td>
                                        <audio controls>
                                            <source src="{{ $call->recording_url }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </td>

                                    <td>{{ $call->recording_duration }}</td>
                                    <td>{{ $call->created_at->toDayDateTimeString() }}</td>
                                    <td>{{ $call->updated_at->toDayDateTimeString() }}</td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        Showing {{ ( $calls->currentpage()-1 ) * $calls->perpage() + 1 }}
                        to {{ $calls->currentpage()*$calls->perpage() }}
                        of {{ $calls->total() }} entries

                        <nav class="ml-auto">
                            {{ $calls->appends($_GET)->links() }}

                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <!-- FILESTYLE-->
    <script src="{{asset('vendor/bootstrap-filestyle/src/bootstrap-filestyle.js')}}"></script>
    <!-- TAGS INPUT-->
    <script src="{{asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.js')}}"></script>
    <!-- CHOSEN-->
    <script src="{{asset('vendor/chosen-js/chosen.jquery.js')}}"></script>
    <!-- SLIDER CTRL-->
    <script src="{{asset('vendor/bootstrap-slider/dist/bootstrap-slider.js')}}"></script>
    <!-- INPUT MASK-->
    <script src="{{asset('vendor/inputmask/dist/jquery.inputmask.bundle.js')}}"></script>
    <!-- WYSIWYG-->
    <script src="{{asset('vendor/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
    <!-- MOMENT JS-->
    <script src="{{asset('vendor/moment/min/moment-with-locales.js')}}"></script>
    <!-- DATETIMEPICKER-->
    <script src="{{asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
    <!-- COLORPICKER-->
    <script src="{{asset('vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js')}}"></script>
    <!-- SELECT2-->
    <script src="{{asset('vendor/select2/dist/js/select2.full.js')}}"></script>

    <!-- Demo-->
    {{--<script src="{{ asset('js/demo/demo-search.js') }}"></script>--}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    <script>
        (function (window, document, $, undefined) {

            $(function () {

                // BOOTSTRAP SLIDER CTRL
                // -----------------------------------

                $('[data-ui-slider]').slider();

                // CHOSEN: Business
                // -----------------------------------
                $('.chosen-select').chosen({
                    placeholder_text_multiple: "Select businesses",
                });


                // CHOSEN: Agents
                // -----------------------------------
                $('.chosen-agent').chosen({
                    placeholder_text_multiple: "Select agents",
                });




            });

        })(window, document, window.jQuery);

        //
        $(function () {


            /**
             * A block of php written to reinstate the values of Date Range Picker
             * when the page reloads with results
             * @type {string}
             */
                <?php
                if ( request( 'by_date' ) and request( 'by_date' ) !== null ) {
                    $dateTime = explode( '/', request( 'by_date' ) );
                    $start    = $dateTime[ 0 ];
                    $end      = $dateTime[ 1 ];
                } else {
                    $start = null;
                    $end   = null;
                }

                ?>
            let isDateSet = "<?php echo ( $start !== null ) ? 'set' : 'not_set' ?>";

            let start = moment().subtract(29, 'days');
            let end = moment();

            // resetting the Date Range Picker with last values
            if (isDateSet === 'set') {

                start = moment('<?php echo $start; ?>');
                end = moment('<?php echo $end; ?>');

                dateRangePickerCallback(start, end);
            }

            $('#report_date_range').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, dateRangePickerCallback);

            dateRangePickerCallback(start, end);

        });

        /**
         * A callback method of Date Range Picker. This method sets
         * the value for input field which we will catch
         * in our controller and also sets value
         * for span tag to show on view
         *
         * @param start
         * @param end
         */
        function dateRangePickerCallback(start, end) {

            $('#report_date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#by_date_hidden').val(start.format('YYYY-MM-DD') + '/' + end.format('YYYY-MM-DD'));
        }
    </script>
@endsection
