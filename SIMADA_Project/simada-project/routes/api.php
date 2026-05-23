<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/rup/pagu-opd', function (Request $request) {
        if ($request->bearerToken() !== 'TOKEN-SIMADA-KEBUMEN-2026') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            [
                'opd' => 'Dinas Pekerjaan Umum',
                'pagu' => 15000000000
            ],
            [
                'opd' => 'Dinas Kesehatan',
                'pagu' => 8500000000
            ],
            [
                'opd' => 'Dinas Pendidikan',
                'pagu' => 12000000000
            ]
        ]);
    });

    Route::get('/tender/realisasi-kontrak', function (Request $request) {
        if ($request->bearerToken() !== 'TOKEN-SIMADA-KEBUMEN-2026') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            [
                'opd' => 'Dinas Pekerjaan Umum',
                'realisasi' => 14200000000
            ],
            [
                'opd' => 'Dinas Kesehatan',
                'realisasi' => 8100000000
            ],
            [
                'opd' => 'Dinas Pendidikan',
                'realisasi' => 11500000000
            ]
        ]);
    });

    Route::get('/spse/paket-tender', function (Request $request) {
        if ($request->bearerToken() !== 'TOKEN-SIMADA-KEBUMEN-2026') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $mockData = json_decode(file_get_contents(database_path('data/spse_paket.json')), true);
        return response()->json($mockData);
    });
});
