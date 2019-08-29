# TopProducts

This module allows to create selections of top products in category and brand.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is TopProducts.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer thelia/top-products-module:~1.1.0
```

## Usage

This module adds a new tab `Top products` in categories and brands where you can create selections and add products in selections.

To retrieve the products of a selections you can use the `top_product` loop.  

## The top_products loop

### Input arguments

The `top_products` loop extend the loop `product`. All the arguments of the product loop are therefore available.

|Argument |Description |
|---      |--- |
|**top_product_id** | The id of the selection |
|**top_product_element_key** | The type of your selection (category or brand) |
|**top_product_element_id** | The id of the element (category or brand) |
|**top_product_selection_code** | The name of the selection |

### Output variables

The output variables are the same as the loop `product`.

### Exemple

    {loop 
        type="top_product" 
        name="top_product_loop" 
        top_product_element_key="category"
        top_product_element_id=$category_id 
        top_product_selection_code="my-selection"
    }
    ...
    {/loop}
