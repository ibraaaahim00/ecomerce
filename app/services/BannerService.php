<?php

namespace App\Services;

use App\Repositories\BannerRepository;

class BannerService
{
    public function __construct(
        private BannerRepository $bannerRepository
    ) {
    }

    public function getBanners()
    {
        return $this->bannerRepository->getAll();
    }
}
