# **Magento 2.4.x Docker + Magento Order Management Mock**

**Magento 2.4.x Docker + Magento Order Management Mock - Services : Nginx 1.14, PHP 7.4-fpm-buster, Mariadb 10.4, mom_mock**

1. Magento 2.4.x installation/Docker steps (refer): https://github.com/nithincninan/magento2-docker

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

3. After the installation, run the following in the Magento folder:

 ```
 composer require magento/mcom-connector --no-update
 ```
 
 Remove composer.lock/vendor folder and run composer install
 
 ```
 bin/magento setup:upgrade
 ```

4. Integrate Magento 2.4.x Docker with Magento Order Management Mock:


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
