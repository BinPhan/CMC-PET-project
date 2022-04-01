<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 * @version March 30, 2022, 8:33 am UTC
 *
 * @property integer $category_id
 * @property string $name
 * @property string $description
 * @property integer $features
 */
class Product extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'category_id',
        'name',
        'description',
        'features'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'features' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'features' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public function attributeValue()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_value', 'product_id', 'attribute_id');
    }
}
