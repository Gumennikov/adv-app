<?php


namespace App\Entity\Adverts\Advert;


class Photo extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'advert_advert_photos';

    public $timestamps = false;

    protected $fillable = ['file'];
}
