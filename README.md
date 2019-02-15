
#RAYAPIN Conference Manager

This is a application for Adminsitrator's Community who need to know which conference will prefer by his members.

With this application, the admin can add conference and then a email will be send to his community and the members
will can vote for most interresting subject.

On his admin's page, he can check the top ten of conferences, manage the conference
and manage the users.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

What things you need to install the software and how to install them?

- [Docker CE](https://www.docker.com/community-edition)
- [Docker Compose](https://docs.docker.com/compose/install)

### Install

- (optional) Create your `docker-compose.override.yml` file

```bash
cp docker-compose.override.yml.dist docker-compose.override.yml
```
> Notice : Check the file content. If other containers use the same ports, change yours.

#### Init & Docker Run

```bash
cp .env.dist .env
docker-compose up -d
docker-compose exec --user=application web composer install
```

### Running the app

You can open a terminal with this command : 
```bash
docker-compose exec web bash
```

To update the doctrine schema, make this command :
```bash
php bin/console d:s:u --force
```
Open the .env file and change this line for the SMTP server via Gmail
```
MAILER_URL=gmail://yourEmail@gmail.com:yourPassword@localhost
```
or a cloud service
```
MAILER_URL=smtp://localhost:25?encryption=&auth_mode=
```

Command to create a admin :  **app:create-admin**

```bash
   php bin/console app:create-admin email@localhost.com pwdAdmin
   ```
   
   - (optional) Implements data in the app with fixture for conference & user 
   
   ```bash
   php bin/console hautelook:fixture:load --purge-with-truncate
   ```
   
 This app is a project, some functions need to be optimize or develop.
 You can check the issues and enjoy your code !
