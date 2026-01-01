<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchTrendingMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:fetch-trending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch trending movies and series from TMDB';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\TMDBService $tmdbService)
    {
        $this->info('Fetching trending media...');
        
        $results = $tmdbService->fetchTrending('day');
        
        $this->info('Fetched ' . count($results) . ' items.');
    }
}
