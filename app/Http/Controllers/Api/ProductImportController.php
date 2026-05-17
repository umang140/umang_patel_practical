<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportProductRequest;
use App\Jobs\ProcessProductImport;
use App\Models\ImportLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductImportController extends Controller
{
    public function store(ImportProductRequest $request): JsonResponse
    {
        $file = $request->file('file');

        $path = $file->store('imports');

        $totalRows = max(0, count(file($file->getRealPath())) - 1);

        $importLog = ImportLog::create([
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'total_rows' => $totalRows,
            'imported_count' => 0,
            'failed_count' => 0,
            'status' => 'pending',
            'errors' => [],
        ]);

        ProcessProductImport::dispatch($importLog, $path);

        return response()->json([
            'import_log_id' => $importLog->id,
            'status_url' => '/api/imports/' . $importLog->id,
        ], 202);
    }

    public function show(int $id): JsonResponse
    {
        $importLog = ImportLog::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$importLog) {
            return response()->json([
                'message' => 'Import log not found.',
            ], 404);
        }

        return response()->json([
            'id' => $importLog->id,
            'filename' => $importLog->filename,
            'status' => $importLog->status,
            'total_rows' => $importLog->total_rows,
            'imported_count' => $importLog->imported_count,
            'failed_count' => $importLog->failed_count,
            'errors' => $importLog->errors,
            'created_at' => $importLog->created_at,
        ]);
    }
}