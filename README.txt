A) Set up your Apache server:
	* For UNIX OS
		1) sudo su -
		2) cd /etc/apache2
		3) vi httpd.conf
		4) Add this line - Include /private/etc/apache2/vhosts/*.conf
		5) mkdir /etc/apache2/vhosts
		6) cd /etc/apache2/vhosts
		7) vi <name-of-the-test-host-file>.conf
		8) Add those lines to set up your virtual hosts(note the DocumentRoot - u should place your path for the both examples)

			<VirtualHost *:80>
			        DocumentRoot "/Users/aleks/Sites/icover-task-custom/web" 
			        ServerName icover-task-custom.local
			        ErrorLog "/private/var/log/apache2/icover-task-custom.local-error_log"
			        CustomLog "/private/var/log/apache2/icover-task-custom.local-access_log" common

			        <Directory "/Users/aleks/Sites/icover-task-custom/web">
			            AllowOverride All
			            Require all granted
			            DirectoryIndex index.html index.htm index.php
			           # ========= Rewrite Rules Start =========

			                RewriteEngine on

			                RewriteCond %{REQUEST_FILENAME} !-d
			                RewriteCond %{REQUEST_FILENAME} !-f
			                RewriteCond %{QUERY_STRING} ^(.*)$
			                RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]

			           # ========= Rewrite Rules End =========
			        </Directory>
			</VirtualHost>

			<VirtualHost *:80>
			        DocumentRoot "/Users/aleks/Sites/icover-task-zend/public"
			        ServerName icover-task-zend.local
			        ErrorLog "/private/var/log/apache2/icover-task-zend.local-error_log"
			        CustomLog "/private/var/log/apache2/icover-task-zend.local-access_log" common

			        <Directory "/Users/aleks/Sites/icover-task-zend/public">
			                DirectoryIndex index.php
			                AllowOverride All
			                Order allow,deny
			                Allow from all
			                <IfModule mod_authz_core.c>
			                        Require all granted
			                </IfModule>
			         </Directory>
			</VirtualHost>

		9) apachectl restart
		10) vi /etc/hosts
		11) Add those lines to the hosts file
			
				127.0.0.1       icover-task-custom.local
				127.0.0.1       icover-task-zend.local




B) If u are checking the icover-task-zend project u should change the DB credentials in the config/autoload/global.php file 
/************************************************************************/
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=icover_task;host=localhost',
        'username' => 'Your username',
        'password' => 'Your password',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
/************************************************************************/
C) If u are checking the icover-task-custom project u should change the DB credentials in the services.php file

/************************************************************************/
$PDO = new ExtendedPdo(
    'mysql:host=127.0.0.1;dbname=icover_task',
    'Your username',
    'Your password'
);
/************************************************************************/

