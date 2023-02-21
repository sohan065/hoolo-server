<?php

namespace App\Repositories;

interface BrandRepositoryInterface
{

    public function store($credentials, $icon);
    public function edit($uuid);
    public function update($credentials, $icon);
    public function delete($uuid);

    public function storeFeatured($credentils);
    public function getAllFeaturedBrand();
    public function deleteFeatured($credentils);
    public function getAllCampaignBrand();

    public function storeCampaign($credentils, $cover);
    public function deleteCampaign($credentils);
}
