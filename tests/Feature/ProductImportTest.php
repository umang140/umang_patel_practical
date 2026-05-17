<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\ProcessProductImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_import_job_is_dispatched(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'products.csv',
            100,
            'text/csv'
        );

        $response = $this->actingAs($user)
            ->postJson('/api/products/import', [
                'file' => $file,
            ]);

        $response->assertStatus(202);

        Queue::assertPushed(ProcessProductImport::class);
    }
}