Rem @author Rune Hjelsvold
call composer require --dev "codeception/codeception"
call composer require --dev "codeception/module-asserts"
call composer require --dev "codeception/module-db"
call composer require --dev "codeception/module-rest"
call vendor\bin\codecept bootstrap
call vendor\bin\codecept generate:suite api
