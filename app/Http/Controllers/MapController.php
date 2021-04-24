<?php

namespace App\Http\Controllers;

use App\DataTables\MapDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMapRequest;
use App\Http\Requests\UpdateMapRequest;
use App\Repositories\MapRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Map;

class MapController extends AppBaseController
{
    /** @var  MapRepository */
    private $mapRepository;

    //to help with data testing and form settings
    public $link = 'maps';
    public $htmlTag = 'maps';
    public $title = 'Maps';
    public $resourceFolder = 'maps';

    public function __construct(MapRepository $mapRepo)
    {
        $this->mapRepository = $mapRepo;
    }

    /**
     * Display a listing of the Map.
     *
     * @param MapDataTable $mapDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(MapDataTable $mapDataTable)
    {
        $this->authorize('viewAny', Map::class);
        $mapDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $mapDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Map.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Map::class);
        return view('maps.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Map in storage.
     *
     * @param CreateMapRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateMapRequest $request)
    {
        $this->authorize('create', Map::class);
        $input = $request->all();

        $map = $this->mapRepository->create($input);

        Flash::success('Map saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('maps.index'));
        }
    }

    /**
     * Display the specified Map.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('view', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        return view('maps.show')->with(
            ['map'=>$map, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Map.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('update', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        return view('maps.edit')->with(
            ['map'=> $map, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Map in storage.
     *
     * @param  string $slug
     * @param UpdateMapRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateMapRequest $request)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('update', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        $input = $request->all();

        $map = $this->mapRepository->update($input, $map->id);

        Flash::success('Map updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('maps.index'));
        }
    }

    /**
     * Remove the specified Map from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('delete', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        $this->mapRepository->delete($map->id);

        Flash::success('Map deleted successfully.');

        return redirect()->back();
    }
}
