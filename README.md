# ifxdemo
Using Scribe to Work With the iFixit 2.0 API
https://www.ifixit.com/api/2.0/doc/

This is an application which interacts with the iFixit API. 
The goal is to provide a solution where a user can pull data from the iFixit API and save/load it to/from a MySQL database.

Breakdown of functionality: 
    www.domain.com/$namespace/$title are the user interaction methods. The iFixit API contains all the information as to what $namespaces and $titles are available to query.
    When a user enters a proper $namespace and $title, the application queries the local MySQL database for a matching $namespace AND $title. If a match is found, it displayes the saved $contents_rendered through Scribe (https://github.com/guardian/scribe/). If a match is not found, it will automatically query the iFixit 2.0 API for the information.
    When a user enters an improper $namespace and $title, the same process will take place, but fail on the database match and will fail on the API match. The application will then display a jQuery error bar to the browser explaining that they need to check out the iFixit API for proper $namespace and $title records.
    There is a 'Load Fresh Content' button that will skip the database check and query the iFixit API directly.
    There is also a 'Save Displayed Content' button which will write the $contents_rendered information that has been passed through Scribe back into the database. If a previous $namespace and $title are found in the database, it will update the database row.
    
    
Notes: 
You will want to modify the MySQL values in the inc/config.php file.
You will also want to get your webserver working on the rewrites properly. My Apache config is as follows:
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !\.(gif|png|jpg|jpeg|css|php|js)$
        ReWriteRule ^(.*)$ /index.php/$1 [L,QSA]
        

