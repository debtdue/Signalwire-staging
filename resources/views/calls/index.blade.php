@extends('layouts.app')

@section('title')
    Calls Logs
@endsection

@section('page_styles')
    <!-- Datatables-->
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Calls Logs</div>
        </div>

        <!-- START widgets box-->
        <div class="container-fluid">
            @include('layouts.flash_messages')

            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="border-bottom: solid #eee">See all inbound and outbound calls logs
                    </div>
                </div>

                <!-- START table-responsive-->

                <table class="table dataTable table-striped table-bordered table-hover my-4 w-100" id="table-ext-3">
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
                        <th>Call Status</th>
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
                                        onclick="blackListNumber(this.id,{{$call->from}})"
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
                            <td>{{ $call->call_status }}</td>
                            <td>{{ $call->created_at->toDayDateTimeString() }}</td>
                            <td>{{ $call->updated_at->toDayDateTimeString() }}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- END table-responsive-->

                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="input-group pull-right">

                                Showing {{ ( $calls->currentpage()-1 ) * $calls->perpage() + 1 }}
                                to {{ $calls->currentpage()*$calls->perpage() }}
                                of {{ $calls->total() }} entries

                            </div>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-8 text-right">
                            {{ $calls->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('page_script')

    <script src="{{asset('vendor/datatables.net/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script>

        /**
         * Add number to DNC list
         */
        function blackListNumber(id, number) {

            $.ajax({
                type: 'POST',
                url: '{{route('dnc.ajax.call.store')}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                data: {
                    'from_number': number,
                },
                success: function (data) {
                    if (data) {

                        /**
                         * Show success message
                         *
                         * @type {string}
                         */
                        let message = 'Successfully added into DNC list';
                        let options = 'success';
                        setTimeout(function () {
                            $.notify(message, options || {});
                        }, 800);

                        /**
                         * Update number button class so it looks like
                         * black listed
                         */
                        $('#' + id).removeClass("btn btn-primary").addClass("btn btn-danger");

                        return true;
                    } else {

                        let message = 'Failed: There was something wrong happened';
                        let options = 'danger';
                        setTimeout(function () {
                            $.notify(message, options || {});
                        }, 800);
                    }
                }
            });
        }
    </script>
@endsection
