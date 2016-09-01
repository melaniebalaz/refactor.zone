Title:      PHP
Author:     janoszen
Published:  2016-08-22
Series:     felreertett-programnyelvek
Categories: programming
Excerpt:    Talán meglepő egy ilyen cím 21 évvel a nyelv megjelenése után, de érdemes megnézni, hogy a világ harmadik 
            legnagyobb oldalát hajtó technológia hova fejlődött az elmúlt pár évben.
Social:     /images/felreertett-programnyelvek-php/social.jpg

Talán meglepő egy ilyen cím 21 évvel a nyelv megjelenése után, de érdemes megnézni, hogy a világ harmadik legnagyobb 
oldalát hajtó technológia hova fejlődött az elmúlt pár évben.

Félreértés ne essék: a PHP egy öreg nyelv, és a fejlődése nem éppen mérnöki alapokon történt. Ennek megfelelően 
rengeteg olyan dolog került a nyelvbe, ami miatt a PHP-t sokan komolytalannak sorolják be. Az azonban tagadhatatlan, 
hogy webes fejlesztéseknél az egyik *legkönyebben üzemeltethető* versenyző, és az elmúlt években a közösség és a 
fejlesztő csapat is rengeteg energiát fektetett abba, hogy a nyelv felnőjön azokhoz a modern webfejlesztés során 
felmerülő feladatokhoz.

Nézzük tehát, hogy mi is az a PHP és hogyan érdemes használni?

## Működési elv

A PHP elsődleges feladata weboldalak kiszolgálása. Működését tekintve a PHP program akkor indul el, amikor beérkezik 
egy HTTP kérés. A PHP program a futása alatt ezt az egyetlen kérést szolgálja ki, majd be is fejezi a működését. Ez 
szöges ellentétben áll azzal, ahogy például a Java vagy a NodeJS működnek, és ennek a fajta működésnek van néhány 
következménye:

* Mivel a PHP program minden lekérésre újraindul, minden feldolgozás (config fájl olvasástól kezdve a különböző 
  adatok betöltéséig) hozzá tesz a válaszidőhöz. Az egyetlen megoldás a cachelés, viszont ez nem feltétlenül teszi 
  egyszerűbbé a programot.
* Ugyanakkor egy átlagos PHP programozónak csak viszonylag keveset kell a memória fogyasztással foglalkoznia, hiszen a
  lekérdezés végével a lefoglalt memória felszabadul.
* Mivel a PHP futások egymástól nagyrészt függetlenek, és néhány egzotikus kivételtől eltekintve nincs osztott 
  memória, így a PHP programozáskor az ezzel kapcsolatos helyzetek kezelésével sem kell foglalkozni. (Gondoljunk csak 
  a Java synchronized blokkjaira, vagy a lockolására.)
* Mivel a PHP egy no-share architektúrát valósít meg és a filerendszer kezelése streamek segítségével elfedhető, a 
  skálázásnál általában nem a PHP futása, hanem az adatbázis jelenti a szűk keresztmetszetet.
* Mivel minden kérés külön PHP szálat igényel, a PHP csak nagyon korlátozottan alkalmas hosszú futásidejű feladatok 
  végrehajtására. (Chat, websocket, long polling, stb.)
* Mivel a PHP futási időben fordul, fejlesztés közben nincs szükség hosszas buildelésre, minden módosítás azonnal 
  látható. Éles környezetben pedig az OPcache gondoskodik a sebességről.
* Éppen ez a fajta architektúra adja a PHP vonzerejét. Amíg más nyelveken egy tisztességes
  programozónak szinte kötelező ismernie a különböző párhuzamosítással járó buktatókat, PHP-ban ezt igen könnyen meg 
  lehet úszni meglepően hosszú ideig. Ez persze nem azt jelenti, hogy egy PHP programozónak ne kellene ismernie 
  Andrew S. Tanenbaum könyveit, de a valóság az, hogy ez sajnos más programnyelvek fejlesztőinél is igen gyakran 
  kimarad.

A fentiek persze nem azt jelentik, hogy a PHP csak így képes működni. Parancssorból ugyanúgy futtatható egy PHP 
program, sőt, még saját folyamatokat is indíthat a PCNTL modullal vagy socketeket is nyithat. Elvben tehát megvan a 
lehetőség arra, hogy akár webszervert is írjunk PHP-ban, azonban a gyakorlat azt mutatja, hogy a memória-hatékony 
programok írása PHP-ban, részben a hiányzó eszközkészlet miatt, részben pedig az ismert memleak-problémája miatt 
meglehetősen nehezek. Ugyan léteznek olyan kezdeményezések mint az appserver.io, de ezek megmaradtak a PHP világ 
peremterületein.

## A nyelv struktúrája

