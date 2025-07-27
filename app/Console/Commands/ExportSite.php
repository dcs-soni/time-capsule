<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Bus\Dispatcher;
use Spatie\Export\Exporter;

class ExportSite extends Command
{
    protected $signature = 'site:export';
    protected $description = 'Export site to static HTML';

    public function handle()
    {
        $this->info('Exporting static site...');

        $exporter = new Exporter(
            app(Dispatcher::class),
            app(UrlGenerator::class)
        );

        $exporter
            ->paths([
                '/',
                '/capsules',
                '/feed.json',
            ])
            ->include([
                public_path('css'),   // Tailwind build
                public_path('js'),    // JS
                public_path('assets'), // Media (if any)
            ])
            ->toDisk('export')
            ->export();

        $this->info('✅ Export complete. Files are in /storage/app/export.');
    }
}
