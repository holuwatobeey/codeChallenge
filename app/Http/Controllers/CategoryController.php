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
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $categories = $this->user->tasks()->get(['name'])->toArray();

        return $categories;
    }
}
