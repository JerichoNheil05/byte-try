<?php

namespace App\Models;

class ProductModel extends BaseModel
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'seller_id',
        'title',
        'description',
        'product_feature',
        'how_it_works',
        'price',
        'category',
        'file_path',
        'preview_path',
        'redirect_url',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getSellerProducts(int $sellerId): array
    {
        return $this->where('seller_id', $sellerId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
