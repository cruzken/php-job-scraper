<?php

namespace App\Jobs;

use App\Jobs\ScrapeJob;

class ScrapeLarajobsJob extends Scrapejob
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
        $scrapeUrl = env('SCRAPE_LARAJOBS_URL');
        $xpath = $this->xpath($scrapeUrl);
        
        $data = array();
        
        $ahref_row = $xpath->query('//tr[@class="highlight"]/td/a');
        if ($ahref_row->length > 0) {

            foreach ($ahref_row as $row) {

                $title    = $this->singleQuery($xpath, './/div[@class="job-wrap"]/div[@class="details"]/div[@class="description"]', $row);
                $company  = $this->singleQuery($xpath, './/div[@class="job-wrap"]/div[@class="details"]/h4', $row);
                $company  = preg_replace("/NEW\s+/", "", $company);
                $location = $this->singleQuery($xpath, './/div[@class="job-wrap"]/div[@style="font-size: small;"]', $row);
                $link     = $row->getAttribute("data-url");

                
                $entry = compact('title', 'company', 'location', 'link');
                $data[] = $this->trim_map($entry);
            }
        }
        $this->storeJobs($data);
    }
}
