<?php

namespace App\Http\Controllers;

use App\DataTables\ClientCategoryDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateClientCategoryRequest;
use App\Http\Requests\UpdateClientCategoryRequest;
use App\Models\Bot;
use App\Repositories\ClientCategoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\ClientCategory;

class ClientCategoryController extends AppBaseController
{
    /** @var  ClientCategoryRepository */
    private $clientCategoryRepository;

    //to help with data testing and form settings
    public $link = 'clientCategories';
    public $htmlTag = 'client-categories';
    public $title = 'Client Categories';
    public $resourceFolder = 'client_categories';

    public function __construct(ClientCategoryRepository $clientCategoryRepo)
    {
        $this->clientCategoryRepository = $clientCategoryRepo;
    }

    /**
     * Display a listing of the ClientCategory.
     *
     * @param ClientCategoryDataTable $clientCategoryDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ClientCategoryDataTable $clientCategoryDataTable)
    {
        $this->authorize('viewAny', ClientCategory::class);
        $clientCategoryDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $clientCategoryDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new ClientCategory.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('client_categories.create')->with(
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
     * Display the specified ClientCategory.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $clientCategory = $this->clientCategoryRepository->getBySlug($slug);

        $this->authorize('view', $clientCategory);

        if (empty($clientCategory)) {
            Flash::error('Client Category not found');

            return redirect(route('clientCategories.index'));
        }

        return view('client_categories.show')->with(
            ['clientCategory'=>$clientCategory, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified ClientCategory.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $clientCategory = $this->clientCategoryRepository->getBySlug($slug);

        $this->authorize('update', $clientCategory);

        if (empty($clientCategory)) {
            Flash::error('Client Category not found');

            return redirect(route('clientCategories.index'));
        }

        $botList = Bot::orderBy('name')->pluck('name', 'slug');

        return view('client_categories.edit')->with(
            ['clientCategory'=> $clientCategory,
            'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Update the specified ClientCategory in storage.
     *
     * @param  string $slug
     * @param UpdateClientCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateClientCategoryRequest $request)
    {
        $clientCategory = $this->clientCategoryRepository->getBySlug($slug);

        $this->authorize('update', $clientCategory);

        if (empty($clientCategory)) {
            Flash::error('Client Category not found');

            return redirect(route('clientCategories.index'));
        }

        $input = $request->all();

        $clientCategory = $this->clientCategoryRepository->update($input, $clientCategory->id);

        Flash::success('Client Category updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('clientCategories.index'));
        }
    }

    /**
     * Remove the specified ClientCategory from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $clientCategory = $this->clientCategoryRepository->getBySlug($slug);

        $this->authorize('delete', $clientCategory);

        if (empty($clientCategory)) {
            Flash::error('Client Category not found');

            return redirect(route('clientCategories.index'));
        }

        $this->clientCategoryRepository->delete($clientCategory->id);

        Flash::success('Client Category deleted successfully.');

        return redirect()->back();
    }
}
