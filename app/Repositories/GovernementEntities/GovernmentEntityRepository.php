<?php

namespace App\Repositories\GovernementEntities;

use App\Models\GovernmentEntities;
use Illuminate\Support\Facades\Cache;

class GovernmentEntityRepository
{
    public function getCodeById(int $id)
    {
        return GovernmentEntities::query()
            ->select([
                'id',
                'code',
            ])
            ->findOrFail($id);
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
