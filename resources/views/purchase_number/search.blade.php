@extends('layouts.app')

@section('title')
    Search Numbers
@endsection

@section('page_styles')
    <!-- CHOSEN-->
    <link rel="stylesheet" href="{{ asset('vendor/chosen_v1.2.0/chosen.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Search Numbers<small>Search SignalWire numbers and purchase them directly from the application</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
                @include('layouts.flash_messages')

                <!-- START card-->
                <div class="card card-default">
                    {{-- <div class="card-header">Search Numbers</div> --}}
                    <div class="card-body">

                        <form class="form-horizontal" method="post" action="{{ route('voip-numbers.list') }}" enctype="multipart/form-data">
                            @csrf

                            {{--Assigned Twillio Account--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="sub_account">Select Account</label>
                                    <div class="col-sm-6">
                                        <select class="chosen-select input-md form-control {{ $errors->has('voip_account_id') ? 'parsley-error' : '' }}" name="voip_account_id" id="sub_account"
                                                data-placeholder="Type account name to search">
                                            <option value="">Select account </option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->friendly_name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('voip_account_id'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('voip_account_id') }}</li></ul>
                                        @endif


                                    </div>
                                </div>
                            </fieldset>

                            {{--Country--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="title">Country</label>
                                    <div class="col-sm-6">
                                        <input id="title" class="form-control {{ $errors->has('country') ? 'parsley-error' : '' }}" type="text" disabled
                                               name="country" value="United States"
                                               placeholder="Enter country">
                                        @if ($errors->has('country'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('country') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Search By Area Code Or Location--}}
                            <fieldset id="search_number_type_block">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Search Number By</label>
                                    <div class="col-sm-6 {{ $errors->has('search_number_type') ? 'parsley-error' : '' }}">
                                        {{--<div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="search_number_type" value="location"
                                                       @if( old('search_number_type')=== null)
                                                       checked=""
                                                       @elseif( old('search_number_type') == "location")
                                                       checked=""
                                                       @endif
                                                       onclick="showSearchNumberOptions(this.value);">
                                                <span class="fa fa-circle"></span>Location</label>
                                        </div>--}}
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="search_number_type" value="area_code_file"
                                                       {{ old('search_number_type') == "area_code_file" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                       onclick="showSearchNumberOptions(this.value);">
                                                <span class="fa fa-circle"></span>Area Code</label>
                                        </div>

                                        {{--
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="search_number_type" value="postal_code_file"
                                                       {{ old('search_number_type') == "postal_code_file" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                       onclick="showSearchNumberOptions(this.value);">
                                                <span class="fa fa-circle"></span>Postal Code</label>
                                        </div>--}}

                                        @if ($errors->has('search_number_type'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('search_number_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Search By AreaCode--}}
                            <fieldset style="display: none" id="search_by_area_code_block">
                                <div class="form-group row">
                                    <label for="search_by_area_code" class="col-sm-2 col-form-label">Search by Area
                                        Code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control filestyle {{ $errors->has('area_codes_file') ? 'parsley-error' : '' }}" type="file"
                                               data-classbutton="btn btn-default"
                                               data-classinput="form-control inline" name="area_codes_file"
                                               id="search_by_area_code">
                                        @if ($errors->has('area_codes_file'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('area_codes_file') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Search by Postan Code--}}
                            <fieldset style="display: none" id="search_by_postal_code_block">
                                <div class="form-group row">
                                    <label for="search_by_area_code" class="col-sm-2 col-form-label">Search by Postal
                                        Code</label>
                                    <div class="col-sm-6">
                                        <input class="form-control filestyle {{ $errors->has('postal_codes_file') ? 'parsley-error' : '' }}" type="file"
                                               data-classbutton="btn btn-default"
                                               data-classinput="form-control inline" name="postal_codes_file"
                                               id="search_by_postal_code">
                                        @if ($errors->has('postal_codes_file'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('postal_codes_file') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Search By Location--}}
                            <fieldset style="display: none" id="search_by_location_block">
                                <div class="form-group row">
                                    <label for="search_by_location" class="col-sm-2 col-form-label">Search By
                                        Location</label>
                                    <div class="col-sm-6">
                                        <input id="search_by_location" class="form-control {{ $errors->has('search_by_location') ? 'parsley-error' : '' }}"
                                               name="search_by_location" type="text"
                                               value="{{old('search_by_location')}}"
                                               placeholder="Enter location like Boston"/>
                                        @if ($errors->has('search_by_location'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('search_number_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Capabilities--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Capabilities</label>
                                    <div class="col-sm-10 {{ $errors->has('capabilities') ? 'parsley-error' : '' }}">
                                        <div class="checkbox c-checkbox needsclick">
                                            <label class="needsclick">
                                                <input class="needsclick" name="capabilities[]" type="checkbox"
                                                       value="any"
                                                       @if( old('capabilities') === null)
                                                       checked=""
                                                       @elseif( in_array('any', old('capabilities') ) )
                                                       checked=""
                                                        @endif
                                                />
                                                <span class="fa fa-check"></span>Any</label>
                                        </div>
                                        <div class="checkbox c-checkbox needsclick">
                                            <label class="needsclick">
                                                <input class="needsclick" name="capabilities[]" type="checkbox"
                                                       value="voice"
                                                       @if ( old('capabilities') !== null and in_array( 'voice', old('capabilities') ) )
                                                       checked=""
                                                        @endif
                                                />
                                                <span class="fa fa-check"></span>Voice</label>
                                        </div>
                                        <div class="checkbox c-checkbox">
                                            <label>
                                                <input type="checkbox" name="capabilities[]" value="sms"
                                                       @if (  old('capabilities') !== null and  in_array( 'sms', old('capabilities') ) )
                                                       checked=""
                                                        @endif
                                                />
                                                <span class="fa fa-check"></span>SMS</label>
                                        </div>
                                        @if ($errors->has('capabilities'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('capabilities') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Number Type--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Number Type </label>
                                    <div class="col-sm-6 {{ $errors->has('number_type') ? 'parsley-error' : '' }}">
                                        {{--Any--}}
{{--                                        <div class="radio c-radio">--}}
{{--                                            <label>--}}
{{--                                                <input type="radio" name="number_type" value="any"--}}
{{--                                                       @if( old('number_type') === null or old('number_type') == 'any' )--}}
{{--                                                       checked=""--}}
{{--                                                        @endif--}}
{{--                                                />--}}
{{--                                                <span class="fa fa-circle"></span>Any</label>--}}
{{--                                        </div>--}}

                                        {{--Mobile--}}
{{--                                        <div class="radio c-radio">--}}
{{--                                            <label>--}}
{{--                                                <input type="radio" name="number_type" value="mobile"--}}
{{--                                                        {{ old('number_type') == "mobile" ? 'checked='.'"'.'checked'.'"' : '' }}--}}
{{--                                                />--}}
{{--                                                <span class="fa fa-circle"></span>Mobile</label>--}}
{{--                                        </div>--}}

                                        {{--Local--}}
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="number_type" value="local"
                                                        {{ old('number_type') == "local" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                />
                                                <span class="fa fa-circle"></span>Local</label>
                                        </div>


                                        {{--TollFree--}}
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="number_type" value="toll_free"
                                                        {{ old('number_type') == "toll_free" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                />
                                                <span class="fa fa-circle"></span>TollFree</label>
                                        </div>


                                        @if ($errors->has('number_type'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('number_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Search Numbers--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Search Numbers</button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <!-- CHOSEN-->
    <script src="{{ asset('vendor/chosen_v1.2.0/chosen.jquery.min.js') }}"></script>

    <!-- FILESTYLE-->
    <script src="{{ asset('vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script>

    <script>

        (function (window, document, $, undefined) {

            $(function () {

                // CHOSEN
                // -----------------------------------
                $('.chosen-select').chosen();


                let searchNumberTypeOnLoad = $('input[name=search_number_type]:checked').val();
                showSearchNumberOptions(searchNumberTypeOnLoad);


            });

        })(window, document, window.jQuery);

        /**
         * Show and hide search numbers options
         *
         * @param option
         */
        function showSearchNumberOptions(option) {
            // alert(option);
            if (option === 'location') {
                $('#search_by_location_block').show('slow');
                $('#search_by_area_code_block').hide('slow');
                $('#search_by_postal_code_block').hide('slow');

                // clearing selected file and its name input
                $("#search_by_area_code").val("");
                $(".bootstrap-filestyle input.form-control ").val('');

            }
            else if (option === 'postal_code_file') {

                $('#search_by_postal_code_block').show('slow');
                $('#search_by_location_block').hide('slow');
                $('#search_by_area_code_block').hide('slow');

                // clearing location input
                $('#search_by_location').val('');
                $(".bootstrap-filestyle input.form-control ").val('');
            }
            else {
                $('#search_by_area_code_block').show('slow');
                $('#search_by_postal_code_block').hide('slow');
                $('#search_by_location_block').hide('slow');

                // clearing location input
                $('#search_by_location').val('');
                $(".bootstrap-filestyle input.form-control ").val('');

            }
        }
    </script>
@endsection
