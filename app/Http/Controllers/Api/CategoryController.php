<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Transformers\CategoryTransformer;

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
