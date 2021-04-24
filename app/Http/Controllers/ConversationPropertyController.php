<?php

namespace App\Http\Controllers;

use App\DataTables\ConversationPropertyDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateConversationPropertyRequest;
use App\Http\Requests\UpdateConversationPropertyRequest;
use App\Repositories\ConversationPropertyRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\ConversationProperty;

class ConversationPropertyController extends AppBaseController
{
    /** @var  ConversationPropertyRepository */
    private $conversationPropertyRepository;

    //to help with data testing and form settings
    public $link = 'conversationProperties';
    public $htmlTag = 'conversation-properties';
    public $title = 'Conversation Properties';
    public $resourceFolder = 'conversation_properties';

    public function __construct(ConversationPropertyRepository $conversationPropertyRepo)
    {
        $this->conversationPropertyRepository = $conversationPropertyRepo;
    }

    /**
     * Display a listing of the ConversationProperty.
     *
     * @param ConversationPropertyDataTable $conversationPropertyDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ConversationPropertyDataTable $conversationPropertyDataTable)
    {
        $this->authorize('viewAny', ConversationProperty::class);
        $conversationPropertyDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $conversationPropertyDataTable->render(
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
     * Display the specified ConversationProperty.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $conversationProperty = $this->conversationPropertyRepository->getBySlug($slug);

        $this->authorize('view', $conversationProperty);

        if (empty($conversationProperty)) {
            Flash::error('Conversation Property not found');

            return redirect(route('conversationProperties.index'));
        }

        return view('conversation_properties.show')->with(
            ['conversationProperty'=>$conversationProperty, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     */
    public function edit()
    {
        //show error message page
        return view('conversation_properties.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly updating new items is not allowed
     *
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified ConversationProperty from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $conversationProperty = $this->conversationPropertyRepository->getBySlug($slug);

        $this->authorize('delete', $conversationProperty);

        if (empty($conversationProperty)) {
            Flash::error('Conversation Property not found');

            return redirect(route('conversationProperties.index'));
        }

        $this->conversationPropertyRepository->delete($conversationProperty->id);

        Flash::success('Conversation Property deleted successfully.');

        return redirect()->back();
    }
}
