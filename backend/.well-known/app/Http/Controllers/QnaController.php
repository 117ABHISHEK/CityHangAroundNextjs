<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QnaController extends Controller
{
    public function index($category_slug)
{
    $category = ProductCategory::where('product_category_slug', $category_slug)->firstOrFail();

    return view('frontend.qna.index', compact('category'));
}

}
