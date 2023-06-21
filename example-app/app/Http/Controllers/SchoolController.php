<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = new School();
        // $data = $school->with('teachers')->get();
        // $data = $school->with(['teachers'=> function($query){
        //     $query->where('age', '>' , 25);
        // }])->get();
        // $data = $school->has('teachers','>','2')->with('teachers')->withcount('teachers')->get();
        $data =$school->doesntHave('teachers')->withCount('teachers')->get();
        $data = $school->whereHas('teachers',function($query){
            $query->where('salary' ,'>' , 1500);
        }, '>=' , 3 )->get();
        return response()->json(['status'=> $data!=null , 'message' => $data != null ?
        "sucess" : "error occurred" , 'data' => $data ], status: $data!= null ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST );

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator($request->all() , [
            'name' => "required|string|min:3|max:25",
        ]);
        if(!$validator->fails()){

        $school = new School();
        $school->name = $request->input('name');
       $saved =  $school->save();    
        return response()->json(['status'=> $saved , 'message' => $saved ? "success": "failed" , 'object'=>$school], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status'=> false , 'message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);




    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $data = School::find($school->id);

        return response()->json(['status'=> $data!=null , 'message' => $data != null ?
        "sucess" : "error occurred" , 'school' => $data ], status: $data!= null ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        //

          
        $validator = Validator($request->all() , [
            'name' => "required|string|min:3|max:25",
        ]);
        if(!$validator->fails()){

        $school = School::find($school->id);
        $school->name = $request->input('name');

        $saved =  $school->save();    
        return response()->json(['status'=> $saved , 'message' => $saved ? "success": "failed" , 'object'=>$school], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['status'=> false , 'message' => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        try {
            $data = School::findOrFail($school->id);
            $deleted =  $data->delete();
            return response()->json(['status'=> $deleted , 'message' => $deleted ? "deleted successfuly": "school is not exist" , 'object'=>$data], $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    
        } catch (\Throwable $th) {
            return response()->json(['status' => false , 'message' => "please enter an exist id "],400);
        }
        
    
    }
}
