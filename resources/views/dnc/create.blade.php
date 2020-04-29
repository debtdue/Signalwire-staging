<!-- START widgets box-->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @include('layouts.flash_messages')

            <div class="card card-default" id="cardDemo1">
                {{-- HEADING --}}
                <div class="card-header">Add numbers in DNC List
                    <a class="float-right" href="#" data-tool="card-collapse" data-toggle="tooltip" title="Collapse Card">
                        <em class="fa fa-minus"></em>
                    </a>
                </div>
                
                <div class="card-wrapper">
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="{{route('dnc-numbers.store')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            {{--Store Options--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">How would you like to add numbers :</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline c-radio">
                                            <input id="inlineradio1" type="radio" name="dnc_method" value="upload_csv" onclick="showDncMethodFields(this.value);" @if( is_null(old('dnc_method')) ) checked="" @elseif( old('dnc_method')=="upload_csv" ) checked="" @elseif( old('dnc_method')=="add_wild_card" ) checked="" @endif />
                                            <span class="fa fa-circle"></span>By uploading a CSV file</label>

                                        {{--Manual--}}
                                        <label class="radio-inline c-radio">
                                            <input id="inlineradio2" type="radio" name="dnc_method" value="add_manually" {{ old('dnc_method') == "add_manually" ? 'checked='.'"'.'checked'.'"' : '' }} onclick="showDncMethodFields(this.value);" />
                                            <span class="fa fa-circle"></span>By entering numbers manually</label>

                                        {{--WildCard--}}
                                        <label class="radio-inline c-radio">
                                            <input id="inlineradio2" type="radio" name="dnc_method" value="add_wild_card" {{ old('dnc_method') == "add_wild_card" ? 'checked='.'"'.'checked'.'"' : '' }} onclick="showDncMethodFields(this.value);" />
                                            <span class="fa fa-circle"></span>By entering wild cards</label>

                                    </div>
                                </div>
                            </fieldset>

                            {{--Upload CSV--}}
                            <fieldset style="display: none" id="dnc_csv_block">
                                <div class="form-group row{{ $errors->has('dnc_csv') ? ' has-error' : '' }}">
                                    <label for="dnc_csv" class="col-sm-2 col-form-label">Upload CSV File</label>
                                    <div class="col-sm-10">
                                        <input class="form-control filestyle {{ $errors->has('dnc_csv') ? 'parsley-error' : '' }}" type="file" data-classbutton="btn btn-default" data-placeholder="Only CSV file types are allowed" data-classinput="form-control inline" name="dnc_csv" id="dnc_csv">

                                        @if ($errors->has('dnc_csv'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('dnc_csv') }}</li></ul>
                                        @else
                                        <span class="help-block m-b-none">The CSV file must only contain ONE Column of Numbers</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Add Manually--}}
                            <fieldset style="display: none" id="dnc_manual_block">
                                <div class="form-group row{{ $errors->has('manual_add') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="manual_add">Manually add numbers</label>
                                    <div class="col-sm-10">
                                        <textarea id="manual_add" class="form-control {{ $errors->has('manual_add') ? 'parsley-error' : '' }}" name="manual_add" placeholder="Write comma separated numbers here">{{ old('manual_add')}}</textarea>
                                        @if ($errors->has('manual_add'))                                        
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('manual_add') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--WildCard--}}
                            <fieldset style="display: none" id="dnc_wild_card_block">
                                <div class="form-group row{{ $errors->has('wild_card_add') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="wild_card_add">Manually add numbers</label>
                                    <div class="col-sm-10">
                                        <textarea id="wild_card_add" class="form-control {{ $errors->has('wild_card_add') ? 'parsley-error' : '' }}" name="wild_card_add" placeholder="Write comma separated wild cards here">{{ old('wild_card_add')}}</textarea>
                                        @if ($errors->has('wild_card_add'))                                        
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('wild_card_add') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting File--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('dnc_greeting_file') ? ' has-error' : '' }}">
                                    <label for="dnc_greeting_file" class="col-sm-2 col-form-label">Upload MP3 for Inbound call from DNC Number</label>
                                    <div class="col-sm-10">
                                        <input class="form-control filestyle {{ $errors->has('dnc_greeting_file') ? 'parsley-error' : '' }}" type="file" data-classbutton="btn btn-default" data-placeholder="Only CSV file types are allowed" data-classinput="form-control inline" name="dnc_greeting_file" id="dnc_greeting_file">
                                        @if ($errors->has('dnc_greeting_file'))                                        
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('dnc_greeting_file') }}</li></ul>
                                        @else
                                        <span class="help-block m-b-none">The CSV file must only contain ONE Column of Numbers</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Store Numbers--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <button class="btn btn-sm btn-primary" type="submit">Add Numbers</button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                        <br><br>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
</div>