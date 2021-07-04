<?php

namespace App\Http\Controllers\API;

use App\Classes\LemurLog;
use App\Classes\LemurStr;
use App\DataTables\BotDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBotRequest;
use App\Http\Requests\CreateTalkRequest;
use App\Http\Requests\UpdateBotRequest;
use App\Http\Resources\BotMetaResource;
use App\Http\Resources\ChatMetaResource;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Turn;
use App\Repositories\BotRepository;
use App\Repositories\ConversationRepository;
use App\Services\TalkService;
use Carbon\Carbon;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Models\Bot;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TalkAPIController extends AppBaseController
{

    /** @var  ConversationRepository */
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepository = $conversationRepo;
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function store(CreateTalkRequest $request, TalkService $talkService)
    {

        dd(2);

        try {
            $talkService->checkAuthAccess($request);

            $talkService->validateRequest($request);

            if (!empty($request->input('message'))) {


                //return the service and return the response only
                $parts = $talkService->run($request->input(), false);
                $res = $parts['response'];
            } else {
                $res = [];
            }
            return $this->sendResponse($res, 'Conversation turn successful');
        } catch (Exception $e) {
            return $this->extractSendError($e);
        }
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @deprecated you should now send meta requests to GET /api/meta/{botId} instead of using POST
     */
    public function old_meta(CreateTalkRequest $request, TalkService $talkService)
    {

        $botSlug = $request->input('bot', false);
        return $this->meta($botSlug,  $request,  $talkService);


    }

    /**
     * Initiate a talk to the bot...
     * @param $botSlug
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function meta($botSlug, CreateTalkRequest $request, TalkService $talkService)
    {

        try {

            $request->merge([
                'bot' => $botSlug,
            ]);

            $talkService->checkAuthAccess($request);

            if ($botSlug) {
                $bot = Bot::where('slug', $botSlug)->firstOrFail();
                return $this->sendResponse(new ChatMetaResource($bot), 'Bot Meta retrieved successfully');
            } else {
                throw new UnprocessableEntityHttpException('Missing botId');
            }
        } catch (Exception $e) {
            return $this->extractSendError($e);
        }
    }
}
