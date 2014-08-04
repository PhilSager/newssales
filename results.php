<?php

// Save in file dbcon.ini:
// [news_connection]
// thishost = "host_name"
// news_db_title = "database_name"
// news_db_table = "table_name"
// user = "user"
// pass = "pass"

$dbcon = parse_ini_file('conf/dbcon.ini');

$county_criterion = "";
$sort = "title";
$sort_criteria = "title";
$search_criteria = array();
$searchterm_exists = false;
$county_exists = false;
$sort_exists = false;
$nothing_to_search = false;
$message = "";
$no_results = false;
$max_per_page = 20;
$max_rows = 1000;
$start = 1;
$end = $start + ($max_per_page - 1);
$prev_start = 1;
$next_start = $start + $max_per_page;
$num_rows = array();

require 'paginator.class.php';

if (!empty($_POST['searchterm']) || !empty($_GET['searchterm'])) {
	$searchterm_exists = true;
	$searchterm = !empty($_POST['searchterm']) ? substr($_POST['searchterm'],0,50) : substr($_GET['searchterm'],0,50);
	if (preg_match('/[^a-zA-Z ]/', $searchterm)) { exit; }
	$searchterm_criterion = "title LIKE '%" . $searchterm . "%' OR city LIKE '%" . $searchterm . "%'";
	array_push($search_criteria, $searchterm_criterion);
}

if (!empty($_POST['county']) || !empty($_GET['county'])) {
	$county_exists = true;
	$county = !empty($_POST['county']) ? substr($_POST['county'],0,10) : substr($_GET['county'],0,10);
	if (preg_match('/[^a-zA-Z]/', $county)) { exit; }
	$county_criterion = "county LIKE '" . $county . "%'";
	array_push($search_criteria, $county_criterion);
}

if (!empty($_POST['start']) || !empty($_GET['start'])) {
	$start = !empty($_POST['start']) ? substr($_POST['start'],0,10) : substr($_GET['start'],0,10);
	if (preg_match('/[^\d]/', $start)) { exit; }
} 

if (!$searchterm_exists && !$county_exists) { 
	$nothing_to_search = true;
	$message = "Need something to search";
}

$statement = "SELECT * FROM " . $dbcon['newssales_db_table'] . " WHERE ";
$pages = new Paginator;
$criteria_total = count($search_criteria);
if (!$nothing_to_search) {
	for ($i = 0; $i < $criteria_total; $i++) {
		if ($i > 0) { $statement .= " AND "; }
	 	$statement .= array_shift($search_criteria);
	}
	
	$count_statement = preg_replace('/\*/','count(*)',$statement);
	try {
		
		$db = new PDO('mysql:host='.$dbcon['thishost'].';dbname='.$dbcon['newssales_db_title'].';charset=utf8', $dbcon['user'], $dbcon['pass']);  
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$num_rows[0] = $db->query($count_statement)->fetchColumn();
		if ($num_rows[0] < 1) { $no_results = true; }
		$pages->items_total = $num_rows[0];
		$pages->mid_range = 5;
		$pages->paginate();
		
		$statement .= " ORDER BY " . $sort_criteria . " " . $pages->limit;
		
		$db = new PDO('mysql:host='.$dbcon['thishost'].';dbname='.$dbcon['newssales_db_title'].';charset=utf8', $dbcon['user'], $dbcon['pass']);
		$results = $db->query($statement);
		$db = null;
		
	} catch(PDOException $e) {  
		print($e->getMessage());
		die();
	}
	
}

?>

<!doctype html public 
  "-//w3c//dtd html 4.01 transitional//en"
  "http://www.w3.org/tr/1999/rec-html401-19991224/loose.dtd">
<html>
<head>
<title>Microfilm Sales List Search Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/json2/20121008/json2.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jStorage/0.3.0/jstorage.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<link type="text/css" rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.6.0/jquery.selectBoxIt.css" />
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.6.0/jquery.selectBoxIt.min.js"></script>

