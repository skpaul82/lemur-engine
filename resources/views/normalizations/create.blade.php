@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Normalization
        </h1>
    </section>
    <div class="content">
        @include('layouts.feedback')
        <div class="box box-primary">
            <div class="box-body add-page">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::open(['route' => 'normalizations.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}

                        @include('normalizations.fields')

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('normalizations.index') }}" class="btn btn-default">Cancel</a>
                        </div>


                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ Html::script('js/validation.js') }}
    {{ Html::script('js/select2.js') }}
@endpush
