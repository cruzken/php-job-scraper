<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use App\JobPosting;
use Illuminate\Support\Facades\Log;

class ScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public $tries = 1;
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

    protected function storeJobs($data)  
    {
        $uniqueJobs = 0;
        
        foreach ($data as $jobPosting) {
            
            if (! JobPosting::where( 'link', '=', $jobPosting['link'])->exists() ) {

                $jobPost = new JobPosting;
                $jobPost->title = $jobPosting['title'];
                $jobPost->company = $jobPosting['company'];
                $jobPost->location = $jobPosting['location'];
                $jobPost->link = $jobPosting['link'];
                $jobPost->save();
                
                $uniqueJobs++;
            } 
            
        }
        
        $time = Carbon::now();
        $scrapeLog = 'storage/logs/scraper.txt';
        $className = get_class($this);
        $jobCount = count($data);
        Log::info("\n {$className} Done Storing {$uniqueJobs} of {$jobCount} Job Postings at {$time}");        
    }
    
    protected function xpath($url)
    {
        $html = file_get_contents($url);
        $doc = new \DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors
        if (!empty($html)) //if any html is actually returned
        { 
            $doc->loadHTML($html);
            libxml_clear_errors(); //remove errors for yucky html
            return new \DOMXPath($doc);
        }
        return null;
    }
    
    protected function singleQuery($xpath, $query, $context = null)
    {
      if ($context)
      {
        return $xpath->query($query, $context)->item(0)->nodeValue;
      }
      return $xpath->query($query)->item(0)->nodeValue;
    }
    
    protected function singleLink($xpath, $query, $context = null, $rootUrl = '')
    {
      if ($context)
      {
        return $rootUrl . $xpath->query($query, $context)->item(0)->getAttribute("href");
      }
      return $rootUrl . $xpath->query($query)->item(0)->getAttribute("href");
    }
    
    protected function trim_map($array) 
    {
        foreach ($array as $key => $value) {
            $array[$key] = trim($value); 
        } 
        return $array;
    }



}
