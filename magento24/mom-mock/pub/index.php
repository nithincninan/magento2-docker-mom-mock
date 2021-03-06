<?php
/**
 * Copyright (c) 2018 Magenerds
 * All rights reserved
 *
 * This product includes proprietary software developed at Magenerds, Germany
 * For more information see http://www.magenerds.com/
 *
 * To obtain a valid license for using this software please contact us at
 * info@magenerds.com
 */

use Doctrine\DBAL\DriverManager;
use MomMock\Controller\Backend\OrderController;
use MomMock\Controller\Backend\RmaController;
use MomMock\Controller\Backend\ShipmentController;
use MomMock\Controller\Backend\ShipmentLabelsController;
use MomMock\Controller\Backend\ShipmentRequestController;
use MomMock\Controller\Backend\JournalController;
use MomMock\Controller\Backend\AggregateController;
use MomMock\Controller\Backend\ProductController;
use MomMock\Controller\Backend\InventoryController;
use MomMock\Controller\Backend\StocksnapshotController;
use MomMock\Controller\EventsController;
use MomMock\Controller\TokenController;
use MomMock\Controller\MomController;
use MomMock\Helper\MethodResolver;
use MomMock\Helper\TemplateHelper;
use MomMock\Helper\RpcClient;

require '../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$app = new \Slim\App(new \Slim\Container($configuration));

$container = $app->getContainer();

$container['db'] = function($c) {
    return DriverManager::getConnection([
        'dbname' => 'mommock',
        'user' => 'root',
        'password' => 'mage_pass_root',
        'host' => 'mariadb_24',
        'driver' => 'pdo_mysql',
    ]);
};

$container['templ'] = function($c) {
    return new Twig_Environment(new Twig_Loader_Filesystem('../app/templates'));
};

$container['method_resolver'] = function($c) {
    return new MethodResolver();
};

$container['template_helper'] = function($c) {
    return new TemplateHelper();
};

$container['rpc_client'] = function($c) {
    return new RpcClient($c->get('db'));
};

$app->get('/', OrderController::class . ':listAction');
$app->get('/order/{id}', OrderController::class . ':detailAction');
$app->get('/shipment/create', ShipmentController::class . ':createShipmentAction');
$app->get('/rma/approve', RmaController::class . ':approveRmaAction');
$app->get('/shipment/labels', ShipmentLabelsController::class . ':requestShipmentLabelsAction');
$app->get('/shipment/request', ShipmentRequestController::class . ':requestShipmentAction');
$app->get('/journal', JournalController::class . ':indexAction');
$app->get('/journal/{id}', JournalController::class . ':detailAction');
$app->get('/aggregate', AggregateController::class . ':indexAction');
$app->get('/aggregate/add', AggregateController::class . ':addAction');
$app->post('/aggregate/add', AggregateController::class . ':addAction');
$app->get('/aggregate/delete/{id}', AggregateController::class . ':deleteAction');
$app->get('/aggregate/{id}', AggregateController::class . ':detailAction');
$app->get('/inventory/add', InventoryController::class . ':addInventoryAction');
$app->get('/product/inventory/add', InventoryController::class . ':addInventoryAction');
$app->get('/product', ProductController::class . ':indexAction');
$app->get('/product/{id}', ProductController::class . ':detailAction');
$app->get('/snapshot/aggregate', StocksnapshotController::class . ':sendSnapshotForAggregateAction');
$app->post('/', MomController::class . ':indexAction');
$app->post('/remote/oms', MomController::class . ':indexAction');
$app->post('/delegate/oms', MomController::class . ':indexAction');
$app->post('/events', EventsController::class . ':indexAction');
$app->post('/oauth/token', TokenController::class . ':indexAction');

$app->run();
