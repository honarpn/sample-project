# sample-project

Log-in Page Project

This Project is designed and implemented by Ali Mohtasham Gilani to present as a code sample to the employers. (This project is produced by using Netbeans IDE, PHP 5.6, and has been tested in demo.serious-games.ir)

Since This Project is well organized and has explanations as comments. Everyone can easily find out how it works. This Project is based on MVC design pattern. All requests go to index.php (according to .htaccess settings) and after that src/app/Router class leads them to suitable controller class located  in src/app/controller folder and after that results will be rendered in View classes located in src/app/view folder. There is a config folder which contains json files and server administrator should set database and email information in config.json file. There is an MySQL script file in database folder which makes a test database with three tables (users,password_resets and posts). Members can see all posts and post text to database when they are logged in. Finally if project files are not in server root  __PREFIX should be defined in the line 14 of index.php.

