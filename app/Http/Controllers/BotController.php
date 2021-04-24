<?php

namespace App\Http\Controllers;

use App\Classes\LemurStr;
use App\DataTables\BotDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBotRequest;
use App\Http\Requests\CreateTalkRequest;
use App\Http\Requests\UpdateBotRequest;
use App\Models\BotAvatar;
use App\Models\BotCategoryGroup;
use App\Models\BotKey;
use App\Models\BotProperty;
use App\Models\BotWordSpellingGroup;
use App\Models\CategoryGroup;
use App\Models\Client;
use App\Models\ClientProperty;
use App\Models\Conversation;
use App\Models\ConversationProperty;
use App\Models\Language;
use App\Models\Turn;
use App\Models\Wildcard;
use App\Models\WordSpellingGroup;
use App\Providers\BotStatsServiceProvider;
use App\Repositories\BotRepository;
use App\Services\BotStatsService;
use App\Services\TalkService;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\CountValidatorAbstract;
use mysql_xdevapi\Exception;
use Response;
use App\Models\Bot;

class BotController extends AppBaseController
{
    /** @var  BotRepository */
    private $botRepository;

    //to help with data testing and form settings
    public $link = 'bots';
    public $htmlTag = 'bots';
    public $title = 'Bots';
    public $resourceFolder = 'bots';

