<?php

namespace App\Console\Commands;

use App\Support\Translations\LocalizedContentSyncer;
use Illuminate\Console\Command;
use Throwable;

class SyncLocalizedContent extends Command
{
    protected $signature = 'content:sync-locales
        {--apply : Persist the detected content locale fixes}
        {--limit= : Limit rows per content table while testing}';

    protected $description = 'Detect and repair English/Urdu database content columns.';

    public function handle(LocalizedContentSyncer $syncer): int
    {
        $apply = (bool) $this->option('apply');
        $limit = $this->option('limit') === null ? null : (int) $this->option('limit');

        try {
            $changes = $syncer->sync($apply, $limit);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($changes === []) {
            $this->info('Database content locales are already in sync.');

            return self::SUCCESS;
        }

        $this->table(['Table', 'Row', 'Field', 'Column', 'Value'], array_map(
            fn (array $change): array => [
                $change['table'],
                $change['row'],
                $change['field'],
                $change['column'],
                $change['value'],
            ],
            $changes,
        ));

        $this->info(($apply ? 'Applied ' : 'Detected ').count($changes).' content locale change(s).');

        if (! $apply) {
            $this->warn('Run php artisan content:sync-locales --apply to write these fixes.');
        }

        return self::SUCCESS;
    }
}
