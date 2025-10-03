<?php
namespace App\Repositories\Configuration\Category;

Interface CategoryInterface{
    public function storeCategory($data);
    public function findCategory($id);
    public function updateCategory($data, $id);
    public function destroyCategory($id);
    public function statusCategory($data);

}
