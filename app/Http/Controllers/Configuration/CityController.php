<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Configuration\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\CityRequest;
use App\Repositories\Configuration\City\CityInterface;

class CityController extends Controller
{

    private $cityRepository;

    public function __construct(CityInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function store(CityRequest $request)
    {
        return $this->cityRepository->storeCity($request);
    }

    public function edit($id)
    {
        return $this->cityRepository->findCity($id);
    }

    public function update(CityRequest $request, $id)
    {
        return $this->cityRepository->updateCity($request, $id);
    }

    public function destroy($id)
    {
        return $this->cityRepository->destroyCity($id);
    }

    // WebBlade
    public function index()
    {

        return view('admin.configuration.city.index');
    }

    public function get_data()
    {
        $city = City::select(['id', 'city_name', 'city_status', 'created_at']);
        // <a onclick="Status('.$city->id.')" class="btn btn-warning"><i class="fa fa-ban"></i></a>
        return DataTables::of($city)
            ->addColumn('action', function ($city) {
                return '<div class="btn-group btn-group-xs">
                
                <a onclick="ModelShow('.$city->id.', \''.$city->city_name.'\')" data-toggle="modal" data-target="#ModelShow" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a onclick="Delete('.$city->id.')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
