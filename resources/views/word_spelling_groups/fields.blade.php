<!-- User Id Field -->
@if( !empty($wordSpellingGroup) && !empty($wordSpellingGroup->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $wordSpellingGroup->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif



<!-- Slug Field -->
@if( !empty($wordSpellingGroup) && !empty($wordSpellingGroup->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', App\Models\WordSpellingGroup::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


<!-- Language Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($wordSpellingGroup)?$wordSpellingGroup->language->slug:(!empty($wordSpellingGroup)?$wordSpellingGroup->language->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', App\Models\WordSpellingGroup::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Description Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"description_field", 'data-test'=>"description_field", App\Models\WordSpellingGroup::getFormValidation('description')] ) !!}
</div>

<div class="clearfix"></div>

@role('admin')

<!-- 'Boolean Is Master Field' checked by default -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="is_master_div">
    {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
    <div class="input-group" data-test="is_master_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_master', 0) !!}
            @if(empty($wordSpellingGroup) || $wordSpellingGroup->is_master==0 || !$wordSpellingGroup->is_master)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_master', '1', $checked, ['id'=>"is_master_field", 'data-test'=>"is_master_field"])  }}
         </span>
        <input type="text" class="form-control" aria-label="..." value="Is Master?">
    </div><!-- /.col-lg-6 -->
</div>
<div class="clearfix"></div>
@endrole
