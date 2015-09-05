<?php

class Tables {

    //TABLES OBJECT
    public $tables = array(
        'smltown_games' => array(
            'id' => "int(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'name' => "varchar(255) UNIQUE not null",
            'password' => "varchar(255)",
            //game status
            'type' => "varchar(255) DEFAULT 'mafia-werewolf'",
            'status' => "int(11) NOT NULL",
            'cards' => "text",
            'night' => "varchar(255)",
            'timeStart' => "bigint(20)",
            'time' => "bigint(20)",
            //admin options
            'dayTime' => "int(11)",
            'openVoting' => "int(1)",
            'endTurn' => "int(1) DEFAULT 1",
            //
            'lastConnection' => "timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP",
            'PRIMARY KEY' => "(id)"
        ),
        'smltown_players' => array(
            'id' => "varchar(255) UNIQUE NOT NULL",
            'name' => "varchar(255)",
            'lang' => "varchar(255)",
            'gameId' => "int(11)",
            'reply' => "text NOT NULL default ''",
            'websocket' => "int(11) DEFAULT 0",
            'lastConnection' => "timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP",
            'PRIMARY KEY' => "(id)"
        ),
        'smltown_plays' => array(
            'id' => "int(11) UNSIGNED NOT NULL AUTO_INCREMENT", //phpmyqdmin edit
            'userId' => "varchar(255) NOT NULL",
            'gameId' => "int(11) UNSIGNED NOT NULL",
            'admin' => "int(11)",
            'card' => "varchar(255)",
            'rulesPHP' => "text",
            'rulesJS' => "text",
            'status' => "int(11)",
            'sel' => "int(11)",
            'message' => "text",
            'reply' => "text NOT NULL default ''",
            'PRIMARY KEY' => "(id)"
        )
    );

    function createDB() {
        //require -> inside this function
        require 'config.php';
        $enlace = mysqli_connect("localhost", $database_user, $database_pass);
        if (!$enlace) {
            echo 'IS YOUR MYSQL WORKING? - WRONG DB CREDENTIALS?';
            return;
        }

        $sql = 'CREATE DATABASE smalltown';
        if (mysqli_query($enlace, $sql)) {
            echo "smalltown data base was created successfully. \n";
        } else {
            echo mysql_error() . "\n";
        }
    }

    function createTables() {
        foreach ($this->tables as $tablename => $array) {
            $sth = sql($this->createTableSring($tablename, $array));
            echo $this->createTableSring($tablename, $array);
            if ($sth->rowCount() > 0) { //nothing changes
                echo "Table $tablename created successfully. ";
            }
        }
    }

    function createTableSring($tablename, $array) {
        $sql = "CREATE TABLE IF NOT EXISTS $tablename(";
        $last_key = end(array_keys($array));
        foreach ($array as $key => $value) {
            $sql = "$sql $key $value";
            if ($key != $last_key) {
                $sql = "$sql,";
            }
        }
        return "$sql)";
    }

    function addColumn($columnName) {
        foreach ($this->tables as $tablename => $array) {
            foreach ($array as $colNames => $value) {
                if ($columnName == $colNames) {
                    sql("ALTER TABLE $tablename ADD $columnName $value");
                    return;
                }
            }
        }
    }

}

//default
include_once 'php/DB.php';
$tables = new Tables;
$tables->createDB();
$tables->createTables();
