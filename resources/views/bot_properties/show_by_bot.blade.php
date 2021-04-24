<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>



        @if(count($botProperties)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else
            {!! Form::open(['route' => 'botProperties.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}
            {!! Form::hidden('bulk', 1) !!}
            {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$htmlTag."-bot_id"] ) !!}
            {!! Form::hidden('redirect_url', url()->current(),['data-test'=>"$htmlTag-redirect-url"] ) !!}
                        @foreach($botProperties as $index => $item)





                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $item->name !!}_div'>
                                    <label for='{!! $item->name !!}_field' data-test='{!! $item->name !!}_label'>{!! $item->name !!}:</label>
                                    <input type='text' name='name[{!! $item->name !!}]' value='{!! $item->value !!}' class='form-control' id='{!! $item->name !!}_value_field' data-test='{!! $item->name !!}_value_field'>
                                </div>


                        @endforeach

                        <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>

                            {!! Form::close() !!}

        @endif

        </div>
</section>

@include('layouts.by_bot_add_modal')
