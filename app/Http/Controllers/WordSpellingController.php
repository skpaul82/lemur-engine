<?php

namespace App\Http\Controllers;

use App\DataTables\WordSpellingDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateWordSpellingRequest;
use App\Http\Requests\UpdateWordSpellingRequest;
use App\Http\Requests\UploadWordSpellingFileRequest;
use App\Models\WordSpelling;
use App\Models\WordSpellingGroup;
use App\Repositories\WordSpellingRepository;
use App\Services\WordSpellingUploadService;
use Flash;
use App\Http\Controllers\AppBaseController;
use \Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Response;

class WordSpellingController extends AppBaseController
{
    /** @var  WordSpellingRepository */
    private $wordSpellingRepository;

    //to help with data testing and form settings
    public $link = 'wordSpellings';
    public $htmlTag = 'word-spellings';
    public $title = 'Word Spellings';
    public $resourceFolder = 'word_spellings';

    public function __construct(WordSpellingRepository $wordSpellingRepo)
    {
        $this->wordSpellingRepository = $wordSpellingRepo;
    }

    /**
     * Display a listing of the WordSpelling.
     *
     * @param WordSpellingDataTable $wordSpellingDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WordSpellingDataTable $wordSpellingDataTable)
    {
        $this->authorize('viewAny', WordSpelling::class);
        $wordSpellingDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wordSpellingDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new WordSpelling.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WordSpelling::class);

        $wordSpellingGroupList = WordSpellingGroup::orderBy('name')->pluck('name', 'slug');

        return view('word_spellings.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'wordSpellingGroupList'=>$wordSpellingGroupList]
        );
    }

    /**
     * Store a newly created WordSpelling in storage.
     *
     * @param CreateWordSpellingRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateWordSpellingRequest $request)
    {
        $this->authorize('create', WordSpelling::class);
        $input = $request->all();

        $wordSpelling = $this->wordSpellingRepository->create($input);

        Flash::success('Word Spelling saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellings.index'));
        }
    }

    /**
     * Display the specified WordSpelling.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('view', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        return view('word_spellings.show')->with(
            ['wordSpelling'=>$wordSpelling, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified WordSpelling.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        
        $this->authorize('update', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $wordSpellingGroupList = WordSpellingGroup::orderBy('name')->pluck('name', 'slug');

        return view('word_spellings.edit')->with(
            ['wordSpelling'=> $wordSpelling, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'wordSpellingGroupList'=>$wordSpellingGroupList]
        );
    }

    /**
     * Update the specified WordSpelling in storage.
     *
     * @param  string $slug
     * @param UpdateWordSpellingRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateWordSpellingRequest $request)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('update', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $input = $request->all();

        $wordSpelling = $this->wordSpellingRepository->update($input, $wordSpelling->id);

        Flash::success('Word Spelling updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellings.index'));
        }
    }

    /**
     * Remove the specified WordSpelling from storage.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('delete', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $this->wordSpellingRepository->delete($wordSpelling->id);

        Flash::success('Word Spelling deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a WordSpellings file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', WordSpelling::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadWordSpellingFileRequest $request
     * @param WordSpellingUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadWordSpellingFileRequest $request, WordSpellingUploadService $uploadService)
    {
        $this->authorize('create', WordSpelling::class);



        try {
            //start the transaction
            DB::beginTransaction();

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertWordSpellingCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertWordSpellingCount . ' Word Spellings saved successfully.');

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
            return redirect('wordSpellingsUpload');
        }
    }
}
