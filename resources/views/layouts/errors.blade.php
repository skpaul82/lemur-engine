@if(!empty($errors))
    @if($errors->any())
        <ul class="alert alert-danger" style="list-style-type: none" data-test="error-list">
            @foreach($errors->all() as $index => $error)
                <li data-test="error-list-{!! $index !!}">{!! $error !!}</li>
            @endforeach
        </ul>
    @endif
@endif
