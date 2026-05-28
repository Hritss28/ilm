<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Return current weather for Kota Mojokerto and Kabupaten Mojokerto.
     */
    public function index(): JsonResponse
    {
        $locations = [
            $this->cacheService->getWeatherByCoordinate(-7.4664, 112.4338, 'Kota Mojokerto'),
            $this->cacheService->getWeatherByCoordinate(-7.5500, 112.5000, 'Kab. Mojokerto'),
        ];

        return response()->json($locations);
    }
}
