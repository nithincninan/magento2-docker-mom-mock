# **Magento 2.4.x Docker + Magento Order Management Mock**

**Magento 2.4.x Docker + Magento Order Management Mock - Services : Nginx 1.14, PHP 7.4-fpm-buster, Mariadb 10.4, mom_mock**

1. Magento 2.4.x installation/Docker steps:

```
    1. Download and install docker desktop (windows/Mac)
    
    2. Build the docker Images:
        docker compose build
        docker compose up -d
        
    3. Add System variable in environmental settings SHELL=/bin/bash (windows)
    
    4. List all the containers(Make sure all the containers are present): 
           docker compose ps
    
    5. Connect to php container: 
           docker exec -it php_24 bash
           
           cd /var/www/magento24
           Install Magento Instance in magento24 ( https://devdocs.magento.com/guides/v2.4/install-gde/composer.html )
          
          	    1. composer create-project --repository-url=https://repo.magento.com/ magento/project-enterprise-edition=2.4.2 m2-242
          		    * enter your Magento authentication keys
                2. mv m2-242/* .
                3. rm -rf m2-242
                
    6. Install Command(from /var/www/magento24):
       
       php bin/magento setup:install \
               --db-host=mariadb_24 \
               --db-name=mage24_db \
               --db-user=mage24_user \
               --db-password=mage24_pass \
               --base-url=http://magento24.loc/  \
               --backend-frontname=admin \
               --admin-user=admin \
               --admin-password=admin123 \
               --admin-email=nithincninan@gmail.com \
               --admin-firstname=nithin \
               --admin-lastname=ninan \
               --language=en_US \
               --currency=USD \
               --timezone=America/Chicago \
               --skip-db-validation \
               --elasticsearch-host=elasticsearch_24 \
               --elasticsearch-port=9200 \
           && chown -R www-data:www-data .            
           
     7. Configure your hosts file: 127.0.0.1 magento24.loc
        
        In windows:- c:\Windows\System32\Drivers\etc\hosts.
        Mac/Ubuntu:- /etc/hosts
        
     8. Open magento24.loc      
         
```


2. Configure the connector: https://omsdocs.magento.com/integration/connector/setup-tutorial/

- 1. Add Magento OMS repo to Composer: [Read Here](https://omsdocs.magento.com/integration/connector/setup-tutorial/#add-magento-oms-repo-to-composer)

```
                {
                    "repositories": [
                        {
                            "type": "composer",
                            "url": "https://mcom-connector.bcn.magento.com"
                        }
                    ]
                }
```

- 2. Add auth.json file: [Read Here](https://omsdocs.magento.com/integration/connector/setup-tutorial/#add-authjson-file)
             
```
                {
                   "http-basic": {
                      "mcom-connector.bcn.magento.com": {
                          "username": "<public-key>",
                          "password": "<private-key>"
                    }
                  }
                }
```

- 3. After the installation, run the following in the Magento folder:

 ```
 * composer require magento/mcom-connector --no-update
 * Remove composer.lock/vendor folder and run composer install 
 * bin/magento setup:upgrade
 ```

3. Integrate Magento 2.4.x Docker with Magento Order Management Mock:


- 1. Goto mom-mock folder & run composer install (mom-mock: https://github.com/Magenerds/mom-mock).

- 2. Create a database - "mommock" in docker container(mariadb_24).
Note: mom-mock to magento24(db connection) is already configured in magento24/mom-mock/pub/web/index.php

- 3. Execute the .sql script in magento24/mom-mock/setup/db.sql in db(mommock)

- 4. Go to magento24/app/etc/env.php and edit the following credentials:
       
```
       'serviceBus' => [
            'url' => 'http://mom-mock.loc/',
            'oauth_server_url' => 'http://mom-mock.loc/',
            'oauth_client_id' => 'mom',
            'oauth_client_secret' => 'mom',
            'application_id' => 'mdc',
            'secret' => 'mom',
            'secure_endpoint' => true
        ],
```
       
- 5. Run setup-upgrade in your MDC instance to register your MDC instance to the MOM mock and to request your first OAuth token

```
bin/magento setup:upgrade --keep-generated
```
