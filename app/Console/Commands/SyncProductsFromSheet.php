<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetProductImporter;
use Illuminate\Console\Command;

class SyncProductsFromSheet extends Command
{
    protected $signature   = 'products:sync-sheet';
    protected $description = 'Import/sync products from Google Sheet CSV into the database';

    public function handle(GoogleSheetProductImporter $importer): int
    {
        $this->info('🔄 Syncing products from Google Sheet...');

        $stats = $importer->import();

        $this->table(
            ['Created', 'Updated', 'Skipped', 'Errors'],
            [[$stats['created'], $stats['updated'], $stats['skipped'], $stats['errors']]]
        );

        $this->info('✅ Sync complete!');

        return Command::SUCCESS;
    }
}
