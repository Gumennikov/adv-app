<?php

namespace App\Http\Router;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Support\Facades\Cache;

class AdvertsPath implements \Illuminate\Contracts\Routing\UrlRoutable
{
    /**
    * @var Region
     */
    public $region;

    /**
    * @var Category
     */
    public $category;

    /**
     * Сеттер, который создает внутри себя клон объекта Region.
     * Т.о. мы обеспечиваем иммутабильность объекта Region (чтобы не менял сам себя)
     * @param Region $region
     * @return AdvertsPath
     */
    public function withRegion(?Region $region): AdvertsPath
    {
        $clone = clone $this;
        $clone->region = $region;
        return $clone;
    }

    public function withCategory(?Category $category): AdvertsPath
    {
        $clone = clone $this;
        $clone->category = $category;
        return $clone;
    }

    public function getRouteKey()
    {
        $segments = [];

        if ($this->region){
            $segments[] = Cache::tags('region')->rememberForever('region_' . $this->region->id, function () {
                return $this->region->getPath();
            });
        }

        if ($this->category){
            $segments[] = Cache::tags('category')->rememberForever('category_' . $this->category->id, function () {
                return $this->category->getPath();
            });
        }

        Cache::tags('region')->flush();

        return implode('/', $segments);
    }

    public function getRouteKeyName(): string
    {
        return 'adverts_path';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $chunks = explode('/', $value);

        /** @var Region|null $region */
        $region = null;
        do {
            $slug = reset($chunks);
            if ($slug && $next = Region::where('slug', $slug)->where('parent_id', $region ? $region->id : null)->first()) {
                $region = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        /** @var Category|null $category */
        $category = null;
        do {
            $slug = reset($chunks);
            if ($slug && $next = Category::where('slug', $slug)->where('parent_id', $category ? $category->id : null)->first()) {
                $category = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        if (!empty($chunks)) {
            abort(404);
        }

        return $this
            ->withRegion($region)
            ->withCategory($category);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
