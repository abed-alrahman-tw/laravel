<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Teacher::all();

        return response()->json(['data' => $data]);

    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator($request->all() , [
            'name' => "string|required|min:3|max:25",
            'age' => "integer|required|min:20|max:65",
            'salary'=> "decimal:0|required|min:1200|max:2500",
            'image' => "image|required|mimes:jpg,png|max:2048"  ,
            'school_id' => "required|integer|exists:schools,id"
        ]);

        $saved = false;
        if(!$validator->fails()){
        $teacher = new Teacher();
        $teacher->name = $request->input('name');
        $teacher->age = $request->input('age');
        $teacher->salary = $request->input('salary');
        $teacher->school_id = $request->input('school_id');

        if($request->hasFile('image')){
            $imageName = time() . str_replace(' ','',$teacher->name). "." .$request->file('image')->extension();
            $request->file('image')->storePubliclyAs('teachers_images',$imageName , ['disk','public']);
            $teacher->image = 'teacher_image'. $imageName;
        }


        $saved = $teacher->save();
            
        }
        return response()->json(['status'=>$saved , 'message' => $saved ? "saved successfully" : $validator->getMessageBag()->first() , ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        
        $validator = Validator($request->all() , [
            'name' => "string|required|min:3|max:25",
            'age' => "integer|required|min:20|max:65",
            'salary'=> "decimal:0|required|min:1200|max:2500",
            'image' => "image|required|mimes:jpg,png|max:2048"  ,
            'school_id' => "required|integer|exists:schools,id"
        ]);

        $saved = false;
        if(!$validator->fails()){
        $teacher = Teacher::find($teacher->id);
        $teacher->name = $request->input('name');
        $teacher->age = $request->input('age');
        $teacher->salary = $request->input('salary');
        $teacher->school_id = $request->input('school_id');

        if($request->hasFile('image')){
            Storage::disk('public')->delete($teacher->image);
            $imageName = time() . str_replace(' ','',$teacher->name). "." .$request->file('image')->extension();
            $request->file('image')->storePubliclyAs('teachers_images',$imageName , ['disk','public']);
            $teacher->image = 'teacher_image'. $imageName;
        }

        $saved = $teacher->save();
            
        }
        return response()->json(['status'=>$saved , 'message' => $saved ? "saved successfully" : $validator->getMessageBag()->first() , ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
