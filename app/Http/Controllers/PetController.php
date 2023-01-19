<?php

namespace App\Http\Controllers;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Http\Resources\PettResource;

class PetController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pet::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'raza' =>'required|min:2|max:120',
            'edad' => 'required|numeric|min:0|max:50',
            'descripcion' => 'required|min:2|max:120',
            'precio' => 'required|numeric|min:0|max:100000'
        ]);
        return Pet::create([
            'raza' => $request->raza,
            'edad' => $request->edad,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response=[];
        $code=500;
        $pet=Pet::find($id);
        if(isset($pet)){        
            $response=$pet;
            $code= 200;
        } else{
            $response=[
                'errors'=>'La mascota no existe',
            ];
            $code= 404; 
        }
        collect($response)->toJson();
        return response($response,$code)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pet=Pet::findOrFail($id);
        $pet->precio=$request->precio;
        $pet->raza=$request->raza;
        $pet->edad=$request->edad;
        $pet->descripcion=$request->descripcion;
        $pet->save();
        return new PettResource($pet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resp=Pet::destroy($id);
        if($resp===1)
        {
            $response=[
            'message'=>'La mascota se elimino correctamente'
            ];
            $code= 200;
        }else{
            $response=[
            'message'=>'La mascota no se encontro'
            ];
            $code= 404;
        }    
        collect($response)->toJson();
        return response($response,$code)->header('Content-Type', 'application/json'); 
    }
}
