# statistics-counter
1/ Name Project: Statistics Counter
2/ Language: PHP (use Zend Framework 1.11)
3/ Assumptions:
- Unique user in this project is unique ip which request to beacon image url, but you can change to anything you want. For example: you can use cookie to define unique user.
4/ Installation:
- Create database with name: statistics_counter
- Import database file (located in /DB/statistics_counter.sql) to database which has just created
- Create user and set permission for user to access database statistics_counter
- Change database config in file /sourceCode/application/constant.ini (in this part "; Begin: Config for database .... ; End: Config for database)
- Config domain: point document root to /sourceCode/public and set AllowOverride All for Directory /sourceCode/public.
For example: In Apache:
<VirtualHost *:80>
    ServerName statistics-counter.com
    ServerAlias *.statistics-counter.com
    DocumentRoot /var/www/html/statistics-counter.com/sourceCode/public
    RewriteEngine on
    RewriteCond %{HTTP_HOST} ^(.*).statistics-counter.com
    RewriteRule (.*) http://statistics-counter.com$1 [R=301,L]
    <Directory "/var/www/html/statistics-counter.com/sourceCode/public">
        Options FollowSymLinks
        AllowOverride all
        Order allow,deny
        Allow from all
        Require all granted
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    </Directory>
</VirtualHost>
- Include beacon image on pages of other web-sites which you want to count hits with code: <img src="<scheme>://<domain>/index/beacon" />. For example: <img src="http://statistics-counter.com/index/beacon" />
- Login admin page to view statistics with username "admin", password "admin" via url: <scheme>://<domain>/admin. For example: http://statistics-counter.com/admin
- Enjoy it :D
5/ Optimization:
- You can use statistics table in Database to store statistics data to access more quickly. Run statistics crontab everyday or everyhour to aggregate data and store them into statistics table. After that, you should use Model_Admin_StatisticsModel class to fetch data from statistics table (located in /sourceCode/application/models/Admin/StatisticsModel.php)
