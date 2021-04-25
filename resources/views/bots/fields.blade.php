<!-- User Id Field -->
@if( !empty($bot) && !empty($bot->user_id))

    <div class="form-group col-sm-6" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $bot->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

@endif

<!-- Slug Field -->
@if( !empty($bot) && !empty($bot->slug) )

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', App\Models\Bot::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='lock' id='slug-lock-button' data-test='slug-lock-button' class='btn btn-success slug-lock-button' style="display:none"><i class='fa fa-unlock'></i></span>
                <span name='unlock' id='slug-unlock-button' data-test='slug-unlock-button' class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@endif


<!-- Language Id Field -->
<div class="form-group col-sm-3 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($bot)?$bot->language->slug:(!empty($bot)?$bot->language->slug:"")), ['disabled'=>$readonly, 'readonly'=>$readonly, 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', App\Models\Bot::getFormValidation('language_id'), 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>


<div class="form-group col-sm-9" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['readonly'=>$readonly, 'class' => 'form-control', App\Models\Bot::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>


<div class="form-group col-sm-3" data-test="name_div">
    {!! Form::label('image', 'Image:', ['data-test'=>"image_label"]) !!}
    <div class="avatar-wrapper">
        @if(!empty($bot)&&!empty($bot->image))
            @php $img = $bot->imageUrl; @endphp
            @php $imgFilename = $bot->image; @endphp
        @else
            @php $img = App\Models\Bot::getDefaultImageUrl(false); @endphp
            @php $imgFilename = ''; @endphp
        @endif

        <img class="bot image-pic" src="{!! $img !!}" />
        <div class="bot upload-button">
            <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
        </div>
        <input name="image-filename" data-test="bot-image-filename"  type="hidden" value="{!! $imgFilename !!}"/>
        <input class="bot image-upload" name="image" id="bot-image" data-test="bot-image"  type="file" accept="image/*"/>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.bot.image-pic').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".bot.image-upload").on('change', function(){
                readURL(this);
            });

            $(".bot.upload-button").on('click', function() {
                $(".bot.image-upload").click();
            });
        });
    </script>
@endpush


<div class="form-group col-sm-9" data-test="summary_div">
    {!! Form::label('summary', 'Summary:', ['data-test'=>"summary_label"]) !!}
    {!! Form::text('summary', null, ['readonly'=>$readonly, 'class' => 'form-control', App\Models\Bot::getFormValidation('summary'),'id'=>"summary_field", 'data-test'=>"summary_field"] ) !!}
</div>

<div class="form-group col-sm-9" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['readonly'=>$readonly, 'rows'=>2, 'class' => 'form-control', App\Models\Bot::getFormValidation('description'),'id'=>"description_field", 'data-test'=>"description_field"] ) !!}
</div>

<div class="form-group col-sm-9" data-test="default_response">
    {!! Form::label('default_response', 'Default Response:', ['data-test'=>"default_response_label"]) !!}
    {!! Form::text('default_response', null, ['readonly'=>$readonly,  'class' => 'form-control', App\Models\Bot::getFormValidation('default_response'),'id'=>"default_response_field", 'data-test'=>"default_response_field"] ) !!}
    <small class="help-block text-muted-wrapped" data-test="">This is the response which is returned if no matching AIML category is found</small>
</div>

<div class="form-group col-sm-9" data-test="lemurtar_div">
    {!! Form::label('lemurtar_url', 'Lemurtar URL:', ['data-test'=>"lemurtar_label"]) !!}
    {!! Form::textarea('lemurtar_url', null, ['readonly'=>$readonly, 'rows'=>2, 'class' => 'form-control', App\Models\Bot::getFormValidation('lemurtar_url'),'id'=>"lemurtar_urlfield", 'data-test'=>"lemurtar_url_field"] ) !!}
    <small class="help-block text-muted-wrapped" data-test="">Visit <a href="https://lemurtar.com">lemurtar.com</a> to generate a talking head avatar for your bot.</small>
</div>

<!-- Status Field -->
<div class="form-group col-sm-3 select2" data-test="status_div">
    {!! Form::label('status', 'Status:', ['data-test'=>"status_label"]) !!}
    {!! Form::select('status', config('lemur_dropdown.item_status'), null, [ 'disabled'=>$readonly, 'readonly'=>$readonly,  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', App\Models\Bot::getFormValidation('status'), 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
</div>



@role('admin')

<!-- 'Boolean Is Master Field' checked by default -->
<div class="form-group col-sm-3" data-test="is_master_div">
    {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
    <div class="input-group" data-test="is_master_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_master', 0) !!}
            @if(empty($bot) || $bot->is_master==0 || !$bot->is_master)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_master', '1', $checked, ['disabled'=>$readonly, 'id'=>"is_master_field", 'data-test'=>"is_master_field"])  }}
         </span>
         <input type="text" class="form-control" {!! ($readonly?'readonly':'') !!} aria-label="..." value="Is Master?">
    </div><!-- /.col-lg-6 -->
</div>

@endrole

<!-- 'Boolean Is Public Field' checked by default -->
<div class="form-group col-sm-3" data-test="is_public_div">
    {!! Form::label('is_public', 'Is Public:', ['data-test'=>"is_public_label"]) !!}
    <div class="input-group" data-test="is_public_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_public', 0) !!}
            @if(empty($bot) || $bot->is_public==0 || !$bot->is_public)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_public', '1', $checked, ['disabled'=>$readonly, 'id'=>"is_public_field", 'data-test'=>"is_public_field"])  }}
         </span>
        <input type="text" {!! ($readonly?'readonly':'') !!} class="form-control" aria-label="..." value="Is Public?">
    </div><!-- /.col-lg-6 -->
</div>

<div class="clearfix"></div>


@push('scripts')
    <script>
        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {
                console.log(numFiles);
                console.log(label);
                $('input#avatar_field').val(label)
            });
        });
    </script>
@endpush
