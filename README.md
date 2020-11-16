# Homestead Helper

## Installation
- `git clone git@github.com:mattsplat/homestead-helper.git`
- `composer install`

or just download the [binary release](https://github.com/mattsplat/homestead-helper/releases/download/v0.1-alpha/homestead-helper)

## About
Homestead helper is built from  [Laravel Zero](https://laravel-zero.com/) as a way to make setting up applications in homestead.

### Commands
- `setup:config` Required before adding applications
- `add:app` Add application to Homestead.yaml and hosts file
- `add:domain` Adds domain entry to hosts file
- `provision` Halts vagrant and restarts with provision flag
- `app:build` Builds executable phar

### Build executable
`php homestead-helper app:build homestead-helper` 
This will create a phar file with the name provided in the builds folder

### Setup
`homestead-helper setup:config`
You will need to enter your Homestead yaml and hosts locations. This must be done before anything other command. A file named `.homestead.config` will be created in the same directory. 

### Add Application
After adding the application files run `homestead-helper add:app`
You will need to enter the location of your application directory locally and the domain name you wish to use.
This will create an entry in the yaml file.

#### additional options for add application 
- Map the folder structure in vagrant to the specific folder
- Halt vagrant and restart with provision flag
- Add a domain to hosts file **this requires entering a password
- If the `--dry-run` flag is used no files will be changed. The changes to the yaml file will be displayed in console.


### Provision
`homestead-helper provision`
This will halt vagrant and restart with provision flag

### Add Domain
`homestead-helper add:domain`
This will add an entry to the hosts file with the homestead ip and the domain provided


## Future ideas
- Run composer install on project
- if empty or non-existent folder provided add clone option
- create .env 
- generate key
- create database
- migrate database / run seeders
- npm install
- rollback changes to yaml and hosts
- remove project

