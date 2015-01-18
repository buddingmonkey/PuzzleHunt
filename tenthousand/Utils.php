<?php
    class Utils {
        public static function asBase26($word) {
            $word = strrev($word);
            $value = '0';
            $mul = '1';
            for ($i=0; $i<strlen($word); $i++) {
                // A=0, B=1, ...
                $value = bcadd($value, bcmul(ord($word[$i])-ord('a'), $mul));
                $mul = bcmul($mul, '26');
            }
            return $value;
        }
        
        public static function cipherShift($string, $distance) {
            $distance = $distance % 26;
            $string = strtolower($string);
            $result = array();
            $characters = str_split($string);
        
            if ($distance < 0) {
                $distance += 26;
            }
        
            foreach ($characters as $idx => $char) {
                $result[$idx] = chr(97 + (ord($char) - 97 + $distance) % 26);
            }   
        
            return join("", $result);
        }
        
        public static $anagramKeys = [];
        public static function buildAnagramList() {
            if (count(self::$anagramKeys) > 0) return;
            foreach(file('Words.txt', FILE_IGNORE_NEW_LINES) as $word) {
                $key = self::anagramKey($word);
                if (isset(self::$anagramKeys[$key]))
                    self::$anagramKeys[$key] = self::$anagramKeys[$key]+1;
                else
                    self::$anagramKeys[$key] = 1;
            }
        }
        
        public static function anagramKey($word) {
            $w = str_split($word);
            sort($w);
            return join('', $w);
        }
        
        public static function hasAnagram($word) {
            $key = self::anagramKey($word);
            if (isset(self::$anagramKeys[$key]))
                return self::$anagramKeys[$key];
            return 0;
        }
        
        // I will never escape this horror
        public static function countDuplicates($word){
            $letterDict = Utils::letterDuplicatesArray($word);
            $duplicateLetters = 0;
            foreach ($letterDict as $letter){
                if ($letter > 1){
                    $duplicateLetters ++;
                }
            }
            return $duplicateLetters;
        }
        
        public static function letterDuplicatesArray($word){
            $letterDict = array();
            
            for ($i=0; $i<strlen($word); $i++) {
                if (array_key_exists($word[$i], $letterDict)){
                    $letterDict[$word[$i]]++;
                } else {
                    $letterDict[$word[$i]] = 1;
                }
            }
            
            return $letterDict;
        }
        
        public static $vowels = [
                'a' => 1,
                'e' => 1,
                'i' => 1,
                'o' => 1,
                'u' => 1
            ];
            
            public static $querty = [
                'top' => [
                    'q' => 1,
                    'w' => 1,
                    'e' => 1,
                    'r' => 1,
                    't' => 1,
                    'y' => 1,
                    'u' => 1,
                    'i' => 1,
                    'o' => 1,
                    'p' => 1
                ],
                'middle' => [
                    'a' => 1,
                    's' => 1,
                    'd' => 1,
                    'f' => 1,
                    'g' => 1,
                    'h' => 1,
                    'j' => 1,
                    'k' => 1,
                    'l' => 1
                ],
                'bottom' => [
                    'z' => 1,
                    'x' => 1,
                    'c' => 1,
                    'v' => 1,
                    'b' => 1,
                    'n' => 1,
                    'm' => 1
                ]

            ];

        public static function countVowels($word) {
            $count = 0;
            for ($i=0; $i<strlen($word); $i++) {
                if (isset(self::$vowels[$word[$i]]))
                    $count++;
            }
            return $count;
        }
        
        public static function quertyCount($word, $row){
            $count = 0;
            
            for ($i = 0; $i < strlen($word); $i++){
                if (array_key_exists($word[$i], self::$querty[$row])){
                    $count++;
                }
            }

            return $count;
        }
        
        public static function countConsonants($word) {
            return strlen($word) - self::countVowels($word);
        }
        
        public static function isVowel($letter) {
            return array_key_exists($letter, self::$vowels);
        }
        
        public static function sumLetterValues($word) {
            $sum = 0;
            for ($i=0; $i<strlen($word); $i++) {
                // A=1, B=2, ...
                $sum += ord($word[$i]) - ord('a') + 1;
            }
            return $sum;
        }

		public static function stateNameNoOverlaps($word){
			return self::containsTextNoOverlaps($word, self::$STATES);
		}

		public static function countryNameNoOverlaps($word){
			return self::containsTextNoOverlaps($word, self::$COUNTRY_CODES);
		}

		public static function elementsNameNoOverlaps($word){
			return self::containsTextNoOverlaps($word, self::$ELEMENTS);
		}

		public static function smallWordsNoOverlaps($word){
			return self::containsTextNoOverlaps3($word, self::$SMALL_WORDS);
		}

	// !!! does not work unless all are the same length, has to be recursive otherwise
	public static function containsTextNoOverlaps($word, $dict){
		$count = 0;
		$len = strlen($word);
		for ($i = 0; $i < $len; $i++){
			$a = array_key_exists($word[$i], $dict);
			$b = $i+1 < $len && array_key_exists(substr($word, $i, 2), $dict);
			if ($a) {
				$count++;
			} else if ($b){
				$count+=2;
				$i++; // skip for overlap
			}
		}

		return $count;
	}

	// !!! does not work, has to be recursive
	public static function containsTextNoOverlaps3($word, $dict){
		$count = 0;
		$len = strlen($word)-1;
		for ($i = 0; $i < $len; $i ++){
			$a = array_key_exists(substr($word, $i, 2), $dict);
			$b = $i+2 < $len && array_key_exists(substr($word, $i+2, 2), $dict);
			$c = $i+3 < $len && array_key_exists(substr($word, $i+2, 3), $dict);
			$d = $i+2 < $len && array_key_exists(substr($word, $i+1, 3), $dict);
			$e = $i+1 < $len && array_key_exists(substr($word, $i, 3), $dict);
			$f = $i+3 < $len && array_key_exists(substr($word, $i+3, 2), $dict);
			$g = $i+4 < $len && array_key_exists(substr($word, $i+3, 3), $dict);
			if ($a && ($b||$c) || (!$f&&!$g) || (!$e && (!$d || ($f || $g)))) {
				$count+=2;
				$i++; // skip for overlap
			} else if ($e && !$d || ($f || $g)){
				$count+=3;
				$i+=2; // skip for overlap
			}
		}

		return $count;
	}

        public static $STATES = array(
			'al'=>1,'ak'=>1,'az'=>1,'ar'=>1,'ca'=>1,'co'=>1,'ct'=>1,'de'=>1,'dc'=>1,'fl'=>1,'ga'=>1,'hi'=>1,'id'=>1,'il'=>1,'in'=>1,'ia'=>1,'ks'=>1,'ky'=>1,'la'=>1,'me'=>1,'md'=>1,'ma'=>1,'mi'=>1,'mn'=>1,'ms'=>1,'mo'=>1,'mt'=>1,'ne'=>1,
			'nv'=>1,'nh'=>1,'nj'=>1,'nm'=>1,'ny'=>1,'nc'=>1,'nd'=>1,'oh'=>1,'ok'=>1,'or'=>1,'pa'=>1,'ri'=>1,'sc'=>1,'sd'=>1,'tn'=>1,'tx'=>1,'ut'=>1,'vt'=>1,'va'=>1,'wa'=>1,'wv'=>1,'wi'=>1,'wy'=>1
		);

		public static $ELEMENTS = [
			'h'=>1,'he'=>1,'li'=>1,'be'=>1,'b'=>1,'c'=>1,'n'=>1,'o'=>1,'f'=>1,'ne'=>1,'na'=>1,'mg'=>1,'al'=>1,'si'=>1,'p'=>1,'s'=>1,'cl'=>1,'ar'=>1,'k'=>1,'ca'=>1,'sc'=>1,'ti'=>1,'v'=>1,'cr'=>1,'mn'=>1,
			'fe'=>1,'co'=>1,'ni'=>1,'cu'=>1,'zn'=>1,'ga'=>1,'ge'=>1,'as'=>1,'se'=>1,'br'=>1,'kr'=>1,'rb'=>1,'sr'=>1,'y'=>1,'zr'=>1,'nb'=>1,'mo'=>1,'tc'=>1,'ru'=>1,'rh'=>1,'pd'=>1,'ag'=>1,'cd'=>1,'in'=>1,
			'sn'=>1,'sb'=>1,'te'=>1,'i'=>1,'xe'=>1,'cs'=>1,'ba'=>1,'la'=>1,'ce'=>1,'pr'=>1,'nd'=>1,'pm'=>1,'sm'=>1,'eu'=>1,'gd'=>1,'tb'=>1,'dy'=>1,'ho'=>1,'er'=>1,'tm'=>1,'yb'=>1,'lu'=>1,'hf'=>1,'ta'=>1,
			'w'=>1,'re'=>1,'os'=>1,'ir'=>1,'pt'=>1,'au'=>1,'hg'=>1,'tl'=>1,'pb'=>1,'bi'=>1,'po'=>1,'at'=>1,'rn'=>1,'fr'=>1,'ra'=>1,'ac'=>1,'th'=>1,'pa'=>1,'u'=>1,'np'=>1,'pu'=>1,'am'=>1,'cm'=>1,'bk'=>1,
			'cf'=>1,'es'=>1,'fm'=>1,'md'=>1,'no'=>1,'lr'=>1,'rf'=>1,'db'=>1,'sg'=>1,'bh'=>1,'hs'=>1,'mt'=>1,'ds'=>1,'rg'=>1,'cn'=>1
		];

		public static $COUNTRY_CODES = [
			'ad'=>1,'ae'=>1,'af'=>1,'ag'=>1,'ai'=>1,'al'=>1,'am'=>1,'ao'=>1,'aq'=>1,'ar'=>1,'as'=>1,'at'=>1,'au'=>1,'aw'=>1,'ax'=>1,'az'=>1,'ba'=>1,'bb'=>1,'bd'=>1,'be'=>1,'bf'=>1,'bg'=>1,'bh'=>1,'bi'=>1,'bj'=>1,'bl'=>1,'bm'=>1,'bn'=>1,'bo'=>1,'bq'=>1,
			'br'=>1,'bs'=>1,'bt'=>1,'bv'=>1,'bw'=>1,'by'=>1,'bz'=>1,'ca'=>1,'cc'=>1,'cd'=>1,'cf'=>1,'cg'=>1,'ch'=>1,'ci'=>1,'ck'=>1,'cl'=>1,'cm'=>1,'cn'=>1,'co'=>1,'cr'=>1,'cu'=>1,'cv'=>1,'cw'=>1,'cx'=>1,'cy'=>1,'cz'=>1,'de'=>1,'dj'=>1,'dk'=>1,'dm'=>1,
			'do'=>1,'dz'=>1,'ec'=>1,'ee'=>1,'eg'=>1,'eh'=>1,'er'=>1,'es'=>1,'et'=>1,'fi'=>1,'fj'=>1,'fk'=>1,'fm'=>1,'fo'=>1,'fr'=>1,'ga'=>1,'gb'=>1,'gd'=>1,'ge'=>1,'gf'=>1,'gg'=>1,'gh'=>1,'gi'=>1,'gl'=>1,'gm'=>1,'gn'=>1,'gp'=>1,'gq'=>1,'gr'=>1,'gs'=>1,
			'gt'=>1,'gu'=>1,'gw'=>1,'gy'=>1,'hk'=>1,'hm'=>1,'hn'=>1,'hr'=>1,'ht'=>1,'hu'=>1,'id'=>1,'ie'=>1,'il'=>1,'im'=>1,'in'=>1,'io'=>1,'iq'=>1,'ir'=>1,'is'=>1,'it'=>1,'je'=>1,'jm'=>1,'jo'=>1,'jp'=>1,'ke'=>1,'kg'=>1,'kh'=>1,'ki'=>1,'km'=>1,'kn'=>1,
			'kp'=>1,'kr'=>1,'kw'=>1,'ky'=>1,'kz'=>1,'la'=>1,'lb'=>1,'lc'=>1,'li'=>1,'lk'=>1,'lr'=>1,'ls'=>1,'lt'=>1,'lu'=>1,'lv'=>1,'ly'=>1,'ma'=>1,'mc'=>1,'md'=>1,'me'=>1,'mf'=>1,'mg'=>1,'mh'=>1,'mk'=>1,'ml'=>1,'mm'=>1,'mn'=>1,'mo'=>1,'mp'=>1,'mq'=>1,
			'mr'=>1,'ms'=>1,'mt'=>1,'mu'=>1,'mv'=>1,'mw'=>1,'mx'=>1,'my'=>1,'mz'=>1,'na'=>1,'nc'=>1,'ne'=>1,'nf'=>1,'ng'=>1,'ni'=>1,'nl'=>1,'no'=>1,'np'=>1,'nr'=>1,'nu'=>1,'nz'=>1,'om'=>1,'pa'=>1,'pe'=>1,'pf'=>1,'pg'=>1,'ph'=>1,'pk'=>1,'pl'=>1,'pm'=>1,
			'pn'=>1,'pr'=>1,'ps'=>1,'pt'=>1,'pw'=>1,'py'=>1,'qa'=>1,'re'=>1,'ro'=>1,'rs'=>1,'ru'=>1,'rw'=>1,'sa'=>1,'sb'=>1,'sc'=>1,'sd'=>1,'se'=>1,'sg'=>1,'sh'=>1,'si'=>1,'sj'=>1,'sk'=>1,'sl'=>1,'sm'=>1,'sn'=>1,'so'=>1,'sr'=>1,'ss'=>1,'st'=>1,'sv'=>1,
			'sx'=>1,'sy'=>1,'sz'=>1,'tc'=>1,'td'=>1,'tf'=>1,'tg'=>1,'th'=>1,'tj'=>1,'tk'=>1,'tl'=>1,'tm'=>1,'tn'=>1,'to'=>1,'tr'=>1,'tt'=>1,'tv'=>1,'tw'=>1,'tz'=>1,'ua'=>1,'ug'=>1,'um'=>1,'us'=>1,'uy'=>1,'uz'=>1,'va'=>1,'vc'=>1,'ve'=>1,'vg'=>1,'vi'=>1,
			'vn'=>1,'vu'=>1,'wf'=>1,'ws'=>1,'ye'=>1,'yt'=>1,'za'=>1,'zm'=>1,'zw'=>1
		];

		public static $SMALL_WORDS = ['aah'=>1,'ab'=>1,'abc'=>1,'abs'=>1,'abt'=>1,'ac'=>1,'ace'=>1,'act'=>1,'ad'=>1,'add'=>1,'adj'=>1,'ado'=>1,'ads'=>1,'adv'=>1,'adz'=>1,'afb'=>1,'aft'=>1,'age'=>1,
			'ago'=>1,'ah'=>1,'aha'=>1,'ahs'=>1,'ai'=>1,'aid'=>1,'ail'=>1,'aim'=>1,'air'=>1,'al'=>1,'alb'=>1,'ale'=>1,'all'=>1,'alp'=>1,'alt'=>1,'am'=>1,'ama'=>1,'amp'=>1,'amu'=>1,'an'=>1,
			'ana'=>1,'and'=>1,'ann'=>1,'ant'=>1,'any'=>1,'ape'=>1,'app'=>1,'apt'=>1,'arc'=>1,'are'=>1,'arf'=>1,'ark'=>1,'arm'=>1,'ars'=>1,'art'=>1,'as'=>1,'ash'=>1,'ask'=>1,'asp'=>1,'at'=>1,'ate'=>1,
			'aud'=>1,'auf'=>1,'auk'=>1,'aux'=>1,'ave'=>1,'avg'=>1,'aw'=>1,'awe'=>1,'awl'=>1,'awn'=>1,'ax'=>1,'axe'=>1,'ay'=>1,'aye'=>1,'baa'=>1,'bad'=>1,'bag'=>1,'bah'=>1,'ban'=>1,'bar'=>1,
			'bas'=>1,'bat'=>1,'bay'=>1,'bb'=>1,'bbl'=>1,'be'=>1,'bed'=>1,'bee'=>1,'beg'=>1,'bel'=>1,'ben'=>1,'bet'=>1,'bey'=>1,'bib'=>1,'bid'=>1,'big'=>1,'bin'=>1,'bio'=>1,'bit'=>1,'bks'=>1,'boa'=>1,
			'bob'=>1,'bod'=>1,'bog'=>1,'bon'=>1,'boo'=>1,'bop'=>1,'bot'=>1,'bow'=>1,'box'=>1,'boy'=>1,'bps'=>1,'br'=>1,'bro'=>1,'bub'=>1,'bud'=>1,'bug'=>1,'bum'=>1,'bun'=>1,'bur'=>1,'bus'=>1,
			'but'=>1,'buy'=>1,'by'=>1,'bye'=>1,'ca'=>1,'cab'=>1,'cad'=>1,'cal'=>1,'cam'=>1,'can'=>1,'cap'=>1,'car'=>1,'cat'=>1,'caw'=>1,'cay'=>1,'cc'=>1,'cd'=>1,'cgs'=>1,'chi'=>1,'chm'=>1,'cia'=>1,
			'cit'=>1,'cl'=>1,'co'=>1,'cob'=>1,'cod'=>1,'cog'=>1,'col'=>1,'com'=>1,'con'=>1,'coo'=>1,'cop'=>1,'cot'=>1,'cow'=>1,'coy'=>1,'cpi'=>1,'cpl'=>1,'cps'=>1,'cpu'=>1,'cr'=>1,'crc'=>1,'cry'=>1,
			'cs'=>1,'csp'=>1,'cst'=>1,'ct'=>1,'ctg'=>1,'cts'=>1,'cub'=>1,'cud'=>1,'cue'=>1,'cup'=>1,'cur'=>1,'cut'=>1,'cwt'=>1,'dab'=>1,'dad'=>1,'dam'=>1,'dan'=>1,'daw'=>1,'day'=>1,'db'=>1,'dbl'=>1,
			'dc'=>1,'de'=>1,'deb'=>1,'dec'=>1,'dei'=>1,'del'=>1,'den'=>1,'der'=>1,'des'=>1,'dew'=>1,'did'=>1,'dig'=>1,'dim'=>1,'din'=>1,'dip'=>1,'dis'=>1,'do'=>1,'doc'=>1,'doe'=>1,'dog'=>1,'dom'=>1,
			'don'=>1,'dos'=>1,'dot'=>1,'doz'=>1,'dp'=>1,'dry'=>1,'dub'=>1,'dud'=>1,'due'=>1,'dug'=>1,'dun'=>1,'duo'=>1,'dup'=>1,'dx'=>1,'dye'=>1,'ear'=>1,'eat'=>1,'eau'=>1,'ebb'=>1,'eel'=>1,'eft'=>1,
			'egg'=>1,'ego'=>1,'eh'=>1,'eke'=>1,'el'=>1,'eld'=>1,'elf'=>1,'elk'=>1,'ell'=>1,'elm'=>1,'emf'=>1,'ems'=>1,'emu'=>1,'en'=>1,'enc'=>1,'end'=>1,'ens'=>1,'eof'=>1,'eon'=>1,'epa'=>1,
			'era'=>1,'ere'=>1,'erg'=>1,'err'=>1,'es'=>1,'esc'=>1,'esp'=>1,'ess'=>1,'et'=>1,'eta'=>1,'etc'=>1,'eve'=>1,'ewe'=>1,'ex'=>1,'ext'=>1,'eye'=>1,'fad'=>1,'fan'=>1,'far'=>1,'fax'=>1,'fay'=>1,
			'fbi'=>1,'fed'=>1,'fee'=>1,'fem'=>1,'fen'=>1,'few'=>1,'fey'=>1,'fez'=>1,'fib'=>1,'fie'=>1,'fig'=>1,'fin'=>1,'fir'=>1,'fit'=>1,'fix'=>1,'flu'=>1,'fly'=>1,'fob'=>1,'foe'=>1,'fog'=>1,
			'fop'=>1,'for'=>1,'fox'=>1,'fps'=>1,'fro'=>1,'fry'=>1,'fun'=>1,'fur'=>1,'fwd'=>1,'ga'=>1,'gab'=>1,'gad'=>1,'gag'=>1,'gal'=>1,'gam'=>1,'gap'=>1,'gar'=>1,'gas'=>1,'gat'=>1,'gds'=>1,'gee'=>1,
			'gel'=>1,'gem'=>1,'gen'=>1,'get'=>1,'gig'=>1,'gip'=>1,'git'=>1,'gnu'=>1,'go'=>1,'goo'=>1,'got'=>1,'gov'=>1,'gum'=>1,'gut'=>1,'guy'=>1,'gym'=>1,'ha'=>1,'had'=>1,'hag'=>1,'hah'=>1,
			'ham'=>1,'hap'=>1,'has'=>1,'hat'=>1,'haw'=>1,'hay'=>1,'he'=>1,'hee'=>1,'hem'=>1,'hen'=>1,'hep'=>1,'her'=>1,'hew'=>1,'hex'=>1,'hey'=>1,'hi'=>1,'hic'=>1,'hid'=>1,'hie'=>1,'him'=>1,'hip'=>1,
			'his'=>1,'hit'=>1,'hob'=>1,'hoc'=>1,'hod'=>1,'hoe'=>1,'hog'=>1,'hoi'=>1,'hon'=>1,'hop'=>1,'hor'=>1,'hot'=>1,'how'=>1,'hp'=>1,'hr'=>1,'hrs'=>1,'hts'=>1,'hub'=>1,'hue'=>1,'hug'=>1,
			'huh'=>1,'hum'=>1,'hun'=>1,'hup'=>1,'hut'=>1,'hwy'=>1,'ibm'=>1,'ic'=>1,'ice'=>1,'icy'=>1,'id'=>1,'ids'=>1,'ie'=>1,'if'=>1,'ifs'=>1,'ii'=>1,'iii'=>1,'ilk'=>1,'ill'=>1,'imp'=>1,'in'=>1,
			'inc'=>1,'ink'=>1,'inn'=>1,'ins'=>1,'int'=>1,'ion'=>1,'iou'=>1,'iqs'=>1,'ira'=>1,'ire'=>1,'irk'=>1,'irs'=>1,'is'=>1,'ism'=>1,'it'=>1,'its'=>1,'iud'=>1,'iv'=>1,'ivy'=>1,'jab'=>1,'jag'=>1,
			'jai'=>1,'jam'=>1,'jar'=>1,'jaw'=>1,'jay'=>1,'jct'=>1,'jet'=>1,'jeu'=>1,'jib'=>1,'jig'=>1,'jim'=>1,'job'=>1,'joe'=>1,'jog'=>1,'jot'=>1,'joy'=>1,'jug'=>1,'jus'=>1,'jut'=>1,'kb'=>1,'keg'=>1,
			'ken'=>1,'key'=>1,'kin'=>1,'kip'=>1,'kit'=>1,'kl'=>1,'la'=>1,'lab'=>1,'lac'=>1,'lad'=>1,'lag'=>1,'lam'=>1,'lap'=>1,'law'=>1,'lax'=>1,'lay'=>1,'lbs'=>1,'lea'=>1,'led'=>1,'lee'=>1,
			'leg'=>1,'lei'=>1,'lek'=>1,'leo'=>1,'let'=>1,'leu'=>1,'lev'=>1,'lex'=>1,'ley'=>1,'lf'=>1,'lh'=>1,'lib'=>1,'lid'=>1,'lie'=>1,'lim'=>1,'lip'=>1,'liq'=>1,'lit'=>1,'ll'=>1,'lo'=>1,'lob'=>1,
			'loc'=>1,'log'=>1,'loo'=>1,'lop'=>1,'lot'=>1,'low'=>1,'lox'=>1,'lpm'=>1,'lr'=>1,'lug'=>1,'lux'=>1,'lye'=>1,'ma'=>1,'mac'=>1,'mag'=>1,'mal'=>1,'man'=>1,'mao'=>1,'map'=>1,'mar'=>1,'mas'=>1,
			'mat'=>1,'maw'=>1,'max'=>1,'may'=>1,'mb'=>1,'mc'=>1,'md'=>1,'me'=>1,'mea'=>1,'meg'=>1,'men'=>1,'mer'=>1,'met'=>1,'mew'=>1,'mf'=>1,'mfd'=>1,'mfg'=>1,'mg'=>1,'mid'=>1,'mig'=>1,'mil'=>1,
			'min'=>1,'mix'=>1,'mkt'=>1,'mn'=>1,'mo'=>1,'mob'=>1,'mod'=>1,'moi'=>1,'mom'=>1,'mon'=>1,'moo'=>1,'mop'=>1,'mot'=>1,'mow'=>1,'mpg'=>1,'mph'=>1,'mr'=>1,'ms'=>1,'msg'=>1,'mss'=>1,'mud'=>1,
			'mug'=>1,'mum'=>1,'mux'=>1,'mw'=>1,'my'=>1,'na'=>1,'nab'=>1,'nae'=>1,'nag'=>1,'nam'=>1,'nan'=>1,'nap'=>1,'nay'=>1,'ne'=>1,'neb'=>1,'nee'=>1,'net'=>1,'new'=>1,'nib'=>1,'nil'=>1,'nim'=>1,
			'nit'=>1,'nix'=>1,'nj'=>1,'nm'=>1,'no'=>1,'nob'=>1,'nod'=>1,'nog'=>1,'nom'=>1,'non'=>1,'nor'=>1,'nos'=>1,'not'=>1,'now'=>1,'nth'=>1,'nu'=>1,'nub'=>1,'nun'=>1,'nut'=>1,'ny'=>1,'oaf'=>1,
			'oak'=>1,'oar'=>1,'oat'=>1,'ob'=>1,'obi'=>1,'od'=>1,'odd'=>1,'ode'=>1,'of'=>1,'off'=>1,'oft'=>1,'oh'=>1,'ohm'=>1,'oho'=>1,'ohs'=>1,'oil'=>1,'ok'=>1,'old'=>1,'ole'=>1,'oms'=>1,'on'=>1,
			'one'=>1,'ooh'=>1,'ope'=>1,'opp'=>1,'ops'=>1,'opt'=>1,'or'=>1,'orb'=>1,'orc'=>1,'ore'=>1,'ors'=>1,'ort'=>1,'os'=>1,'oui'=>1,'our'=>1,'out'=>1,'ova'=>1,'ow'=>1,'owe'=>1,'owl'=>1,'own'=>1,
			'ox'=>1,'oxy'=>1,'oz'=>1,'pa'=>1,'pac'=>1,'pad'=>1,'pal'=>1,'pan'=>1,'pap'=>1,'par'=>1,'pas'=>1,'pat'=>1,'paw'=>1,'pax'=>1,'pay'=>1,'pbx'=>1,'pc'=>1,'pct'=>1,'pea'=>1,'ped'=>1,'peg'=>1,
			'pen'=>1,'pep'=>1,'per'=>1,'pet'=>1,'pew'=>1,'pf'=>1,'phi'=>1,'pi'=>1,'pie'=>1,'pig'=>1,'pin'=>1,'pip'=>1,'pit'=>1,'pix'=>1,'pkg'=>1,'pl'=>1,'ply'=>1,'pm'=>1,'po'=>1,'pod'=>1,'poi'=>1,
			'pol'=>1,'pop'=>1,'pow'=>1,'pox'=>1,'pp'=>1,'ppd'=>1,'pre'=>1,'pro'=>1,'prs'=>1,'pry'=>1,'psf'=>1,'psi'=>1,'pts'=>1,'pub'=>1,'pug'=>1,'pun'=>1,'pup'=>1,'pus'=>1,'put'=>1,'pyx'=>1,'qed'=>1,
			'qts'=>1,'qty'=>1,'qua'=>1,'que'=>1,'qui'=>1,'quo'=>1,'rad'=>1,'rag'=>1,'rah'=>1,'ram'=>1,'ran'=>1,'rap'=>1,'rat'=>1,'raw'=>1,'ray'=>1,'rd'=>1,'re'=>1,'reb'=>1,'rec'=>1,'red'=>1,
			'ref'=>1,'reg'=>1,'rem'=>1,'rep'=>1,'req'=>1,'ret'=>1,'rev'=>1,'rex'=>1,'rf'=>1,'rh'=>1,'rho'=>1,'rib'=>1,'rid'=>1,'rig'=>1,'rim'=>1,'rip'=>1,'rn'=>1,'rob'=>1,'roc'=>1,'rod'=>1,'roe'=>1,
			'rom'=>1,'rot'=>1,'row'=>1,'rpm'=>1,'rte'=>1,'rub'=>1,'rue'=>1,'rug'=>1,'rum'=>1,'run'=>1,'rut'=>1,'rya'=>1,'rye'=>1,'sa'=>1,'sac'=>1,'sad'=>1,'sag'=>1,'sal'=>1,'sam'=>1,'san'=>1,
			'sap'=>1,'sat'=>1,'saw'=>1,'sax'=>1,'say'=>1,'sc'=>1,'sci'=>1,'sd'=>1,'se'=>1,'sea'=>1,'sec'=>1,'see'=>1,'seq'=>1,'set'=>1,'sew'=>1,'sh'=>1,'she'=>1,'shy'=>1,'si'=>1,'sib'=>1,'sic'=>1,
			'sin'=>1,'sip'=>1,'sir'=>1,'sis'=>1,'sit'=>1,'six'=>1,'ski'=>1,'sky'=>1,'sly'=>1,'sn'=>1,'so'=>1,'soc'=>1,'sod'=>1,'sol'=>1,'son'=>1,'sop'=>1,'sot'=>1,'sow'=>1,'sox'=>1,'soy'=>1,'sp'=>1,
			'spa'=>1,'spy'=>1,'sr'=>1,'sri'=>1,'ss'=>1,'st'=>1,'sty'=>1,'sub'=>1,'sue'=>1,'sui'=>1,'sum'=>1,'sun'=>1,'sup'=>1,'tab'=>1,'tad'=>1,'tag'=>1,'tai'=>1,'tam'=>1,'tan'=>1,'tao'=>1,'tap'=>1,
			'tar'=>1,'tat'=>1,'tau'=>1,'taw'=>1,'tax'=>1,'tbs'=>1,'tea'=>1,'tee'=>1,'tem'=>1,'ten'=>1,'tex'=>1,'th'=>1,'the'=>1,'tho'=>1,'thy'=>1,'ti'=>1,'tic'=>1,'tie'=>1,'til'=>1,'tim'=>1,
			'tin'=>1,'tip'=>1,'tis'=>1,'tm'=>1,'tmh'=>1,'to'=>1,'toe'=>1,'tog'=>1,'tom'=>1,'ton'=>1,'too'=>1,'top'=>1,'tor'=>1,'tot'=>1,'tov'=>1,'tow'=>1,'toy'=>1,'tpk'=>1,'try'=>1,'tsp'=>1,'tty'=>1,
			'tub'=>1,'tug'=>1,'tun'=>1,'tup'=>1,'tut'=>1,'tux'=>1,'tv'=>1,'two'=>1,'tx'=>1,'ufo'=>1,'ugh'=>1,'uh'=>1,'uhs'=>1,'uke'=>1,'ult'=>1,'ump'=>1,'un'=>1,'up'=>1,'ups'=>1,'urb'=>1,'urn'=>1,
			'us'=>1,'usa'=>1,'use'=>1,'ut'=>1,'va'=>1,'val'=>1,'van'=>1,'vat'=>1,'vc'=>1,'vee'=>1,'vet'=>1,'vex'=>1,'via'=>1,'vie'=>1,'vim'=>1,'vin'=>1,'vip'=>1,'viz'=>1,'vol'=>1,'von'=>1,'vow'=>1,
			'vox'=>1,'vs'=>1,'vt'=>1,'wa'=>1,'wad'=>1,'wag'=>1,'wan'=>1,'war'=>1,'was'=>1,'wax'=>1,'way'=>1,'we'=>1,'web'=>1,'wed'=>1,'wee'=>1,'wen'=>1,'wet'=>1,'wha'=>1,'who'=>1,'why'=>1,'wig'=>1,
			'win'=>1,'wit'=>1,'wiz'=>1,'wk'=>1,'woe'=>1,'wok'=>1,'won'=>1,'woo'=>1,'wow'=>1,'wpm'=>1,'wry'=>1,'wye'=>1,'xii'=>1,'xiv'=>1,'xix'=>1,'xvi'=>1,'xx'=>1,'xxi'=>1,'xxv'=>1,'yak'=>1,'yam'=>1,
			'yap'=>1,'yaw'=>1,'yay'=>1,'yds'=>1,'ye'=>1,'yea'=>1,'yen'=>1,'yep'=>1,'yes'=>1,'yet'=>1,'yew'=>1,'yid'=>1,'yin'=>1,'yip'=>1,'yod'=>1,'yon'=>1,'you'=>1,'yow'=>1,'yr'=>1,'yrs'=>1,
			'yuk'=>1,'yup'=>1,'zag'=>1,'zap'=>1,'zed'=>1,'zee'=>1,'zen'=>1,'zig'=>1,'zip'=>1,'zn'=>1,'zoo'=>1];
    }
    
    Utils::buildAnagramList();
?>