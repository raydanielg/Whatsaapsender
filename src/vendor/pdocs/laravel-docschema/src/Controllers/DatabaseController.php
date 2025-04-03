<?php

namespace Alex\LaravelDocSchema\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Alex\LaravelDocSchema\Helpers\DatabaseManager;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    { 
        //
    }
}
