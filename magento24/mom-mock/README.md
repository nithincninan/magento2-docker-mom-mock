# Magento Order Management Mock

## Overview
This mock simulates the Magento Order Management system. It is designed to be used for 
local development or test systems which can not connect to a real Magento Order Management
instance.

It supports the basic order workflow interaction between MDC and MOM. This includes the
following functionality:

## Supported Messages 
| Message | Endpoint | Description |
|----|----|----|
| magento.service_bus.remote.register | oms | Register integration |
| magento.service_bus.remote.unregister | oms | Unregister integration |
| magento.sales.order_management.create | oms | Create an order in MOM |
| magento.logistics.fulfillment_management.customer_shipment_done | mdc | Complete Shipment |
| magento.sales.order_management.updated | mdc | Update Order Status in MDC |
| magento.logistics.carrier_management.request_shipping_details | mdc | Request Shipping label from MDC |
| magento.postsales.return_management.authorize | oms | Request a RMA |
| magento.catalog.product_management.updated | oms | Export Product to MOM |
| magento.postsales.return_management.updated | mdc | Update RMA status |
| magento.postsales.refund_management.updated | mdc | Creates a creditmemo |
| magento.inventory.aggregate_stock_management.updated | mdc | Stock update from MOM |
| magento.logistics.warehouse_management.request_shipment| mdc | Request shipment from warehouse |
| magento.inventory.source_repository.search| oms | Request source information |

## Features
- Order overview and detail page
- Ship/Cancel line items
- Request a shipping label from a carrier integration
- RMA overview and approval
- Trigger refund
- API journal
- Send a stock snapshot
- Stock aggregate and source management
- Product and inventory overview
- Request shipments from a source or warehouse