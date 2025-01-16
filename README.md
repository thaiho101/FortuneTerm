- Alway add file config.php

- Edit Your Custom Configuration File: Open your .conf file in the sites-available directory:
sudo nano /etc/apache2/sites-available/your-custom-file.conf

- Update ports.conf to Include the New Port: Open the ports.conf file to add the new port:
sudo nano /etc/apache2/ports.conf

- Enable Your New Site Configuration: Use a2ensite to enable your custom configuration:
sudo a2ensite your-custom-file.conf

- Disable Any Conflicting Configurations (Optional): If you’re not using the default site, you can disable it to avoid conflicts:
sudo a2dissite 000-default.conf

- Restart the apache:
sudo systemctl restart apache2