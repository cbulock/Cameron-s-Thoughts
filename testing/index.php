<!DOCTYPE html>
<html>
	<head>
		<title>API Tool</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../js/jquery.ct.js"></script>
		<script type="text/javascript" src="tool.js"></script>
		<link rel="stylesheet" type="text/css" href="tool.css" />
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/ui-lightness/jquery-ui.css" />
	</head>
	<body>
		<h1>API Tool</h1>
		<div id="tabs">
			<ul>
				<li><a href="#tab-1">Query</a></li>
				<li><a href="#tab-2">Docs</a></li>
			</ul>
			<div id="tab-1">
				<form action="api.php" method="post" id='form'>
					<fieldset>
						<legend>Method</legend>
						<select name='method' id='method'>
							<option></option>
						</select>
					</fieldset>
					<fieldset id='args'>
						<legend>Arguments</legend>
						<button id="add_args">Add</button>
					</fieldset>
					<button type="submit" id="send">Send</button>
				</form>
				<textarea id="results" rows="20" cols="75"></textarea>
			</div>
			<div id="tab-2">
				<iframe src="http://cbulock.com/ct3/api"></iframe>
			</div>
		</div>
	</body>
</html>
