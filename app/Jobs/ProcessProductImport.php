<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ImportLog;
use App\Services\ProductImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public ImportLog $log,
        public string $path
    ) {
    }

    public function backoff(): array
    {
        return [10, 30];
    }

    public function handle(ProductImportService $service): void
    {
        $service->handle($this->log, $this->path);
    }

    public function failed(Throwable $e): void
    {
        $this->log->update([
            'status' => 'failed',
            'errors' => [
                [
                    'message' => $e->getMessage(),
                ]
            ],
        ]);
    }
}