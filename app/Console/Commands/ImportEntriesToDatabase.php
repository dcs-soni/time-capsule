<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use Statamic\Facades\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportEntriesToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entries:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import file-based entries to database';

   
    public function handle()
    {
        $this->info('Importing entries from files to database...');
        
        // Get all file-based entries from capsules collection
        $fileEntries = collect();
        $capsuleFiles = glob(base_path('content/collections/capsules/*.md'));
        
        foreach ($capsuleFiles as $file) {
            $content = file_get_contents($file);
            
            if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
                $frontMatter = $matches[1];
                $body = trim($matches[2]);
                
                $data = [];
                foreach (explode("\n", $frontMatter) as $line) {
                    if (strpos($line, ':') !== false) {
                        [$key, $value] = explode(':', $line, 2);
                        $data[trim($key)] = trim($value, " '\"");
                    }
                }
                
                $fileEntries->push([
                    'id' => $data['id'] ?? basename($file, '.md'),
                    'title' => $data['title'] ?? '',
                    'unlock_date' => $data['unlock_date'] ?? null,
                    'early_unlock' => $data['early_unlock'] ?? false,
                    'updated_by' => $data['updated_by'] ?? null,
                    'updated_at' => $data['updated_at'] ?? time(),
                    'slug' => pathinfo($file, PATHINFO_FILENAME),
                    'content' => $body,
                    'file' => $file
                ]);
            }
        }
        
        $this->info("Found {$fileEntries->count()} entries to import");
        
        foreach ($fileEntries as $entry) {
            DB::table('entries')->updateOrInsert(
                ['id' => $entry['id']], 
                [
                    'id' => $entry['id'],
                    'site' => 'default',
                    'origin_id' => null,
                    'published' => true,
                    'slug' => $entry['slug'],
                    'uri' => '/capsules/' . $entry['slug'],
                    'date' => $entry['unlock_date'],
                    'order' => null,
                    'collection' => 'capsules',
                    'blueprint' => 'capsule',
                    'data' => json_encode([
                        'title' => $entry['title'],
                        'unlock_date' => $entry['unlock_date'],
                        'early_unlock' => $entry['early_unlock'] === 'true',
                        'updated_by' => $entry['updated_by'],
                        'updated_at' => (int)$entry['updated_at'],
                        'content' => $entry['content']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            $this->line("Imported: {$entry['title']}");
        }
        
        $this->info('Import completed successfully!');
        $this->info('Clearing Statamic caches...');
        
        $this->call('cache:clear');
        $this->call('config:clear');
        
        $this->info('Done!');
    }
}
