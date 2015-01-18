<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(-1);
	header('content-type: text/plain');

	require 'vendor/autoload.php';
	use Parse\ParseClient;

	// load your parse keys and put them here
	assert(false, 'load your parse keys (don\'t check them in)');
	ParseClient::initialize($appId, $restKey, $masterKey);

	class db {
		public static function testFunc() {

	//		$parseObject = new Parse\ParseObject("Pyramid");
	//		$parseObject->setObjectId
	//		$parseObject->set("col", 1);
	//		$parseObject->set("row", 1);
	//		$parseObject->set("solution", "word");
	//
	//		try {
	//			$parseObject->save();
	//			echo 'New object created with objectId: ' . $parseObject->getObjectId();
	//		} catch (ParseException $ex) {
	//			// Execute any logic that should take place if the save fails.
	//			// error is a ParseException object with an error code and message.
	//			echo 'Failed to create new object, with error message: ' + $ex->getMessage();
	//		}

		}

		public static function saveResult($row, $col, $word, $message) {

			$o = null;//self::findResult($row, $col);
			if (empty($o)) {
				$o = new Parse\ParseObject('Pyramid');
				$o->set('row', $row);
				$o->set('col', $col);
			}
			if (is_array($word)) {
				$o->set('options', join('|', $word));
			} else {
				$o->set('solution', $word);
			}
			if ($message) {
				$o->set('message', $message);
			}
			try {
				$o->save();
			} catch(\Parse\ParseException $ex) {
				print "Failed to save: r$row,c$col: $word (" .$ex->getMessage().")\n";
			}
		}

		static function findResult($row, $col) {
			$q = new Parse\ParseQuery('Pyramid');
			$q->equalTo('row', $row);
			$q->equalTo('col', $col);
			return $q->first();
		}

		static function fetchRow($row) {
			$q = new Parse\ParseQuery('Pyramid');
			$q->equalTo('row', $row);
			$q->limit(200);
			return $q->find();
		}
	}

?>