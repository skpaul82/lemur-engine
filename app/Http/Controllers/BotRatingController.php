<?php

namespace App\Http\Controllers;

use App\DataTables\BotRatingDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBotRatingRequest;
use App\Http\Requests\ResetBotRatingRequest;
use App\Http\Requests\UpdateBotRatingRequest;
use App\Models\Bot;
use App\Repositories\BotRatingRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\BotRating;

class BotRatingController extends AppBaseController
{
    /** @var  BotRatingRepository */
    private $botRatingRepository;

    //to help with data testing and form settings
    public $link = 'botRatings';
    public $htmlTag = 'bot-ratings';
    public $title = 'Bot Ratings';
    public $resourceFolder = 'bot_ratings';

    public function __construct(BotRatingRepository $botRatingRepo)
    {
        $this->botRatingRepository = $botRatingRepo;
    }

    /**
     * Display a listing of the BotRating.
     *
     * @param BotRatingDataTable $botRatingDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotRatingDataTable $botRatingDataTable)
    {
        $this->authorize('viewAny', BotRating::class);
        $botRatingDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botRatingDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotRating.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotRating::class);

        return view('bot_ratings.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created BotRating in storage.
     *
     * @param CreateBotRatingRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotRatingRequest $request)
    {
        $this->authorize('create', BotRating::class);

        $input = $request->all();

        $botRating = $this->botRatingRepository->create($input);

        Flash::success('Bot Rating saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botRatings.index'));
        }
    }

    /**
     * Display the specified BotRating.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botRating = $this->botRatingRepository->getBySlug($slug);

        $this->authorize('view', $botRating);

        if (empty($botRating)) {
            Flash::error('Bot Rating not found');

            return redirect(route('botRatings.index'));
        }

        return view('bot_ratings.show')->with(
            ['botRating'=>$botRating, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotWordSpellingGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit()
    {
        return view('bot_ratings.edit')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly updating items is not allowed
     *
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified BotRating from storage.
     *
     * @param string $slug
     *
     * @throws \Exception
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $botRating = $this->botRatingRepository->getBySlug($slug);

        $this->authorize('delete', $botRating);

        if (empty($botRating)) {
            Flash::error('Bot Rating not found');
            return redirect()->back();
        }

        $botRatingId = $botRating->id;

        $this->botRatingRepository->delete($botRatingId);

        Flash::success('Bot Rating deleted successfully.');

        return redirect()->back();
    }


    /**
     * Remove the specified BotRating from storage.
     *
     *
     * @param ResetBotRatingRequest $request
     * @return Response
     * @throws AuthorizationException
     */
    public function reset(ResetBotRatingRequest $request)
    {
        $input = $request->all();

        $bot = Bot::find($input['bot_id']);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect()->back();
        }

        $this->authorize('delete', $bot);

        $this->botRatingRepository->deleteByBot($bot->id);

        Flash::success('All Bot Ratings have been deleted successfully.');

        return redirect()->back();
    }
}
