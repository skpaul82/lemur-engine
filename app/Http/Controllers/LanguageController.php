<?php

namespace App\Http\Controllers;

use App\DataTables\LanguageDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Repositories\LanguageRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Language;

class LanguageController extends AppBaseController
{
    /** @var  LanguageRepository */
    private $languageRepository;

    //to help with data testing and form settings
    public $link = 'languages';
    public $htmlTag = 'languages';
    public $title = 'Languages';
    public $resourceFolder = 'languages';

    public function __construct(LanguageRepository $languageRepo)
    {
        $this->languageRepository = $languageRepo;
    }

    /**
     * Display a listing of the Language.
     *
     * @param LanguageDataTable $languageDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(LanguageDataTable $languageDataTable)
    {
        $this->authorize('viewAny', Language::class);
        $languageDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $languageDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Language.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Language::class);

        return view('languages.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Language in storage.
     *
     * @param CreateLanguageRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateLanguageRequest $request)
    {
        $this->authorize('create', Language::class);

        $input = $request->all();

        $language = $this->languageRepository->create($input);

        Flash::success('Language saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('languages.index'));
        }
    }

    /**
     * Display the specified Language.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('view', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        return view('languages.show')->with(
            ['language'=>$language, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Language.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('update', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        return view('languages.edit')->with(
            ['language'=> $language, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Language in storage.
     *
     * @param string $slug
     * @param UpdateLanguageRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateLanguageRequest $request)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('update', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        $input = $request->all();

        $language = $this->languageRepository->update($input, $language->id);

        Flash::success('Language updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('languages.index'));
        }
    }

    /**
     * Remove the specified Language from storage.
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
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('delete', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        $this->languageRepository->delete($language->id);

        Flash::success('Language deleted successfully.');

        return redirect()->back();
    }
}
