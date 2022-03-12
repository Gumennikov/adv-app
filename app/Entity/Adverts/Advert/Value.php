<?php


namespace App\Entity\Adverts\Advert;

/**
 * @property int $attribute_id
 * @property string $value
 */

class Value extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'advert_advert_values';

    public $timestamps = false;

    protected $fillable = ['attribute_id', 'value'];
}
