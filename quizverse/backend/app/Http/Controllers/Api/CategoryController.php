<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

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
}