<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Exceptions\AimlUploadException;
use App\Http\Requests;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UploadCategoryFileRequest;
use App\Models\Bot;
use App\Models\BotProperty;
use App\Models\CategoryGroup;
use App\Models\ClientCategory;
use App\Models\EmptyResponse;
use App\Models\Turn;
use App\Repositories\CategoryRepository;
use App\Services\AimlUploadService;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;
use App\Models\Category;
use SimpleXMLElement;

class CategoryController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    //to help with data testing and form settings
    public $link = 'categories';
    public $htmlTag = 'categories';
    public $title = 'Categories';
    public $resourceFolder = 'categories';

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        $this->authorize('viewAny', Category::class);
        $categoryDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $categoryDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        return view('categories.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList]
        );
    }


    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromTurn($id)
    {
        $this->authorize('create', Category::class);

        $turn = Turn::find($id);
        $previousTurn = Turn::PreviousTurn($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }


        return view('categories.create_from_turn')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
                'categoryGroupList'=>$categoryGroupList,
                'redirect_url'=>url()->previous(),
                'turn'=>$turn,
                'previousTurn'=>$previousTurn]
        );
    }
    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromEmptyResponse($id)
    {
        $this->authorize('create', Category::class);

        $emptyResponse = EmptyResponse::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('categories.create_from_empty_response')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList,
            'emptyResponse'=>$emptyResponse]
        );
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromClientCategory($id)
    {
        $this->authorize('create', Category::class);

        $clientCategory = ClientCategory::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('categories.create_from_client_category')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList,
            'clientCategory'=>$clientCategory]
        );
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromCopy($id)
    {
        $this->authorize('create', Category::class);

        $category = Category::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('categories.create_from_copy')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
                'categoryGroupList'=>$categoryGroupList,
                'category'=>$category]
        );
    }
    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        try {
            //start the transaction
            DB::beginTransaction();

            $input = $request->all();

            $category = $this->categoryRepository->create($input);

            Flash::success('Category saved successfully.');

            //we may need to do some extra deleting
            if (isset($input['delete_original'])) {
                if (isset($input['empty_response_id'])) {
                    EmptyResponse::findAndDeleteFromInput($input);
                } elseif (isset($input['client_category_id'])) {
                    ClientCategory::findAndDeleteFromInput($input);
                }
            }

            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made: '.$e->getMessage());
            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categories.index'));
        }
    }

    /**
     * Display the specified Category.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('view', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with(
            ['category'=>$category, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('update', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        return view('categories.edit')->with(
            ['category'=> $category, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList]
        );
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  string $slug
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('update', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $input = $request->all();

        $category = $this->categoryRepository->update($input, $category->id);

        Flash::success('Category updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categories.index'));
        }
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('delete', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }


        $this->categoryRepository->delete($category->id);

        Flash::success('Category deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a Category file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', Category::class);
        return view('categories.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadCategoryFileRequest $request
     * @param AimlUploadService $aimlUploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadCategoryFileRequest $request, AimlUploadService $aimlUploadService)
    {
        $this->authorize('create', Category::class);

        try {
            //start the transaction
            DB::beginTransaction();

            $file = $request->file('aiml_file');
            $input = $request->input();
            $insertCategoryCount = $aimlUploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertCategoryCount . ' Categories saved successfully.');

            // Commit the transaction
            DB::commit();
        } catch (AimlUploadException $e) {
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made - '.$e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            Flash::error('An error occurred - no changes have been made');
            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect('categoriesUpload');
        }
    }

    public function downloadCsv($categoryGroupSlug)
    {


        $categoryGroup = CategoryGroup::where('slug', $categoryGroupSlug)->first();
        $categoriesArr = Category::selectRaw(
            '? as Filename, pattern as Pattern, topic as Topic, that as That, 
                                                template as Template, status as Status',
            [$categoryGroupSlug]
        )->where('category_group_id', $categoryGroup->id)->orderBy('id')->get()->toArray();

        if (empty($categoryGroup) || count($categoriesArr)<=0) {
            Flash::error('Categories not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.$categoryGroupSlug.'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];




        # add headers for each column in the CSV download
        array_unshift($categoriesArr, array_keys($categoriesArr[0]));

        $callback = function () use ($categoriesArr) {
            $FH = fopen('php://output', 'w');
            foreach ($categoriesArr as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function downloadAiml($categoryGroupSlug)
    {


        $categoryGroup = CategoryGroup::where('slug', $categoryGroupSlug)->first();
        $categoriesArr = Category::selectRaw('pattern, topic, that, template, status')
            ->where('category_group_id', $categoryGroup->id)->orderBy('id')->get()->toArray();

        if (empty($categoryGroup) || count($categoriesArr)<=0) {
            Flash::error('Categories not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/xml'
            ,   'Content-Disposition' => 'attachment; filename='.$categoryGroupSlug.'.aiml'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];


        // creating object of SimpleXMLElement
        $xml = new SimpleXMLElement('<?xml version="1.0"?><aiml></aiml>');





        foreach ($categoriesArr as $index => $category) {

            if ($category['topic']!='') {
                $subnode = $xml->addChild('topic');
                $subnode->addAttribute('name', $category['topic']);
                $subnode = $subnode->addChild('category');
            } else {
                $subnode = $xml->addChild('category');
            }
            $subnode->addChild('pattern', $category['pattern']);
            if ($category['that']!='') {
                $subnode->addChild('that', $category['that']);
            }
            $subnode->addChild('template', $category['template']);
        }

        $stringXml = $xml->asXML();

        //lets try and make it look slightly readable
        $stringXml = str_replace("&gt;", ">", $stringXml);
        $stringXml = str_replace("&lt;", "<", $stringXml);

        $stringXml = str_replace("<aiml", "\n<aiml", $stringXml);
        $stringXml = str_replace("</aiml", "\n</aiml", $stringXml);
        $stringXml = str_replace("<topic", "\n\t<topic", $stringXml);
        $stringXml = str_replace("<category", "\n\t\t<category", $stringXml);
        $stringXml = str_replace("<pattern", "\n\t\t\t<pattern", $stringXml);
        $stringXml = str_replace("<template", "\n\t\t\t<template", $stringXml);
        $stringXml = str_replace("</category", "\n\t\t</category", $stringXml);
        $stringXml = str_replace("</topic", "\n\t</topic", $stringXml);


        $aiml = <<<XML
$stringXml
XML;
        $aiml = simplexml_load_string($aiml);

        return response::make($aiml->asXML(), 200, $headers);
    }




    // function defination to convert array to xml
    public function arrayToXml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
                }
                $subnode = $xml_data->addChild($key);

                $this->arrayToXml($value, $subnode);
            } else {
                if (strpos($value, '<')!==false) {
                    $value = <<<XML
$key
XML;
                }

                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
