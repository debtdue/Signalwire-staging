
<form action="{{ route('reports.search') }}" method="GET">
    @csrf
    <fieldset>
        <div class="form-group row">
            <div class="col-lg-12 col-md-12">
                <div class="form-row">


                    {{--Business--}}
                    <div class="col-lg-4 ">
                        <label for="search_by_business" class="control-label mb">By Businesses</label>
                        <select id="search_by_business" class="chosen-select input-md form-control"
                                multiple name="businesses[]">

                            @foreach($businesses as $business)
                                <option
                                    @if( ! empty(request('businesses') ) and ! is_null(request('businesses'))  )
                                    @foreach(request('businesses') as $selection)
                                    @if( $business->id == $selection) selected
                                    @endif
                                    @endforeach
                                    @endif
                                    value="{{ $business->id }}">{{ $business->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{--Date range--}}
                    <div class="col-lg-3 ">
                        <label class="control-label mb">By Date</label>
                        <div id="report_date_range" class="pull-right"
                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;<span></span><b class="caret"></b>
                            <input type="hidden" name="by_date" value="" id="by_date_hidden"/>
                        </div>
                    </div>


                    {{--Call duration--}}
                    <div class="col-lg-2 ml-4 ">
                        <label for="sl2" class="control-label mb">By Call Duration Range</label>
                        <div class="input-group">
                            <input class="slider form-control" id="sl2" data-ui-slider="" type="text"
                                   value="" name="call_duration"
                                   data-slider-min="1" data-slider-max="10000" data-slider-step="5"
                                   @if( ! is_null(request('call_duration')))
                                   data-slider-value="[{{ request('call_duration') }}]"
                                   @else
                                   data-slider-value="[5,50]"
                                @endif
                            />

                        </div>
                    </div>


                    {{--Submit--}}
                    <div class="col-lg-2  float-right">
                        <label class="control-label mb"></label>
                        <div class="input-group">
                            <button class="btn btn-success" type="submit">Apply search filters</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </fieldset>
</form>
<hr/>
