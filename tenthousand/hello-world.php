<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    header('content-type: text/plain');
    
    include 'Solvers.php';
    include 'db.php';
    
    db::testFunc();
    
    assert(Solvers::scrabblePoints('word') == 8);
    
    assert(!Solvers::caesarShiftCheck('word'));
    
    assert(Solvers::doubleLetter('wword'));
    
    assert(Solvers::twoPair('wwordd'));
    
    assert(Solvers::nonOverlappingTwoPair("Woorood"));
    assert(!Solvers::nonOverlappingTwoPair("Wooord"));
    
    assert(Solvers::distinctConsonants("worrd") == 3);
    
    assert(Solvers::anagramInWordList("nodaban"));
    
    assert(Solvers::oneLetterAnagram("nodaban"));
    
    assert(Solvers::twoLetterAnagram("nodaban"));
    
    //assert(Solvers::stateAbbvLetterCoverageOver50("akcomdff"));
    //assert(!Solvers::stateAbbvLetterCoverageOver50("abcdef"));
    
    assert(Solvers::wordLength("word") == 4);
    
    assert(Solvers::bottomQuertyCountExactly25("zxcvasdfqwertyui"));
    assert(!Solvers::bottomQuertyCountExactly25("zxcvasdfqwe"));
    
    assert(Solvers::endsWithWord("supper", "per"));
    assert(Solvers::startsWithWord("supper", "sup"));
    
    
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

echo 'Hello world from Cloud9!';

?>
