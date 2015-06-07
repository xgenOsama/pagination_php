<?php

//// make the script run only if there is a page number to the script
    if(isset($_POST['pn'])){
    $rpp = preg_replace('#[^0-9]#','',$_POST['rpp']);
    $last = preg_replace('#[^0-9]#','',$_POST['last']);
    $pn = preg_replace('#[^0-9]#','',$_POST['pn']);

    /// This makes sure the page number isn't below 1 , or more 1 or more our last page

    if($pn < 1){
        $pn = 1;
    }else if($pn > $last){
        $pn = $last;
    }
    // connect to our database here

    include_once ('mysqli_connection.php');
    //This sets the range of rows to query for the chosen $pn
    $limit = 'LIMIT '.($pn - 1)*$rpp.','.$rpp;
    ///This is your query again, it is for grabbing just one page worth of rows by applying $limit
    $sql = "SELECT id ,firstname,lastname,datemade FROM users WHERE approved='1' ORDER BY id DESC $limit";
    /*echo $sql;
    exit();*/
    $query = mysqli_query($db_conx,$sql);
    $dataString = "";
    while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
        $id = $row['id'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        //$itemdate = strtotime('%b %d %Y',strtotime($row['datemade']));
        $dataString .=$id.'|'.$firstname.'|'.$lastname.'||';
    }

    /// close your database communication
    mysqli_close($db_conx);
    /////Echo the result back to ajax
    echo $dataString;
    exit();
    }