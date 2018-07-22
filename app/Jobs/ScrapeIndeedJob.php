<?php

namespace App\Jobs;

use App\Jobs\ScrapeJob;

class ScrapeIndeedJob extends ScrapeJob
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
        //
        $scrapeUrl = env('SCRAPE_INDEED_URL') . env('SCRAPE_INDEED_QUERY');
        $xpath = $this->xpath($scrapeUrl);
        
        $data = array();
        
        $ahref_row = $xpath->query('//div[@data-tn-component="organicJob"]');
        if ($ahref_row->length > 0) {

            foreach ($ahref_row as $row) {

                $title    = $this->singleQuery($xpath, 'h2[@class="jobtitle"]', $row);
                $company  = $this->singleQuery($xpath, '//span[@class="company"]', $row);
                $location = $this->singleQuery($xpath, 'span[@class="location"]', $row);
                $date     = $this->singleQuery($xpath, './/span[@class="date"]', $row);
                $date     = preg_replace('/.+?\s·\s/', '', $date);
                $link     = $this->singleLink($xpath, 'h2[@class="jobtitle"]/a', $row, env('SCRAPE_INDEED_URL'));
                
                $entry = compact('title', 'company', 'location', 'date', 'link');
                $data[] = $this->trim_map($entry);
            }
        }
        
        $this->storeJobs($data);
    }
}
