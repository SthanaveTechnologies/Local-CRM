<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\crmController;
use App\Http\Controllers\notesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/contacts', [crmController::class, 'index']);
Route::post('/contacts', [crmController::class, 'store']);

Route::get('/colleges', [notesController::class, 'getColleges']);
Route::post('/colleges', [notesController::class, 'storeCollege']);

Route::get('/courses', [notesController::class, 'getCourses']);
Route::post('/courses', [notesController::class, 'storeCourse']);

Route::get('/subject', [notesController::class, 'getSubjectsByCourseId']);
Route::post('/subject', [notesController::class, 'storeSubject']);

Route::get('/units', [notesController::class, 'getUnitsBySubjectId']);
Route::post('/units', [notesController::class, 'storeUnit']);
