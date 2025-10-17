# mercury
Mercury Launcher <br> 
EMAIL BULK SOFTWARE  

![alt text](https://i.postimg.cc/wBz5d60N/Screenshot-2025-10-12-224531.png)
![alt text](https://i.postimg.cc/y6GF8hLB/Screenshot-2025-10-12-224558.png)

Basically i dev 
- the save and load functions for the GRAPEJS editor
- A crud for Editor and the SMTP 
- And a BULK script with PHPMailer

- DEV STACK: 

- PHP 8 <br>
- MYSQL 5 <br> 
- HTML5 <br>

- Libraries used : 

- GRAPESJS ( https://grapesjs.com/ ) <br>
https://github.com/GrapesJS <br>
https://grapesjs.com/demo-newsletter-editor.html <br>
https://github.com/GrapesJS/preset-newsletter <br>
  
- Cruddiy <br>
https://github.com/jan-vandenberg/cruddiy

- ADMINLTE <br>
https://adminlte.io/

- PHPMailer <br>
https://github.com/PHPMailer/PHPMailer

- phpMyAdmin <br>
https://www.phpmyadmin.net/

- HOW TO INSTALL
1. Create a database and import the SQL script from folder : _backup_bd
2. Create a admin user, use for the password field a MD5 data type Function
3. Modify the DATABASE conections in : mysqli.inc.php
4. Modify the DB credentials in the file : core/app/config.php
for the folders cruddiy-202312.1 and cruddiy-202312.1_smtp

- HOW TO USE
1. Design a newsletter in the Newsletters Menu, the visual editor activate on click the name column
( Save from the editor at least once )
2. Set a SMTP provider in the SMTP menu, i suggest a gmail account, ask google for the common use of gmail for SMTP
3. SEND a newsletters in the Mercury Launcher Menu
