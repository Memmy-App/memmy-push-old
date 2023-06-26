<?php

namespace App\Console\Commands;

use App\Lemmy\LemmyHelper;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

            $account->setChecked();

            $lemmy->setup($account->username, $account->instance, $account->auth_token);

            $lastReply = $lemmy->getLastReply($account->last_reply_id);

            if(!$lastReply) {
                continue;
            }

            if($account->last_reply_id < $lastReply["id"]) {
                $account->setLastReplyId($lastReply["id"]);

                error_log($lastReply["content"]);
            }

            usleep(1);
        }
    }
}
