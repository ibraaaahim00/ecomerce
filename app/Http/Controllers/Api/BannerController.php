<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Services\BannerService;

class BannerController extends Controller
{
    public function __construct(
        private BannerService $bannerService
    ) {
    }

    public function index()
    {
        $banners = $this->bannerService->getBanners();

        return BannerResource::collection($banners);
    }
}
