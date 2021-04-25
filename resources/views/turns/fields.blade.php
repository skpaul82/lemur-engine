<!-- Slug Field -->
@if( !empty($turn) && !empty($turn->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', App\Models\Turn::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button'
                      class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@endif

<!-- Conversation Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="conversation_id_div">
    {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $turn->conversation->slug, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", App\Models\Turn::getFormValidation('conversation_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($turn)&&!empty($turn->conversation_id))
                <a href="{!!url('conversations/'.$turn->conversation->slug) !!}" id='turn_button' class='btn btn-warning' data-test='turn_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>


<!-- Category Id Field -->
@if( !empty($turn) && !empty($turn->category_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="category_id_div">
        {!! Form::label('category_id', 'Category Id:', ['data-test'=>"category_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('', $turn->category->slug, ['class' => 'form-control', 'id'=>"category_id_field", 'data-test'=>"category_id_field", App\Models\Turn::getFormValidation('category_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($turn)&&!empty($turn->category_id))
                    <a href="{!!url('/categories/'.$turn->category->slug) !!}" id='turn_button' class='btn btn-warning' data-test='turn_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
@endif


<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="input_div">
    {!! Form::label('input', 'Input:', ['data-test'=>"input_label"]) !!}
    {!! Form::text('input', null, ['class' => 'form-control', App\Models\Turn::getFormValidation('input'),'id'=>"input_field", 'data-test'=>"input_field"] ) !!}
</div>


<div class="clearfix"></div>

<!-- Output Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="output_div">
    {!! Form::label('output', 'Output:', ['data-test'=>"output_label"]) !!}
    {!! Form::textarea('output', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"output_field", 'data-test'=>"output_field", App\Models\Turn::getFormValidation('output')] ) !!}
</div>

<div class="clearfix"></div>
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="source_div">
    {!! Form::label('source', 'Source:', ['data-test'=>"source_label"]) !!}
    {!! Form::select('source', config('lemur_dropdown.turn_source'), null, [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', App\Models\Turn::getFormValidation('source'), 'data-test'=>"$htmlTag-source-select", 'id'=>"$htmlTag-source-select"]) !!}
</div>


<div class="clearfix"></div>
