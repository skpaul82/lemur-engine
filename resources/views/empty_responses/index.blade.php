@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Empty Responses</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body table-responsive">
                    @include('empty_responses.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection
