rm -rf pub\static\*
docker cp php_24:/var/www/magento24/pub/static pub/
rm -rf generated\code\*
docker cp php_24:/var/www/magento24/generated/code generated/