<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.2.12/jquery.jgrowl.min.css" />
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.2.12/jquery.jgrowl.min.js"></script>

<script type="text/javascript" src="formly/formly.js"></script>
<link rel="stylesheet" href="formly/formly.css" type="text/css" />

<style type="text/css">

body {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
}

.paging-section {
	text-align:center;
	padding:6px;
	height: 30px;
}
	
.paginate {
	font-family: Arial, Helvetica, sans-serif;
	font-size: .7em;
}

a.paginate {
	border: 1px solid #000080;
	padding: 2px 6px 2px 6px;
	text-decoration: none;
	color: #000080;
}

a.paginate:hover {
	background-color: #000080;
	color: #FFF;
	text-decoration: underline;
}

a.current {
	border: 1px solid #000080;
	font: bold .7em Arial,Helvetica,sans-serif;
	padding: 2px 6px 2px 6px;
	cursor: default;
	background:#000080;
	color: #FFF;
	text-decoration: none;
}

span.inactive {
	border: 1px solid #999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: .7em;
	padding: 2px 6px 2px 6px;
	color: #999;
	cursor: default;
}

a.navlinks {
	text-decoration:none;
}
a.navlinks:hover {
	color: red;
	text-decoration: underline;
}

div.jGrowl div.resultsAlerts {
	background-color: #808080;
	width: 200px;
	min-height: 0px;
	border: 1px solid #000;
}

</style>

<script type="text/javascript">	
	$(document).ready(function() {
		$('#newssalesResults').formly(); 		
	});	
</script>

	
</head>
<body>

<div style="text-align:center;padding:20px;">
	<a class="navlinks" href="index.php">Microfilm Sales List Search</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a class="navlinks" href="http://www.ohiohistory.org/collections--archives/archives-library">Library/Archives Home</a>
</div>
 	
<div class="paging-section">
	<?php echo $pages->display_pages(); ?>
</div>

<br/>

<?php
	echo( '<div style="width:100%">' );
	echo( '<form id="newssalesResults" style="margin: 0 auto; width: 500px">' );
	
	if ($nothing_to_search) {
		echo("<p>".$message."</p>");
	} else if ($no_results) { 
		echo("<p>No results.</p>");
	} else {
		
		while($row = $results->fetch(PDO::FETCH_ASSOC)) {
			
			echo( '<label for="result-'.$row['id'].'">' . $row['title'] );
			echo( '<span style="white-space: normal; font-size:80%">' );
			echo( ", " );
			echo( (trim($row['dates']) != "") ? $row['dates'] : "n/a" );
			echo( ", " );
			echo( (trim($row['city']) != "") ? $row['city'] : "na" );
			echo( ", " );
			echo( (trim($row['county']) != "") ? $row['county'] . " County" : "n/a" );
			echo( ", " );
			echo( (trim($row['location']) != "") ? $row['location'] : "n/a" );
			echo( ", " );
			echo( (trim($row['rols']) != "") ? $row['rols'] : "n/a" );
			echo( "</span>" );
			echo( '</label><br/>' );
			echo( '<hr>' );
			
		}
		
	}
	echo( '</form>' );
	echo( '<form id="searchVals" action="results.php" method="post">' );
	echo( '<input type="hidden" name="searchterm" value="'.($searchterm_exists ? $searchterm : "").'">' );
	echo( '<input type="hidden" name="county" value="'.($county_exists ? $county : "").'">' );
	echo( '<input type="hidden" name="sort" value="'.($sort_exists ? $sort : "").'">' );
	echo( '<input type="hidden" name="start" value="'.$start.'">' );
	echo( '</form>' );
	echo( '</div>' );	
?>

<div style="text-align:center;padding:6px; height: 30px;margin-top:10px;">
   	<?php echo $pages->display_pages(); ?>
    <br/>&nbsp;<br/><a href="index.php">Microfilm Sales List Search Home</a><br/>
</div> 
	
  </body>
</html>

