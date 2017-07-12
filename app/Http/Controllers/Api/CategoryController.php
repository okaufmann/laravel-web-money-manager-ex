<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::ofUser(Auth::user())->rootCategories()->orderBy('name')->get();

        return fractal()
            ->collection($data)
            ->transformWith(new CategoryTransformer());
    }

    public function subCategories(Category $category)
    {
        $data = $category->categories;

        return fractal()
            ->collection($data)
            ->transformWith(new CategoryTransformer());
    }
}
