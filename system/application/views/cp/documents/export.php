<html>
<head>
<title></title>
<style type="text/css">
table, td, th { border:1px solid #000; border-collapse: collapse}
td, th {
  padding:4px;
  vertical-align: top;
}
th {
  background: #BFBFBF;
}
</style>
</head>
<body>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<h1 style="text-align:right;"><?php echo $doc->title; ?></h1>


<h4 style="font-weight:normal; text-align:right;">Prepared by the Riddle Brothers</h4>
<h4 style="font-weight:normal; text-align:right;"><?php echo date("M d, Y"); ?></h4>

<br clear="all" style="page-break-before:always" />

<?php echo $compiled; ?>
	
</body>
</html>