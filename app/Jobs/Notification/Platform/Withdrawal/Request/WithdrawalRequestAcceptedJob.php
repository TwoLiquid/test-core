<?php

namespace App\Jobs\Notification\Platform\Withdrawal\Request;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class WithdrawalRequestAcceptedJob
 *
 * @package App\Jobs\Notification\Platform\Withdrawal\Request
 */
class WithdrawalRequestAcceptedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * WithdrawalRequestAcceptedJob constructor
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
