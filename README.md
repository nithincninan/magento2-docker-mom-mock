# **Magento 2.4.x Docker + Magento Order Management Mock**

**Magento 2.4.x Docker + Magento Order Management Mock - Services : Nginx 1.14, PHP 7.4-fpm-buster, Mariadb 10.4, mom_mock**

**1. Magento 2.4.x installation/Docker steps:**

```
    1. Download and install docker desktop (windows/Mac)
        * Local machine have alteast 16GB RAM
        * Docker > Preferences > Resources > Advanced : at least 4 or 5 CPUs and 8.0 GB RAM
    
    2. Build the docker Images:
        docker-compose build
        docker-compose up -d
        
    3. Add System variable in environmental settings SHELL=/bin/bash (windows)
    
    4. List all the containers(Make sure all the containers are present): 
           docker-compose ps
    
    5. Connect to php container: docker exec -it php_24 bash
           
           * cd /var/www/magento24
           
           * Install Magento Instance in magento24 ( https://devdocs.magento.com/guides/v2.4/install-gde/composer.html )
          
          	    1. composer create-project --repository-url=https://repo.magento.com/ magento/project-enterprise-edition=2.4.2 m2-242
          		    * enter your Magento authentication keys
          		    
                2. mv m2-242/* .
                3. rm -rf m2-242
                4. Install Command(from /var/www/magento24):
       
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
                               --use-rewrites=1 \
                               --search-engine=elasticsearch7 \
                               --elasticsearch-host=elasticsearch \
                               --elasticsearch-port=9200 \
                           && chown -R www-data:www-data .
           
                5. Cross check if ES is configured, if not update the below setting in app/etc/env.php:
             
                        'system' => [
                                'default' => [
                                    'catalog' => [
                                        'search' => [
                                            'engine' => 'elasticsearch7',
                                            'elasticsearch7_server_hostname' => 'elasticsearch',
                                            'elasticsearch7_server_port' => '9200',
                                            'elasticsearch7_index_prefix' => 'magento24_index'
                                        ]
                                    ]
                                ]
                            ],
           
                6. Enable Developer Mode: php bin/magento deploy:mode:set developer
                7. Make sure cache enabled : php bin/magento cache:enable
                8. Configure Redis default/page caching
                     php bin/magento setup:config:set --cache-backend=redis --cache-backend-redis-server=redis --cache-backend-redis-port=6379 --cache-backend-redis-db=0
                     bin/magento setup:config:set --page-cache=redis --page-cache-redis-server=redis --page-cache-redis-db=1
         
                9. Configure Redis for session storage
                    php bin/magento setup:config:set --session-save=redis --session-save-redis-host=redis --session-save-redis-port=6379 --session-save-redis-log-level=4 --session-save-redis-db=2
     
                10. Run the cli commands:
                    * php bin/magento setup:upgrade
                    * php bin/magento setup:di:compile
                    * php -dmemory_limit=6G bin/magento setup:static-content:deploy -f
           
     6. Configure your hosts file: 127.0.0.1 magento24.loc
        
        In windows:- c:\Windows\System32\Drivers\etc\hosts.
        Mac/Ubuntu:- /etc/hosts
        
     7. Open http://magento24.loc/ && http://magento24.loc/admin/
              
```


**2. Configure the connector:** https://omsdocs.magento.com/integration/connector/setup-tutorial/

- 1. Add Magento OMS repo to m2 composer file: [Read Here](https://omsdocs.magento.com/integration/connector/setup-tutorial/#add-magento-oms-repo-to-composer)

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

- 3. After the installation, run the following cmd's in php container:

 ```
 * docker exec -it php_24 bash -> goto cd /var/www/magento24
 * composer require magento/mcom-connector --no-update
 * Remove composer.lock/vendor folder and run composer install
 ```

**3. Integrate Magento 2.4.x Docker with Magento Order Management Mock:**


- 1. Goto mom-mock folder & run composer install (mom-mock: https://github.com/Magenerds/mom-mock).
        php_24 container:
        * cd mom-mock/ -> composer install
        * cd /var/www/magento24 -> Run "chown -R www-data:www-data ."
        * bin/magento setup:upgrade/setup:di:compile/setup:static-content:deploy

- 2. Create a database - "mommock" in docker container(mariadb_24).
        * docker exec -it php_24 bash -> mysql -h mariadb_24 -u root -p
        * create database mommock; -> use mommock;

- 3. Execute the .sql script in magento24/mom-mock/setup/db.sql in db(mommock)
        * source /var/www/magento24/mom-mock/setup/db.sql
        * Note: mom-mock to magento24(db connection) is already configured in magento24/mom-mock/pub/web/index.php

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

- 6. Configure your hosts file: 127.0.0.1 mom-mock.loc
        
        In windows:- c:\Windows\System32\Drivers\etc\hosts.
        Mac/Ubuntu:- /etc/hosts
        
- 7. Open http://mom-mock.loc/

```
        * Connect with sales-channel refer: https://omsdocs.magento.com/integration/connector/setup-tutorial/#setup-sales-channel-id

        * To compatible with latest php version(7.4), fix the below change:
        
        Error: use Deprecated: Unparenthesized `a ? b : c ? d : e` is deprecated. Use either `(a ? b : c) ? d : e` or `a ? b : (c ? d : e)` 
        in /var/www/magento24/mom-mock/vendor/twig/twig/lib/Twig/Node.php on line 42
        
        Fix: 
        * File: mom-mock/vendor/twig/twig/lib/Twig/Node.php
        * change to ->(is_object($node) ? get_class($node) : null === $node) ? 'null' : gettype($node), $name, get_class($this)))
```        


**For Performace tunning:**

```
           1. Computer, Cores & RAM : 
           
                * Local machine have alteast 16GB RAM
                * Docker > Preferences > Resources > Advanced : at least 4 or 5 CPUs and 8.0 GB RAM
                
           2. Use “delegated”/"cached" Volume Mounts for the files what is necessary for:
           
                * Use docker-compose.dev.yaml: which will sync only app/composer files (Use after #1-#7):
                
                   * docker-compose -f docker-compose.dev.yml up -d
                   * docker cp magento24 php_24:/var/www/ (move all m2 files to php_24 container)
                   * Enable the below options(docker-compose.dev.yml) and run: docker-compose -f docker-compose.dev.yml up -d
                    - ./magento24/app:/var/www/magento24/app:delegated
                    - ./magento24/composer.json:/var/www/magento24/composer.json:delegated
                    - ./magento24/composer.lock:/var/www/magento24/composer.lock:delegated
                   * docker exec -it php_24 bash then goto cd /var/www/magento24 and Run "chown -R www-data:www-data ."
                        
                        
                * Once CLI completes(setup:upgrade / di:compile / content:deploy).
                   * Go to local machine DIR - ({{LOCALHOST-DIR}}/magento2-docker-mom-mock/magento24) and run sync_24.sh
                   (sycn generated/pub-static directory from continer to host)
                * docker exec -it php_24 bash then goto cd /var/www/magento24 and Run "chown -R www-data:www-data ."
                   
                   
           Note: 
            * Make sure what ever files modifying except(app/composer files) should be sync using "docker cp" command ( host->continer / vice versa ).
            * docker exec -it php_24 bash -> goto cd /var/www/magento24 -> Run "chown -R www-data:www-data ."
```
