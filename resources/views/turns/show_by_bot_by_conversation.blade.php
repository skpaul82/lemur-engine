@if((($fullConversation == null) || count($fullConversation->turns)<=0))

    <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

@else

    @php $botImageUrl  = $bot->imageUrl; @endphp

    <!-- Conversations are loaded here -->
    <div class="direct-chat-messages">
    @foreach($fullConversation->conversationHumanLogs as $index => $item)





            <!-- Message to the right -->
            <div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right">{!! $fullConversation->client->slug !!}</span>
                    <span class="direct-chat-timestamp pull-left">
                        @if(!empty($item->category->slug))
                            <a href="{!! url('/categories/'.$item->category->slug) !!}">View AIML</a>
                        @endif
                        @if(!empty($item->slug))
                            <a href="{!! url('/turns/'.$item->slug) !!}">View Log</a>
                        @endif

                        {!! Carbon\Carbon::parse($item->created_at)->format('d M Y H:m:sA'); !!}
                    </span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="{!! Avatar::create($fullConversation->client->slug)->toBase64() !!}" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">{!! $item->input !!}</div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->

            <!-- Message. Default to the left -->
            <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{!! $bot->name !!}</span>
                    <span class="direct-chat-timestamp pull-right">{!! Carbon\Carbon::parse($item->updated_at)->format('d M Y H:m:sA'); !!}</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="{!! $botImageUrl !!}" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    {!! $item->output !!}
                </div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->





    @endforeach

    </div>
    <div class="clearfix"></div>
    <!--/.direct-chat-messages-->
@endif
