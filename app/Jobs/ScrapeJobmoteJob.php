<?php


namespace App\Jobs;

use App\Jobs\ScrapeJob;

class ScrapeJobmoteJob extends ScrapeJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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