    public function __construct(BotRepository $botRepo)
    {
        $this->botRepository = $botRepo;
    }

    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotDataTable $botDataTable)
    {

        $this->authorize('viewAny', Bot::class);
        $botDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function list(BotDataTable $botDataTable)
    {
        $this->authorize('viewAny', Bot::class);
        $botDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Bot.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Bot::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('bots.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList,
            'readonly'=>false]
        );
    }

    /**
     * Store a newly created Bot in storage.
     *
     * @param CreateBotRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotRequest $request)
    {
        $this->authorize('create', Bot::class);

        $input = $request->all();

        try {
            //start the transaction
            DB::beginTransaction();

            $bot = $this->botRepository->create($input);


            $bot = $this->uploadImage($request, $bot);

            Flash::success('Bot saved successfully.');
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made');
            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(url('bots/'.$bot->slug."/edit"));
        }
    }

    /**
     * Display the specified Bot.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('view', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }
        $this->htmlTag = 'bots-readonly';

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['bot'=> $bot, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }



    /**
     * Show the form for editing the specified Bot.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('update', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['bot'=> $bot, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified Bot in storage.
     *
     * @param  string $slug
     * @param UpdateBotRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotRequest $request)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('update', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $input = $request->all();


        try {
            //start the transaction
            DB::beginTransaction();

            $bot = $this->botRepository->update($input, $bot->id);

            $this->uploadImage($request, $bot);

            Flash::success('Bot updated successfully.');
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made');
            return redirect()->back();
        }


        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(url('bots/'.$bot->slug."/edit"));
        }
    }

    /**
     * Remove the specified Bot from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('delete', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect()->back();
        }

        $this->botRepository->delete($bot->id);

        Flash::success('Bot deleted successfully.');

        return redirect()->back();
    }

    /**
 * Display all the properties for this bot in the tab
 *
 * @param  string $slug
 *
 * @return Response
 */
    public function botProperties($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botProperties';
        $htmlTag = 'bot-properties';
        $title = 'Bot Properties';
        $resourceFolder = 'bot_properties';

        //set a list of all the available properties for the ui
        $savedProperties = BotProperty::getFullPropertyList($bot->id);
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['bot'=> $bot,'botProperties'=> $savedProperties, 'link'=>$link,
            'htmlTag'=>$htmlTag,
            'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList]
        );
    }



    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function botCategoryGroups($id)
    {
        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botCategoryGroups';
        $htmlTag = 'bot-category-groups';
        $title = 'Bot Category Groups';
        $resourceFolder = 'bot_category_groups';

        //set a list of all the available category groups for the ui
        $allCategoryGroups = BotCategoryGroup::getAllCategoryGroupsForBot($bot->id);
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['bot'=> $bot,'categoryGroups'=> $allCategoryGroups,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList]
        );
    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @param string $conversationSlug
     * @return Response
     */
    public function conversations($id, $conversationSlug = 'list')
    {


        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'conversations';
        $htmlTag = 'conversations';
        $title = 'Conversations';
        $resourceFolder = 'conversations';

        $fullConversation=null;
        $conversationProperties = null;
        $client = null;


        //get a list of the last 20 conversations and their count...
        $conversations = Conversation::where('bot_id', $bot->id)->latest('updated_at')->take(20)->get();

        //if we have a conversation...
        if ($conversations!==null) {
            if ($conversationSlug!='list') {
                //get the specific conversation for this bot
                $fullConversation = Conversation::where('slug', $conversationSlug)
                    ->where('bot_id', $bot->id)->latest('updated_at')->first();
                //get a list of the last 20 conversations and their count...
                $selectedConversation = Conversation::where('bot_id', $bot->id)->latest('updated_at')->take(20)->get();

                $conversations = $selectedConversation->merge($conversations);
            } else {
                //get all conversations for the bot we are looking at
                $fullConversation = Conversation::where('bot_id', $bot->id)->latest('updated_at')->first();
            }
            if ($fullConversation) {
                //get the properties for this conversation
                $conversationProperties = ConversationProperty::where('conversation_id', $fullConversation->id)
                    ->pluck('value', 'name');
                //get the properties for this conversation
                $client = Client::find($fullConversation->client_id);
            }
        }

        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['botList'=>$botList, 'bot'=> $bot,
            'conversations'=> $conversations,
            'fullConversation'=>$fullConversation,
            'targetConversationSlug' => ($fullConversation->slug??''),
            'client'=>$client,
            'conversationProperties'=> $conversationProperties,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder]
        );
    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @return Response
     */
    public function botPlugins($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botPlugins';
        $htmlTag = 'bot-plugins';
        $title = 'Bot Plugins';
        $resourceFolder = 'bot_plugins';

        //set a list of all the available category groups for the ui
        $allWordSpellingGroups = BotWordSpellingGroup::getAllWordSpellingsGroupsForBot($bot->id);
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        return view('bots.edit_all')->with(
            ['bot'=> $bot,'wordSpellingGroups'=> $allWordSpellingGroups,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Display all the keys for this bot in the tab
     *
     * @param $id
     * @return Response
     */
    public function botKeys($id)
    {

        $bot = $this->botRepository->find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botKeys';
        $htmlTag = 'bot-keys';
        $title = 'Bot Keys';
        $resourceFolder = 'bot_keys';

        //set a list of all keys for this bot
        $botKeys = BotKey::orderBy('name')->where('bot_id', $bot->id)->get();
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');


        return view('bots.edit_all')->with(
            ['bot'=> $bot,'botKeys'=> $botKeys,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @param BotStatsService $botStatsService
     * @return Response
     */
    public function stats($id, BotStatsService $botStatsService)
    {

        $bot = $this->botRepository->find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botStats';
        $htmlTag = 'bot-stats';
        $title = 'Bot Stats';
        $resourceFolder = 'bot_stats';

        //set a list of all keys for this bot
        $botKeys = BotKey::orderBy('name')->where('bot_id', $bot->id)->get();
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');












        return view('bots.edit_all')->with([
            'bot'=> $bot,
                'botKeys'=> $botKeys,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'botList' => $botList,
                'resourceFolder'=>$resourceFolder,
                'botRatingCount'=>$bot->botRatingCount(),
                'botRatingAvg'=>$bot->botRatingAvg(),
                'yearlyConversationStat' =>   $botStatsService->getYearByMonthConversationStats($bot),
                'monthlyConversationStat' =>   $botStatsService->getMonthByDayConversationStats($bot),
                'allTimeConversationStat' =>   $botStatsService->getAllTimeConversationStats($bot),
                'todayConversationStat' =>   $botStatsService->getTodayConversationStats($bot),
                'yearlyTurnStat' =>   $botStatsService->getYearByMonthTurnStats($bot),
                'monthlyTurnStat' =>   $botStatsService->getMonthByDayTurnStats($bot),
                'allTimeTurnStat' =>   $botStatsService->getAllTimeTurnStats($bot),
                'todayTurnStat' =>   $botStatsService->getTodayTurnStats($bot),
                'daysInMonthKey' => $botStatsService->getDaysInMonthKey(),
                'monthsInYearKey' => $botStatsService->getMonthsInYearKey(),

                ]





        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function wigdets($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botWidgets';
        $htmlTag = 'bot-widgets';
        $title = 'Bot Widgets';
        $resourceFolder = 'bot_widgets';


        // get a nice list of word spelling groups
        return view('bots.edit_all')->with(
            ['bot'=> $bot,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'resourceFolder'=>$resourceFolder
            ]
        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function chatForm($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botChat';
        $htmlTag = 'bot-chat';
        $title = 'Bot Chat';
        $resourceFolder = 'bot_chat';

        // get a nice list of word spelling groups
        return view('bots.edit_all')->with(
            ['bot'=> $bot,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'conversation'=>[],
                'resourceFolder'=>$resourceFolder
            ]
        );
    }


    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function chat($id, CreateTalkRequest $request, TalkService $talkService)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botChat';
        $htmlTag = 'bot-chat';
        $title = 'Bot Chat';
        $resourceFolder = 'bot_chat';

        try {
            if (!empty($request->input('message'))) {
                $talkService->checkOwnerAccess($request);


                //return the service and return the response and debug
                $parts = $talkService->run($request->input(), true);
                //get the latest conversations from the service
                $conversation=$talkService->getConversation();

                $res=$parts['response'];
                $debug=$parts['debug'];
                $allResponses=$parts['allResponses'];
            } else {
                $res=[];
                $conversation=[];
                $debug=[];
                $allResponses=[];
            }


            // get a nice list of word spelling groups
            return view('bots.edit_all')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>$res,
                    'conversation'=>$conversation,
                    'debug'=>$debug,
                    'sentences' => $allResponses,
                ]
            );
        } catch (\Exception $e) {
            // get a nice list of word spelling groups
            return view('bots.edit_all')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>['error'=>true,'message'=>$e->getMessage(),'line'=>$e->getLine(),'file'=>basename($e->getFile())],
                    'conversation'=>[],
                    'debug'=>[],
                    'sentences' => [],
                ]
            );
        }


    }



    /**
     * @param $request
     * @param $bot
     */
    public function uploadImage($request, $bot)
    {


        if (!empty($request->file('image'))) {
            //the location we store publicly available images
            $publicAdapter = env('PUBLIC_STORAGE_ADAPTER');

            $path = $request->file('image')->storeAs(
                'images',
                $bot->slug.".".$request->file('image')->extension(),
                $publicAdapter
            );
            $bot->image = $path;
            $bot->save();
            return $bot;
        }

        return $bot;
    }
}
