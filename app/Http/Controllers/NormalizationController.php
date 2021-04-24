<?php

namespace App\Http\Controllers;

use App\DataTables\NormalizationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNormalizationRequest;
use App\Http\Requests\UpdateNormalizationRequest;
use App\Models\Language;
use App\Repositories\NormalizationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Normalization;

class NormalizationController extends AppBaseController
{
    /** @var  NormalizationRepository */
    private $normalizationRepository;

    //to help with data testing and form settings
    public $link = 'normalizations';
    public $htmlTag = 'normalizations';
    public $title = 'Normalizations';
    public $resourceFolder = 'normalizations';

    public function __construct(NormalizationRepository $normalizationRepo)
    {
        $this->normalizationRepository = $normalizationRepo;
    }

    /**
     * Display a listing of the Normalization.
     *
     * @param NormalizationDataTable $normalizationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(NormalizationDataTable $normalizationDataTable)
    {
        $this->authorize('viewAny', Normalization::class);
        $normalizationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $normalizationDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Normalization.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Normalization::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('normalizations.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created Normalization in storage.
     *
     * @param CreateNormalizationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateNormalizationRequest $request)
    {
        $this->authorize('create', Normalization::class);
        $input = $request->all();

        $normalization = $this->normalizationRepository->create($input);

        Flash::success('Normalization saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('normalizations.index'));
        }
    }

    /**
     * Display the specified Normalization.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('view', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        return view('normalizations.show')->with(
            ['normalization'=>$normalization, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Normalization.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('update', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('normalizations.edit')->with(
            ['normalization'=> $normalization, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified Normalization in storage.
     *
     * @param  string $slug
     * @param UpdateNormalizationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateNormalizationRequest $request)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('update', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $input = $request->all();

        $normalization = $this->normalizationRepository->update($input, $normalization->id);

        Flash::success('Normalization updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('normalizations.index'));
        }
    }

    /**
     * Remove the specified Normalization from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('delete', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $this->normalizationRepository->delete($normalization->id);

        Flash::success('Normalization deleted successfully.');

        return redirect()->back();
    }
}
