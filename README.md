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

Run the script and after that the API should be working.

### Before running setup.bat

- Run xampp and start the services **Apace** and **MySQL**.
- If xampp is not located in `C:\xampp`; this needs to be updated in the setup.bat script (line 4).
- If your **MySQL** server does not have "root" as username and "" as password; this need to be updated in the script


# Using the API

The [API design](https://git.gvk.idi.ntnu.no/course/idatg2204/idatg2204-2021-workspace/amundhel/db-project/-/blob/master/documents/API_design.pdf) can be used as instructions on using the API.

For each endpoint you need to add a cookie for to your request.

## Adding a cookie to request in postman

1. Click the **cookies** button under the send button used to send a request
1. Add a domain. It can be called **localhost**
1. Add a cookie with the key **auth_token** and value corresponding to the token of the endpoint you want to access

## Tokens

| Endpoint | Token |
|---|---|
| Customer (/customer) | b39f008e318efd2bb988d724a161b61c6909677f |
| Production planner (/production-planner) | c841ebbb901fc70a4534ec4b72fe73908d7ffae1 |
| Customer representative (/rep) | 97a134dbbcbecefa823f6ca3cfb68d3c84899cd8 |
| Shipper (/shipper)  | 99211ca0bba8148f1800715fd959fe64931da9df |
| Storekeeper (/storekeeper) | 5627b0ea56d96c8d0a1da0bf7816ae6df9e0277d |

# Running tests
- Run command `php vendor/bin/codecept run` in root folder of the project
