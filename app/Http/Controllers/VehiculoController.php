<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehiculo;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        //realizar una lista por busqueda
        if ($buscar == '') {
            $vehiculos = Vehiculo::orderBy('id', 'desc')->paginate(3);
        } else {
            $vehiculos = Vehiculo::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }

        //paginacion
        return [
            'pagination' => [
                'total'        => $vehiculos->total(),
                'current_page' => $vehiculos->currentPage(),
                'per_page'     => $vehiculos->perPage(),
                'last_page'    => $vehiculos->lastPage(),
                'from'         => $vehiculos->firstItem(),
                'to'           => $vehiculos->lastItem(),
            ],
            'vehiculos' => $vehiculos
        ];
    }

    //obtener las categorias que estan activas
    public function selectVehiculo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $vehiculos = Vehiculo::where('condicion', '=', '1')
            ->select('id', 'placa')->orderBy('placa', 'asc')->get();
        return ['vehiculo' => $vehiculos];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $vehiculos = new Vehiculo();
        $vehiculos->placa = $request->placa;
        $vehiculos->marca = $request->marca;
        $vehiculos->modelo = $request->modelo;
        $vehiculos->npuertas = $request->npuertas;
        $vehiculos->tipovehiculo = $request->tipovehiculo;
        $vehiculos->condicion = '1';
        $vehiculos->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $vehiculos = Vehiculo::findOrFail($request->id);
        $vehiculos->placa = $request->placa;
        $vehiculos->marca = $request->marca;
        $vehiculos->modelo = $request->modelo;
        $vehiculos->npuertas = $request->npuertas;
        $vehiculos->tipovehiculo = $request->tipovehiculo;
        $vehiculos->condicion = '1';
        $vehiculos->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $vehiculos = Vehiculo::findOrFail($request->id);
        $vehiculos->condicion = '0';
        $vehiculos->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $vehiculos = Vehiculo::findOrFail($request->id);
        $vehiculos->condicion = '1';
        $vehiculos->save();
    }
}
