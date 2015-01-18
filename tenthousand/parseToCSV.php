<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	header('content-type: text/plain');

	include 'db.php';


	$csv = [];
	for ($i=0; $i<125*2; $i++) {
		$csv[] = array_fill(0, 125, '');
	}

	$count = 0;
	for($row=0; $row<125; $row++) {
		$results = DB::fetchRow($row);
		foreach ($results as $o) {
			$count++;
			$r = $o->get('row');
			$c = $o->get('col');
			$a = $o->get('solution');
			$opts = $o->get('options');
			$m = $o->get('message');

			if (empty($a) && !empty($opts)) {
				$a = $opts;
			}
			$csv[$r*2][$c] = $a;
			if ($m) {
				$csv[$r*2+1][$c] = $m;
			}
		}
	}
	print $count;

	$fp = fopen('output.csv','w');
	foreach($csv as $row) {
		fputcsv($fp, $row);
	}
	fclose($fp);
?>
