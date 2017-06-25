<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::rootCategories()->orderBy('name')->get();

        return fractal()
            ->collection($data)
            ->transformWith(new CategoryTransformer());
    }

    public function subCategories(Category $category)
    {
        $data = $category->subCategories;

        return fractal()
            ->collection($data)
            ->transformWith(new CategoryTransformer());
    }
}
