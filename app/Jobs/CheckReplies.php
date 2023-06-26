<?php

namespace App\Jobs;

use App\Lemmy\LemmyHelper;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckReplies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LemmyHelper $lemmy): void
    {
        $accounts = Account::get();

        foreach($accounts as $account) {
            $lemmy->setup($account->username, $account->instance, $account->auth_token);

            $lastReply = $lemmy->getLastReply($account->last_reply_id);

            if(!$lastReply) {
                error_log("none");
                continue;
            }

            if($account->last_reply_id != $lastReply["id"]) {
                $account->setLastReplyId($lastReply["id"]);

                error_log($lastReply["content"]);
            }
        }
    }
}
