<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Lista wszystkich kategorii
     *
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Lista kategorii",
     *     description="Pobiera listę wszystkich kategorii",
     *     @OA\Response(
     *         response=200,
     *         description="Lista kategorii",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Historia"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'categories' => Category::all()
        ]);
    }

    /**
     * Kategorie dla selecta
     *
     * @OA\Get(
     *     path="/categories/select",
     *     tags={"Categories"},
     *     summary="Kategorie dla selecta",
     *     description="Pobiera kategorie w formacie id => name dla selectów",
     *     @OA\Response(
     *         response=200,
     *         description="Kategorie",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="categories", type="object", example={"1":"Historia", "2":"Geografia"})
     *         )
     *     ),
     *     security={{"bearer": {}}}
     * )
     */
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