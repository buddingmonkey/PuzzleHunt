<?php
    include 'Utils.php';
    
    class Solvers {

		public static function scrabblePointsBetween($word, $low, $high) {
			$score = self::scrabblePoints($word);
			return $score >= $low && $score <= $high;
		}

        public static function scrabblePointsExactly($word, $score) {
            return self::scrabblePoints($word) == $score;
        }
        
        public static function scrabblePoints($word){
            $scrabbleArray =  array(
                97 => 1,
                98 => 3,
                99 => 3,
                100 => 2,
                101 => 1,
                102 => 4,
                103 => 2,
                104 => 4,
                105 => 1,
                106 => 8,
                107 => 5,
                108 => 1,
                109 => 3,
                110 => 1,
                111 => 1,
                112 => 3,
                113 => 10,
                114 => 1,
                115 => 1,
                116 => 1,
                117 => 1,
                118 => 4,
                119 => 4,
                120 => 8,
                121 => 4,
                122 => 10,
            );
            
            $wordLen = strlen($word);
            $id = "";
            $scrabblePoints = 0;
            for( $i = 0; $i <= $wordLen - 1; $i++ ) {
                $char = substr( $word, $i, 1 );
                $scrabblePoints += $scrabbleArray[ord(strtolower($char))];
            }
            return $scrabblePoints;
        }
        
        public static function caesarShiftCheck($word){
            $lines = file('Words.txt', FILE_IGNORE_NEW_LINES);
            $shiftWord = Utils::cipherShift($word, 3);
            foreach ($lines as $w){
                if ($w == $shiftWord){
                    return true;
                }
            }
            return false;
        }
        
        public static function oneLetterAnagram($word){
            $letters = range('a', 'z');
            foreach ($letters as $letter){
                if (Utils::hasAnagram($word.$letter) > 0) {
                    return true;
                }
            }
            return false;
        }
        
        public static function twoLetterAnagram($word){
            $letters = range('a', 'z');
            foreach ($letters as $a){
                foreach ($letters as $b){
                    if (Utils::hasAnagram($word.$a.$b) > 0) {
                        return true;
                    }
                }
            }
            return false;
        }
        
        public static function hasMultipleDiffDoubledLetters($word) {
            $doubled = [];
            $len = strlen($word);
            for ($i=1; $i<$len; $i++) {
                if ($word[$i] == $word[$i-1]) {
                    $doubled[$word[$i]] = 1;
                }
            }
            return count($doubled) > 1;
        }
        
        public static function hasMultipleSameDoubledLetters($word) {
            $doubled = [];
            $len = strlen($word);
            for ($i=1; $i<$len; $i++) {
                if ($word[$i] == $word[$i-1]) {
                    if (isset($doubled[$word[$i]]))
                        return true;
                    else
                        $doubled[$word[$i]] = 1;
                    $i++; // ignore 3 in a row
                }
            }
            return false;
        }
        
        public static function hasDoubledLetters($word) {
            $len = strlen($word);
            for ($i=1; $i<$len; $i++) {
                if ($word[$i] == $word[$i-1]) {
                    return true;
                }
            }
            return false;
        }
        
        public static function doubleLetter($word){
            return Utils::countDuplicates($word) > 0;
        }
        
        public static function twoPair($word){
            return Utils::countDuplicates($word) > 1;
        }
        
        public static function nonOverlappingTwoPair($word){
            $letterDict = Utils::letterDuplicatesArray($word);
            
            foreach ($letterDict as $letter){
                if ($letter > 3) return true;
            }
            
            return false;
        }


		public static function distinctBetween($word, $type, $low, $high){
			$count = self::distinct($word, $type);
			return $count >= $low && $count <= $high;
		}

		public static function distinctEquals($word, $type, $count){
			return self::distinct($word, $type) == $count;
		}

		public static function distinct($word, $type){
			$letterDict = Utils::letterDuplicatesArray($word);

			$count = 0;

			foreach ($letterDict as $key => $value){
				if ($type == 'vowels') {
					if (Utils::isVowel($key)){
						$count++;
					}
				} else if ($type == 'consonants') {
					if (!Utils::isVowel($key)){
						$count++;
					}
				} else {
					$count++;
				}
			}

			return $count;
		}


		public static function distinctConsonantsExactly($word, $count){
            return self::distinctConsonants($word) == $count;
        }

        public static function distinctConsonants($word){
            $letterDict = Utils::letterDuplicatesArray($word);
            
            $consonants = 0;
            
            foreach ($letterDict as $key => $value){
                if (!Utils::isVowel($key)){
                    $consonants++;
                }
            }
            
            return $consonants;
        }

        public static function distinctVowelsExactly($word, $count){
            return self::distinctVowels($word) == $count;
        }
  
        public static function distinctVowels($word){
            $letterDict = Utils::letterDuplicatesArray($word);
            
            $count = 0;
            
            foreach ($letterDict as $key => $value){
                if (Utils::isVowel($key)){
                    $count++;
                }
            }
            
            return $count;
        }
        
        public static function distinctLettersEquals($word, $count) {
            $dict = Utils::letterDuplicatesArray($word);
            return count($dict) == $count;
        }

		public static function mostCommonCharactersExactWhole($word, $type, $count) {
			return (self::mostCommonCharacters($word, $type) == $count);
		}

		public static function mostCommonCharactersExact($word, $type, $percent) {
			return (self::mostCommonCharacters($word, $type) / strlen($word) == $percent);
		}

		public static function mostCommonCharactersBetweenWhole($word, $type, $low, $high) {
			$count = self::mostCommonCharacters($word, $type);
			return $count >= $low && $count <= $high;
		}

		public static function mostCommonCharactersBetween($word, $type, $low, $high) {
			$percent = (self::mostCommonCharacters($word, $type) / strlen($word));
			return $percent >= $low && $percent <= $high;
		}

		public static function mostCommonCharacters($word, $type) {
			$letterDict = Utils::letterDuplicatesArray($word);
			$max = 0;
			foreach ($letterDict as $key => $value){
				if ($type == 'vowel'){
					if (Utils::isVowel($key))
						$max = max($max, $value);
				} else if ($type == 'consonant'){
					if (!Utils::isVowel($key))
						$max = max($max, $value);
				} else {
					$max = max($max, $value);
				}
			}
			return $max;
		}


		public static function anagramInWordList($word){
            return Utils::hasAnagram($word) > 1;        // don't count itself
        }

		public static function stateAbbvLetterCoverageExact($word, $percent){
			return Utils::stateNameNoOverlaps($word) / strlen($word) == $percent;
		}

		public static function stateAbbvLetterCoverageBetween($word, $low, $high){
			$percent =  Utils::stateNameNoOverlaps($word) / strlen($word);
			return $percent >= $low && $percent <= $high;
		}

		public static function stateAbbvLetterCoverageExactWhole($word, $count){
			return Utils::stateNameNoOverlaps($word) == $count;
		}

		public static function stateAbbvLetterCoverageBetweenWhole($word, $low, $high){
			$count =  Utils::stateNameNoOverlaps($word);
			return $count >= $low && $count <= $high;
		}

		public static function countryAbbvLetterCoverageExact($word, $percent){
			return Utils::countryNameNoOverlaps($word) / strlen($word) == $percent;
		}

		public static function countryAbbvLetterCoverageBetween($word, $low, $high){
			$percent =  Utils::countryNameNoOverlaps($word) / strlen($word);
			return $percent >= $low && $percent <= $high;
		}

		public static function countryAbbvLetterCoverageExactWhole($word, $count){
			return Utils::countryNameNoOverlaps($word) == $count;
		}

		public static function countryAbbvLetterCoverageBetweenWhole($word, $low, $high){
			$count =  Utils::countryNameNoOverlaps($word);
			return $count >= $low && $count <= $high;
		}

		public static function elementsAbbvLetterCoverageExact($word, $percent){
			return Utils::elementsNameNoOverlaps($word) / strlen($word) == $percent;
		}

		public static function elementsAbbvLetterCoverageBetween($word, $low, $high){
			$percent =  Utils::elementsNameNoOverlaps($word) / strlen($word);
			return $percent >= $low && $percent <= $high;
		}

		public static function smallWordsCoverageExact($word, $percent){
			return Utils::smallWordsNoOverlaps($word) / strlen($word) == $percent;
		}

		public static function smallWordsCoverageBetween($word, $low, $high){
			$percent =  Utils::smallWordsNoOverlaps($word) / strlen($word);
			return $percent >= $low && $percent <= $high;
		}

		public static function smallWordsCoverageExactWhole($word, $count){
			return Utils::smallWordsNoOverlaps($word) == $count;
		}

		public static function smallWordsCoverageBetweenWhole($word, $low, $high){
			$count =  Utils::smallWordsNoOverlaps($word);
			return $count >= $low && $count <= $high;
		}


		public static function wordLength($word, $count){
            return strlen($word) == $count;
        }
        
        public static function wordLengthBetween($word, $low, $high){
            return strlen($word) >= $low && strlen($word) <= $high;
        }

		public static function quertyCountExactly($word, $row, $percent){
			$qwertyCount = Utils::quertyCount($word, $row);
			return $qwertyCount / strlen($word) == $percent;
		}

		public static function quertyCountExactWhole($word, $row, $count){
			$qwertyCount = Utils::quertyCount($word, $row);
			return $qwertyCount == $count;
		}

		public static function quertyCountBetween($word, $row, $low, $high) {
			$qwertyCount = Utils::quertyCount($word, $row);
			$percent = $qwertyCount / strlen($word);
			return $percent >= $low && $percent <= $high;
		}

		public static function quertyCountBetweenWhole($word, $row, $low, $high) {
			$qwertyCount = Utils::quertyCount($word, $row);
			return $qwertyCount >= $low && $qwertyCount <= $high;
		}

        public static function endsWith($word, $end){
            return (substr($word, -strlen($end)) == strtolower($end));
        }
        
        public static function sha1_endsWith($word, $match) {
            return substr_compare(sha1($word), strtolower($match), -strlen($match)) == 0;
        }

        public static function sha1_startsWith($word, $match) {
            return substr_compare(sha1($word), strtolower($match), 0, strlen($match), true) == 0;
        }
        
        public static function sha1_contains($word, $match) {
            return substr_count(sha1($word), strtolower($match)) > 0;
        }
        
        public static function startsWith($word, $match) {
            return substr_compare($word, strtolower($match), 0, strlen($match), true) == 0;
        }

        public static function contains($word, $match) {
            return substr_count($word, strtolower($match)) > 0;
        }

        public static function endsWithVowel($word) {
            return Utils::isVowel($word[0]);
        }
        
        public static function startsWithVowel($word) {
            return Utils::isVowel($word[0]);
        }
        
        public static function sumOfLetters_divisibleBy($word, $divisor) {
            $sum = Utils::sumLetterValues($word);
            return $sum % $divisor == 0;
        }
        
        public static function sumOfLetters($word, $sum) {
            return Utils::sumLetterValues($word) == $sum;
        }
        
        public static function sumOfLetters_between($word, $low, $high) {
            $sum = Utils::sumLetterValues($word);
            return $sum >= $low && $sum <= $high;
        }

		public static function vowelsBetweenPercent($word, $low, $high) {
			$p = Utils::countVowels($word) / strlen($word);
			return $p >= $low && $p <= $high;
		}
		public static function vowelsExactPercent($word, $percent) {
			return Utils::countVowels($word) / strlen($word) == $percent;
		}

		public static function vowelsBetweenWhole($word, $low, $high) {
			$count = Utils::countVowels($word);
			return $count >= $low && $count <= $high;
		}
		public static function vowelsExactWhole($word, $count) {
			return Utils::countVowels($word) == $count;
		}

		public static function consonantsBetweenPercent($word, $low, $high) {
			$p = Utils::countConsonants($word) / strlen($word);
			return $p >= $low && $p <= $high;
		}

		public static function consonantsExactPercent($word, $percent) {
			return Utils::countConsonants($word) / strlen($word) == $percent;
		}

        public static function isBase26_divisibleBy($word, $divisor) {
            return bcmod(Utils::asBase26($word), $divisor) == '0';
        }
        
        public static function isBase26_64bitFloat($word) {
            $val = Utils::asBase26($word);
            while (bcmod($val, '2') == 0) {
                $val = bcdiv($val, '2');
            }

            // frac < 2^53 precision
            return bccomp($val, '9007199254740992') < 0;
        }
        
        public static function isBase26_32bitFloat($word) {
            $val = Utils::asBase26($word);
            while (bcmod($val, '2') == 0) {
                $val = bcdiv($val, '2');
            }

            // frac < 2^24 precision
            return bccomp($val, '16777216') < 0;
        }
        
        public static function isBase26_32bit_uint($word) {
            return bccomp(Utils::asBase26($word), '4294967296') < 0;
        }
        
        public static function isBase26_64bit_uint($word) {
            return bccomp(Utils::asBase26($word), '18446744073709551616') < 0;
        }
        
    }
?>