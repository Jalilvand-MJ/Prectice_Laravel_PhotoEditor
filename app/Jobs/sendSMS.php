<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class sendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone, $pattern, $values;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $pattern, $values = '')
    {
        $this->phone = $phone;
        $this->pattern = env($pattern);
        $this->values = $values;
        $this->onQueue('SMS');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::withHeaders([
            'Authentication' => env('SMS_AUTH_TOKEN')
        ])->post('https://api.mrapi.ir/V2/sms/custom', [
            'PhoneNumber' => $this->phone,
            'CustomPatternID' => $this->pattern,
            'Values' => $this->values,
            'WithSignature' => false,
            'Token' => env('SMS_PROJECT_TOKEN'),
            'ProjectType' => 1
        ]);
    }
}
