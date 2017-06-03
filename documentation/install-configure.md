Installation and Configuration
===
[Back: Prerequisite](prerequisite.md)

**Assumptions**

* All the prerequisites are completed.
* Apache web directory avcc

AVCC Application
----------
Application is build in PHP Framework **[Symfony2] (http://symfony.com)**

**1) Goto apache web directory**

**2) Clone code from git using following command.**

	$ git clone git@github.com:avpreserve/avcc.git .

**3) Composer**

Composer is a tool for dependency management in php. It allows to declare the dependent libraries and install them.

  **Steps to install dependent libraries**

**Get Composer**

	download composer from https://getcomposer.org/

**Install Composer**

	$ php composer.phar install

**4) Application configuration variable (app/config/parameter.yml.dist)**

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
        ...host: 127.0.0.1
        ...port: 9306
        ...indexName: records
        baseUrl: 'http://yourdomain.com/' 
        webUrl : '/path/to/web/folder/'
        ga_tracking: UA-xxxxx-x
        stripe_publishkey: key_publish
        stripe_secretkey: key_secret
        qa_instance: false
        enable_stripe: false
        amazon_aws_key: 'aws_key'
        amazon_aws_secret_key: 'aws_secret_key'
        amazon_s3_bucket_name: 'bucket_name'
        amazon_s3_base_url: 'https://s3.amazonaws.com/bucket_name/'

**5) Database**

        $ php app/console doctrine:schema:update --force --dump-sql

**6) Default Data for database**

You can use  SQL file for importing default data into table. Path for file is documentation/database/avcc.sql

		

[Next: Sphinx Configuration](sphinx.md)



