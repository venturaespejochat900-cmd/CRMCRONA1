<?php

/* Connect to the local server using Windows Authentication and
specify the AdventureWorks database as the database in use. */
$serverName = "217.61.143.7";
$connectionInfo = array( "Database"=>"Sage", "UID"=>"logic", "PWD"=>"Sage2009+");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
     echo "Could not connect.\n";
     die( print_r( sqlsrv_errors(), true));
}

/* Set up the Transact-SQL query.*/
$tsql = "select sysBinario from lsysBinary where sysIdBinario='$_GET[ImagenExt]'";
/*sysIdBinario='$_GET[ImagenExt]'";*/

/*print_r($tsql);*/

/* Set the parameter values and put them in an array.*/
$productPhotoID = 70;
$params = array( $productPhotoID);

/* Execute the query.*/
$stmt = sqlsrv_query($conn, $tsql,$params);

if( $stmt === false )
{
     echo "Error in statement execution.</br>";
     die( print_r( sqlsrv_errors(), true));
}else {
    if (sqlsrv_fetch($stmt)) {
        $image = sqlsrv_get_field($stmt, 0,
            SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY));
        fpassthru($image);
    } else {

        echo "Error in retrieving data.</br>";
        die(print_r(sqlsrv_errors(), true));


        /*Free statement and connection resources.*/
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    }

}

?>

