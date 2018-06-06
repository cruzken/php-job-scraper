<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Scrape;

class ScrapeAuthenticJobs extends Scrape
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:authenticjobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Job Postings at Authentic Jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $html = file_get_contents( env('SCRAPE_AUTHENTICJOBS_API') );
        $jobs = unserialize($html);
        $data = array();

        if (!empty($jobs)) { //if any html is actually returned

                foreach ($jobs['listings']['listing'] as $row) {

                    $title    = $row['title'];
                    $company  = $row['company']['name'];
                    $location = $row['company']['location']['name'] ?? 'remote';
                    $date     = $row['post_date'];
                    $link     = $row['url'];

                    $entry = compact('title', 'company', 'location', 'date', 'link');
                    $data[] = $this->trim_map($entry);
                }

        }
        $this->storeJobs($data);
        
    }
    
}
