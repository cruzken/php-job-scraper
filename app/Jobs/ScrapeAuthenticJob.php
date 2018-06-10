<?php

namespace App\Jobs;

use App\Jobs\ScrapeJob;

class ScrapeAuthenticJob extends ScrapeJob
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
