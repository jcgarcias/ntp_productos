<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/productos-4b3e7-firebase-adminsdk-gg41h-4045cb3f85.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $db = $firebase->getDatabase();
        $reference = $db->getReference('productos');
        $snapshot = $reference->getSnapshot();
        $value = $snapshot->getValue();
        return($value);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $validacion =   Validator::make($request->all(),[
            'nombre'    =>  'required',
            'cantidad'  =>  'required|numeric',
            'precio'    =>  'required|numeric',
        ]);
        if ($validacion->fails()) {
            return response(['val' => false, 'errores' => $validacion->errors()->all()],505);
        }else{
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/productos-4b3e7-firebase-adminsdk-gg41h-4045cb3f85.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri('https://productos-4b3e7.firebaseapp.com/')
                ->create();

            $database = $firebase-> getDatabase();
            $newPost = $database
                ->getReference('categorias')
                ->push([
                    'producto'  =>  $request->nombre,
                    'cantidad'  =>  $request->cantidad,
                    'precio'    =>  $request->precio,
                    'fecha'     =>  now(),
                ]);
            return str_replace('https://productos-4b3e7.firebaseio.com/productos/','',$newPost);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
