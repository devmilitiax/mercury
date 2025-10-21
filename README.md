# mercury
Mercury Launcher <br> 
<strong> EMAIL BULK SOFTWARE </strong> 

![alt text](https://i.postimg.cc/wBz5d60N/Screenshot-2025-10-12-224531.png)
![alt text](https://i.postimg.cc/y6GF8hLB/Screenshot-2025-10-12-224558.png)

UPDATE 21 OCTOBER 2025
![alt text](https://i.postimg.cc/y8ZXH8yQ/Screenshot-2025-10-21-140944.png)
Manage and select inner multiple SMTP accounts

<strong> Basically i dev </strong> 
- the save and load functions for the GRAPEJS editor
- A crud for Editor and the SMTP 
- And a BULK script with PHPMailer
- Place all in a ADMINLTE Dashboard

<strong> DEV STACK: </strong> 

- PHP 8 <br>
- MYSQL 5 <br> 
- HTML5 <br>

<strong> Libraries used : </strong>

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

<strong> HOW TO INSTALL : </strong>

1. Create a database and import the SQL script from folder : _backup_bd
2. Create a admin user, use for the password field a MD5 data type Function
3. Modify the DATABASE conections in : mysqli.inc.php
4. Modify the DB credentials in the file : core/app/config.php
for the folders cruddiy-202312.1 and cruddiy-202312.1_smtp

<strong>HOW TO USE : </strong>

1. Design a newsletter in the Newsletters Menu, the visual editor activate on click the name column
( Save from the editor at least once )
2. Set a SMTP provider in the SMTP menu, i suggest a gmail account, ask google for the common use of gmail for SMTP
3. SEND a newsletters in the Mercury Launcher Menu
