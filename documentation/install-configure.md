Installation and Configuration
===
[Back: Prerequisite](prerequisite.md)

**Assumptions**

* All the prerequisites are completed.
* Apache web directory avcc

VIAA Application
----------
Application is build in PHP Framework **[Symfony2] (http://symfony.com)**

**1) Goto apache web directory**

**2) Clone code from git using following command.**

	$ git clone git@github.com:avpreserve/avcc.git .

**3) Use mySQL dump file to initialize database with default schema.**
   
  **Steps to use schema in mySQL**

**Connection with mysql**

	mysql -h host -u username -password

**Select database**

	use database_name

**Use default schema**

	source documentation/database/schema.sql

**4) Application configuration variable (app/config/parameter.yml.dist)**
  
  Rename file config.php.dist to config.php
        $config['base_url'] = 'http://domain.com/';                  Base url for website. 
        database_driver:   pdo_mysql
        database_host:     127.0.0.1
        database_port:     ~
        database_name:     symfony
        database_user:     root
        database_password: ~
        mailer_transport:  smtp
        mailer_host:       127.0.0.1
        mailer_user:       ~
        mailer_password:   ~
        mail_from_email:   noreply@domain.com
        locale:            en
        secret:            ThisTokenIsNotSoSecretChangeIt
        sphinx:
                 host: 127.0.0.1
                 port: 9306
                 indexName: records
        baseUrl: 'http://avccqa.avpreserve.com/' 
        webUrl : '/home/avccqa/avcc/web/' 
            

[Next: Sphinx Configuration](sphinx.md)



