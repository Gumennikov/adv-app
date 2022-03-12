<?php

namespace App\Providers;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CacheServiceProvider extends \Illuminate\Support\ServiceProvider
{
    private $classes = [
        Region::class,
        Category::class,
    ];

    public function boot()
    {
        foreach($this->classes as $class){
            $this->registerFlusher($class);
        }
    }

    private function registerFlusher($class): void
    {
        /** @var Model $class */
        $flush = function() use ($class) {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::saved($flush);
        $class::updated($flush);
        $class::deleted($flush);
    }
}
