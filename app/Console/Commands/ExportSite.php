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

        // Set app environment for static generation
        config(['app.env' => 'static']);
        config(['app.url' => '.']);

        // Collect paths manually for known working routes
        $paths = [
            '/feed.json',
        ];
        
        // Get all published entries from capsules collection only
        $entries = \Statamic\Facades\Entry::whereCollection('capsules')->filter(function ($entry) {
            return $entry->published();
        });
        
        foreach ($entries as $entry) {
            $url = $entry->url();
            if ($url !== null) {
                $paths[] = $url;
            }
        }
        
        // Try to add home route using a custom route
        try {
            $paths[] = '/';
        } catch (\Exception $e) {
            $this->warn('Skipping home page due to error: ' . $e->getMessage());
        }
        
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

        // Post-process files to fix asset paths
        $this->fixAssetPaths();

        $this->info('✅ Export complete. Files are in /dist directory.');
    }

    private function fixAssetPaths()
    {
        $distPath = base_path('dist');
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($distPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'html') {
                $content = file_get_contents($file->getPathname());
                
                // Get relative depth for links
                $relativePath = str_replace($distPath . '/', '', $file->getPathname());
                $depth = substr_count($relativePath, '/');
                $prefix = $depth > 0 ? str_repeat('../', $depth) : './';
                
                // Fix asset paths to use relative paths
                $content = preg_replace(
                    '/href="http:\/\/localhost\/build\//',
                    'href="' . $prefix . 'build/',
                    $content
                );
                $content = preg_replace(
                    '/src="http:\/\/localhost\/build\//',
                    'src="' . $prefix . 'build/',
                    $content
                );
                
                // Fix internal links to use relative paths for GitHub Pages
                if ($depth > 0) {
                    // For nested pages, fix links to other capsules and home
                    $content = preg_replace(
                        '/href="\/capsules\/([^"]+)"/',
                        'href="../$1/"',
                        $content
                    );
                    $content = preg_replace(
                        '/href="\/([^c][^"]*)"/',
                        'href="../../$1"',
                        $content
                    );
                } else {
                    // For root pages, make capsule links relative
                    $content = preg_replace(
                        '/href="\/capsules\/([^"]+)"/',
                        'href="capsules/$1/"',
                        $content
                    );
                }
                
                file_put_contents($file->getPathname(), $content);
            }
        }
        
        $this->info('Fixed asset paths and internal links for static deployment');
    }
}
