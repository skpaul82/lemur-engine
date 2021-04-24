<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\ClientCategory;
use App\Models\Conversation;
use App\Models\Turn;
use App\Models\EmptyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('welcome');
    }
}
