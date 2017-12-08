# Bitcoin Faucet Rotator - Installation Instructions 

First of all, thank you for your interest in this script. I hope you will gain some good use from it :). If you experience any complications installing this script, please let me know - I am willing to help you, and amend these instructions if necessary.

The steps below outline the instructions needed to install the script.

## Installation 

At the time of writing these instructions, this script uses [Laravel version 5.5.*](https://laravel.com/docs/5.5/). When new stable releases of Laravel are made, and are compatible with this project, this version will change.

These instructions are for Linux-based servers using Apache 2.4+. If you have servers powered by other operating systems (e.g. Windows, MAC OS, etc.), please let me know, and feel free to contribute installation instructions for said operating system/s.

If you don't currently have a server already, I highly recommend the USD$5/mo package from [DigitalOcean](https://www.digitalocean.com/?refcode=65f76388fd4a). If you choose to sign up through my link, we will both get USD$10 credit :).

If you experience any issues with installation, please [log them as issues](https://github.com/rattfieldnz/bitcoin-faucet-rotator-v2/issues) so others may provide feedback (make sure you anonymise any file paths, passwords, etc). Alternatively, you can contact me through email (details at the bottom of this install.md file). This script has not long been released to the public, so there are bound to be some issues with different setups - which is why your feedback and contributions are very important.

### Pre-requisites

Before you begin installing the script, please make sure your server meets the following specifications:

* PHP version - 7.0 or greater
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* Enable [Mod Rewrite](https://www.digitalocean.com/community/tutorials/how-to-rewrite-urls-with-mod_rewrite-for-apache-on-ubuntu-16-04), [Mod Headers](https://www.garron.me/en/bits/enable-mod-headers-apache-2.html) for Apache 
* Install [Composer](https://getcomposer.org/doc/00-intro.md)
* Optional (if contributing): [Node.js](https://nodejs.org/en/download/package-manager/), Node Package Manager (comes with NodeJS), and [Bower](https://bower.io/#install-bower)*

*As the first version of this project is now 'legacy', it was built with a less-modern package manager called Bower. Eventually, this project will move to using the more modern approach the current Laravel version uses (see [https://laravel.com/docs/5.5/mix](https://laravel.com/docs/5.5/mix)).

---

### Step 1

Navigate to the root directory of your web server, then clone this repository. 

For example, I would navigate to '/var/www' and issue the following command:

    git clone https://github.com/rattfieldnz/bitcoin-faucet-rotator-v2.git yourdomain.com

### Step 2

Once the cloning process has completed, go into the repository folder (or the directory you cloned the repository into), 
and issue the following command:

    composer self-update && composer install

The first part of the command will update the Composer installation, then the second part will install all necessary dependencies required.

This process can take a few minutes, depending on your server resources and bandwidth speed.

### Step 3

Once the Composer process has finished, we will need to edit the .env.example file with the related configuration values. 

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
will have been seeded from the .env file you created in Step three. You can edit these when you log in to the script.

### Step 5

Since this script is built on Laravel, it needs the 'storage' directory to have the correct access and ownership privileges to store cache and log files.

To do this, you can execute the following command - which will recursively alter access and write permissions on the folder:

    chgrp -R www-data storage && chmod -R 775 storage

### Step 6

To enable Laravel to function properly for this script, run the following command:

    php artisan key:generate

### Step 7

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

> a2ensite yourdomain.com.conf && service apache2 reload

### Step 8

Barring any potential errors in the previous installation, the script should be successful installed. Visit yourdomain.com in a (modern) web browser to see the functioning script in action.

___

## OPTIONAL STEPS (if you plan on contributing to this project)

### Step 9

After the command in Step five has been executed, you will need to set up the initial styling sources of the script - specified in the 'bower.json' file.

This script uses Bootstrap and Font Awesome styling for the 'simple' design. If you have installed Bower, execute this command:

    bower install

If you are logged in as the root user, append '--allow-root' to this command (it's generally not recommended to run scripts as root).

### Step 10

To bring all the relevant CSS files into one, and the same for Javascript files, this script relies on third-party libraries specified in the 'package.json' file.

If you have installed Node.JS and Node Package Manager, execute the following command:

    npm install

This may take a few minutes to finish, depending on your server's resources and bandwidth speed.

### Step 11

With Step seven complete, we can now process css in the SASS files, combine and minify our CSS, and combine/minify our Javascript files. To do this, execute the following commands:

    gulp copyfiles
    gulp
    gulp minifycss
    gulp minifyjs

The first command copies jQuery, Bootstrap, FontAwesome, JQuery UI, CKEditor files to directories specified in the 'gulpfile.js' file.

The second command compiles SASS into CSS, combines CSS files into a single unified file, and combines Javascript/JQuery files into a unified Javascript file.

The third and fourth commands do as you might think - minify CSS and Javascript scripts respectively.
    
**NOTE:** Depending on your setup, you may experience errors relating to the dependencies. You may need to install each of the dependencies manually. To install each of the dependencies manually, open the ‘package.json’ file to see the required dependencies, then go ‘npm install dependencyName’ for each package (e.g. npm install bootstrap-sass, npm install gulp, etc). You can also install these globally by using npm –g dependencyName. As a precaution, before running the ‘gulp commands’, I suggest you also run ‘npm rebuild node-sass’ or just ‘npm rebuild’ when installing and updating these dependencies. A reference for this is at http://stackoverflow.com/questions/27257424/nodejs-npm-how-to-reinstall-recompile-copied-app-packages.

### Notes

If you encounter any errors during installation, please let me know by emailing me at emailme[AT]robertattfield[DOT]com. Eventually, I will like the installation process to be simpler, so having many users with different hosting platforms and requirements will improve this.