<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>





        @if(count($categoryGroups)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else

            <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='check_all_div'>
                <div class='input-group'>
                    <span class='input-group-addon'>
                        <input type='checkbox' name="all" id="checkall" data-test='check_all_field'>
                    </span>
                <input type='text' value='Check All' class='form-control' readonly="readonly">
                </div>
            </div>

            <div class="clearfix"></div>



            {!! Form::open(['route' => 'botCategoryGroups.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}


                    @foreach($categoryGroups as $index => $categoryGroup)


                                {!! Form::hidden('bulk', 1) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>$categoryGroup->category_group_id."-redirect-url"] ) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$categoryGroup->category_group_id."-bot_id"] ) !!}

                                {!! Form::hidden('category_group_id['.$index.']', $categoryGroup->category_group_id,['data-test'=>$categoryGroup->category_group_id."-category_group_id"] ) !!}



                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $categoryGroup->name !!}_div'>
                                    <label for='{!! $categoryGroup->name !!}_field' data-test='{!! $categoryGroup->name !!}_label'>{!! $categoryGroup->name !!} categories:</label>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>

                                            <input type='hidden' name='linked[{!! $index !!}]' value='0'>

                                            @if(empty($categoryGroup->is_linked) )
                                                @php $checked = '' @endphp
                                            @else
                                                @php $checked = 'checked=\'true\'' @endphp

                                            @endif

                                        <input type='checkbox' class="cb-element" name='linked[{!! $index !!}]' value='1' {!! $checked !!}  id='{!! $categoryGroup->name !!}_link_field' {$validation} data-test='{!! $categoryGroup->name !!}_link_field'>
                                        </span>

                                        <input type='text' value='{!! strtolower($categoryGroup->name) !!}' class='form-control' id='{!! $categoryGroup->name !!}_value_field' data-test='{!! $categoryGroup->name !!}_value_field'>


                                            <div class="input-group-btn">
                                                <a data-title="{!! ucwords($categoryGroup->name) !!}" data-description="{!! $categoryGroup->description !!}" id='{!! $categoryGroup->category_group_id !!}_info_button' data-test='{!! $categoryGroup->category_group_id !!}_info_button' class='btn btn-info open-info-button'><i class='fa fa-info-circle'></i></a>


                                                <a href="{!! url('categories?col=1&q='.$categoryGroup->category_group_id) !!}"class="btn btn-warning show-button" data-test="show-button-0">
                                                    <i class="fa fa-tree"></i>
                                                </a>


                                            @if(Auth::user()->id === $categoryGroup->user_id || Auth::user()->hasRole('admin'))

                                                <a href="{!! url('categoryGroups/'.$categoryGroup->category_group_id.'/edit') !!}" id='{!! $categoryGroup->category_group_id !!}_edit_button' data-test='{!! $categoryGroup->category_group_id !!}_edit_button' class='btn btn-success edit-button'><i class='fa fa-edit'></i></a>

                                                @endif

                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class='fa fa-download'></i>
                                                    <span class="fa fa-caret-down"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{!! url('/categories/'.$categoryGroup->category_group_id.'/download/csv') !!}" type='link' id='{!! $categoryGroup->category_group_id !!}_download_csv_button' data-test='{!! $categoryGroup->category_group_id !!}_download_csv_button'>CVS</a></li>
                                                        <li><a href="{!! url('/categories/'.$categoryGroup->category_group_id.'/download/aiml') !!}" type='link' id='{!! $categoryGroup->category_group_id !!}_download_csv_button' data-test='{!! $categoryGroup->category_group_id !!}_download_csv_button'>AIML</a></li>
                                                    </ul>
                                            </div>


                                    </div>
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
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click','.open-info-button',function(){


                let title = $(this).attr('data-title');
                let description = $(this).attr('data-description');

                //coin field - which should be disabled in this form
                $('div#showInfoModal p#info-body').html(description);
                $('div#showInfoModal #info-title').html(title);

                $('div#showInfoModal').modal('show');
            });


        });
    </script>
@endpush
<div class="modal" id="showInfoModal" tabindex="-1" role="dialog" data-test='show-info-modal'>
    <div class="modal-dialog modal-lg info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="info-title"></h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <p id="info-body"></p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    <button type="button" class="btn btn-secondary" data-test="show-info-modal-close" data-dismiss="modal">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#checkall').change(function () {
            $('.cb-element').prop('checked',this.checked);
        });

        $('.cb-element').change(function () {
            if ($('.cb-element:checked').length == $('.cb-element').length){
                $('#checkall').prop('checked',true);
            }
            else {
                $('#checkall').prop('checked',false);
            }
        });
    </script>
@endpush


