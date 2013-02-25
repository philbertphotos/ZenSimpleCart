<?php
/**
 * 
 * Place <?php if (function_exists('printCartPrice')) { ?><?php printCartPrice(); ?><?php  } ?> in your theme's image.php file where you want it to appear.
 
 
 *	Place in the index or header <?php if (function_exists('printCartWidget')) { ?><?php printCartWidget(); ?><?php  } ?>
 *
 * 
  *a very simple but smart shopping cart  
 * @package plugins
 */

$plugin_is_filter = 2;
$plugin_description = gettext("Adds SimpleCart fuctions to ZenPhoto.");

$plugin_author = "Joseph Philbert";
$plugin_version = '1.3';
$plugin_URL = 'http://www.philbertphotography.com/';
$option_interface = 'ZenSimpleCartOptions';

if (version_compare(ZENPHOTO_VERSION,'1.4.0') < 0) {
ob_start();
ZenSimpleCartHead();
$str = ob_get_contents();
ob_end_clean();
addPluginScript($str);
} else {
zp_register_filter('theme_head','ZenSimpleCartHead');
}

/**
 * Plugin option handling class
 *
 */
class ZenSimpleCartOptions {

	function ZenSimpleCartOptions() {
		setOptionDefault('zensimplecart_email', 'none@none.com');
		setOptionDefault('zensimplecart_checkout', '');
		setOptionDefault('zensimplecart_currency', 'USD');
		setOptionDefault('zensimplecart_css', 'lightcart.css');
	}

