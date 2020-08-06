<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $categories = $this->user->categories()->get(['name'])->toArray();

        return $categories;
    }
    public function show($id)
    {
        $category = $this->user->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category does not exist.'
            ], 400);
        }

        return $category;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category = new Category();
        $category->title = $request->title;
        $category->description = $request->description;

        if ($this->user->categories()->save($category))
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be added.'
            ], 500);
    }
    public function update(Request $request, $id)
    {
        $category = $this->user->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category does not exist.'
            ], 400);
        }

        $updated = $category->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be updated.'
            ], 500);
        }
    }
    public function destroy($id)
    {
        $category = $this->user->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category does not exist.'
            ], 400);
        }

        if ($category->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be deleted.'
            ], 500);
        }
    }
}
