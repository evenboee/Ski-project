# DB project

Members: Amund Helgestad and Even Bryhn Bøe

References to others work are there to credit the source not to pass guilt.

Group members take full responsibility for any error in borrowed code.

Main inspiration for structure: `https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project`

Project case: `https://git.gvk.idi.ntnu.no/course/idatg2204/idatg2204-2021/-/wikis/The-Project-Case`

Developed using **PHP 8.0**

# Known to be missing:
- DB users. Only one implemented partly. Idea is roughly the same for other users as well
- URI `order/{state}/{id}` does not enforce flow of states (new->open->skis-available->ready)
- Proper return value for uri `rep/order`
- API tests
- Defining test cases

# Assumptions
- Department of employee in project disctiption is interpereted to be synnonymous with role (i.e. customer representative or storekeeper)

# Notes
- Physical and logical models for Employees and Customers does not fully reflect conceptual model. The resoning is to keep track of employee and employee numbers to ensure they are unique even across different types of employees and customers.

# Setting up project

- Requires XAMPP, PhpStorm, Git, Composer, Windows

## Cloning repository
- Assuming you have git installed
- Go into the folder you want
- Run `git clone https://git.gvk.idi.ntnu.no/course/idatg2204/idatg2204-2021-workspace/amundhel/db-project.git`
- Run `cd db-project`. This folder will be knows as the root folder of the project

## Setting up deployment path
- Assuming you already have XAMPP with php and PhpStorm installed
- Start Apache and MySQL from the XAMPP control panel
- Open project in PhpStorm
- Go into File/Settings/Build,Execution,Deployment/Deployement
- Click plus sign and click + and select `Local or mounted folder` to create new server
- In field folder add the absolute path to xampp/htdocs
- For web server URL add `http://localhost`
- Navigate to mappings window
- For the field local path select absolute path to root folder of the project
- For deployment path add `api\v1`
- For web path add `/api/v1`
- Optional: Navigate to excluded paths and add local paths to setup, tests, .gitignore, README.md, runner.php
- Move `.htacces` in the *setup* folder to `xampp/htdocs`

## Setting up database
- In XAMPP under MySQL service, click Admin
- Create a new database by clicking new
- For use in tests:
    - Name the project *project_test*
- For deployment:
    - Rename `dbCredentialsTemplate.php` to `dbCredentials.php`
      - Note: The content `dbCredentialsTemplate.php` does not need to be changed
    - Name the project the same as DB_NAME in `dbCredentials.php` (you are free to choose a name)
    - Either navigate to Import in phpMyAdmin and import `skies.sql` or navigate to SQL and copy file content of `skis.sql`

## Setting up codeception
- Move `setupCodeception.bat` from setup folder to root folder of the project
- Run `setupCodeception.bat` with terminal (i.e. cmd)
    - Write `y` when the option presents itself
- Replace file content of `tests/unit.suite.yml` and `tests/api.suite.yml` with contents of `setup/unit.suite.yml` and `setup/api.suite.yml`

## Running unit tests
- Run command `php vendor/bin/codecept run unit`
