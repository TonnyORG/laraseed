<?php

namespace TonnyORG\LaraSeed\Database\Traits;

use Illuminate\Support\Facades\DB;

trait SeedersHandler
{
    /**
     * Table name used for seeders
     * @var string
     */
    private $seedersTable = 'seeders';

    /**
     * Run the seeder but first, make sure it
     * haven't ran before.
     *
     * @param  string $seederClass
     */
    private function try(string $seederClass)
    {
        $exists = DB::table($this->seedersTable)
            ->where(['class' => $seederClass])
            ->first();

        if ($exists) {
            return;
        }

        $this->call($seederClass);

        $this->record($seederClass);
    }

    /**
     * Stores the class name for further needs.
     *
     * @param  string $seederClass
     */
    private function record(string $seederClass)
    {
        DB::table($this->seedersTable)
            ->updateOrInsert([
                'class' => $seederClass,
            ], [
                'created_at' => now(),
            ]);
    }
}
