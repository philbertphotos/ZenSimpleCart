ZenSimpleCart
=============

Integrates a shopping basket/cart into Zenphoto CMS that uses Simplecart.js which allows you to turn your gallery into a shop for selling your images.  
More options coming soon. (Updating Google Wallet and Amazon Check out stay tuned) 
--------------
Installation Instructions
Unzip files and upload the 'yourzenphotoinstalltion/plugins' folder.
Go to plugins>ZenSimpleCart and set your paypal email.


After installation

Place this anywhere in your theme's image.php file where you want the CART to appear.
<?php if (function_exists('printCartPrice')) { ?><?php printCartPrice(); ?><?php } ?>

Place in the index or header in your theme's image.php where ever you want the CART add widget to appear.
<?php if (function_exists('printCartWidget')) { ?><?php printCartWidget(); ?><?php } ?>

Pricing can be set per Album or per image in the "custom Codeblock" section(image are load first if any)
FORMAT separate price from description with ";" and separate each section with "|" .

Accepted samples
49.00;8x10 |99.00;12x16 |300.00;16x20 |1000.00;100 cm x 80
-----------
49.00;8x10
|99.00;12x16
|300.00;16x20
|1000.00;100 cm x 80
-----------
49.00;8x10|
99.00;12x16|
300.00;16x20|
1000.00;100 cm x 80

49.00;8x10|

99.00;12x16|

300.00;16x20|

1000.00;100 cm x 80
