<div class="row">
    {{--Total Businesses--}}
    <div class="col-lg-3 col-sm-6">
        <!-- START widget-->
        <div class="panel widget bg-primary">
            <div class="row row-table">
                <div class="col-xs-4 text-center bg-primary-dark pv-lg">
                    <em class="icon-briefcase fa-3x"></em>
                </div>
                <div class="col-xs-8 pv-lg">
                    <div class="h2 mt0">{{$counts['total_businesses']}}</div>
                    <div class="text-uppercase">Businesses</div>
                </div>
            </div>
        </div>
    </div>

    {{--Total Accounts--}}
    <div class="col-lg-3 col-sm-6">
        <!-- START widget-->
        <div class="panel widget bg-purple">
            <div class="row row-table">
                <div class="col-xs-4 text-center bg-purple-dark pv-lg">
                    {{--<em class="icon-globe fa-3x"></em>--}}
                    <em class="icon-phone fa-3x"></em>
                </div>
                <div class="col-xs-8 pv-lg">
                    <div class="h2 mt0">{{$counts['total_accounts']}}
                        <small>Twilio</small>
                    </div>
                    <div class="text-uppercase">Sub Accounts</div>
                </div>
            </div>
        </div>
    </div>

    {{--Total Agents  bg-green-dark --}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <!-- START widget-->
        <div class="panel widget bg-green">
            <div class="row row-table">
                <div class="col-xs-4 text-center bg-green-light pv-lg">
                    <em class="fa fa-users fa-3x"></em>
                </div>
                <div class="col-xs-8 pv-lg">
                    <div class="h2 mt0">{{$counts['total_agents']}}</div>
                    <div class="text-uppercase"> Agents</div>
                </div>
            </div>
        </div>
    </div>

    {{--Total Numbers--}}
    <div class="col-lg-3 col-md-6 col-sm-12">
        <!-- START widget-->
        <div class="panel widget bg-info">
            <div class="row row-table">
                <div class="col-xs-4 text-center bg-info-dark pv-lg">
                    {{--<em class="icon-bubbles fa-3x"></em>--}}
                    <em class="icon-grid fa-3x"></em>
                </div>
                <div class="col-xs-8 pv-lg">
                    <div class="h2 mt0">{{$counts['total_numbers']}}
                        <small>Twilio</small>
                    </div>
                    <div class="text-uppercase">Phone Numbers</div>
                </div>
            </div>
        </div>
    </div>
</div>