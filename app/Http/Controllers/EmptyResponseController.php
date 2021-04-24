<?php

namespace App\Http\Controllers;

use App\DataTables\EmptyResponseDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateEmptyResponseRequest;
use App\Http\Requests\UpdateEmptyResponseRequest;
use App\Repositories\EmptyResponseRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\EmptyResponse;

class EmptyResponseController extends AppBaseController
{
    /** @var  EmptyResponseRepository */
    private $emptyResponseRepository;

    //to help with data testing and form settings
    public $link = 'emptyResponses';
    public $htmlTag = 'empty-responses';
    public $title = 'Empty Responses';
    public $resourceFolder = 'empty_responses';

    public function __construct(EmptyResponseRepository $emptyResponseRepo)
    {
        $this->emptyResponseRepository = $emptyResponseRepo;
    }

    /**
     * Display a listing of the EmptyResponse.
     *
     * @param EmptyResponseDataTable $emptyResponseDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(EmptyResponseDataTable $emptyResponseDataTable)
    {
        $this->authorize('viewAny', EmptyResponse::class);
        $emptyResponseDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $emptyResponseDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new EmptyResponse.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        return view('empty_responses.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
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
     * Display the specified EmptyResponse.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $emptyResponse = $this->emptyResponseRepository->getBySlug($slug);

        $this->authorize('view', $emptyResponse);

        if (empty($emptyResponse)) {
            Flash::error('Empty Response not found');

            return redirect(route('emptyResponses.index'));
        }

        return view('empty_responses.show')->with(
            ['emptyResponse'=>$emptyResponse, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
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
        return view('empty_responses.edit')->with(
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
     * Remove the specified EmptyResponse from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $emptyResponse = $this->emptyResponseRepository->getBySlug($slug);

        $this->authorize('delete', $emptyResponse);

        if (empty($emptyResponse)) {
            Flash::error('Empty Response not found');

            return redirect(route('emptyResponses.index'));
        }

        $this->emptyResponseRepository->delete($emptyResponse->id);

        Flash::success('Empty Response deleted successfully.');

        return redirect()->back();
    }
}
