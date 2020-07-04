<?php

namespace Mi2\Messages\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    function __construct()
    {
        global $sqlconf;
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $sqlconf["host"],
            'port' => $sqlconf["port"],
            'database' => $sqlconf["dbase"],
            'username' => $sqlconf["login"],
            'password' => $sqlconf["pass"],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        // Setup the Eloquent ORM…
        $capsule->bootEloquent();
    }

}
