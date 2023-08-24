<?php

namespace Tutorial\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Tutorial\Category\Http\Requests\CategoryRequest;
use Tutorial\Category\Models\Category;
use Tutorial\Category\Repositories\CategoryRepo;
use Tutorial\Common\Responses\AjaxResponses;

class CategoryController extends Controller
{
    public $repo;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }

    public function index()
    {
        $this->authorize('manage', Category::class);
        $categories = $this->repo->all();
        return view('Category::index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('manage', Category::class);
        $this->repo->store($request);
        return back();
    }

    public function edit($categoryId)
    {
        $this->authorize('manage', Category::class);
        $categories = $this->repo->allExpectById($categoryId);
        $category = $this->repo->findById($categoryId);
        return view('Category::edit', compact('categories', 'category'));
    }

    public function update($categoryId, CategoryRequest $request)
    {
        $this->authorize('manage', Category::class);
        $this->repo->update($categoryId, $request);
        return redirect(route('categories.index'));
    }

    public function destroy($categoryId)
    {
        $this->authorize('manage', Category::class);
        $this->repo->delete($categoryId);
        return AjaxResponses::SuccessResponse();
    }
}
