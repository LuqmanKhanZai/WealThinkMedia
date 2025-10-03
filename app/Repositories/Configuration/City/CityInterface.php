<?php
namespace App\Repositories\Configuration\City;

Interface CityInterface{
    public function storeCity($data);
    public function findCity($id);
    public function updateCity($data, $id);
    public function destroyCity($id);
}
