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

```
    
Add plugin dependencies to your AppKernel.php file:

```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \Czende\BalikonosShippingExportPlugin\BalikonosShippingExportPlugin(),
    ]);
}
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/
