<?php
    include_once 'Solvers.php';
    
    class Parser {
        public static function runTests() {
            // return self::test();
            
            $dir = 'examples/';
            $count = 0;
            foreach(scandir($dir) as $file) {
                if ($file == '.' || $file == '..') continue;
                print $file." -> ";
                // if ($count++ < 1) continue;
                $lines = file($dir.$file, FILE_IGNORE_NEW_LINES);
                if (!preg_match('/.*? (\w+):$/', array_shift($lines), $matches))
                    continue;
                $word = strtolower($matches[1]);
                print $word . "\n";
                foreach ($lines as $line) {
                    if (empty($line)) continue;
                    
                    if (preg_match('/^(.*?): NO$/', $line, $matches)) {
                        // evaluates to false
                        $result = self::parseAndEvaluateQuestion($matches[1], $word);
                        assert($result == false || $result === null, $matches[1]);
                    } else if (preg_match('/^(.*?): YES$/', $line, $matches)) {
                        $result = self::parseAndEvaluateQuestion($matches[1], $word);
                        assert($result == true || $result === null, $matches[1]);
                    } else {
                        $result = self::parseAndEvaluateQuestion($line, $word);
                        assert($result == true || $result === null, $line);
                    }
                }
                // just one for now
                // break;
            }
        }
        
        public static function test() {
            // print self::parseAndEvaluateQuestion('Letters located in the top row on a QWERTY keyboard: exactly 75.0% of the letters', 'wyes');
            print "commandeering: " . Utils::asBase26('commandeering') . "\n";
            Solvers::isBase26_64bitFloat('commandeering');
        }
        
        // should be cleaned up to understand the between, exactly syntax instead of having 4 copies of each
        private static $paths = [
            '/^Word interpreted as a base 26 number \(A=0, B=1, etc\) is representable as an unsigned 64-bit integer$/', ['Solvers', 'isBase26_64bit_uint'],
            '/^Word interpreted as a base 26 number \(A=0, B=1, etc\) is representable as an unsigned 32-bit integer$/', ['Solvers', 'isBase26_32bit_uint'],
            '/^Word interpreted as a base 26 number \(A=0, B=1, etc\) is exactly representable in IEEE 754 double-precision floating point format$/', ['Solvers', 'isBase26_64bitFloat'],
            '/^Word interpreted as a base 26 number \(A=0, B=1, etc\) is exactly representable in IEEE 754 single-precision floating point format$/', ['Solvers', 'isBase26_32bitFloat'],
            '/^Word interpreted as a base 26 number \(A=0, B=1, etc\) is divisible by ([0-9]+)$/', ['Solvers', 'isBase26_divisibleBy'],
            '/^Vowels: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'vowelsBetweenPercent'],
            '/^Vowels: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'vowelsExactPercent'],
			'/^Vowels: ([0-9]+)$/', ['Solvers', 'vowelsExactWhole'],
			'/^Vowels: between ([0-9]+) and ([0-9]+) \(inclusive\)$/', ['Solvers', 'vowelsBetweenWhole'],
            '/^Sum of letters \(A=1, B=2, etc\): between ([0-9]+) and ([0-9]+) \(inclusive\)$/', ['Solvers', 'sumOfLetters_between'],
            '/^Sum of letters \(A=1, B=2, etc\) is divisible by ([0-9]+)$/', ['Solvers', 'sumOfLetters_divisibleBy'],
            '/^Sum of letters \(A=1, B=2, etc\): ([0-9]+)$/', ['Solvers', 'sumOfLetters'],
            '/^Starts with a vowel$/', ['Solvers', 'startsWithVowel'],
            '/^SHA-1 hash of lowercased word, expressed in hexadecimal, contains: ([0-9a-zA-Z]+)$/', ['Solvers', 'sha1_contains'],
            '/^SHA-1 hash of lowercased word, expressed in hexadecimal, starts with: ([0-9a-zA-Z]+)$/', ['Solvers', 'sha1_startsWith'],
            '/^SHA-1 hash of lowercased word, expressed in hexadecimal, ends with: ([0-9a-zA-Z]+)$/', ['Solvers', 'sha1_endsWith'],
			'/^Letters located in the (top|bottom|middle) row on a QWERTY keyboard: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'quertyCountExactly'],
			'/^Letters located in the (top|bottom|middle) row on a QWERTY keyboard: ([0-9]+)$/', ['Solvers', 'quertyCountExactWhole'],
			'/^Letters located in the (top|bottom|middle) row on a QWERTY keyboard: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'quertyCountBetween'],
			'/^Letters located in the (top|bottom|middle) row on a QWERTY keyboard: between ([0-9]+) and ([0-9]+) \(inclusive\)$/', ['Solvers', 'quertyCountBetweenWhole'],
			'/^Base Scrabble score: ([0-9]+) points$/', ['Solvers', 'scrabblePointsExactly'],
			'/^Base Scrabble score: between ([0-9]+) and ([0-9]+) \(inclusive\) points$/', ['Solvers', 'scrabblePointsBetween'],
            '/^Can be Caesar shifted to produce another word in the word list$/', ['Solvers', 'caesarShiftCheck'],
            '/^Can be combined with one additional letter to produce an anagram of something in the word list$/', ['Solvers', 'oneLetterAnagram'],
            '/^Can be combined with two additional letters to produce an anagram of something in the word list$/', ['Solvers', 'twoLetterAnagram'],
            '/^Contains: (\w+)$/', ['Solvers', 'contains'],
            '/^Starts with: (\w+)$/', ['Solvers', 'startsWith'],
            '/^Ends with: (\w+)$/', ['Solvers', 'endsWith'],
            '/^Length: ([0-9]+) letters$/', ['Solvers', 'wordLength'],
            '/^Length: between ([0-9]+) and ([0-9]+) \(inclusive\) letters$/', ['Solvers', 'wordLengthBetween'],
            '/^Contains at least one doubled letter$/', ['Solvers', 'hasDoubledLetters'],
            '/^Contains at least two different doubled letters$/', ['Solvers', 'hasMultipleDiffDoubledLetters'],
            '/^Contains at least two nonoverlapping occurrences of the same doubled letter$/', ['Solvers', 'hasMultipleSameDoubledLetters'],
			'/^Distinct (letters|vowels|consonants): ([0-9]+)$/', ['Solvers', 'distinctEquals'],
			'/^Distinct (letters|vowels|consonants): between ([0-9]+) and ([0-9]+) \(inclusive\)$/', ['Solvers', 'distinctBetween'],
            '/^Has at least one anagram that is also in the word list$/', ['Solvers', 'anagramInWordList'],
			'/^If you marked nonoverlapping US state postal abbreviations, you could mark at most: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'stateAbbvLetterCoverageBetween'],
			'/^If you marked nonoverlapping US state postal abbreviations, you could mark at most: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'stateAbbvLetterCoverageExact'],
			'/^If you marked nonoverlapping US state postal abbreviations, you could mark at most: between ([0-9]+) and ([0-9]+) \(inclusive\) letters?$/', ['Solvers', 'stateAbbvLetterCoverageBetweenWhole'],
			'/^If you marked nonoverlapping US state postal abbreviations, you could mark at most: ([0-9]+) letters?$/', ['Solvers', 'stateAbbvLetterCoverageExactWhole'],
			'/^If you marked nonoverlapping officially-assigned ISO 3166-1 alpha-2 country codes, you could mark at most: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'countryAbbvLetterCoverageBetween'],
			'/^If you marked nonoverlapping officially-assigned ISO 3166-1 alpha-2 country codes, you could mark at most: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'countryAbbvLetterCoverageExact'],
			'/^If you marked nonoverlapping officially-assigned ISO 3166-1 alpha-2 country codes, you could mark at most: between ([0-9]) and ([0-9]+) \(inclusive\) letters?$/', ['Solvers', 'countryAbbvLetterCoverageBetweenWhole'],
			'/^If you marked nonoverlapping officially-assigned ISO 3166-1 alpha-2 country codes, you could mark at most: ([0-9]+) letters?$/', ['Solvers', 'countryAbbvLetterCoverageExactWhole'],
// broken
//			'/^If you marked nonoverlapping chemical element symbols \(atomic number 112 or below\), you could mark at most: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'elementsAbbvLetterCoverageBetween'],
//			'/^If you marked nonoverlapping chemical element symbols \(atomic number 112 or below\), you could mark at most: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'elementsAbbvLetterCoverageExact'],
			'/^Most common (letter|vowel|consonant)\(s\) each account\(s\) for: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'mostCommonCharactersExact'],
			'/^Most common (letter|vowel|consonant)\(s\) each account\(s\) for: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'mostCommonCharactersBetween'],
			'/^Most common (letter|vowel|consonant)\(s\) each appear\(s\): ([0-9]+) times?$/', ['Solvers', 'mostCommonCharactersExactWhole'],
			'/^Most common (letter|vowel|consonant)\(s\) each appear\(s\): between ([0-9]+) and ([0-9]+) \(inclusive\) times?$/', ['Solvers', 'mostCommonCharactersBetweenWhole'],
// broken
//			'/^If you marked nonoverlapping occurrences of words in the word list that are 3 or fewer letters long, you could mark at most: between ([0-9\.]+%) and ([0-9\.]+%) \(inclusive\) of the letters$/', ['Solvers', 'smallWordsCoverageBetween'],
//			'/^If you marked nonoverlapping occurrences of words in the word list that are 3 or fewer letters long, you could mark at most: exactly ([0-9\.]+%) of the letters$/', ['Solvers', 'smallWordsCoverageExact'],
//			'/^If you marked nonoverlapping occurrences of words in the word list that are 3 or fewer letters long, you could mark at most: between ([0-9]) and ([0-9]+) \(inclusive\) letters?$/', ['Solvers', 'smallWordsCoverageBetweenWhole'],
//			'/^If you marked nonoverlapping occurrences of words in the word list that are 3 or fewer letters long, you could mark at most: ([0-9]+) letters?$/', ['Solvers', 'smallWordsCoverageExactWhole'],
//			'/^$/', ['Solvers', ''],

        ];
        public static function parseAndEvaluateQuestion($question, $word) {
            for ($i=0; $i<count(self::$paths); $i+=2) {
                $path = self::$paths[$i];
                if (is_array($path)) continue;
                if (preg_match($path, $question, $matches)) {
                    $matches[0] = $word;
                    for ($j=1; $j<count($matches); $j++) {
                        $matches[$j] = self::cleanInput($matches[$j]);
                    }
                    // print $question;
                    // print_r($matches);
                    return forward_static_call_array(self::$paths[$i+1], $matches);
                }
            }
            print "no solver found for: \"".$question."\"\n";
            return null;
        }

        public static function parseQuestion($question) {
            for ($i=0; $i<count(self::$paths); $i+=2) {
                $path = self::$paths[$i];
                if (is_array($path)) continue;
                if (preg_match($path, $question, $matches)) {
                    $matches[0] = null;
                    for ($j=0; $j<count($matches); $j++) {
                        $matches[$j] = self::cleanInput($matches[$j]);
                    }
                    
                    return [self::$paths[$i+1], $matches];
                }
            }
            // print "no solver found for: \"".$question."\"\n";
            return null;
        }
        
        public static function evaluateQuestion($question, $words, &$hasSolver) {
			// check for yes/no at end of question
			$requiredResult = true;
			if (preg_match('/: (NO|YES)$/', $question, $matches)) {
				if ($matches[1] == "NO") {
					$requiredResult = false;
				}
				$question = substr($question, 0, -strlen($matches[0]));
			}

            list($solver, $params) = self::parseQuestion($question);
			if ($solver === null) {
				$hasSolver = false;
				return $words;
			}
			$hasSolver = true;
            $outList = [];
            foreach ($words as $word) {
                $params[0] = $word;
                if (forward_static_call_array($solver, $params) == $requiredResult)
                    $outList[] = $word;
            }
            return $outList;
        }
        
        public static function cleanInput($val) {
            if (strval(intval($val)) === $val) {
                return intval($val);
            }
            if (preg_match('/^[0-9]+(\.[0-9]+)?%$/', $val)) {
                return floatval($val) / 100;
            }
            if (preg_match('/^[0-9]+(\.[0-9]+)?$/', $val)) {
                return floatval($val);
            }
            return strtolower($val);
        }
    }
?>