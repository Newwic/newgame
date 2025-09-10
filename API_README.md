Quick API deploy (XAMPP)

This project includes PHP API endpoints under `api/` that should be served by your local XAMPP Apache instance.

1. Copy files to XAMPP htdocs and restart Apache (PowerShell script included):

   Open an elevated PowerShell and run:

   .\tools\deploy_api.ps1

   If your Apache service has a different name, provide it as the third parameter:

   .\tools\deploy_api.ps1 -ApacheServiceName 'Apache2.4'

2. Create database and tables:

   - Open phpMyAdmin or mysql CLI and run `api/create_tables.sql`.

3. Test the reviews endpoint:

   - GET: http://localhost/api/get_reviews.php
   - POST: http://localhost/api/reviews.php (JSON body: { product, name, rating, comment })

If you see a 404 from Apache, ensure files are copied to C:\xampp\htdocs\api and Apache is running.
