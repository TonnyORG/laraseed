<?php

namespace TonnyORG\LaraSeed\Console\Commands;

use Illuminate\Console\Command;

class UpdateAppRevisionNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraseed:update-revision-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the revision number of the application in the .env file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $basePath = base_path();
        $envFilePath = base_path('.env');
        $currentRevisionNumber = intval(config('laraseed.revision_number'));
        $newRevisionNumber = intval(shell_exec("cd {$basePath} ; git rev-list --count HEAD"));

        $this->info('Updating your application\'s revision number');

        shell_exec("find {$envFilePath} -type f -exec sed -i 's/LARASEED_REVISION_NUMBER={$currentRevisionNumber}/LARASEED_REVISION_NUMBER={$newRevisionNumber}/g' {} \;");

        $this->info('Done!');
    }
}
