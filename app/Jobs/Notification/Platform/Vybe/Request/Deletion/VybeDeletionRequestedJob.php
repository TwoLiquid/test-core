<?php

namespace App\Jobs\Notification\Platform\Vybe\Request\Deletion;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class VybeDeletionRequestedJob
 *
 * @package App\Jobs\Notification\Platform\Vybe\Request\Deletion
 */
class VybeDeletionRequestedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * VybeDeletionRequestedJob constructor
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
