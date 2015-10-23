# php-login-demo
Just a small demo application for logging into a PHP/MySQL environment, using sessions

# USAGE
1. Create a MySQL database with the following fields: (int)client_id,(varchar)user_name,(varchar)password_hash,(datetime)last_login
2. In php/config.php, set appropriate parameters for accessing the MySQL database.
3. Add a test user account to MySQL using something like phpMyAdmin.
4. Log into the test app and verify that a PHP session is passed to the browser.

# NOTES
1. This is NOT production ready! This is just a demo. A real app will need a secret key stored in a publically-inaccessible file.
2. Again, NOT production ready! A production ready app will have an administrative console for adding users, rather than having you enter them (including their passwords) in plain text!
3. If I haven't said it enough, this is NOT PRODUCTION READY! I take no responsibility whatsoever for anyone who implements this as-is in a production environment. Use it to learn, not for production. 
