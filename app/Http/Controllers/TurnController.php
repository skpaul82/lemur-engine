<?php

namespace App\Http\Controllers;

use App\DataTables\TurnDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTurnRequest;
use App\Http\Requests\UpdateTurnRequest;
use App\Repositories\TurnRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Turn;

class TurnController extends AppBaseController
{
    /** @var  TurnRepository */
    private $turnRepository;

    //to help with data testing and form settings
    public $link = 'turns';
    public $htmlTag = 'turns';
    public $title = 'Turns';
    public $resourceFolder = 'turns';

    public function __construct(TurnRepository $turnRepo)
    {
        $this->turnRepository = $turnRepo;
    }

    /**
     * Display a listing of the Turn.
     *
     * @param TurnDataTable $turnDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(TurnDataTable $turnDataTable)
    {
        $this->authorize('viewAny', Turn::class);
        $turnDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $turnDataTable->render(
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
     * Display the specified Turn.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $turn = $this->turnRepository->getBySlug($slug);

        $this->authorize('view', $turn);

        if (empty($turn)) {
            Flash::error('Turn not found');

            return redirect(route('turns.index'));
        }

        return view('turns.show')->with(
            ['turn'=>$turn, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
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
        return view('turns.edit')->with(
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
     * Remove the specified Turn from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $turn = $this->turnRepository->getBySlug($slug);

        $this->authorize('delete', $turn);

        if (empty($turn)) {
            Flash::error('Turn not found');

            return redirect(route('turns.index'));
        }

        $this->turnRepository->delete($turn->id);

        Flash::success('Turn deleted successfully.');

        return redirect()->back();
    }
}
