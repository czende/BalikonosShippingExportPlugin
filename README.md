## Overview

This simple Sylius plugin is based on top of the [BitBag ShippingExportPlugin](https://github.com/BitBagCommerce/ShippingExportPlugin) and provides connection to Balikonos.cz external API. For more information about BitBag ShippingExportPlugin read this [blog post.](https://bitbag.shop/blog/bitbag-shipping-export-plugin-simple-way-to-control-shipments-in-your-online-store)

## Prerequisites

For proper Balikonos.cz exports you have to know:
- **collection_place_code**
- **client_id**
- **client_secret** (client password)
- **refresh_token**

I recommend you to read [official documentation](https://balikonos.cz/doc-api/)

## Installation

```bash
$ composer require czende/balikonos-shipping-export-plugin
$ composer require bitbag/shipping-export-plugin:dev-master

```
    
Add plugin dependencies to your AppKernel.php file:

```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \BitBag\ShippingExportPlugin\ShippingExportPlugin(),
        new \Czende\BalikonosShippingExportPlugin\BalikonosShippingExportPlugin(),
    ]);
}
```
Add to app/config/config.yml:
```
imports:
    ...

    - { resource: "@ShippingExportPlugin/Resources/config/config.yml" }
```
Add to app/config/routing.yml
```
bitbag_shipping_export_plugin:
    resource: "@ShippingExportPlugin/Resources/config/routing.yml"
    prefix: /admin
```
## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/
