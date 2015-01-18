<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    header ('content-type: text/plain');

    // include 'Utils.php';
    include 'Solvers.php';
    include 'Parser.php';

    assert(Utils::asBase26('bb') == 27, 'base26 fail');
    assert(Utils::asBase26('aaa') == 0, 'base26 fail');
    assert(Utils::asBase26('bba') == '702', 'base26 fail');
    assert(Solvers::isBase26_32bit_uint('baaaaaa') == true, '< 32bit'); // 308915776
    assert(Solvers::isBase26_32bit_uint('baaaaaaa') == false, '< 32bit');  // 8031810176
    assert(Solvers::isBase26_64bit_uint('baaaaaabaaaaaa') == true, '< 64bit');
    assert(Solvers::isBase26_64bit_uint('baaaaaaabaaaaaaa') == false, '< 64bit');
    
    assert(Solvers::isBase26_divisibleBy('bb', 2) == false);
    assert(Solvers::isBase26_divisibleBy('ba', 2) == true);
    
    assert(Utils::countVowels('aeiouacdfg') == 6);
    assert(Utils::countConsonants('aeiouycdfg') == 5);
    
    testWord_Undertrained();
    testWord_Floor();
    
    function testWord_Undertrained() {
        $word = strtolower('UNDERTRAINED');
        assert(Solvers::isBase26_64bit_uint($word) == true);
        assert(Solvers::isBase26_64bitFloat($word) == false);
        assert(Solvers::vowelsBetweenPercent($word, 41.6/100, 41.7/100) == true);
        assert(Solvers::sumOfLetters_between($word, 132, 140) == true);
        assert(Solvers::sumOfLetters_divisibleBy($word, 2) == false);
        assert(Solvers::sumOfLetters_divisibleBy($word, 3) == false);
        assert(Solvers::sumOfLetters_divisibleBy($word, 5) == false);
        assert(Solvers::sumOfLetters_divisibleBy($word, 7) == true);
        assert(Solvers::startsWithVowel($word) == true);
        assert(Solvers::sha1_contains($word, '919D'));
    }
    
    function testWord_Floor() {
        $word = strtolower('FLOOR');
        assert(Solvers::isBase26_32bitFloat($word) == true);
        assert(Solvers::isBase26_64bitFloat($word) == true);
        
    }
    
    Parser::runTests();
//
//	$words = [];
//	foreach (file($root . 'words.txt', FILE_IGNORE_NEW_LINES) as $word) {
//		if (strlen($word) < 4) {
//			$words[] = $word;
//		}
//	}
//	var_export($words);

    
    print 'done';
    
?>