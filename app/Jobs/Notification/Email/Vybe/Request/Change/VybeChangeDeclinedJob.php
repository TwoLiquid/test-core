<?php

namespace App\Jobs\Notification\Email\Vybe\Request\Change;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class VybeChangeDeclinedJob
 *
 * @package App\Jobs\Notification\Email\Vybe\Request\Change
 */
class VybeChangeDeclinedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * VybeChangeDeclinedJob constructor
     *
     * @param array $parameters
     */
    public function __construct(
        array $parameters
    )
    {
        /** @var array parameters */
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}