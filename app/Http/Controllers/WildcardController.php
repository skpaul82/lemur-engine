<?php

namespace App\Http\Controllers;

use App\DataTables\WildcardDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateWildcardRequest;
use App\Http\Requests\UpdateWildcardRequest;
use App\Repositories\WildcardRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Wildcard;

class WildcardController extends AppBaseController
{
    /** @var  WildcardRepository */
    private $wildcardRepository;

    //to help with data testing and form settings
    public $link = 'wildcards';
    public $htmlTag = 'wildcards';
    public $title = 'Wildcards';
    public $resourceFolder = 'wildcards';

    public function __construct(WildcardRepository $wildcardRepo)
    {
        $this->wildcardRepository = $wildcardRepo;
    }

    /**
     * Display a listing of the Wildcard.
     *
     * @param WildcardDataTable $wildcardDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WildcardDataTable $wildcardDataTable)
    {
        $this->authorize('viewAny', Wildcard::class);
        $wildcardDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wildcardDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the default message for
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('wildcards.create')->with(
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
     * Display the specified Wildcard.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wildcard = $this->wildcardRepository->getBySlug($slug);

        $this->authorize('view', $wildcard);

        if (empty($wildcard)) {
            Flash::error('Wildcard not found');

            return redirect(route('wildcards.index'));
        }

        return view('wildcards.show')->with(
            ['wildcard'=>$wildcard, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Wildcard.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit()
    {
        return view('wildcards.edit')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
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
        //we do not updating items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified Wildcard from storage.
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
        $wildcard = $this->wildcardRepository->getBySlug($slug);

        $this->authorize('delete', $wildcard);

        if (empty($wildcard)) {
            Flash::error('Wildcard not found');

            return redirect(route('wildcards.index'));
        }

        $wildcardId = $wildcard->id;

        $this->wildcardRepository->delete($wildcardId);

        Flash::success('Wildcard deleted successfully.');

        return redirect()->back();
    }
}
