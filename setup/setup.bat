REM @author Even B. Bøe
REM Requires windows, composer and xampp (with active services: Apache and MySQL)

SET XAMPP_PATH=C:\xampp
SET MYSQL_USER="root"
SET MYSQL_PASS=""
SET ORIGINAL_PATH=%CD%

call cd %XAMPP_PATH%\mysql\bin
call mysql -u %MYSQL_USER% --password=%MYSQL_PASS% < %ORIGINAL_PATH%\..\documents\physical_model.sql

call cd %ORIGINAL_PATH%
call cd ..

REM Setting up codeception
call composer -n require --dev "codeception/codeception"
call composer -n require --dev "codeception/module-asserts"
call composer -n require --dev "codeception/module-db"
call composer -n require --dev "codeception/module-rest"
call vendor\bin\codecept -n bootstrap
call composer update

call copy /Y setup\api.suite.yml tests
call copy /Y setup\unit.suite.yml tests

REM Copying settings files for PhpStorm
call mkdir .idea
call copy /Y setup\webServers.xml .idea
call copy /Y setup\deployment.xml .idea

REM Copying files to htdocs
call copy /Y setup\.htaccess %XAMPP_PATH%\htdocs

call mkdir %XAMPP_PATH%\htdocs\api\v1
call mkdir %XAMPP_PATH%\htdocs\api\v1\controller
call mkdir %XAMPP_PATH%\htdocs\api\v1\controller\Endpoints
call mkdir %XAMPP_PATH%\htdocs\api\v1\db
call mkdir %XAMPP_PATH%\htdocs\api\v1\db\db_models

call copy /Y api.php %XAMPP_PATH%\htdocs\api\v1
call copy /Y RESTConstants.php %XAMPP_PATH%\htdocs\api\v1
call copy /Y db\* %XAMPP_PATH%\htdocs\api\v1\db
call copy /Y db\db_models\* %XAMPP_PATH%\htdocs\api\v1\db\db_models
call copy /Y controller\* %XAMPP_PATH%\htdocs\api\v1\controller
call copy /Y controller\Endpoints\* %XAMPP_PATH%\htdocs\api\v1\controller\Endpoints

call cd setup