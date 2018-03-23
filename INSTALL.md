# Bitcoin Faucet Rotator - Installation Instructions 

First of all, thank you for your interest in this script. I hope you will gain some good use from it :). If you experience any complications installing this script, please let me know - I am willing to help you, and amend these instructions if necessary.

The steps below outline the instructions needed to install the script.

## Installation 

At the time of writing these instructions, this script uses [Laravel version 5.6.*](https://laravel.com/docs/5.6/). When new stable releases of Laravel are made, and are compatible with this project, this version will change.

These instructions are for Linux-based servers using Apache 2.4+. If you have servers powered by other operating systems (e.g. Windows, MAC OS, etc.), please let me know, and feel free to contribute installation instructions for said operating system/s.

If you don't currently have a server already, I highly recommend the USD$5/mo package from [DigitalOcean](https://www.digitalocean.com/?refcode=65f76388fd4a). If you choose to sign up through my link, we will both get USD$10 credit :).

If you experience any issues with installation, please [log them as issues](https://github.com/rattfieldnz/bitcoin-faucet-rotator-v2/issues) so others may provide feedback (make sure you anonymise any file paths, passwords, etc). Alternatively, you can contact me through email (details at the bottom of this install.md file). This script has not long been released to the public, so there are bound to be some issues with different setups - which is why your feedback and contributions are very important.

### Pre-requisites

Before you begin installing the script, please make sure your server meets the following specifications:

* PHP version - 7.1.3 or greater
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* Enable [Mod Rewrite](https://www.digitalocean.com/community/tutorials/how-to-rewrite-urls-with-mod_rewrite-for-apache-on-ubuntu-16-04), [Mod Headers](https://www.garron.me/en/bits/enable-mod-headers-apache-2.html) for Apache 
* Install [Composer](https://getcomposer.org/doc/00-intro.md)
* Install [Node.js](https://nodejs.org/en/download/package-manager/), Node Package Manager (comes with NodeJS), and [Yarn](https://yarnpkg.com/en/).

---

### Step 1

Navigate to the root directory of your web server, then clone this repository. 

For example, I would navigate to '/var/www' and issue the following command:

    git clone https://github.com/rattfieldnz/bitcoin-faucet-rotator-v2.git yourdomain.com

### Step 2

Make the 'setup' script executable by running the following command: `chmod u+x setup-project`.

Once you have ran the command above, you may begin installing the project's dependencies by running the following command: 

`./setup-project`

This process can take a few minutes, depending on your server resources and bandwidth speed.

### Step 3

Once Step 2 has finished, we will need to edit the .env.example file with the related configuration values. 

I am currently using MySQL as the DBMS for this site, but you can also use others - such as PostgreSQL.

If you have not made a database yet, do so now. Once you have, note down the details and add them into the .env file.

Once you have edited in the necessary configuration values, make a copy of the .env.example file and rename it as .env.

### Step 4

Once Step three has been successful, you can now run the database migration and seeding commands. This sets up the database with the required tables, and seeds said tables with some values. 

To begin this process, make sure you are in the root of the repository (or the directory you cloned it to) and enter the following commands:

    php artisan migrate --seed

The first part of the command creates the database tables, and the second part seeds the database with the appropriate values.

If you encounter difficulty with this step, please let me know, and I will use feedback to make necessary modifications.

<strong>NOTE:</strong> To get referral income from the faucets, you will need to replace my referral codes with your own. Your user credentials to log in 
will have been seeded from the .env file you created in Step 3. You can edit these when you log in to the script.

### Step 5

If you have a Linux-based server (as I am using), and are using Apache, use the following command to create an Apache sites file for your script:

    touch /etc/apache2/sites-available/yourdomain.com.conf 

Once you have created this file, enter the following into it:

    <VirtualHost yourdomain.com:80>
        ServerName yourdomain.com
        ServerAdmin webmaster@yourdomain.com
        DocumentRoot "/var/www/yourdomain.com/public"
        <Directory "/var/www/yourdomain.com/public">
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/error.log
        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>

Once this file has the above in it, execute the following commands to load it into Apache:

`a2ensite yourdomain.com.conf && service apache2 reload`

#### NOTE: 
If you are running this in a local testing environment, add `AccessFileName .htaccess-dev` below the DocumentRoot line above.
I have encountered authentication issues (e.g. not being able to log in) due to settings in the main .htaccess file, and have found 
that only having the default .htaccess in my local testing environment removes the issues I was having. I plan on using a process of 
elimination to see what exact .htaccess setting are causing the issues, and will report my findings here.

### Step 6

Barring any potential errors in the previous installation, the script should be successful installed. Visit yourdomain.com in a (modern) web browser to see the functioning script in action.

___

### Notes

If you encounter any errors during installation, please let me know by emailing me at emailme[AT]robertattfield[DOT]com. Eventually, I will like the installation process to be simpler, so having many users with different hosting platforms and requirements will improve this.