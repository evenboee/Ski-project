# DB project

Members: Amund Helgestad and Even Bryhn Bøe

References to others work are there to credit the source not to pass guilt.

Group members take full responsibility for any error in borrowed code.

Inspiration for architecture: `https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project`

Project case: `https://git.gvk.idi.ntnu.no/course/idatg2204/idatg2204-2021/-/wikis/The-Project-Case`

Developed using **PHP 8.0**

# Known to be missing:
- DB users. Only one implemented. Idea is roughly the same for other users as well
- URI `order/{state}/{id}` does not enforce flow of states (new->open->skis-available->ready)

# Setting up project

- Requires XAMPP, git, Composer, Windows

## Cloning repository
- Assuming you have git installed
- Go into the folder you want
- Run `git clone https://git.gvk.idi.ntnu.no/course/idatg2204/idatg2204-2021-workspace/amundhel/db-project.git`

## Running deployment script

**IMPORTANT NOTE: Running this script will overwrite the .htaccess, any files in the xampp folder htdocs\api\v1 and any database named db_project5276.**

The setup script **setup.bat** can be found in */setup*.

After running the script the API should be working.

### Before running setup.bat

- Run xampp and start the services **Apace** and **MySQL**.
- If xampp is not located in `C:\xampp`; this needs to be updated in the setup.bat script (line 4).
- If your **MySQL** server does not have "root" as username and "" as password; this need to be updated in the script

## Running tests
- Run command `php vendor/bin/codecept run`
