<?php

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

$db_server              = 'localhost';
$db_name                = 'mercury';
$db_user                = 'root';
$db_password            = '';
$no_of_records_per_page = '10';
$appname                = 'newsletters3';
$language               = 'en';
$translations           = include("locales/$language.php");


$upload_max_size        = 5000000; // default 5MB
$upload_target_dir      = "uploads/"; // relative to core/app
$upload_persistent_dir  = true; // Do not delete uploads folder when regenerating CRUD files
$upload_disallowed_exts = array(
    'php', 'php3', 'php4', 'php5', 'php7', 'phtml', // PHP and PHP-like files
    'html', 'htm', 'js', 'jsp', 'asp', 'aspx',      // HTML, JavaScript, and Server-side scripts
    'exe', 'bat', 'sh', 'bin',                      // Executable and shell script files
    'sql', 'sqlite', 'db',                          // Database files
    'htaccess', 'htpasswd',                         // Apache server files
    'pl', 'py', 'cgi',                              // Script files (Perl, Python, CGI)
    'jar', 'war', 'ear',                            // Java archives
    'vbs', 'ps1', 'psm1',                           // Script files (VBScript, PowerShell)
    'wsf', 'scf',                                   // Windows Script files
    'reg',                                          // Registry files
    'swf',                                          // Adobe Flash files
    'lnk',                                          // Windows shortcut files
);


$protocol               = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
$domain                 = $protocol . '://' . $_SERVER['HTTP_HOST']; // Replace domain with your domain name. (Locally typically something like localhost)

$link                   = mysqli_connect($db_server, $db_user, $db_password, $db_name);
$query = "SHOW VARIABLES LIKE 'character_set_database'";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
        if (!$link->set_charset($row[1])) {
            printf("Error loading character set %s: %s\n", $row[1], $link->error);
            exit();
        } else {
            // printf("Current character set: %s", $link->character_set_name());
        }
    }
}

?>
