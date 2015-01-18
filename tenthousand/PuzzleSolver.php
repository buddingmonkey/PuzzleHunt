<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
header('content-type: text/plain');

include_once 'Solvers.php';
include_once 'Parser.php';
include_once 'db.php';

set_time_limit(86400);
ini_set('max_execution_time', 86400);

if (ob_get_level() == 0)
	ob_start();

$root = 'data/';

$wordlist = file($root . 'words.txt', FILE_IGNORE_NEW_LINES);
foreach ($wordlist as $word) {
	if (empty($word)) {
		print "found empty word";
	}
}
print "wordlist: " . count($wordlist);

$count = 0;
$folders = scandir($root);
usort($folders, function($a,$b) {
	$n1 = -1;
	$n2 = -1;
	if (preg_match('/\d+/', $a, $m)) {
		$n1 = intval($m[0]);
	}
	if (preg_match('/\d+/', $b, $m)) {
		$n2 = intval($m[0]);
	}
	return $n1 < $n2 ? -1 : ($n1 == $n2 ? 0 : 1);
});
//sort($folders, SORT_ASC);
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
for ($i=count($folders)-1 - $offset; $i>=0; $i-=$step) {
	$item = $folders[$i];
	$dir = $root . $item . '/';
	if (is_dir($dir) && preg_match('/^row[0-9]+$/', $item)) {
		foreach (scandir($dir) as $file) {
			if (preg_match('/^row([0-9]+)_col([0-9]+)\.txt$/', $file, $matches)) {
				$row = $matches[1];
				$col = $matches[2];
				processFile($dir . $file, $row, $col);
				$count++;
//				if ($count > 2)
//					break;
			}
		}
	}
//	if ($count > 2)
//		break;
}

function processFile($file, $row, $col) {
	global $wordlist;
	print "Processing file: ".basename($file)."\n";
	$curWords = $wordlist;
	$missingSolvers = 0;
	$lines = file($file, FILE_IGNORE_NEW_LINES);

	for ($i=0; $i<count($lines); $i++) {
		$line = $lines[$i];
		if (!empty($line)) {
			$hasSolver = null;
			$curWords = Parser::evaluateQuestion($line, $curWords, $hasSolver);
			if (!$hasSolver) {
				$missingSolvers++;
			}
		} else {
			$i++;
			break;
		}
	}
	$message = '';
	if ($i < count($lines) && $lines[$i]) {
		$message = $lines[$i];
	}
	print "$file solution [-$missingSolvers solvers] (".count($curWords).") ".(count($curWords) < 5 ? join('|', $curWords) : '')."\n";
	if (count($curWords) == 1) {
		DB::saveResult((int)$row, (int)$col, $curWords[0], $message);
	}
	else if (count($curWords) < 5) {
		DB::saveResult((int)$row, (int)$col, $curWords, $message);
	}
	print "\n";
	ob_flush();
	flush();
	usleep(10000);
}

?>