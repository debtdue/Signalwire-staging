{{-- HEADING --}}
<div class="card-header">Search Results
    <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh Card" data-spinner="standard">
        <em class="fas fa-sync"></em>
    </a>
</div>

<!-- START card-body-->
<div class="card-body">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Serial #</th>
            <th>Calls</th>
        </tr>
        </thead>
        <tbody>

        @foreach($calls as $call)
            <tr>
                <td>
                    <div class="checkbox c-checkbox">
                        {{ $counter++ }}
                    </div>
                </td>
                <td>
                    <div class="media-box">
                        <a class="pull-left" href="#">
                            {{--<img class="media-box-object img-responsive img-rounded thumb64" src="{{ asset('img/dummy.png') }}" alt="">--}}
                        </a>
                        <div class="media-box-body">
                            <a class="pull-right btn btn-info btn-sm"
                               href="{{ route('reports.searched_call_details', $call->id) }}">View</a>
                            <h4 class="media-box-heading">{{ $call->business['title'] }}</h4>
                            <small class="text-muted">To: {{ $call->to }}, From: {{ $call->from }}</small>
                            {{--<p>--}}

                            {{--@if( isset($call->business->agents ))--}}
                            {{--@foreach( $call->business->agents  as $agent)--}}
                            {{--@if( $call->to == $agent->phone_number )--}}
                            {{--Received By: Agent Name {{$agent->first_name}} <br/>--}}
                            {{--@endif--}}
                            {{--@endforeach--}}
                            {{--@endif--}}
                            {{--Recording Type: {{ $call->recording_type }} <br/>--}}
                            {{--Recording Duration: {{ $call->recording_duration }} <br/>--}}
                            {{--Call Duration: {{ $call->dial_call_duration }} <br/>--}}
                            {{--Call Direction: {{ $call->direction }} <br/>--}}
                            {{--Call Status: {{ $call->dial_call_status }} <br/>--}}
                            {{--</p>--}}
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

<!-- END table-responsive-->
<div class="card-footer">
    <div class="row">
        <div class="col-lg-4">
            Showing {{ ( $calls->currentpage()-1 ) * $calls->perpage() + 1 }}
            to {{ $calls->currentpage()*$calls->perpage() }}
            of {{ $calls->total() }} entries
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-6 text-right">
            {{ $calls->appends(request()->query())->links() }}
        </div>
    </div>
</div>