<?php

use Carbon\Carbon;
use App\Models\Name;
use App\Models\Adress;
use App\Models\Matche;
use App\Models\Record;
use App\Models\Conflict;
use App\Models\Watchlist;
use App\Models\MatchState;
use App\Models\RecordState;
use App\Models\RecordDetail;
use App\Models\AdditionalInfo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    return view("welcome");
      
});
