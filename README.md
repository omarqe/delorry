Tutorial install

1. Install XAMPP (Google tutorial camna nak install XAMPP)
2. Start Apache & MySQL on XAMPP Control Panel
3. Bukak Google Chrome, navigate to PHPMyAdmin (http://localhost/phpmyadmin). Login, then create a database for the DeLorry.
4. Copy & paste all the files in the ZIP archive in your XAMPP ```C:\XAMPP\htdocs``` folder (directory depends on where you install your XAMPP).
4. Bukak configuration file dalam ```includes/config.php```, set database info (host, user, password and database name). If the database info is incorrect, *an error will appear and the system will not run*,
5. Navigate to http://localhost/table.php. Green table creation status will appear on screen. There should be two tables created (user & booking).
6. Delete ```table.php``` file.
7. Go to http://localhost/ and register an account.
8. Go to PHPMyAdmin again and click the database you've created in Step 3. Find table user and your account that you've registered in previous step will appear. Change `group` for that account to ```admin``` to access administrator panel.
9. Good luck.
