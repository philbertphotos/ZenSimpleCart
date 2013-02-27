<?php
/**
 * 
 *	Place <?php if (function_exists('printCartPrice')) { ?><?php printCartPrice(); ?><?php  } ?> in your theme's image.php file where you want it to appear.
 *
 *	Place in the index or header <?php if (function_exists('printCartWidget')) { ?><?php printCartWidget(); ?><?php  } ?>
 *	
 *	Place under an image or anywhere you want a add button (optional) <?php if (function_exists('printAddWidget')) { ?><?php printAddWidget(); ?><?php  } ?>
 * 
  *a very simple but smart shopping cart  
 * @package plugins
 */

$plugin_is_filter = 2;
$plugin_description = gettext("Adds SimpleCart fuctions to ZenPhoto.");

$plugin_author = "Joseph Philbert";
$plugin_version = '1.5';
$plugin_URL = 'http://philbertphotos.github.com/ZenSimpleCart';
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
				//$price_list['price'][$i] = $x[0];	//TODO		
				$i++;
			
				//unset($x);
			}
			//unset($value);
		}
		//unset($array);
		return $price_list;		
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
							"<a href='javascript:;' class='simpleCart_increment tooltip_t' title='increase quantity''><img src='<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/images/increment.png' title='increase quantity' alt='arrow up'/></a>" +
							"<a href='javascript:;' class='simpleCart_decrement tooltip_t' title='decrease quantity'><img src='<?php echo WEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/ZenSimpleCart/images/decrement.png' title='decrease quantity' alt='arrow down'/></a>" +
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
function printAddWidget() { 
?>
<!--Start printAddWidget--> 
<? global $_zp_current_album,$_zp_current_image;
$file_name = pathinfo($_zp_current_image->getFilename(), PATHINFO_FILENAME);
?>
<div class="item_name" style="display:none"><?php echo $file_name; ?></div>
<div class="additem"><a href="javascript:;" class="item_add addProduct button button_accent">Add Item</a></div>
<!--END printAddWidget -->	 
 <?php } 
 
function printCartWidget() { ?>
<!--Start printCartWidget -->
 <div id="cart" style="display:none">
  <div class="heading">
	<a id="basket" class="tooltip_l" title="Shopping Cart" href="#"> 
	<span>
    <i></i><i class="simpleCart_quantity"> </i><i>-items Shopping Cart</i></span></a></div>
  <div class="content shadow">
<div class="mini-cart-info">
	   <div class="simpleCart_items">
	<div class="item-custom"></div>
	<div class="item-name tooltip_b"></div>
	<div class="item-total"></div>
</div>
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

function printCartPrice() {
global $_zp_current_album,$_zp_current_image;
$file_name = pathinfo($_zp_current_image->getFilename(), PATHINFO_FILENAME);

if (getImageTitle() == $file_name) {
} else {
$file_name = $file_name ." (" . getImageTitle().")";
}

if ($_zp_current_album->getCodeblock()) {
$getcodeblock =($_zp_current_album->getCodeblock());
$codeblock = unserialize($getcodeblock);
@eval('/>'.$codeblock[$number]);
foreach ($codeblock as $value) {
$pricelist = explode("|", $value);
?>
<div class="zensimpleprice">
<div class="simpleCart_shelfItem">
<div class="item_name" style="display:none"><?php echo $file_name; ?></div>
<div id="pricedesc" class="item_description" style="display:none"></div> <!--DESCRIPTION TODO-->
<div><h1 id="priceshow">$0</h1></div> <!--Show price TODO add option pricing-->
<div class="selectitems">
	<!--create selection values-->
<select id="priceselect" class="item_price">
<option value="0">choose option</option>
<?php for ($i = 0; $i < count(getPriceList($value)["price"]); ++$i) {
$price = getPriceList($value)["price"][$i];
$desc = getPriceList($value)["desc"][$i]; 
?>
<option value="<?php echo $price; ?>"><?php echo $desc; ?></option>
<?php } ?>
</select> <br /> </div>
<div class="item_thumb"><?php printCustomSizedImage(null, 150, 150); ?></div>
<p><input type="button" class="item_add button button_accent addProduct" value="Add to cart" /></p>
</div>
</div>

<!-- Pseudo User Information Box -->
<div id="info_box">
  <div id="lightbox_close"></div>
<div id="content">
<span style="text-align: center;">
<div class="paymentlogo"></div>
</div>
</span>
<div style="text-align: center;">
<h2>Please wait, your order is being processed and you will be redirected to the PayPal website.</h2>
</div>
</div>
</div>
<div id="info_box_shadow"></div>
<a id="lightbox_open" href="#" style="display:hidden"></a>
<!--   Array Test 
$json = json_encode($datadesc);
    $phpStringArray = str_replace(array("{","}",":"), array("array(","}","=>"), $json);
    echo $phpStringArray;-->       
<?php	
} 
}
} 		
?>
