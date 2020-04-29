@extends('layouts.app')

@section('title')
    Reports: Call Details
@endsection


@section('page_styles')
@endsection

@section('content')

    <!-- Page content-->
    <div class="content-wrapper">
        <h3>
            <em class="fa fa-phone fa-fw mr-sm"></em>Call Details</h3>
        <div class="panel">
            <div class="panel-body">

                <h3 class="mt0">{{ ucfirst( $call->direction ) }} Call to {{ $call->business->title }} Business
                    <button class="btn btn-primary pull-right" type="button" onclick="window.print();">Print</button>
                </h3>

                <hr>
                <div class="row mb-lg">
                    <div class="col-lg-4 col-xs-6 br pv">
                        <div class="row">
                            <div class="col-md-2 text-center visible-md visible-lg">
                                <em class="fa fa-briefcase fa-3x text-muted"></em>
                            </div>
                            <div class="col-md-10">
                                <h4>{{ $call->business->title }}</h4>
                                <address></address>
                                Business Description: {{ $call->business->description }}
                                <br>Twilio Account: {{ $call->business->account->title }}
                                <br>Neverland
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6 br pv">
                        <div class="row">
                            <div class="col-md-2 text-center visible-md visible-lg">
                                <em class="fa fa-user fa-3x text-muted"></em>

                            </div>
                            <div class="col-md-10">

                                @if( isset($call->business->agents ))
                                    @foreach( $call->business->agents  as $agent)
                                        @if( $call->to == $agent->phone_number )
                                            {{--@if( $call->to == '17722915752' )--}}
                                            <h4>{{ $agent->first_name . ' ' . $agent->last_name }} </h4>
                                            <address></address>
                                            <em class="icon-envelope-open"></em> {{ $agent->email }}
                                            <br><em class="icon-phone"></em> {{ $agent->phone_number }}
                                            <br>
                                        @endif
                                    @endforeach
                                @else
                                    <h4>No Agent found</h4>
                                @endif


                            </div>
                        </div>
                    </div>
                    <div class="clearfix hidden-md hidden-lg">
                        <hr>
                    </div>
                    <div class="col-lg-4 col-xs-12 pv">
                        <div class="clearfix">
                            <p class="pull-left">CALL SID.</p>
                            <p class="pull-right mr">{{ $call->call_sid }}</p>
                        </div>
                        <div class="clearfix">
                            <p class="pull-left">Received At</p>
                            <p class="pull-right mr">{{ $call->created_at->toDayDateTimeString() }}</p>
                        </div>
                        <div class="clearfix">
                            <p class="pull-left">Details Updated At</p>
                            <p class="pull-right mr">{{ $call->updated_at->toDayDateTimeString() }}</p>
                        </div>
                    </div>
                </div>


                {{--Basic details--}}
                <div class="table-responsive table-bordered mb-lg">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>To</th>
                            <th>From</th>
                            <th>Duration</th>
                            <th>Type</th>
                            <th>Direction</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach($call)--}}
                        {{--@endforeach--}}
                        <tr>
                            <td>{{ $call->to }}</td>
                            <td>{{ $call->from }}</td>
                            <td>{{ $call->dial_call_duration }}</td>
                            <td>

                                @if($call->recording_type == 'voice_mail')
                                    Voice-mail
                                @else
                                    Conversation
                                @endif

                            </td>
                            <td>{{ ucfirst( $call->direction ) }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>


                {{--Recording Block--}}
                <div class="row">

                    <div class="col-sm-offset-0 col-sm-8 pv">
                        <div class="clearfix">
                            <p class="pull-left h3">Play Recording:</p>
                            <p class="pull-right mr h3">
                                <audio controls>
                                    <source src="{{ $call->recording_url }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </p>
                        </div>


                    </div>


                    {{--Other Details--}}
                    <div class="row">
                        <div class="col-sm-offset-8 col-sm-4 pv">

                            <div class="clearfix">
                                <p class="pull-left">From Zip</p>
                                <p class="pull-right mr">{{ $call->from_zip }}</p>
                            </div>

                            <div class="clearfix">
                                <p class="pull-left">From State</p>
                                <p class="pull-right mr">{{ $call->from_state }}</p>
                            </div>

                            <div class="clearfix">
                                <p class="pull-left">From Country</p>
                                <p class="pull-right mr">{{ $call->from_country }}</p>
                            </div>

                            <div class="clearfix">
                                <p class="pull-left h3">Call Status</p>
                                <p class="pull-right mr h3">{{ ucfirst($call->dial_call_status )}}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="hidden-print">
                    <div class="col-sm-offset-8 col-sm-4 pv clearfix">
                        <a href="{{ route('reports.search') }}" class="btn btn-success pull-right" type="button">Search Again?</a>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('page_script')


@endsection