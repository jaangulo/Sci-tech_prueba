<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // llama a la  plantilla que se llama principal que se encuentra en la carperta view
   // return view('principal');

   //la plantilla que voy a visualizar esta en la carpeta contenido y el archivo contenido
    return view('contenido/contenido');

    
});

Route::get('/vehiculos', 'VehiculoController@index');
Route::post('/vehiculos/registrar', 'VehiculoController@store');
Route::put('/vehiculos/actualizar', 'VehiculoController@update');
Route::put('/vehiculos/desactivar', 'VehiculoController@desactivar');
Route::put('/vehiculos/activar', 'VehiculoController@activar');
Route::get('/vehiculos/selectvehiculo', 'VehiculoController@selectvehiculo');

Route::get('/personas', 'PersonaController@index');
Route::post('/personas/registrar', 'PersonaController@store');
Route::put('/personas/actualizar', 'PersonaController@update');
Route::put('/personas/desactivar', 'PersonaController@desactivar');
Route::put('/personas/activar', 'PersonaController@activar');

