<aside class="col-lg-3">




    <!-- START messages and activity-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Latest Calls</div>
        </div>

        <!-- START list group-->
        <div class="list-group">
            @foreach($last5Calls as $call)
                <!-- START list group item-->
                <div class="list-group-item">
                    <div class="media-box">
                        <div class="pull-left">
                                 <span class="fa-stack">
                                    <em class="fa fa-circle fa-stack-2x @if($call->direction == 'inbound') text-purple @else text-success @endif"></em>
                                    <em class="fa fa-phone fa-stack-1x fa-inverse text-white"></em>
                                 </span>
                        </div>

                        <div class="media-box-body clearfix">
                            <small class="text-muted pull-right ml">{{ $call->created_at->diffForHumans() }}</small>
                            <div class="media-box-heading"><a class="text-purple m0" href="#">{{ $call->from }}</a>
                            </div>
                            <p class="m0">
                                <small><a href="#">{{ $call->to }}</a>
                                </small>
                            </p>
                        </div>

                    </div>
                </div>
                <!-- END list group item-->
            @endforeach
        </div>
        <!-- END list group-->


        <!-- START panel footer-->
        <div class="panel-footer clearfix">
            <a class="pull-left" href="{{ route('calls.logs') }}">
                <small>Go to Calls Logs</small>
            </a>
        </div>
        <!-- END panel-footer-->
    </div>
    <!-- END messages and activity-->




    <!-- START loader widget-->
    <div class="panel panel-default">
        <div class="panel-body">
            <a class="text-muted pull-right" href="#">
                <em class="fa fa-arrow-right"></em>
            </a>
            <div class="text-info">Current Month Average Calls</div>


            <div class="text-center pv-xl">
                <div class="easypie-chart easypie-chart-lg" data-easypiechart data-percent="{{ $currentMonthAvgCalls }}"
                     data-animate="{&quot;duration&quot;: &quot;800&quot;, &quot;enabled&quot;: &quot;true&quot;}"
                     data-bar-color="#23b7e5" data-track-Color="rgba(200,200,200,0.4)"
                     data-scale-Color="false" data-line-width="10" data-line-cap="round" data-size="145">
                    <span>{{ $currentMonthAvgCalls }}%</span>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <p class="text-muted">
                <em class="fa fa-phone fa-fw"></em>
                <span>This Month</span>
                <span class="text-dark">{{ $counts['total_calls'] }} Calls</span>
            </p>
        </div>
    </div>
    <!-- END loader widget-->

</aside>