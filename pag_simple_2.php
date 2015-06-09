<?php
 include_once('mysqli_connection.php');
/// This first query is just to get the total count of rows
$sql = "SELECT COUNT(id) FROM users WHERE approved ='1'";
$query = mysqli_query($db_conx,$sql);
$row = mysqli_fetch_row($query);


//here we have the total row count
$rows = $row[0];
/// This is the number fo results we want displayed per page
$page_rows = 5 ;
// this tells us the page number of our last page
$last = ceil($rows/$page_rows);
///This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
/// Establish the $pagenum variable
$pagenum = 1;
if(isset($_GET['pn'])){
    $pagenum = preg_replace('~[^0-9]~','',$_GET['pn']);
}

///This makes sure the page number isn't blew 1 , or more than our $last page
if($pagenum < 1){
    $pagenum = 1;
}else if ($pagenum > $last){
    $pagenum = $last;
}

/// This sets the range of rows to query for the chosen $pagnum
$limit = 'LIMIT '.($pagenum-1)*$page_rows.','.$page_rows;
////this is your query again it is for grabbing just one page worth of by applying $limit
$sql = "SELECT id,firstname,lastname FROM users WHERE approved='1' ORDER BY id DESC $limit";

$query = mysqli_query($db_conx,$sql);
$list = '';
while($row = mysqli_fetch_array($query)){
    $id = $row[0];
    $firstname = $row[1];
    $lastname = $row[2];
    $list .= '<p><a>'.$firstname.' '.$lastname.'</a></p>';
}
/*var_dump(mysqli_fetch_array($query));
exit();*/
///This shows the user what page they are on , ante the total number of page
$textline1 = "testimonials (<b>$rows</b>)";
$textline2 = "Page <b>pagennum</b> of <b>$last</b>";
///Establish the $paginationCtrls variable
$paginationCtrls = "";
//if there is more than 1 page worth the results
if($last != 1){
    /*First we check if we are on page one . if we are then we don't need a link to the previous page or the first page so we do nothing if we aren't the we generate links to the first page and to the previous page */
    if($pagenum > 1){
        $previous = $pagenum - 1 ;
        $paginationCtrls .='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">previous</a> &nbsp; &nbsp';
        //Render clickable number links that should appear on the life target page number
        for($i = $pagenum-4 ; $i <$pagenum; $i++){
            if($i > 0 ){
                $paginationCtrls .='<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'"></a> &nbsp;';
            }
        }
    }
    /// Render the target page number , but without it being link
    /*$paginationCtrls .= ''.$pagenum.' &nbsp;';
    /// Render the clickable number links that should appear on the right
    for($i = $pagenum-1; $i <= $last ; $i++){
        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp;';
        if($i >= $pagenum+4){
            break;
        }
    }*/
    ///This does the same as above , only checking if we are on the last page , and the generating the 'next'
    if($pagenum != $last){
        $next = $pagenum+1;
        $paginationCtrls .= '&nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">'.$next.'</a>';
    }
}


/// close database connection

mysqli_close($db_conx);
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <style type="text/css">
            body{
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            }
            div#pagination_controls{font-size:21px; }
            div#pagination_controls > a {color: #06F;}
            div#pagination_controls > a:visited{color: #204d74;}
        </style>
    </head>
    <body>
      <div>
          <h2> <?= $textline1?> Paged</h2>
          <p><?= $textline2?></p>
          <p><?= $list ?></p>
          <div id="pagination_controls">
              <?=$paginationCtrls?>
          </div>
      </div>

    </body>
</html>
