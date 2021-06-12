#export COMPOSE_HTTP_TIMEOUT=150
echo "  exec [docker-compose.dev] command"
COMPOSE_HTTP_TIMEOUT=200 docker-compose -f docker-compose.dev.yml up -d
#echo "  exec [cp host to container] command"
#docker cp magento24 php_24:/var/www/
echo "  exec [docker-compose.dev-delegated] command"
COMPOSE_HTTP_TIMEOUT=200 docker-compose -f docker-compose.dev-delegated.yml up -d
echo " completed! "
exit


docker-compose -f docker-compose.dev.yml up -d
docker-compose -f docker-compose.dev-delegated.yml up -d