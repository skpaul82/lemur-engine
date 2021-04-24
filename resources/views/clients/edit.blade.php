@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Clients
        </h1>
    </section>
    <div class="content">
        @include('layouts.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot edit clients turns directly, they are automatically created when the bot talks to a user.<br/>You can ban a user by clicking the <i class="fa fa-ban"></i> icon in the client table.</div>
            </div>
        </div>


    </div>
@endsection
