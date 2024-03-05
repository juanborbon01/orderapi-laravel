<?php

namespace App\Http\Controllers;

use App\Models\Observation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
    private $rules = [
        'description' => 'required|string|max:50|min:3',        
    ];

    private $traductionAttributes = array(
        'description' => 'descripción',
    );

    /**
     * Display a listing of the resource.
     */
    
     public function applyValidator(Request $request)
     {
         $validator = Validator::make($request->all(), $this->rules);
         $validator->setAttributeNames($this->traductionAttributes);
         $data = [];
         if($validator->fails())
         {
             $data = response()->json([
                 'errors' => $validator->errors(),
                 'data' => $request ->all()
             ],Response::HTTP_BAD_REQUEST);
         }
         return $data;
     }
    public function index()
    {
        $observations = Observation::all();
        return response()->json($observations, Response::HTTP_OK);  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request);
        if(!empty($data))
        {
            return $data;
        }

        $observation = Observation::create($request->all());
        $respone =[
            'message' => 'registro creado exitosamente',
            'causal' => $observation
        ];
        return response()->json($respone,Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Observation $observation)
    {
        return Response()->json($observation, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Observation $observation)
    {
        $data = $this->applyValidator($request);
        if(!empty($data))
        {
            return $data;
        }

        $observation->update($request->all());
        $respone =[
            'message' => 'registro actualizado exitosamente',
            'causal' => $observation
        ];
        return response()->json($respone,Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Observation $observation)
    {
        $observation-> delete();
        $respone =[
            'message' => 'registro eliminado exitosamente',
            'causal' => $observation->id 
        ];
        return response()->json($respone,Response::HTTP_OK);
    }
}
