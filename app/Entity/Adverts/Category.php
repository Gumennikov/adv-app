<?php

namespace App\Entity\Adverts;

use Kalnoy\Nestedset\NodeTrait;

class Category extends \Illuminate\Database\Eloquent\Model
{
    use NodeTrait;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function getPath(): string
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }

    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }

    /**
     * @return Attribute[]
     */
    public function allAttributes(): array
    {
        $parent = $this->parentAttributes();
        $own = $this->attributes()->orderBy('sort')->getModels();
        return array_merge($parent, $own);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
