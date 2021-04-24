<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryGroupDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCategoryGroupRequest;
use App\Http\Requests\UpdateCategoryGroupRequest;
use App\Models\Language;
use App\Repositories\CategoryGroupRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\CategoryGroup;

class CategoryGroupController extends AppBaseController
{
    /** @var  CategoryGroupRepository */
    private $categoryGroupRepository;

    //to help with data testing and form settings
    public $link = 'categoryGroups';
    public $htmlTag = 'category-groups';
    public $title = 'Category Groups';
    public $resourceFolder = 'category_groups';

    public function __construct(CategoryGroupRepository $categoryGroupRepo)
    {
        $this->categoryGroupRepository = $categoryGroupRepo;
    }

    /**
     * Display a listing of the CategoryGroup.
     *
     * @param CategoryGroupDataTable $categoryGroupDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(CategoryGroupDataTable $categoryGroupDataTable)
    {
        $this->authorize('viewAny', CategoryGroup::class);
        $categoryGroupDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $categoryGroupDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new CategoryGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', CategoryGroup::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('category_groups.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created CategoryGroup in storage.
     *
     * @param CreateCategoryGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateCategoryGroupRequest $request)
    {
        $this->authorize('create', CategoryGroup::class);
        $input = $request->all();

        $categoryGroup = $this->categoryGroupRepository->create($input);

        Flash::success('Category Group saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categoryGroups.index'));
        }
    }

    /**
     * Display the specified CategoryGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('view', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        return view('category_groups.show')->with(
            ['categoryGroup'=>$categoryGroup, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified CategoryGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('update', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('category_groups.edit')->with(
            ['categoryGroup'=> $categoryGroup,
            'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified CategoryGroup in storage.
     *
     * @param  string $slug
     * @param UpdateCategoryGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateCategoryGroupRequest $request)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('update', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        $input = $request->all();

        $categoryGroup = $this->categoryGroupRepository->update($input, $categoryGroup->id);

        Flash::success('Category Group updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categoryGroups.index'));
        }
    }

    /**
     * Remove the specified CategoryGroup from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('delete', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }


        $this->categoryGroupRepository->delete($categoryGroup->id);

        Flash::success('Category Group deleted successfully.');

        return redirect()->back();
    }
}
