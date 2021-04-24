<?php

namespace App\Http\Controllers;

use App\DataTables\WordTransformationDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateWordTransformationRequest;
use App\Http\Requests\UpdateWordTransformationRequest;
use App\Http\Requests\UploadWordTransformationFileRequest;
use App\Models\Language;
use App\Repositories\WordTransformationRepository;
use App\Services\WordTransformationUploadService;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Response;
use App\Models\WordTransformation;

class WordTransformationController extends AppBaseController
{
    /** @var  WordTransformationRepository */
    private $wordTransformationRepository;

    //to help with data testing and form settings
    public $link = 'wordTransformations';
    public $htmlTag = 'word-transformations';
    public $title = 'Word Transformations';
    public $resourceFolder = 'word_transformations';

    public function __construct(WordTransformationRepository $wordTransformationRepo)
    {
        $this->wordTransformationRepository = $wordTransformationRepo;
    }

    /**
     * Display a listing of the WordTransformation.
     *
     * @param WordTransformationDataTable $wordTransformationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WordTransformationDataTable $wordTransformationDataTable)
    {
        $this->authorize('viewAny', WordTransformation::class);
        $wordTransformationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wordTransformationDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new WordTransformation.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WordTransformation::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('word_transformations.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created WordTransformation in storage.
     *
     * @param CreateWordTransformationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateWordTransformationRequest $request)
    {
        $this->authorize('create', WordTransformation::class);
        $input = $request->all();

        $wordTransformation = $this->wordTransformationRepository->create($input);

        Flash::success('Word Transformation saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordTransformations.index'));
        }
    }

    /**
     * Display the specified WordTransformation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);
        $this->authorize('view', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        return view('word_transformations.show')->with(
            ['wordTransformation'=>$wordTransformation, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified WordTransformation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('update', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('word_transformations.edit')->with(
            ['wordTransformation'=> $wordTransformation, '
        link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified WordTransformation in storage.
     *
     * @param  string $slug
     * @param UpdateWordTransformationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateWordTransformationRequest $request)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('update', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $input = $request->all();

        $wordTransformation = $this->wordTransformationRepository->update($input, $wordTransformation->id);

        Flash::success('Word Transformation updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordTransformations.index'));
        }
    }

    /**
     * Remove the specified WordTransformation from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('delete', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $this->wordTransformationRepository->delete($wordTransformation->id);

        Flash::success('Word Transformation deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a WordTransformations file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', WordTransformation::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadWordTransformationFileRequest $request
     * @param WordTransformationUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadWordTransformationFileRequest $request, WordTransformationUploadService $uploadService)
    {
        $this->authorize('create', WordTransformation::class);

        try {
            //start the transaction
            DB::beginTransaction();

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertWordTransformationCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertWordTransformationCount . ' Word Transformations saved successfully.');

            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made');
            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect('wordTransformationsUpload');
        }
    }
}
