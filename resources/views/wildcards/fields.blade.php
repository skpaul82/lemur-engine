<!-- Conversation Id Field -->
@if( !empty($wildcard) && !empty($wildcard->conversation_id) && strpos('conversation_id', '_id')!==false)

    @php
        $modelParts = explode('_id', 'conversation_id');
        $goToModel = $modelParts[0]."s";
    @endphp

    <div class="form-group col-sm-3" data-test="conversation_id_div">
        {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
        <div class="input-group">
            {!! Form::number('conversation_id', null, ['class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", 'data-validation'=>"required"]) !!}
            <div class="input-group-btn">
                @if(!empty($wildcard)&&!empty($wildcard->conversation_id))
                    <a href="{!!url($goToModel.'/'.$wildcard->conversation_id) !!}" id='wildcard_button' class='btn btn-warning' data-test='wildcard_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-3" data-test="conversation_id_div">
    {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
    {!! Form::number('conversation_id', null, ['class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", 'data-validation'=>"required"]) !!}
</div>

@endif


<!-- Slug Field -->
@if( !empty($wildcard) && !empty($wildcard->slug) && strpos('slug', 'slug')!==false)

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
        {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, 'id'=>"slug_field", 'readonly'=>'readonly', App\Models\Wildcard::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button' class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button' class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@elseif( !empty($wildcard) && !empty($wildcard->slug) && strpos('slug', 'url')!==false)

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('slug'), 'id'=>"slug_field", 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                @if(!empty($wildcard)&&!empty($wildcard->slug))
                    <a href="{!! $wildcard->slug !!}" id='slug_button' class='btn btn-warning' data-test='slug_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-6" data-test="slug_div">
    {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
    {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('slug'),'id'=>"slug_field", 'data-test'=>"slug_field"] ) !!}
</div>


@endif


<!-- Type Field -->
@if( !empty($wildcard) && !empty($wildcard->type) && strpos('type', 'slug')!==false)

    <div class="form-group col-sm-6" data-test="type_div">
        {!! Form::label('type', 'Type:', ['data-test'=>"type_label"]) !!}
        <div class="input-group">
        {!! Form::text('type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, 'id'=>"type_field", 'readonly'=>'readonly', App\Models\Wildcard::getFormValidation('type'), 'data-test'=>"type_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button' class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button' class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@elseif( !empty($wildcard) && !empty($wildcard->type) && strpos('type', 'url')!==false)

    <div class="form-group col-sm-6" data-test="type_div">
        {!! Form::label('type', 'Type:', ['data-test'=>"type_label"]) !!}
        <div class="input-group">
            {!! Form::text('type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('type'), 'id'=>"type_field", 'data-test'=>"type_field"]) !!}
            <div class="input-group-btn">
                @if(!empty($wildcard)&&!empty($wildcard->type))
                    <a href="{!! $wildcard->type !!}" id='type_button' class='btn btn-warning' data-test='type_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-6" data-test="type_div">
    {!! Form::label('type', 'Type:', ['data-test'=>"type_label"]) !!}
    {!! Form::text('type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('type'),'id'=>"type_field", 'data-test'=>"type_field"] ) !!}
</div>


@endif


<!-- Value Field -->
@if( !empty($wildcard) && !empty($wildcard->value) && strpos('value', 'slug')!==false)

    <div class="form-group col-sm-6" data-test="value_div">
        {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
        <div class="input-group">
        {!! Form::text('value', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, 'id'=>"value_field", 'readonly'=>'readonly', App\Models\Wildcard::getFormValidation('value'), 'data-test'=>"value_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button' class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button' class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@elseif( !empty($wildcard) && !empty($wildcard->value) && strpos('value', 'url')!==false)

    <div class="form-group col-sm-6" data-test="value_div">
        {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
        <div class="input-group">
            {!! Form::text('value', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('value'), 'id'=>"value_field", 'data-test'=>"value_field"]) !!}
            <div class="input-group-btn">
                @if(!empty($wildcard)&&!empty($wildcard->value))
                    <a href="{!! $wildcard->value !!}" id='value_button' class='btn btn-warning' data-test='value_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-6" data-test="value_div">
    {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
    {!! Form::text('value', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255, App\Models\Wildcard::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
</div>


@endif

