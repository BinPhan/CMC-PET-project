<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version March 29, 2022, 8:14 am UTC
 */

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'description',
        'parent_id',
        'status'
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
        return Category::class;
    }

    public function assignChild($item, $sourceMaterial)
    {
        $children = $sourceMaterial->where('parent_id', $item->id);
        if (!$children->isEmpty()) {
            $item->child = $children;
            $item->child->map(function ($item, $key) use ($sourceMaterial) {
                $this->assignChild($item, $sourceMaterial);
                return $item;
            });
        }
    }
}