A nyelv struktúráját tekintve egy keverék a Java-stílusú objektum orientáció és a klasszikusabb procedurális nyelvek 
között. Ez azt jelenti, hogy ugyanúgy írhatunk procedurális kódot, mint objektum orientáltat. A legtöbb beépített 
függvény (sajnos) még a klasszikus struktúrát követi, nagyon kevés funkció rendelkezik objektum orientált megfelelővel.

A PHP egy gyengén típusos nyelv, ami azt jelenti, hogy maga a futtató automatikusan átkonvertálja a változók tipusait
egymás között. így például a következő kifejezések értelmezésre kerülnek:

```php
// Az ures string megfeleltetheto boolean hamissal
var_dump("" == false); //true

// A nem ures string megfeleltetheto a boolean igazzal
var_dump("Hello world!" == true); //true

// A 0-t tartalmazó string boolean false-nak felel meg.
var_dump("0"  == false); //true
var_dump("00" == true); //true
var_dump("1"  == true); //true
```

## A PHP rossz hírneve

A PHP fejlesztői körökben meglehetősen rossz hírnévnek örvend, mára már talán kissé jogtalanul is. Néha olyan 
badarságokat is lehet hallani, hogy a PHP nem is programnyelv, ami egyértelműen hülyeség, hiszen maga a nyelv 
Turing-teljes, ami legjobb tudomásom szerint a legszigorúbb definíció egy programnyelvre. (A futtató környezetről 
lehet vitatkozni, bár ott különbséget kell tenni az alap PHP, a HHVM, a Quercus és a többi alternatív PHP futtató 
között.)

Kétség nem fér hozzá, a PHP a hírnevét saját magának is köszönheti. A gyenge típusosság, az OOP hiánya, illetve az 
alacsony belépési küszöb mind-mind hozzájárultak hozzá, hogy hihetetlen mennyiségű gyenge kód született. Ez nem 
korlátozódik házi projektekre, vagy eldugott webstúdiók munkájára, hanem jó néhány ismertebb CMS is meglepően gyenge 
kóddal rendelkezett. Néhány CMS komoly erőfeszítéseket tett az architekturális gyengeségek orvoslása érdekében, sok 
rendszer fejlesztőcsapata viszont nem látja be az alkotásuk hibáit és megmaradtak a régi stílusú PHP-nál.

A kód gyengesége nem korlátozódik a PHP-ra. PHP-ban viszont sokkal könnyebb gyenge kódot írni, mert sokkal elnézőbb a
kezdők hibáival szemben. Ezzel szemben egy ilyen rendszerről egy döntéshozó is könnyebben belátja azt, hogy csinálni
kell vele valamit, hiszen nagyon rosszul skálázódik, és jó eséllyel az új fejlesztések is komoly bugokkal küzdenek.

Sokkal veszélyesebb egy cég vagy projekt fejlődésére az, amikor a projekt OOP-nek kinéző, szorosan kapcsolt modulok 
halmazából áll, hiszen ezekből sokkal nagyobb rendszert lehet építeni mielőtt gond lesz belőle. Az ilyen 
rendszereknél a döntéshozók csak annyit látnak, hogy minden új fejlesztés hihetetlenül lassú. Erről bővebben a Tiszta
kód sorozatunkban írtunk.

A PHP, mint minden más programnyelv is, egy eszköz. Eszközt pedig feladathoz választunk. Remélhetőleg senki nem fog 
nekiállni PHP-ban chat programot vagy játékszervert írni, mert nem arra való. Ugyanúgy remélhetőleg nem fog senki 
nekiállni egy kis céges honlapra hatalmas, több szerveres Java architektúrát tervezni.

Ha szeretnénk egy olyan programnyelvet, ahol nem kell sokat agyalni a memóriakezelésen vagy a deployoláson, a PHP jó 
választás lehet, különösen ha megtámogatod olyan eszközökkel mint a PHPStorm, PHPUnit vagy a Scrutinizer CI. Ezzel 
szemben ha olyan feladat jön szembe, amire a PHP nem alkalmas, például a fent említett chat program, vagy olyan 
program, ahol szigorú típusosságra van szükség, esetleg egy nagy elosztott rendszert építünk, nem eretnekség más 
technológiának is utána nézni. A jó kód ismérvei szinte minden programnyelven ugyanazok, a többi pedig némi 
tanulással könnyen elsajátítható.

Általánosságban szólva pedig ne nézzünk minden feladatot szögnek csak azért, mert van egy kalapácsunk. Válasszunk 
olyan technológiát, ami alkalmas a feladat elvégzésére és amihez van kellő szakértelem. Ha pedig neki állnál 
valamilyen nyelvet szidni ilyen vagy olyan okból kifolyólag, előbb gondolj bele, hogy a választott kedvenc 
programnyelvedben más legalább ennyi kivetni valót találna.