	function getOptionsSupported() {
		return array(										
	gettext('Paypal Email ') => array('key' => 'zensimplecart_email', 'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your paypal email address.")),	
										
	gettext('Checkout Type ID') => array('key' => 'zensimplecart_checkout', 'type' => OPTION_TYPE_SELECTOR,
										'order'=>0,
										'selections' => array(gettext('PayPal') => 'PayPal', gettext('GoogleCheckout') => 'GoogleCheckout',gettext('Amazon Payments') => 'AmazonPayments'),
										'desc' => gettext('Choose a payment type.')),
										
	gettext('Style') => array('key' => 'zensimplecart_css', 'type' => OPTION_TYPE_SELECTOR, 
				'order' => 1,
				'selections' => array(gettext('dark') => 'darkcart.css', gettext('light') => 'lightcart.css'),
				'desc' => gettext('Select a dark or light overall color style for your cart')),
				
	gettext(' Flat Shipping Rate') => array('key' => 'zensimplecart_flatship', 'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your flatshiping rate")),
										
	gettext('Currency') => array('key' => 'zensimplecart_currency', 'type' => OPTION_TYPE_SELECTOR,
			'order'=>0,
			'selections' => array(gettext('US Dollar') => 'USD', gettext('Australian Dollar') => 'AUD', gettext('Brazilian Real') => 'BRL', gettext('Canadian Dollar') => 'CAD',gettext('Czech Koruna') => 'CZK',gettext('Danish Krone') => 'DKK',gettext('Euro') => 'EUR',gettext('Hong Kong Dollar') => 'HKD',gettext('Hungarian Forint') => 'HUF',gettext('Japanese Yen') => 'JPY',gettext('Mexican Peso') => 'MXN',gettext('Swiss Franc') => 'CHF'),
	        'desc' => gettext('Select the currency type')),
		
		);
	}
}




/*shippingFlatRate, 
shippingQuantityRate, 
shippingTotalRate.  
shippingCustom*/


/*function getNewFileName($filename, $dir) {
   if (is_file("$dir/$filename")) {
       if (strpos($filename, "_") === false) {
           $filename = str_replace(".xml","_1.xml",$filename);
           return getNewFileName($filename, $dir);
       }
       else {
               $pos = strpos($filename, "_");
               $counter = (int)substr($filename, $pos+1,1);
               $counter++;
               $filename = substr($filename,0, $pos)."_".$counter.".xml";
               return getNewFileName($filename, $dir);
       }
    }
    return (string)$filename;
}*
/**
	* Parses a price list element string and returns a pricelist array
	* 
	* @param string $prices A text string of price list elements in the form
	*	<size>;<media>;<price>;<quantity>|<size>;<media>;<price>| ...
	* @return array
	*/
	 function getPriceList($prices) {
		$i = 0;
		if(!empty($prices) && preg_match('|;q|', $prices));
	
		$array = explode('|', trim($prices));

		if(!empty($array) && is_array($array)) {
			
			foreach($array as $value) {
			
				$x = explode(';', trim($value));
				
				$price_list['price'][$i] = $x[0];
				$price_list['desc'][$i] = $x[1];
				//$price_list['price'][$i] = $x[0];			
				$i++;
			
				//unset($x);
			}
			//unset($value);
		}
		//unset($array);
		return $price_list;		
	}	

function printCartPrice() {
global $_zp_current_album,$_zp_current_image;
$file_name = pathinfo($_zp_current_image->getFilename(), PATHINFO_FILENAME);

if ($_zp_current_album->getCodeblock()) {
$getcodeblock =($_zp_current_album->getCodeblock());
$codeblock = unserialize($getcodeblock);
@eval('/>'.$codeblock[$number]);
foreach ($codeblock as $value) {
$pricelist = explode("|", $value);
echo '<div class="zensimpleprice">';
echo "<div class='simpleCart_shelfItem'>";
echo "<div class='item_name' style='display:none'>".$file_name."</div>";
echo "<div id='pricedesc' class='item_description'style='display:none'>test</div>"; //DESCRIPTION TODO
echo "<div><h1 id='priceshow'>$0</h1></div>"; //Show price TODO add option pricing
echo "<div class='selectitems'>";
	//create selection values
echo '<select id="priceselect" class="item_price">';
echo "<option value='0'>choose option</option>";
for ($i = 0; $i < count(getPriceList($value)['price']); ++$i) {
echo "<option value='".getPriceList($value)['price'][$i]."'>";
echo getPriceList($value)['desc'][$i]."</option>";
}
echo "</select> <br /> </div>";    
echo "<div class='item_thumb'>".printCustomSizedImage(null, 150, 150)."</div>";
echo "<p><input type='button' class='item_add button button_accent addProduct' value='Add to cart' /></p>";
echo "</div>";
echo "</div>";
/*   Array Test 
$json = json_encode($datadesc);
    $phpStringArray = str_replace(array("{","}",":"), array("array(","}","=>"), $json);
    echo $phpStringArray;*/           
} 
}
} 	

function ZenSimpleCartHead() { 
?>
	<script type="text/javascript" src="<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/js/simpleCart.min.js"></script>
	<script type="text/javascript" src="<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/js/plugins.js" defer=""></script>
	<script type="text/javascript" src="<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/js/custom.js" defer=""></script>
	<link rel="stylesheet" href="<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/css/zensimplecart.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/css/<?php echo getOption('zensimplecart_css'); ?>" type="text/css" />
	    <script>
		
    simpleCart({
	shippingFlatRate: "<?php echo getOption('zensimplecart_flatship'); ?>",	
	cartColumns: [
    { attr: "image", label: false, view: "Image"},
    { view: "image" , attr: "thumb", label: false },
    { attr: "name" , label: "Name" } ,
    { attr: "price" , label: "Price", view: 'currency' } ,
    { view: "decrement" , label: false , text: "-" } ,
    { attr: "quantity" , label: "Qty" } ,
    { view: "increment" , label: false , text: "+" } ,
    { attr: "total" , label: "SubTotal", view: 'currency' } ,
    { view: "remove" , text: "Remove" , label: false }
    ],	
    checkout: {
    type: "<?php echo getOption('zensimplecart_checkout'); ?>",
    email: "<?php echo getOption('zensimplecart_email'); ?>",
	currency: "<?php echo getOption('zensimplecart_currency'); ?>",
    },
    });
	
	
    </script>
 
<script>
	simpleCart({
		//Setting the Cart Columns for the sidebar cart display.
		cartColumns: [
			//A custom cart column for putting the quantity and increment and decrement items in one div for easier styling.
			{ view: function(item, column){
				return	"<span>"+item.get('quantity')+"</span>" + 
						"<div>" +
							"<a href='javascript:;' class='simpleCart_increment'><img src='<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/images/increment.png' title='+1' alt='arrow up'/></a>" +
							"<a href='javascript:;' class='simpleCart_decrement'><img src='<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/images/decrement.png' title='-1' alt='arrow down'/></a>" +
						"</div>";
			}, attr: 'custom' },
			//Name of the item
			{ attr: "name" , label: false },
			//Subtotal of that row (quantity of that item * the price)
			{ view: 'currency', attr: "total" , label: false  }
		],
		cartStyle: 'div'
	});
	
</script>


	<?php
}

function printCartWidget() { ?>
<!--Start printCartWidget -->
 <div id="cart">
  <div class="heading">
	<a id="basket" title="Shopping Cart" href="#"> 
	<span>
    <i></i><i class="simpleCart_quantity"> </i><i>-items Shopping Cart</i></span></a></div>
  <div class="content shadow">
       <div class="mini-cart-info">
	   
<?php 		
$scitems = '<script type="text/javascript">simpleCart.quantity()</script>';
echo $scitems;
if ( $scitems == 0 ) { ?>
<div class="simpleCart_items"><h1> Your shopping cart is empty!</h1></div>
<?php } else { ?>
<div class="simpleCart_items">
<div class="item-custom"></div>
<div class="item-name"></div>
<div class="item-total"></div>
</div>
<?php } ?>
    </div>
<div class="mini-cart-total">
<div class="iconcart"> </div>
	<div align="right"><b>Postage:</b><span class="simpleCart_shipping">0</span></div></br>
    <div align="right"><b>Sub-Total:</b><span class="simpleCart_total"> </span></div></br>
    <div align="right"><b>GrandTotal:</b><span class="simpleCart_grandTotal"> </span></div>	
</div>
<div class="checkout"><a href="javascript:;" class="simpleCart_empty button button_accent">Empty Cart</a>
<a class="simpleCart_checkout button" href="javascript:;">Checkout</a>   </div>
    <div class="cart-arrow"></div>
 
</div>
</div>
<!--END printCartWidget -->	 
 <?php } 
?>
