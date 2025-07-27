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

        // Collect all entry URLs dynamically
        $paths = [];
        
        // Get all published entries
        $entries = \Statamic\Facades\Entry::all()->filter(function ($entry) {
            return $entry->published();
        });
        
        foreach ($entries as $entry) {
            $paths[] = $entry->url();
        }
        
        // Add additional paths
        $paths[] = '/feed.json';
        
        $this->info('Found ' . count($paths) . ' paths to export: ' . implode(', ', $paths));

        $exporter = new Exporter(
            app(Dispatcher::class),
            app(UrlGenerator::class)
        );

        // Include assets that exist (source => target mapping)
        $includeFiles = [];
        $possibleAssets = [
            'css' => public_path('css'),
            'build' => public_path('build'), 
            'assets' => public_path('assets'),
            'favicon.ico' => public_path('favicon.ico'),
        ];
        
        foreach ($possibleAssets as $name => $sourcePath) {
            if (file_exists($sourcePath)) {
                // Map source to target (same name in export)
                $includeFiles[$sourcePath] = $name;
                $this->info("Including: {$name} from {$sourcePath}");
            }
        }

        $exporter
            ->paths($paths)
            ->includeFiles($includeFiles)
            ->export();

        $this->info('✅ Export complete. Files are in /dist directory.');
    }
}
