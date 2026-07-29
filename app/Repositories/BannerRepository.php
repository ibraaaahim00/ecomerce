<?php

namespace App\Repositories;

use App\Models\Banner;

class BannerRepository
{
    public function getAll()
    {
        return Banner::where('is_active', true)
            ->latest()
            ->get();
    }
}
