<?php 
/// connect to our database here 
include_once ('mysqli_connection.php');
///This first query is just to get the total count of rows
$sql = "SELECT COUNT(id) FROM users WHERE approved='1'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
//Here we have the total row count 
$total_rows = $row[0];
///specify how many results per page
$rpp = 4;
///This tells us the page number of our last page
$last = ceil($total_rows/$rpp);
///This makes sure $last cannot be less than 1
if ($last < 1){
	$last = 1;
}
// close the database connection
mysqli_close($db_conx);
?>
<!DOCTYPE html>
<html>
	<head>
        <style>
            .username{
                color: cornflowerblue;
                font-weight: bold;
            }
            body{
                width: 700px;
                position: relative;
                margin: auto;
            }
        </style>
        <link type="text/css" rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
	<script type="text/javascript">
	function request_page(pn){
        var results_box = document.getElementById('results_box');
        var pagination_controls = document.getElementById('pagination_controls');
        results_box.innerHTML = "loading results ....";
        var ajax = new XMLHttpRequest();
        ajax.open('POST','pagination_parser.php',true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
            if(ajax.readyState == 4 && ajax.status == 200){
                var dataArray = ajax.responseText.split("||");
                var html_output = '';
                for(var i = 0 ; i < dataArray.length -1 ; i ++){
                    var itemArray = dataArray[i].split('|');
                    html_output += "ID :"+itemArray[0]+" - Testmonial from <b class='username'>"+itemArray[1]+" "+itemArray[2]+"</b><hr/>";
                }
                results_box.innerHTML = html_output;
            }
        };
        ajax.send("rpp="+rpp+"&last="+last+"&pn="+pn);

        /// change teh pagination controls
        var paginationCtrls = "";
        /// only if there is moere than 1 page results give the user pagination controls

        if(last != 1){
            if(pn > 1){
                paginationCtrls += '<li><a onclick="request_page('+(pn-1)+')">&lt;</a></li>';
            }
            //paginationCtrls += '&nbsp; &nbsp; <b>Page '+pn+' of ' +last+ '</b>&nbsp; &nbsp;';
            if(pn != last){
                paginationCtrls += '<li><a onclick="request_page('+(pn+1)+')">&gt;</a><li>';
            }
        }
        pagination_controls.innerHTML = paginationCtrls;
	}
	</script>
	</head>
	<body>

		<div id="results_box" class="list-group" style="margin:auto 100px">
		</div>
        <div id="pagination_controls" class="pagination">
        </div>
		<script type="text/javascript">
            var rpp = <?=$rpp?>;
            var last = <?=$last?>;
			request_page(1);
		</script>
	</body>
</html>