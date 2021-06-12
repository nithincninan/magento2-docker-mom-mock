#rm -rf generated/code/*
#php bin/magento setup:upgrade --keep-generated
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php -dmemory_limit=6G bin/magento setup:static-content:deploy -f
#chmod -R 777 var pub app/etc/* generated
