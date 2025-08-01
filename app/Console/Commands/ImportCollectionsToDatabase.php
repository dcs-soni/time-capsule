<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;

class ImportCollectionsToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collections:import';

    /**
     * console command description.
     *
     * @var string
     */
    protected $description = 'Import file-based collections to database';


    public function handle()
    {
        $this->info('Importing collections from files to database...');
        
        // Read capsules.yaml
        $capsulesFile = base_path('content/collections/capsules.yaml');
        if (file_exists($capsulesFile)) {
            $capsuleData = Yaml::parseFile($capsulesFile);
            
            DB::table('collections')->updateOrInsert(
                ['handle' => 'capsules'],
                [
                    'handle' => 'capsules',
                    'title' => $capsuleData['title'] ?? 'Capsules',
                    'settings' => json_encode($capsuleData),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            $this->info('Imported capsules collection');
        }
        
        $pagesFile = base_path('content/collections/pages.yaml');
        if (file_exists($pagesFile)) {
            $pageData = Yaml::parseFile($pagesFile);
            
            DB::table('collections')->updateOrInsert(
                ['handle' => 'pages'],
                [
                    'handle' => 'pages',
                    'title' => $pageData['title'] ?? 'Pages',
                    'settings' => json_encode($pageData),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            $this->info('Imported pages collection');
        }
        
        $this->info('Collection import completed!');
        

        $this->call('cache:clear');
        $this->call('config:clear');
    }
}
