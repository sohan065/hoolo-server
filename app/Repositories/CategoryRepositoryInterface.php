<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    public function getAllGcategory();
    public function storeGcategory($credentials, $icon);
    public function editGcategory($uuid);
    public function updateGcategory($credentials, $icon);
    public function deleteGcategory($uuid);

    public function getAllPcategory();
    public function storePcategory($credentials, $iconicon);
    public function editPcategory($uuid);
    public function updatePcategory($credentials, $icon);
    public function deletePcategory($uuid);

    public function getAllCategory();
    public function storeCategory($credentials, $icon);
    public function editCategory($uuid);
    public function updateCategory($credentials, $icon);
    public function deleteCategory($uuid);

    public function getAllDpCategory();
    public function storeDpCategory($credentials,$icon);
    public function editDpCategory($uuid);
    public function updateDpCategory($credentials);
    public function deleteDpCategory($uuid);

    public function storeFeatured($credentils, $cover);
    public function getAllFeaturedCategory();
    public function deleteFeatured($credentils);
    public function getAllCampaignCategory();
    public function storeCampaign($credentils, $cover);

    public function deleteCampaign($credentils);
}
