<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /*zwraca wszystkie kategorie*/
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'categories' => Category::all()
        ]);
    }

    /*zwraca wszystkie kategorie dla selecta*/
    public function selectAllForSelect()
    {
        return response()->json([
            'status' => 'success',
            'categories' => Category::pluck('name', 'id')
        ]);
    }

    /*Zapisanie kategorie*/
    public function save(Request $request) {
        
        $quiz = Category::create([
            'name' => $request['name'],
        ]);

        return response()->json([
            'status' => 'success',
            'categories' => $quiz
        ]);
    }
}