<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Scrape;

class ScrapeFreelancermap extends Scrape
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:freelancermap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Job Postings at freelancermap';

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
