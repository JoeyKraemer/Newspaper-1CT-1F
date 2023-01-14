# Group F year 1 period 1 

## Project Gemorskos

A NHLStenden student project in cooparation with an imaginary dutch newspaper that is represented by one of our teachers Bart Oerlemans.

### Group members:
* Yohan Lozanov (Leader) Email: yohan.lozanov@student.nhlstenden.com
* Joey Krämer (Co-leader) Email: joey.kramer@student.nhlstenden.com
* Beyza Ölmez  Email: beyza.olmez@student.nhlstenden.com
* Sebastian Güntzel Email: sebastian.guntzel@student.nhlstenden.com
* Nikolay Minins Email: nikolay.minins@student.nhlstenden.com
* Timothy Adewale Email: adewale.adewale@student.nhlstenden.com
* Stefan Tasca Email: stefan.tasca@student.nhlstenden.com

## Getting started
These instrutions will aid you in setting up the environment for us of our Web-Application on your localhost-machine and for your linux server

## Local on your own machine
This section is only for people that want to run our web-application in a docker-container
### Prerequisites

#### Windows

- [Docker desktop](https://docs.docker.com/desktop/windows/install/)

#### Mac

- [Docker Desktop for Mac](https://docs.docker.com/desktop/mac/install/)
  - Note: Do check the architecture of your Mac! (x64/arm64)

#### Linux and friends

- [Docker engine](https://docs.docker.com/engine/install/#server)
  - Select your distribution from the table and follow the provided instructions.


#### Docker Container recommendation
- We recommend to use the following docker environment 
- [Docker Container](https://github.com/Schmitzenbergh/NHL_Stenden_PHP_Docker_Env)

### Running

1. Download the archive containing the necessary files.
2. Extract the files to the docker folder structure  in which you will start our project. (The project files should be placed into the /app/public directory)
3. Change the filename of ".env.TEMPLATE" into ".env", change the database details to your own settings and also the mail credentials to your own Gmail (our password recovery is designed to work with GMAIL)

``` .env 
#Specific project name
COMPOSE_PROJECT_NAME="your_project"

# Environment Variables for database.
DB_SERVER="your_database"
DB_ROOT_USER="your_database_user"
DB_ROOT_PASSWORD="your_database_password"

MAIL_ADDRESS="your@gmail.com"
MAIL_PASSWORD="your password"

```

4. Move the "docker-compose.yaml.TEMPLATE" file into your docker-container. Change the name into "docker-compose.yaml".
5. Move the ".env" file into your docker-container. It needs to be in the same folder where your "docker-compose.yaml" is.
6. Open your prefered terminal/console in the project folder and execute "docker-compose up" in your console.

``` Powershell
docker-compose up
```
6. Wait a few seconds till the docker-container is running. (First time you docker-compose up your it will take a moment)
7. Open [PHPmyAdmin](http://127.0.0.1:8080), enter your database login data you set in step 3. to login.
8. Change credentials from Admin user on Line: 85, please replace the placeholder mail with your own to make the password recovery work.
9. Import the "DatabaseStructure.SQL" in PHPmyAdmin. Check if the database appears on the left side of the screen
10. Got to [127.0.0.1](http://127.0.0.1)  in your favorite browser. 
11. Login to the web-application, the admin account is pre set for your. Username: "Admin", Password: "user123" (you will be asked to change it, first time login in)
12. Now, that you are logged in you can fully enjoy and configurade the web-application to your needs.
13. Enjoy
