<?php

namespace App\Repositories\Configuration\City;

use App\Models\Configuration\City;
use App\Repositories\Configuration\City\CityInterface;

class CityRepository implements CityInterface
{
    public function storeCity($request)
    {
        // try {
            // Create a new city record in the City table
            City::create($request->except('submit'));
            // If the operation was successful, return a success response
            return response()->success($request->city_name.' added successfully');
        // } catch (\Exception $e) {
        //     // If an exception occurs, return an error response
        //     return response()->error($request->city_name.' Error occurred. Please try again');
        // }
    }

    public function findCity($id)
    {
        $data = City::select('id', 'city_name')->find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'City not found']);
        }
        return response()->json($data);
    }

    public function updateCity($request, $id)
    {
        $data = City::find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'City not found']);
        }
        $data = City::find($id)->update($request->except('submit'));
        return  $data ?
                response()->success($request->city_name. ' has been updated')
                :
                response()->error($request->city_name.'Error occurred please try again');
    }

    public function destroyCity($id)
    {
        // Step 1: Check if the city exists
        $city = City::find($id);
        if (!$city) {
            return response()->json(['msg' => 'City not found']);
        }
        $deleted = $city->delete();
        // Step 6: Return appropriate response
        return $deleted ?
            response()->success('Operation performed successfully') :
            response()->error('Error occurred please try again');

    }

}
