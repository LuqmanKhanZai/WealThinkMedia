<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Category;
use App\Http\Requests\Configuration\CategoryRequest;
use App\Repositories\Configuration\Category\CategoryInterface;

class CategoryController extends Controller
{

    private $categoryRepository;
    public function __construct(CategoryInterface $categoryRepository)
    {
        // $this->middleware('permission:category.list')->only(['render_list','web_index']);
        // $this->middleware('permission:category.create')->only(['store','create']);
        // $this->middleware('permission:category.update')->only(['update']);
        // $this->middleware('permission:category.delete')->only(['destroy']);
        $this->categoryRepository = $categoryRepository;
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryRepository->storeCategory($request);
    }

    public function edit($id)
    {
        return $this->categoryRepository->findCategory($id);
    }

    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryRepository->updateCategory($request, $id);
    }

    public function destroy($id)
    {
        return $this->categoryRepository->destroyCategory($id);
    }

    public function status(Request $request)
    {
        return $this->categoryRepository->statusCategory($request);
    }


    // WebBlade
    public function index()
    {
        return view('admin.configuration.category.index');
    }

    public function get_data()
    {
        $category = Category::get();

        return DataTables::of($category)
            ->addColumn('action', function ($category) {
                return '<div class="btn-group btn-group-xs">
                <a onclick="Status('.$category->id.')" class="btn btn-warning"><i class="fa fa-ban"></i></a>
                <a onclick="ModelShow('.$category->id.', \''.$category->category_name.'\')" data-toggle="modal" data-target="#ModelShow" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a onclick="Delete('.$category->id.')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


}
