# magento2-docker-mom-mock
**Magento 2.4.x Docker + Magento Order Management Mock**

1. Magento 2.4.x installation/Docker steps (refer): https://github.com/nithincninan/magento2-docker

2. Configure the connector: https://omsdocs.magento.com/integration/connector/setup-tutorial/

        
        1. Add Magento OMS repo to Composer:

            https://omsdocs.magento.com/integration/connector/setup-tutorial/#add-magento-oms-repo-to-composer

                {
                    "repositories": [
                        {
                            "type": "composer",
                            "url": "https://mcom-connector.bcn.magento.com"
                        }
                    ]
                }
            
        2. Add auth.json file: 

             https://omsdocs.magento.com/integration/connector/setup-tutorial/#add-authjson-file
                {
                   "http-basic": {
                      "mcom-connector.bcn.magento.com": {
                          "username": "<public-key>",
                          "password": "<private-key>"
                    }
                  }
                }
           
         3. After the installation, run the following in the Magento folder:
            composer require magento/mcom-connector --no-update
            Remove composer.lock/vendor folder and run composer install
            bin/magento setup:upgrade

3. Integrate Magento 2.4.x Docker with Magento Order Management Mock:


    1. Goto mom-mock folder and run composer install (mom-mock is taken from https://github.com/Magenerds/mom-mock).

    2. Create an database - mommock in docker container(mariadb_24).

    3. mom-mock db setting to magento24 is already configured in mom-mock/pub/web/index.php

    4. Execute the .sql script in mom-mock/setup/db.sql in db(mommock)

    5. Go to magento24/app/etc/env.php and edit the following credentials:
       
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
       
    6. Run bin/magento setup:upgrade --keep-generated in your MDC instance to register your MDC instance to the MOM mock and to request your first OAuth token





