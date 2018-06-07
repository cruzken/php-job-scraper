<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Scrape;

class ScrapeIndeed extends Scrape
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:indeed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Job Postings at Indeed';

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
        $scrapeUrl = env('SCRAPE_INDEED_URL') . env('SCRAPE_INDEED_QUERY');
        $xpath = $this->xpath($scrapeUrl);
        
        $data = array();
        
        $ahref_row = $xpath->query('//div[@data-tn-component="organicJob"]');
        if ($ahref_row->length > 0) {

            foreach ($ahref_row as $row) {

                $title    = $this->singleQuery($xpath, 'h2[@class="jobtitle"]', $row);
                $company  = $this->singleQuery($xpath, 'span[@class="company"]', $row);
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
