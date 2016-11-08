# statistics-counter
<ol>
    <li>Name Project: Statistics Counter.</li>
    <li>Language: PHP (use Zend Framework 1.11)</li>
    <li>Template admin: AdminLTE (more infor at https://almsaeedstudio.com/themes/AdminLTE/index2.html)</li>
    <li>Assumptions:
        <ul>
            <li>Unique user in this project is unique ip which request to beacon image url, but you can change to anything you want. For example: you can use cookie to define unique user.</li>
        </ul>
    </li>
    <li>Installation:
        <ul>
            <li>Create database with name: statistics_counter</li>
            <li>Import database file (located in /DB/statistics_counter.sql) to database which has just created</li>
            <li>Create user and set permission for user to access database statistics_counter</li>
            <li>Change database config in file /sourceCode/application/constant.ini (in this part "; Begin: Config for database .... ; End: Config for database)</li>
            <li>Config domain: point document root to /sourceCode/public and set AllowOverride All for Directory /sourceCode/public.
            For example: In Apache:
<pre>
&lt;VirtualHost *:80&gt;
    ServerName statistics-counter.com
    ServerAlias *.statistics-counter.com
    DocumentRoot /var/www/html/statistics-counter.com/sourceCode/public
    RewriteEngine on
    RewriteCond %{HTTP_HOST} ^(.*).statistics-counter.com
    RewriteRule (.*) http://statistics-counter.com$1 [R=301,L]
    &lt;Directory "/var/www/html/statistics-counter.com/sourceCode/public"&gt;
        Options FollowSymLinks
        AllowOverride all
        Order allow,deny
        Allow from all
        Require all granted
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;
</pre>
            </li>
            <li>Include beacon image on pages of other web-sites which you want to count hits with code: 
<pre>
&lt;img src='&lt;scheme&gt;://&lt;domain&gt;/index/beacon'/&gt;
</pre>
            For example: 
            <pre>&lt;img src='http://statistics-counter.com/index/beacon' /&gt;</pre>
            </li>
            <li>Login admin page to view statistics with username "admin", password "admin" via url: <pre>&lt;scheme&gt;://&lt;domain&gt;/admin</pre>
            For example: 
            <pre>http://statistics-counter.com/admin</pre></li>
            <li>Enjoy it :D</li>
        </ul>
    </li>
    <li>Optimization:
        <ul>
            <li>You can use statistics table in Database to store statistics data to access more quickly. Run statistics crontab everyday or everyhour to aggregate data and store them into statistics table. After that, you should use Model_Admin_StatisticsModel class to fetch data from statistics table (located in /sourceCode/application/models/Admin/StatisticsModel.php)</li>
        </ul>
    </li>
</ol>
