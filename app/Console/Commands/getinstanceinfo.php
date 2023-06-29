<?php

namespace App\Console\Commands;

use App\Lemmy\LemmyHelper;
use Illuminate\Console\Command;

class getinstanceinfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getinstanceinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $instances = [
        "lemmy.world",
        "lemmy.ml",
        "feddit.de",
        "sh.itjust.works",
        "lemmy.ca",
        "lemm.ee",
        "blahaj.zone"
    ];

    /**
     * Execute the console command.
     */
    public function handle(LemmyHelper $lemmy)
    {
        $result = [];

        foreach($this->instances as $instance) {
            $lemmy->setup("memmyapp", $instance);

            $res = $lemmy->getSiteInfo();

            if(!$res) continue;

            $result[] = $res;
        }

        file_put_contents(env("INSTANCES_PATH"), json_encode($result));
    }
}
