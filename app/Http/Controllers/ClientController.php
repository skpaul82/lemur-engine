<?php

namespace App\Http\Controllers;

use App\DataTables\ClientDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Bot;
use App\Repositories\ClientRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\Client;

class ClientController extends AppBaseController
{
    /** @var  ClientRepository */
    private $clientRepository;

    //to help with data testing and form settings
    public $link = 'clients';
    public $htmlTag = 'clients';
    public $title = 'Clients';
    public $resourceFolder = 'clients';

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param ClientDataTable $clientDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ClientDataTable $clientDataTable)
    {
        $this->authorize('viewAny', Client::class);
        $clientDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $clientDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('clients.create')->with(
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
     * Display the specified Client.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('view', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('clients.show')->with(
            ['client'=>$client, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
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
        return view('clients.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Client in storage.
     *
     * @param  string $slug
     * @param UpdateClientRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateClientRequest $request)
    {

        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('update', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $input = $request->all();

        $client = $this->clientRepository->update($input, $client->id);

        Flash::success('Client updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('clients.index'));
        }
    }

    /**
     * Remove the specified Client from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('delete', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($client->id);

        Flash::success('Client deleted successfully.');

        return redirect()->back();
    }
}
