<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public $table = 'attributes';

    public $fillable = [
        'name'
    ];

    public function attributeValue()
    {
        return $this->hasOne(AttributeValue::class, 'attribute_id', 'id');
    }
}
