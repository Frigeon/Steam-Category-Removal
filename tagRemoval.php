<html>
	<head>
		<title>Steam Category Removal Tool</title>
	</head>
	<body>
<?php 
if(!isset($_GET['inputFile']) || empty($_GET['inputFile'])) {
	$_GET['inputFile'] = 'C:\Program Files (x86)\Steam\userdata\\';
}

if(!isset($_GET['categoryFile']) || empty($_GET['categoryFile'])) {
	$_GET['categoryFile'] = '\7\remote\sharedconfig.vdf';
}

if(isset($_GET['userNumber'])) {
	$userNumber = $_GET['userNumber'];
	$categoryFile = $_GET['categoryFile'];
	$inputFile = $_GET['inputFile'];
	$inputfile = $inputFile . $userNumber . $categoryFile;
	$handle = fopen($inputfile, 'r');
	$lines = array();
	if($handle) {
		$tagLine = false;
		$tagBrace = false;
		$tagClose = false;
		$count = 0;
		while(($line = fgets($handle)) !== false) {
			$count++;
			if(stristr($line, "tags")) {
				$tagLine = true;
			}

			if(stristr($line, "{") && $tagLine) {
				$tagBrace = true;
			}

			if(stristr($line, "}") && $tagLine &  $tagBrace) {
				$tagClose = true;
			}

			if(!$tagLine && !$tagBrace && !$tagClose) {
				$lines[] = $line;
				echo '[*] Read Line ' . $count . ': ' . $line . ' into Memory.' . "<br />";
			} else {
				echo '[x] Removed Line ' . $count . ': ' . $line . ' from read data.' . "<br />";
			}

			if($tagClose) {
				$tagLine = false;
				$tagBrace = false;
				$tagClose = false;
			}
		}

		echo '<br /><br />';

		fclose($handle);
		$handle = fopen($inputfile, 'w');
		$count = 0;
		foreach($lines as $line) {
			$count++;
			fwrite($handle, $line);
			echo '[!] Wrote Line ' . $count . ': ' . $line . ' to file.' . "<br />";
		}
		fclose($handle);

	} else {
		echo 'Error! Error! Unable to open file!' . "\n";
		echo '<pre>';
		print_r($userNumber);
		print_r($inputFile);
		print_r($categoryFile);
		print_r($inputfile);
	}
} else { ?>
	<form method="GET">
		<h2>Input Steam User Number (number under the userdata folder that you wish to remove categories from) <input type="number" name="userNumber" placeholder="12345678"></h2>
		<h2>Enter Steam Directory (Default is "C:\Program Files (x86)\Staem\userdate\") <input type="text" name="inputFile" placeholder="C:\Program Files (x86)\Steam\userdata\"></h2>
		<h2>Enter file location within given steam directory (Default is "\7\remote\sharedconfig.vdf") <input type="text" name="categoryFile" placeholder="\7\remote\sharedconfig.vdf"></h2>
		<input type="submit" value="Clear Categories">
	</form>
<?php
} ?>
	</body>
</html>