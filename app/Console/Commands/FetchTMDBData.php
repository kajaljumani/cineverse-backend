<?php

namespace App\Console\Commands;

use App\Services\TMDBService;
use Illuminate\Console\Command;

class FetchTMDBData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch trending and popular movies/series from TMDB and update the database';

    /**
     * Execute the console command.
     */
    public function handle(TMDBService $tmdbService)
    {
        $this->info('Starting TMDB fetch...');
        
        $tmdbService->fetchAndCacheAll();
        
        $this->info('TMDB fetch completed successfully.');
    }
}
