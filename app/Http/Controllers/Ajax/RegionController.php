<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Entity\Region;

class RegionController
{
    public function get(Request $request): array
    {
        $parent = $request->get('parent') ?: null;

        return Region::where('parent_id', $parent)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}
