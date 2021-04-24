# Lemur Engine

The Lemur Engine is a PHP/MySQL/AIML Chatbot. Written using the Laravel Framework.

### Demo

You can demo the bot at the website:
    
https://lemurengine.com

### Quick Start

The Lemur Engine is written in Laravel so you can just bring her up as you would any Laravel project.

If you would prefer to use the included Vagrant method then follow the instructions below.


#### Prerequisites
You will need the following installed on your machine to run the Lemur Engine locally.

* Virtual Box
* Vagrant

Follow the steps below to set this up on your machine.

##### Step One

    Copy .env.sample to .env and fill in the details.

The .env.sample file has already has the default vagrant DB settings.

You may need to change these to match your settings.

    DB_CONNECTION=lemurengine
    DB_HOST=127.0.0.1
    DB_PORT=3306    
    DB_DATABASE=testing
    DB_USERNAME=root
    DB_PASSWORD=secret

##### Step Two
Create an entry in your /etc/hosts file

    192.168.10.10 lemurengine.local

##### Step Three

Start the server, login and go to the root directory

    vagrant up
    vagrant ssh
    cd /vagrant

##### Step Four

Create the database - the login details below are the default vagrant mysql settings. In this example the database is called 'lemurengine'.

    mysql -uroot -psecret
    create database lemurengine;

##### Step Five

On the VM inside the /vagrant directory run composer to install the dependencies.

    cd /vagrant
    COMPOSER_MEMORY_LIMIT=-1 composer install

##### Step Six

On the VM inside the /vagrant directory and run the following commands to set up Laravel

    cd /vagrant
    php artisan key:generate
    php artisan migrate:fresh --seed
    php artisan storage:link

If you encounter a symlink() error when running the last command then exit the vm and try to run the same command from the project root in the host machine.

You should now be able to access Lemur Engine locally on http://lemurengine.local

#### Access

The default local url is http://lemurengine.local

You should be able to log in as an admin the following:

    User: admin@lemurengine.local
    Password: password

Change this password either directly using tinker. Or using the forgotten email feature at the front of the site.

### Full Documentation

Read the Lemur Docs here:
    
https://docs.lemurengine.com
    
### Testing

To run the tests on the VM type
    
    cd /vagrant
    php artisan test --testsuite=All    
    

### Development

(more to come)..    
To check the code against the coding standards

    cd /vagrant
    php artisan test --testsuite=All 
