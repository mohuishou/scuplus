<?php

namespace App\Jobs;

class EmailJob extends Job
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
        if ($this->attempts() > 3) {
            $this->release(10);
        }
    }
}
