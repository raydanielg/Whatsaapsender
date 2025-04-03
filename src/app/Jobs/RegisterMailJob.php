<?php

namespace App\Jobs;

use App\Http\Utility\SendMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegisterMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $emailTemplate;
    public array $mailCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $emailTemplate, array $mailCode)
    {
        $this->user = $user;
        $this->emailTemplate = $emailTemplate;
        $this->mailCode = $mailCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SendMail::MailNotification($this->user, $this->emailTemplate, $this->mailCode);
    }
}
