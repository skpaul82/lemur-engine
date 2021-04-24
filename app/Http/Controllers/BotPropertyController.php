<?php

namespace App\Http\Controllers;

use App\DataTables\BotPropertyDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBotPropertyRequest;
use App\Http\Requests\UpdateBotPropertyRequest;
use App\Http\Requests\UploadBotPropertyFileRequest;
use App\Models\Bot;
use App\Repositories\BotPropertyRepository;
use App\Services\BotPropertyUploadService;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;
use App\Models\BotProperty;

class BotPropertyController extends AppBaseController
{
    /** @var  BotPropertyRepository */
    private $botPropertyRepository;

    //to help with data testing and form settings
    public $link = 'botProperties';
    public $htmlTag = 'bot-properties';
    public $title = 'Bot Properties';
    public $resourceFolder = 'bot_properties';

    public function __construct(BotPropertyRepository $botPropertyRepo)
    {
        $this->botPropertyRepository = $botPropertyRepo;
    }

    /**
     * Display a listing of the BotProperty.
     *
     * @param BotPropertyDataTable $botPropertyDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotPropertyDataTable $botPropertyDataTable)
    {
        $this->authorize('viewAny', BotProperty::class);
        $botPropertyDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botPropertyDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotProperty.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotProperty::class);

        $botList = Bot::orderBy('name')->pluck('name', 'slug');

        return view('bot_properties.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Store a newly created BotProperty in storage.
     *
     * @param CreateBotPropertyRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotPropertyRequest $request)
    {
        $this->authorize('create', BotProperty::class);
        $input = $request->all();

        if (!empty($input['bulk'])) {
            $this->botPropertyRepository->bulkCreate($input);
            Flash::success('Bot Properties updated and saved successfully.');
        } else {
            $botProperty = $this->botPropertyRepository->create($input);
            Flash::success('Bot Property saved successfully.');
        }


        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botProperties.index'));
        }
    }

    /**
     * Display the specified BotProperty.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botProperty = $this->botPropertyRepository->getBySlug($slug);

        $this->authorize('view', $botProperty);

        if (empty($botProperty)) {
            Flash::error('Bot Property not found');

            return redirect(route('botProperties.index'));
        }

        return view('bot_properties.show')->with(
            ['botProperty'=>$botProperty, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotProperty.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $botProperty = $this->botPropertyRepository->getBySlug($slug);

        $this->authorize('update', $botProperty);

        if (empty($botProperty)) {
            Flash::error('Bot Property not found');

            return redirect(route('botProperties.index'));
        }

        $botList = Bot::orderBy('name')->pluck('name', 'slug');

        return view('bot_properties.edit')->with(
            ['botProperty'=> $botProperty, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Update the specified BotProperty in storage.
     *
     * @param  string $slug
     * @param UpdateBotPropertyRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotPropertyRequest $request)
    {
        $botProperty = $this->botPropertyRepository->getBySlug($slug);


        $this->authorize('update', $botProperty);

        if (empty($botProperty)) {
            Flash::error('Bot Property not found');

            return redirect(route('botProperties.index'));
        }

        $input = $request->all();

        $botProperty = $this->botPropertyRepository->update($input, $botProperty->id);

        Flash::success('Bot Property updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botProperties.index'));
        }
    }

    /**
     * Remove the specified BotProperty from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $botProperty = $this->botPropertyRepository->getBySlug($slug);

        $this->authorize('delete', $botProperty);

        if (empty($botProperty)) {
            Flash::error('Bot Property not found');

            return redirect(route('botProperties.index'));
        }

        $this->botPropertyRepository->delete($botProperty->id);

        Flash::success('Bot Property deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a BotProperties file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', BotProperty::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadBotPropertyFileRequest $request
     * @param BotPropertyUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadBotPropertyFileRequest $request, BotPropertyUploadService $uploadService)
    {
        $this->authorize('create', BotProperty::class);


        try {
            //start the transaction
            DB::beginTransaction();

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertBotPropertyCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertBotPropertyCount .' Bot Properties Values saved successfully.');

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
            return redirect('botPropertiesUpload');
        }
    }

    public function botPropertiesDownload($id)
    {

        $bot = Bot::find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.$bot->slug.'-properties'.'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];


        $recommendedProperties = config('lemur.required_bot_properties');
        $recommendedProperties = array_flip($recommendedProperties);
        $savedProperties = BotProperty::selectRaw('? as botId, name as Name, value as Value', [$bot->slug])
            ->where('bot_id', $id)->get()->toArray();

        //remove any of the recommended properties that are already set...
        foreach ($savedProperties as $index => $property) {
            $savedProperties[$index]['botId']=$bot->slug;

            $pName = $property['Name'];

            if (isset($recommendedProperties[$pName])) {
                unset($recommendedProperties[$pName]);
            }
        }




        $recommendedProperties = array_flip($recommendedProperties);

        foreach ($recommendedProperties as $property) {
            $defaultValue['botId']=$bot->slug;
            $defaultValue['Name']=$property;
            $defaultValue['Value']='';

            $savedProperties[]=$defaultValue;
        }

        usort($savedProperties, function ($a, $b) {
            return $a['Name'] <=> $b['Name'];
        });


        # add headers for each column in the CSV download
        array_unshift($savedProperties, array_keys($savedProperties[0]));

        $callback = function () use ($savedProperties) {
            $FH = fopen('php://output', 'w');
            foreach ($savedProperties as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }
}
