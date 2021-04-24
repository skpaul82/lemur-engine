@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Chat
            <small>with {!! $chatbot->name !!}</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">

        <form action="/chat" method="POST" id="chat-form">

        <div class="row">

            <div class="col-md-4">
                <!-- DIRECT CHAT PRIMARY -->
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title  pull-left  col-md-8">Direct Chat</h3>

                        <div class="box-tools pull-right col-md-4">
                            <!-- Platform Id Field -->
                            <div class="form-group select2-sm" data-test='select-bot-div'>
                                <div class="form-group">
                                    {!! Form::select('bot', $botList, $chatbot->slug, ['id'=>'select-bot-id','data-test'=>'select-bot-id','class' => 'form-control select2 single', 'placeholder'=>"Select"] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages" id="direct-chat-messages">
                            <!-- Message. Default to the left -->
                        @if((($conversation != null) && count($conversation->turns->take(10))>0))

                            @php $chatbotImage = $chatbot->imageUrl; @endphp

                            @foreach($conversation->conversationHumanLogs as $index => $item)

                                <!-- Message. Default to the left -->
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">{!! $conversation->client->slug !!}</span>
                                            <span class="direct-chat-timestamp pull-right">{!! $item->created_at !!}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="direct-chat-img" src="{!! Avatar::create($conversation->client->slug)->toBase64() !!}" alt="Message User Image"><!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {!! $item->input !!}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->

                                    <!-- Message to the right -->
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-right">{!! $chatbot->name !!}</span>
                                            <span class="direct-chat-timestamp pull-left">{!! $item->created_at !!}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="img-circle direct-chat-img" src="{!! $chatbotImage !!}" alt="Message Bot Image"><!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {!! $item->output !!}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>



                            @endforeach

                        @endif
                            <!-- /.direct-chat-msg -->
                        </div>
                        <!--/.direct-chat-messages-->
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        @csrf
                            {{ Form::hidden('client', MD5(Auth::user()->id)) }}
                            <div class="input-group">
                                <input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-flat">Send</button>
                                </span>
                            </div>
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->

            <div class="col-md-8">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">

                    <div class="box-body">

                        @if(Auth::user()->id === $chatbot->user_id || Auth::user()->hasRole('admin'))



                        <div class="nav-tabs-custom">
                            <!-- response tabs --->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#state" data-toggle="tab">State</a></li>
                                <li><a href="#debug" data-toggle="tab">Debug</a></li>
                                <li><a href="#journey" data-toggle="tab">Journey</a></li>
                                <li><a href="#final" data-toggle="tab">Final</a></li>
                                @if(Auth::user()->hasRole('admin'))
                                <li><a href="#admin" data-toggle="tab">Admin</a></li>
                                @endif
                                <li><a href="#sentences" data-toggle="tab">Sentences</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="state">

                                    @if(!empty($response['state']))
                                        <pre><code>{{ json_encode($response['state'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">Start talking and detailed debug information will appear here</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="debug">
                                    @if(!empty($response['debug']))
                                        <pre><code>{{ json_encode($response['debug'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No debug information</div>
                                    @endif

                                </div>

                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="journey">
                                    @if(!empty($response['journey']))
                                        <pre><code>{{ json_encode($response['journey'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No journey information</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="final">
                                    @if(!empty($response['final']))
                                        <pre><code>{{ json_encode($response['final'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No final information</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->

                                @if(Auth::user()->hasRole('admin'))
                                    <div class="tab-pane" id="admin">
                                        @if(!empty($response['admin']))
                                            <pre><code>{{ json_encode($response['admin'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                        @else
                                            <div class="alert alert-info">No admin information</div>
                                        @endif
                                    </div>
                                @endif

                                <div class="tab-pane" id="sentences">
                                    @if(!empty($response['info']))
                                        <div class="alert alert-info">{!! $response['info'] !!}</div>
                                    @endif
                                        @if(!empty($response['sentences']))
                                        <pre><code>{{ json_encode($response['sentences'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @endif
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>


                        @else

                            <div class="alert alert-info">Only the owner of this bot can see detailed debug data.</div>

                        @endif
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>

        </div>
        </form>
    </section>
    <!-- /.content -->

@endsection
@push('scripts')
    {{ Html::script('js/select2.js') }}
<script>

    $( "#select-bot-id" ).change(function(){
        $("form#chat-form").submit();
    });

    var element = document.getElementById('direct-chat-messages');
    element.scrollTop = element.scrollHeight - element.clientHeight;
    $( "#message" ).focus();
</script>
@endpush


