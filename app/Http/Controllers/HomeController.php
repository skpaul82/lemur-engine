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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $bots = Bot::where('user_id', Auth::user()->id)->orWhere('is_master', 1)
            ->orderBy('is_master')->orderBy('name')->get();

        $backGroundColorArr = ['bg-aqua', 'bg-green' ,'bg-yellow', 'bg-red'];

        return view('home')->with(
            [
            'bots'=>$bots,
            'backGroundColorArr'=>$backGroundColorArr]
        );
    }
}
