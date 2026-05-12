<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class conexion extends Controller
{
    public static function OpenConnectionSQLServer(){
        try {
            $serverName = "11.0.40.3,1433";
            $connectionOptions = array("Database"=>"Sage",
                "Uid"=>"sa", "PWD"=>"#Ideafix*.99", "CharacterSet" => "UTF-8");
            $conn = sqlsrv_connect($serverName, $connectionOptions);
            if($conn == false)
                print_r(sqlsrv_errors());
            return $conn;
        }
        catch(Exception $e) {
            echo("Error!");
        }
    }
}
