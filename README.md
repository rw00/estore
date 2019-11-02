PROJECT OVERVIEW
----------------
This project was developed for Web Development and Design course between November and December 2015. I cooperated with one teammate ytz00@mail.aub.edu

Basically, it is an e-commerce web app for e-devices where users can trade these items. It features chat system and secure login.


INSTALLATION
------------
1. Change the defined variables in 'php/classes/db.php' according to your MySQL Server configuration;
	DB_USERNAME and DB_PASSWORD

	Or change the username and password of your MySQL Server to 'root' for both.
    
2. Start the MySQL Server.

3. Execute the 'estore.sql' on MySQL.

4. Change the defined variables in 'php/classes/Mailing.php' according to your email configuration.
	Set the properties for the SMTP of your Mail Server.

	You could use GMail but it must first be activated for access from Untrusted Sources /:
	
	Go here 'https://myaccount.google.com/security' and activate 'Allow less secure apps'  

5. Allow HTACCESS by checking 'rewrite_module' in WAMP Apache modules.

6. Place the entire 'estore' folder in the directory 'wamp/www/'

7. Browse to 'localhost/estore/' on an Internet browser.

8. You may login with predefined users: 'raafatwahb@gmail.com' and 'ytz00@mail.aub.edu' with password '12341234'
