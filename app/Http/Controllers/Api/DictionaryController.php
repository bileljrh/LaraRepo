<?php

namespace App\Http\Controllers\Api;

use App\Dictionary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Auth;


class DictionaryController extends Controller
{
/*
    public function __construct(){
        $this->middleware('auth');
    }
    */

    public $successStatus = 200;


    public function getDictionary(){
        //$dictionary=Dictionary::all();
        //return $this->
        return response()->json(Dictionary::all(),200);

    }


/*
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'word'=>'required',
            'definition'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validate Error',$validator->errors() );
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;
        $post = Dictionary::create($input);
        return $this->sendResponse($post, 'Post added Successfully!' );

    }
    */

    public function userPosts($id)
    {
    $userID = Auth::id();//Auth::user()->id;
    if ( $userID != $id) {
        return response(['error'=>'you dont have rights'],422);
        //return $this->sendError('you dont have rights' , $errorMessage);
    }
    $posts = Dictionary::where('user_id' , $id)->get();
   // return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved Successfully!' );
   //return response(['message'=>'Posts retrieved Successfully']);  
   return response()->json($posts,201);
 
     }

     //suppression d'un post (dictionnaire)

     public function destroy(Dictionary $dictionary,$id)
    {
       // $errorMessage = [] ;
       $dictionary=Dictionary::find($id);
       
       if(is_null($dictionary)){
        return response()->json(['error','dictionary Not Found'],404);
    }

        if ( $dictionary->user_id != Auth::id()) {
          //  return $this->sendError('you dont have rights' , $errorMessage);
         // return response()->json(['error' => $dictionary], $this-> successStatus);
          return response()->json(['error' => "you dont have rights"], 401);        


        }
        $dictionary->delete();
        //return $this->sendResponse(new PostResource($post), 'Post deleted Successfully!' );
        //return response()->json(['success' => $dictionary], $this-> successStatus);
      //  return response()->json(null,204);

      return response()->json(['data' => "Deleted successfuly"], 200);        

    }


    //ajout d'un dictionnaire 

    public function store(Request $request)
    {
       
        $input = $request->all();
        $validator = Validator::make($input,[
            'word'=>'required|unique:dictionaries',
            'definition'=>'required',
            'slug'=>'required'
        ]);
        if ($validator->fails()) {
           // return $this->sendError('Validate Error',$validator->errors() );
           return response(['error'=>$validator->errors()->all()], 422);      

        }

        
        $userID = Auth::id();
        //$user = Auth::user();
        $input['user_id'] = $userID;
        $dictionary = Dictionary::create($input);
      //  return $this->sendResponse($post, 'Post added Successfully!' );
      return response()->json(['data','Post added Successfully'],200);


    }



    //mise a jour d'un post

    public function update(Request $request,Dictionary $dictionary, $id)
    {

        $dictionary=Dictionary::find($id);

        if(is_null($dictionary)){
            return response()->json(['error','dictionary Not Found'],404);
        }

        $input = $request->all();
        $validator = Validator::make($input,[
            'word'=>'required',
            'definition'=>'required'
        ]);
        if ($validator->fails()) {
           // return $this->sendError('Validation error' , $validator->errors());
           return response()->json(['error' => "please verify your inputs"], 401);        

        }


        if ( $dictionary->user_id != Auth::id()) {
           // return $this->sendError('you dont have rights' , $validator->errors());
           return response()->json(['error' => "you dont have rights"], 401);        

        }
        $dictionary->word = $input['word'];
        $dictionary->definition = $input['definition'];
        $dictionary->update();

        //return $this->sendResponse(new PostResource($post), 'Post updated Successfully!' );
        return response()->json(['message','Post updated Successfully! '],200);


    }


    

}



