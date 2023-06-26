<?php

namespace App\Console\Commands;

use App\Lemmy\LemmyHelper;
use App\Models\Account;
use App\Notifications\ReplyReceived;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;

class runchecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:runchecks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(LemmyHelper $lemmy)
    {
        while(true) {
            $account = Account::where("last_checked", "<=", Carbon::now()->subMinute())->orWhere("last_checked", null)->first();

            if(!$account) {
                sleep(1);
                continue;
            }

            error_log("found one.");
            $account->setChecked();

            $lemmy->setup($account->username, $account->instance, $account->auth_token);

            $lastReply = $lemmy->getLastReply();

            if(!$lastReply) {
                continue;
            }

            if($account->last_reply_id < $lastReply["id"]) {
                $account->setLastReplyId($lastReply["id"]);

                error_log($lastReply["content"]);

                $account->notify(new ReplyReceived($lastReply));
            }

            usleep(1);
        }
    }
}
