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

call composer -n require --dev "codeception/codeception"
call composer -n require --dev "codeception/module-asserts"
call composer -n require --dev "codeception/module-db"
call composer -n require --dev "codeception/module-rest"
call vendor\bin\codecept -n bootstrap
call composer update

call copy /Y setup\api.suite.yml tests
call copy /Y setup\unit.suite.yml tests

call mkdir .idea
call copy /Y setup\webServers.xml .idea
call copy /Y setup\deployment.xml .idea

call copy setup\.htaccess %XAMPP_PATH%\htdocs

call cd setup