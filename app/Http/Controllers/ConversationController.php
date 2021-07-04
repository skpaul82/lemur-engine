<?php

namespace App\Http\Controllers;

use App\DataTables\ConversationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateConversationRequest;
use App\Http\Requests\UpdateConversationRequest;
use App\Http\Requests\UpdateConversationSlugRequest;
use App\Http\Requests\UpdateLanguageSlugRequest;
use App\Models\Bot;
use App\Models\Language;
use App\Repositories\ConversationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Conversation;

class ConversationController extends AppBaseController
{
    /** @var  ConversationRepository */
    private $conversationRepository;

    //to help with data testing and form settings
    public $link = 'conversations';
    public $htmlTag = 'conversations';
    public $title = 'Conversations';
    public $resourceFolder = 'conversations';

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepository = $conversationRepo;
    }

    /**
     * Display a listing of the Conversation.
     *
     * @param ConversationDataTable $conversationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ConversationDataTable $conversationDataTable)
    {
        $this->authorize('viewAny', Conversation::class);
        $conversationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $conversationDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     * @return Response
     */
    public function create()
    {

        return view($this->resourceFolder.'.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly saving new items is not allowed
     *
     */
    public function store()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Display the specified Conversation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $conversation = $this->conversationRepository->getBySlug($slug);

        $this->authorize('view', $conversation);

        if (empty($conversation)) {
            Flash::error('Conversation not found');

            return redirect(route('conversations.index'));
        }

        return view('conversations.show')->with(
            ['conversation'=>$conversation, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     * @return Response
     */
    public function edit()
    {
        //show error message page
        return view('conversations.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Conversation in storage.
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified Conversation from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $conversation = $this->conversationRepository->getBySlug($slug);

        $this->authorize('delete', $conversation);

        if (empty($conversation)) {
            Flash::error('Conversation not found');

            return redirect(route('conversations.index'));
        }

        $this->conversationRepository->delete($conversation->id);

        Flash::success('Conversation deleted successfully.');

        return redirect()->back();
    }

    /**
     * Update the specified Conversation in storage.
     *
     * @param  Conversation $conversation
     * @param UpdateConversationSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate($conversation, UpdateConversationSlugRequest $request)
    {

        $this->authorize('update', $conversation);

        $inputAll=$request->all();

        $conversationCheck = $this->conversationRepository->getBySlug($inputAll['original_slug']);

        if (empty($conversation)||empty($conversationCheck)) {
            Flash::error('Conversation not found');
            return redirect(route('conversations.index'));
        }

        if($conversationCheck->id != $conversation->id){
            Flash::error('Conversation slug mismatch');
            return redirect(route('conversations.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $conversation = $this->conversationRepository->update($input, $conversation->id);

        Flash::success('Conversation slug updated successfully.');

        return redirect(route('conversations.index'));



    }
}
