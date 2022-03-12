<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-regions');
    }

    public function index()
    {
        $regions = Region::where('parent_id', null)->orderBy('name')->get();

        return view('admin.regions.index', compact('regions'));
    }
    // Render create form
    public function create(Request $request)
    {
        $parent = null;

        if ($request->get('parent')){
            $parent = Region::findOrFail($request->get('parent'));
        }

        return view('admin.regions.create', compact('parent'));
    }

    // Create Region
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string:max:255|unique:regions,name,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'slug' => 'required|string:max:255|unique:regions,slug,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'parent' => 'nullable|exists:regions,id',
        ]);

        //$region = Region::create($request->only(['name', 'slug', 'parent_id']));
        $region = Region::create([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent'],
        ]);

        Cache::tags('region')->flush();

        return redirect()->route('admin.regions.show', $region);
    }

    public function show(Region $region)
    {
        $regions = Region::where('parent_id', $region->id)->orderBy('name')->get();

        return view('admin.regions.show', compact('region', 'regions'));
    }

    // Render update form
    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    // Updating Region
    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
            'name' => 'required|string:max:255|unique:regions,name,' . $region->id . ',id,parent_id,' . $region->parent_id,
            'slug' => 'required|string:max:255|unique:regions,slug,' . $region->id . ',id,parent_id,' . $region->parent_id,
        ]);

        $region->update($request->only(['name', 'slug']));

        return redirect()->route('admin.regions.show', $region);
    }

    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('admin.regions.index');
    }
}
