<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WorkController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class,"register"]);
Route::post('/login', [AuthController::class,"login"]);

    Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class,"logout"]);

    Route::get('/portfolios', [PortfolioController::class,"index"]);
    Route::post('/portfolios', [PortfolioController::class,"store"]);
    Route::get('/portfolios/{id}', [PortfolioController::class,"show"]);
    Route::put('/portfolios/{id}', [PortfolioController::class,"update"]);
    Route::delete('/portfolios/{id}', [PortfolioController::class,"destroy"]);


    Route::get('/portfolios/{portfolio_id}/sections', [SectionController::class,'index']);
    Route::get('/portfolios/{section_id}', [SectionController::class,"show"]);
    Route::post('/portfolios/{portfolio_id}/sections', [SectionController::class,'store']);

    Route::put('/portfolios/{portfolio_id}/sections/{section_id}', [SectionController::class,'update']);
    Route::delete('/portfolios/{portfolio_id}/sections/{section_id}', [SectionController::class,'destroy']);


    Route::get('/portfolios/{portfolio_id}/contacts', [ContactController::class,'index']);
    Route::get('/portfolios/{section_id}', [ContactController::class,"show"]);
    Route::post('/portfolios/{portfolio_id}/contacts', [ContactController::class,'store']);
    Route::put('/portfolios/{portfolio_id}/contacts/{contact_id}', [ContactController::class,'update']);
    Route::delete('/portfolios/{portfolio_id}/contacts/{contact_id}', [ContactController::class,'destroy']);

    Route::get('/portfolios/{portfolio_id}/works', [WorkController::class,'index']);
    Route::get('/portfolios/{work_id}', [WorkController::class,"show"]);
    Route::post('/portfolios/{portfolio_id}/works', [WorkController::class,'store']);
    Route::put('/portfolios/{portfolio_id}/works/{works_id}', [WorkController::class,'update']);
    Route::delete('/portfolios/{portfolio_id}/works/{works_id}', [WorkController::class,'destroy']);

    });
