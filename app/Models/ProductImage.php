<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProductImage
 * @package App\Models
 * @version March 31, 2022, 3:22 am UTC
 *
 * @property integer $product_id
 * @property integer $type
 * @property string $path
 */
class ProductImage extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'product_images';


    protected $dates = ['deleted_at'];

    const THUMB_IMAGE = 1;
    const FEATURE_IMAGE = 2;


    public $fillable = [
        'product_id',
        'type',
        'path'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'type' => 'integer',
        'path' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_id' => 'required',
        'type' => 'required',
        'image' => 'required'
    ];


}
