<!-- User Id Field -->
@if( !empty($category) && !empty($category->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $category->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($category) && !empty($category->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', App\Models\Category::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button' class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button' class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
@endif


@if(!empty($category) && !empty($category->categoryGroup))

    <!-- Category Group Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="category_group_id_div">
        {!! Form::label('category_group_slug', 'Category Group:', ['data-test'=>"category_group_slug_label"]) !!}
        <div class="input-group">
            {!! Form::select('category_group_slug', $categoryGroupList, (!empty($category)?$category->categoryGroup->slug:(!empty($category)?$category->categoryGroup->slug:"")), [  App\Models\WordSpelling::getFormValidation('category_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control allow-new select2', 'data-test'=>"$htmlTag-category_group_slug_select", 'id'=>"$htmlTag-category_group_slug_select-select"]) !!}
            <div class="input-group-btn">
                @if(!empty($category)&&!empty($category->category_group_id))
                    <a href="{!!url('/categoryGroups/'.$category->categoryGroup->slug) !!}" id='categoryGroup_button' class='btn btn-warning' data-test='categoryGroup_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>

    </div>

    <div class="clearfix"></div>

@else

    <!-- Category Group Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="category_group_id_div">
        {!! Form::label('category_group_slug', 'Category Group:', ['data-test'=>"category_group_slug_label"]) !!}
        {!! Form::select('category_group_slug', $categoryGroupList, (!empty($category)?$category->categoryGroup->slug:(!empty($category)?$category->categoryGroup->slug:"")), [  App\Models\WordSpelling::getFormValidation('category_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control allow-new select2', 'data-test'=>"$htmlTag-category_group_id_select", 'id'=>"$htmlTag-category_group_id_select-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="pattern_div">
    {!! Form::label('pattern', 'Pattern:', ['data-test'=>"pattern_label"]) !!}
    {!! Form::text('pattern', null, ['class' => 'form-control', App\Models\Category::getFormValidation('pattern'),'id'=>"pattern_field", 'data-test'=>"pattern_field"] ) !!}
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
