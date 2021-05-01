<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\texteditor; 
use App\Http\Controllers\Controller;

class RicheditorController extends Controller
{
    public function ajoutericch(Request $request){
        $rich = texteditor::create($request->all());
        return response($rich,201);
    
    }

    public function getText(){

        return response()->json(texteditor::all(),200);
   }
}
