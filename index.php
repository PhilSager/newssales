<!doctype html public 
  "-//w3c//dtd html 4.01 transitional//en"
  "http://www.w3.org/tr/1999/rec-html401-19991224/loose.dtd">
<html> 
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
<title>Microfilm Sales List Search</title>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.2.12/jquery.jgrowl.min.css" />
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.2.12/jquery.jgrowl.min.js"></script>

<script type="text/javascript" src="formly/formly.js"></script>
<link rel="stylesheet" href="formly/formly.css" type="text/css" />

<script type="text/javascript">
	
	$(document).ready(function() {		
		$('#newssalesform').formly(); 
	});
	
	function validateForm() {

		var searchtermExists = /[A-Na-n]/.test(document.forms["newssalessearch"]["searchterm"].value);
		
		if (!searchtermExists) {
			$.jGrowl("Need something to search", { theme: 'validation', header: 'Search Error', live: 10000 });
			return false;
		}
		
	}
</script>
<style type="text/css">
body {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
}
.heading {
	text-align:center;
	font-size:120%;padding:20px;
}
.backto {
	text-align:center;
	padding:10px;
}
a.navlinks {
	text-decoration: none;
}
a.navlinks:hover {
	color: red;
	text-decoration: underline;
}
div.jGrowl div.validation {
	background-color: #808080;
	width: 200px;
	min-height: 0px;
	border: 1px solid #000;
}

</style>
</head> 
<body>
		
	<div class="backto"><a class="navlinks" href="http://www.ohiohistory.org/collections--archives/archives-library">Library/Archives Home</a></div>
	<div class="heading">Microfilm Sales List Search</div>
	
	<div style="width:100%">
	
	<form id="newssalesform" name="newssalessearch" action="results.php" method="POST" onsubmit="return validateForm()"  style="width: 500px;margin: 0 auto">
	
		<br/>Search by a title or city: <input type="search" name="searchterm" size="25" maxsize="50">

		<br/><input type="submit" value="Search" />
		
	</form>

<div style="width:500px;margin: 0 auto;">

<p>This database is a list of microfilm newspapers for which OHS owns master negative film. The public may purchase these items by the roll. You may search the list by city or newspaper title. Or browse by county:</p>	

		<a href="results.php?county=a">A</a> 
		<a href="results.php?county=b">B</a> 
		<a href="results.php?county=c">C</a> 
		<a href="results.php?county=d">D</a> 
		<a href="results.php?county=e">E</a> 
		<a href="results.php?county=f">F</a> 
		<a href="results.php?county=g">G</a> 
		<a href="results.php?county=h">H</a> 
		<a href="results.php?county=j">J</a> 
		<a href="results.php?county=k">K</a> 
		<a href="results.php?county=l">L</a> 
		<a href="results.php?county=m">M</a> 
		<a href="results.php?county=n">N</a> 
		<a href="results.php?county=o">O</a> 
		<a href="results.php?county=p">P</a> 
		<a href="results.php?county=r">R</a> 
		<a href="results.php?county=s">S</a> 
		<a href="results.php?county=t">T</a> 
		<a href="results.php?county=u">U</a> 
		<a href="results.php?county=v">V</a> 
		<a href="results.php?county=w">W</a>

<p>View or download the complete newspaper sales list as one long <a href="docs/news_sales.pdf">PDF file</a> (142k).</p>

<p>To place an order or for contact information, Download <a href="docs/order_form_microfilm.pdf">Order Form</a> (PDF; 47KB) and see Microfilm Sales List Ordering.</p>

</div>

</div>


</body>
</html>