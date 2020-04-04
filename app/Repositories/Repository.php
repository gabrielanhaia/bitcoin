<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Class Repository where there are all the common operations for all the repositories.
 * @package App\Repositories
 */
abstract class Repository
{
    /**
     * Start a database transaction.
     */
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * Commit a database. transaction
     */
    public function commitTransaction()
    {
        DB::commit();
    }

    /**
     * Rollback a database transaction.
     */
    public function rollbackTransaction()
    {
        DB::rollBack();
    }
}
