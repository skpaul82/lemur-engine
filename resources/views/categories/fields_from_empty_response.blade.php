

    <!-- Category Group Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="category_group_id_div">
        {!! Form::label('category_group_id', 'Category Group:', ['data-test'=>"category_group_id_label"]) !!}
        {!! Form::select('category_group_id', $categoryGroupList, (!empty($category)?$category->categoryGroup->slug:(!empty($category)?$category->categoryGroup->slug:"")), [  App\Models\WordSpelling::getFormValidation('category_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control allow-new select2', 'data-test'=>"$htmlTag-category_group_id_select", 'id'=>"$htmlTag-category_group_id_select-select"]) !!}
    </div>

<div class="clearfix"></div>



<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="pattern_div">
    {!! Form::label('pattern', 'Pattern:', ['data-test'=>"pattern_label"]) !!}
    {!! Form::text('pattern', $emptyResponse->input, ['class' => 'form-control', App\Models\Category::getFormValidation('pattern'),'id'=>"pattern_field", 'data-test'=>"pattern_field"] ) !!}
    <small class="help-block" data-test="login-fields-email-help-block">
        <span>You do not need to add the enclosing &lt;pattern>&lt;/pattern> tags.</span>
    </small>
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="topic_div">
    {!! Form::label('topic', 'Topic:', ['data-test'=>"topic_label"]) !!}
    {!! Form::text('topic', null, ['class' => 'form-control', App\Models\Category::getFormValidation('topic'),'id'=>"topic_field", 'data-test'=>"topic_field"] ) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="that_div">
    {!! Form::label('that', 'That:', ['data-test'=>"that_label"]) !!}
    {!! Form::text('that', null, ['class' => 'form-control', App\Models\Category::getFormValidation('that'),'id'=>"that_field", 'data-test'=>"that_field"] ) !!}
    <small class="help-block" data-test="login-fields-email-help-block">
        <span>You do not need to add the enclosing &lt;that>&lt;/that> tags.</span>
    </small>
</div>

<div class="clearfix"></div>

<!-- Template Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="template_div">
    {!! Form::label('template', 'Template:', ['data-test'=>"template_label"]) !!}
    {!! Form::textarea('template', null, ['rows' => 10, 'class' => 'form-control', 'id'=>"template_field", 'data-test'=>"template_field", App\Models\Category::getFormValidation('template')] ) !!}
    <small class="help-block" data-test="login-fields-email-help-block">
        <span>You do not need to add the enclosing &lt;template>&lt;/template> tags.</span>
    </small>
</div>

<div class="clearfix"></div>

<!-- Status Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="status_div">
    {!! Form::label('status', 'Status:', ['data-test'=>"status_label"]) !!}
    {!! Form::select('status', config('lemur_dropdown.item_status'), null, [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', App\Models\Turn::getFormValidation('status'), 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
</div>
    <div class="clearfix"></div>
    <!-- 'Boolean delete_original' checked by default -->
    <div class="form-group col-sm-6" data-test="delete_original_div_div">
        {!! Form::label('delete_original', 'Delete Empty Response:', ['data-test'=>"delete_original_label"]) !!}
        <div class="input-group" data-test="delete_original_div_group">
        <span class="input-group-addon">
            {!! Form::hidden('delete_original', 0) !!}
            {{ Form::checkbox('delete_original', '1', true, ['id'=>"delete_original_field", 'data-test'=>"delete_original_field"])  }}
         </span>
            <input type="text" class="form-control" aria-label="..." value="Delete the original empty response on category creation?">
        </div><!-- /.col-lg-6 -->
    </div>

<div class="clearfix"></div>

{!! Form::hidden('empty_response_id', $emptyResponse->slug) !!}
{!! Form::hidden('redirect_url', url('/emptyResponses')) !!}

