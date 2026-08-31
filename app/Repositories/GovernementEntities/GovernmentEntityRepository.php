<?php

namespace App\Repositories\GovernementEntities;

use App\Models\GovernmentEntities;
use Illuminate\Support\Facades\Cache;

class GovernmentEntityRepository
{
    public function getCodeById($id)
    {
        $entity = GovernmentEntities::where('id', $id)->firstOrFail();
        return $entity;
    }

    public function getAllEntities()
    {
        try {
            return Cache::remember('government_entities', 3600, function () {
                return GovernmentEntities::select('id','name')->orderBy('name')->get();
            });
        } catch (\Throwable $e) {
            \Log::error('GovEntities load failed: '.$e->getMessage());
            return collect([]);
        }
    }
}
