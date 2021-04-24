@section('css')
    @include('layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%', 'data-test'=>$htmlTag.'-datatable', 'class' => 'table table-striped table-bordered hover'],true) !!}

@push('scripts')
{{ Html::script('js/tables.js') }}
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush

@include('layouts.datatable_delete_modal')
