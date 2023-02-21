<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function getAllProduct();
    public function productDetails($credentials);
    public function saveProduct($credentials, $token);
    public function storeProduct($credentials, $merchatant_uuid);
    public function storeDetails($credentials, $productImages, $product_uuid);
    public function storeVariants($credentials, $attributeImages, $product_uuid);

    public function editProduct();
    public function updateProduct();
    public function storeProductGallery($image, $token);
    public function deleteProductGallery($uuid, $token);

    public function deleteProduct();

    public function getAllFeaturedProduct();
    public function storeFeatured($credentils);
    public function deleteFeatured($credentils);
    public function getAllCampaignProduct();

    public function storeCampaign($credentils, $cover);
    public function deleteCampaign($credentils);
}
