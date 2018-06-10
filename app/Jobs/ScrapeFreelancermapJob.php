<?php

namespace App\Jobs;

use App\Jobs\ScrapeJob;

class ScrapeFreelancermapJob extends ScrapeJob
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
        $scrapeUrl = env('SCRAPE_FREELANCERMAP_URL') . env('SCRAPE_FREELANCERMAP_QUERY');
        $xpath = $this->xpath($scrapeUrl);
        
        $data = array();
        
        $ahref_row = $xpath->query('//li[@class="project-row"]');
        if ($ahref_row->length > 0) {

            foreach ($ahref_row as $row) {

                $title    = $this->singleQuery($xpath, './/h3[@class="title"]', $row);
                $company  = $this->singleQuery($xpath, './/div[@class="company"]', $row);
                $location = $this->singleQuery($xpath, './/span[@class="country"]', $row);
                $date     = $this->singleQuery($xpath, './/div[@class="created"]', $row);
                $date     = preg_replace('/.+?\s·\s/', '', $date);
                $link     = $this->singleLink($xpath, './/h3[@class="title"]/a', $row, env('SCRAPE_FREELANCERMAP_URL'));
                
                $entry = compact('title', 'company', 'location', 'date', 'link');
                $data[] = $this->trim_map($entry);
            }
        }
        
        $this->storeJobs($data);
    }
}
