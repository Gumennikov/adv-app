<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Entity\User;
use App\Entity\Region;
use App\Entity\Adverts\Category;
use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Advert\Advert;
use App\Http\Router\AdvertsPath;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('login', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Login', route('login'));
});

Breadcrumbs::for('login.phone', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Login', route('login.phone'));
});

Breadcrumbs::for('register', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Register', route('register'));
});

Breadcrumbs::for('password.request', function (BreadcrumbTrail $trail) {
    $trail->parent('login');
    $trail->push('Reset Password', route('password.request'));
});

Breadcrumbs::for('password.reset', function (BreadcrumbTrail $trail) {
    $trail->parent('password.request');
    $trail->push('Change', route('password.reset'));
});

// Cabinet
Breadcrumbs::for('cabinet.home', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Cabinet', route('cabinet.home'));
});

Breadcrumbs::for('cabinet.profile.home', function (BreadcrumbTrail $trail) {
    $trail->parent('cabinet.home');
    $trail->push('Profile', route('cabinet.profile.home'));
});

Breadcrumbs::for('cabinet.profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('cabinet.profile.home');
    $trail->push('Edit', route('cabinet.profile.edit'));
});

Breadcrumbs::for('cabinet.profile.phone', function (BreadcrumbTrail $trail) {
    $trail->parent('cabinet.profile.home');
    $trail->push('Phone', route('cabinet.profile.phone'));
});

//Cabinet Adverts

Breadcrumbs::for('cabinet.adverts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('cabinet.home');
    $trail->push('Adverts', route('cabinet.adverts.index'));
});

Breadcrumbs::for('cabinet.adverts.create', function (BreadcrumbTrail $trail) {
    $trail->parent('adverts.index');
    $trail->push('Create', route('cabinet.adverts.create'));
});

Breadcrumbs::for('cabinet.adverts.create.region', function (BreadcrumbTrail $trail, AdvertsPath $path) {
    if ($parent = $path->region->parent) {
        $trail->parent('cabinet.adverts.create.region', $path->withRegion($parent));
    } else {
        $trail->parent('cabinet.adverts.create.category');
    }
    $trail->parent('cabinet.adverts.create');
    $trail->push($path->category->name, route('cabinet.adverts.create.region', $path));
});

Breadcrumbs::for('cabinet.adverts.create.advert', function (BreadcrumbTrail $trail, AdvertsPath $path) {
    $trail->parent('cabinet.adverts.create.region', $path);
    $trail->push($path->region ? $path->region->name : 'All', route('cabinet.adverts.create.advert', $path));
});

// Admin

Breadcrumbs::for('admin.home', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Admin', route('admin.home'));
});

// Users

Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.users.index');
    $trail->push('Users', route('admin.users.create'));
});

Breadcrumbs::for('admin.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::for('admin.users.edit', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push('Edit', route('admin.users.edit', $user));
});

// Region

Breadcrumbs::for('admin.regions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Regions', route('admin.regions.index'));
});

Breadcrumbs::for('admin.regions.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.regions.index');
    $trail->push('Regions', route('admin.regions.create'));
});

Breadcrumbs::for('admin.regions.show', function (BreadcrumbTrail $trail, Region $region) {
    if ($parent = $region->parent) {
        $trail->parent('admin.regions.show', [$parent]);
    } else {
        $trail->parent('admin.regions.index');
    }
    $trail->push($region->name, route('admin.regions.show', $region));
});

Breadcrumbs::for('admin.regions.edit', function (BreadcrumbTrail $trail, Region $region) {
    if ($parent = $region->parent) {
        $trail->parent('admin.regions.show', [$parent]);
    } else {
        $trail->parent('admin.regions.index');
    }
    $trail->push($region->name, route('admin.regions.show', $region));
    $trail->push('Edit', route('admin.regions.edit', $region));
});

// Advert

Breadcrumbs::for('admin.adverts.adverts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Categories', route('admin.adverts.adverts.index'));
});

// Advert Categories

Breadcrumbs::for('admin.adverts.categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.home');
    $trail->push('Categories', route('admin.adverts.categories.index'));
});

Breadcrumbs::for('admin.adverts.categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.adverts.categories.index');
    $trail->push('Categories', route('admin.adverts.categories.create'));
});

Breadcrumbs::for('admin.adverts.categories.show', function (BreadcrumbTrail $trail, Category $category) {
    if ($parent = $category->parent) {
        $trail->parent('admin.adverts.categories.show', $parent);
    } else {
        $trail->parent('admin.adverts.categories.index');
    }
    $trail->push($category->name, route('admin.adverts.categories.show', $category));
});

Breadcrumbs::for('admin.adverts.categories.edit', function (BreadcrumbTrail $trail, Category $category) {
    if ($parent = $category->parent) {
        $trail->parent('admin.adverts.categories.show', $parent);
    } else {
        $trail->parent('admin.adverts.categories.index');
    }
    $trail->push($category->name, route('admin.adverts.categories.show', $category));
    $trail->push('Edit', route('admin.adverts.categories.edit', $category));
});

// Advert Category Attributes

Breadcrumbs::for('admin.adverts.categories.attributes.create', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('admin.adverts.categories.show', $category);
    $trail->push('Create', route('admin.adverts.categories.attributes.create', $category));
});

Breadcrumbs::for('admin.adverts.categories.attributes.show', function (BreadcrumbTrail $trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.adverts.categories.show', $category);
    $trail->push($attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

Breadcrumbs::for('admin.adverts.categories.attributes.edit', function (BreadcrumbTrail $trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $trail->push('Edit', route('admin.adverts.categories.attributes.edit', [$category, $attribute]));
});

// Advert Region

Breadcrumbs::for('adverts.inner_region', function (BreadcrumbTrail $trail, AdvertsPath $path) {
    if ($path->region && $parent = $path->region->parent) {
        $trail->parent('adverts.inner_region', $path->region->withRegion($parent));
    } else {
        $trail->parent('home');
        $trail->push('Adverts', route('adverts.index'));
    }
    if ($path->region) {
        $trail->push($path->region->name, route('adverts.index', $path));
    }
});

Breadcrumbs::for('adverts.inner_category', function (BreadcrumbTrail $trail, AdvertsPath $path, AdvertsPath $orig) {
    if ($path->category && $parent = $path->category->parent) {
        $trail->parent('adverts.inner_category', $path->withCategory($parent), $orig);
    } else {
        $trail->parent('adverts.inner_region', $orig);
    }
    if ($path->category) {
        $trail->push($path->category->name, route('adverts.index', $path));
    }
});

Breadcrumbs::for('adverts.index', function (BreadcrumbTrail $trail, AdvertsPath $path = null) {
    $path = $path ?: adverts_path(null, null);
    $trail->parent('adverts.inner_category', $path, $path);
});

Breadcrumbs::for('adverts.show', function (BreadcrumbTrail $trail, Advert $advert) {
    $trail->parent('adverts.index', adverts_path($advert->region, $advert->category));
    $trail->push($advert->title, route('adverts.show', $advert));
});
// Favorites

Breadcrumbs::for('cabinet.favorites.index', function (BreadcrumbTrail $trail) {
    $trail->parent('cabinet.home');
    $trail->push('Adverts', route('cabinet.favorites.index'));
});
