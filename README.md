extension-minimum\_order\_amount
============================

### Summary

This extension requires that the customer's subtotal be greater than your specified amount to check out, and that they have at least a specified number of items in their cart. If the customer does not have enough to checkout, the checkout will throw an error with a notification that the customer needs to add more to their cart. 

### Global Variables

The add-on also adds two global variables that can be used on any templates:

```
{ct_minimum_order_setting}
{ct_minimum_qty_setting}
```

### Installation

This is a standard EE extension which is installed & configured like other extensions: 

1. Move the `cartthrob_minimum_order_amount` folder to `system/user/addons/`
1. Install it from the Add-On Manager

### No Warranty/Support

This add-on is provided as-is at no cost with no warranty expressed or implied. Support is not included. Make a file and database backup before using it. 