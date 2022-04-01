<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version March 30, 2022, 8:33 am UTC
 */

class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'name',
        'description',
        'features'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    /**
     * Search function
     */
    public function search($query, $key, $value)
    {
        switch ($key) {
            case 'name':
                return $query->where($key, $value);
                break;
            case 'features':
                return $query->where($key, $value);
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Create attribute and map it to product
     */
    public function syncAttribute($product, $attributeIds)
    {
        $product->attribute()->sync(
            []
        );
        $product->attribute()->sync(
            $attributeIds
        );
    }
}
