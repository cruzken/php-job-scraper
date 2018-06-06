<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Scrape;

class ScrapeJobmote extends Scrape
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:jobmote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Job Postings at Jobmote';

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
        $scrapeUrl = env('SCRAPE_JOBMOTE_URL') . env('SCRAPE_JOBMOTE_QUERY');
        $xpath = $this->xpath($scrapeUrl);
        
        $data = array();
        
        $ahref_row = $xpath->query('//ul[@class="jobs"]/li');
        if ($ahref_row->length > 0) {

            foreach ($ahref_row as $row) {

                $title    = $this->singleQuery($xpath, './/h3/a', $row);
                $company  = $this->singleQuery($xpath, './/span/strong', $row);
                $location = 'remote';
                $date     = $this->singleQuery($xpath, './/span', $row);
                $date     = preg_replace('/.+?\s·\s/', '', $date);
                $link     = $this->singleLink($xpath, './/h3/a', $row, env('SCRAPE_JOBMOTE_URL'));
                
                $entry = compact('title', 'company', 'location', 'date', 'link');
                $data[] = $this->trim_map($entry);
            }
        }
        
        $this->storeJobs($data);
        
    }
    
}
