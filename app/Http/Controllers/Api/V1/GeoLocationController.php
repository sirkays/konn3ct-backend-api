<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\GeoLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * GeoLocationController
 *
 * Provides geolocation-based country and currency suggestions for the checkout flow.
 * The result is a SUGGESTION only — the authoritative currency is always server-side.
 *
 * GET /api/v1/geo/detect
 */
class GeoLocationController extends Controller
{
    public function __construct(
        private readonly GeoLocationService $geoService
    ) {}

    /**
     * Detect the client's country and suggest a currency.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function detect(Request $request): JsonResponse
    {
        $result = $this->geoService->resolve($request);

        return response()->json([
            'country_code' => $result['countryCode'],
            'currency'     => $result['currency'],
        ], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }
}
