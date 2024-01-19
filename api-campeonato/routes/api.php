<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\Championship\ChampionshipController;
use App\Http\Controllers\Dashboard\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Historry\HistoryController;
use App\Http\Controllers\Dashboard\Simulation\SimulationController;
use App\Http\Controllers\Dashboard\Teams\TeamsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
 
    'prefix' => 'auth'
 
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

//Route::group(['middleware' => 'auth:api'], function () {

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource("championship", ChampionshipController::class);
    Route::resource("teams", TeamsController::class);
    Route::resource("simulation", SimulationController::class);
    Route::resource("history", HistoryController::class);

    Route::resource("my-championship", DashboardController::class);

    
    Route::post('simulation/prize-draw/{id}', [SimulationController::class, 'prizeDraw']);
    Route::post('simulation/generate-semi-finals', [SimulationController::class, 'generateSemifinals']);
    Route::post('simulation/generate-third-place-match', [SimulationController::class, 'generateThirdPlaceMatch']);
    Route::post('simulation/generate-final-match', [SimulationController::class, 'generateFinalMatch']);

    Route::post('simulation/verify-exist-simulation/{id}', [SimulationController::class, 'countResultsForChampionship']);
   // Route::post('simulation/verify-fases-simulation/{id}', [SimulationController::class, 'verifyFases']);
    
   
    Route::get('simulation/list/match/{id}', [SimulationController::class, 'listForMatch']);

    Route::get('simulation/list/result/match/{id}', [SimulationController::class, 'listResultMatch']);

    Route::put('simulation/match/{id}', [SimulationController::class, 'simulateGame']);

    Route::get("history/list/championship/{id}", [HistoryController::class, 'listHistoryForChampionship']);

    Route::post('simulation/disputa-penaltys/{id}', [SimulationController::class, 'disputaPenaltys']);

 
});
