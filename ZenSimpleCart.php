<?php
/**
 * 
 *	Place in the index.php (or the file that loads theme header just after the <body> tag) <?php if (function_exists('printCartWidget')) { ?><?php printCartWidget(); ?><?php  } ?>
*You may have to add this to multiple PHP files this depends on the theme 
 *	
 *	Place <?php if (function_exists('printCartPrice')) { ?><?php printCartPrice(); ?><?php  } ?> in your theme's image.php file where you want it to appear.
 *
 *
 * Example of pricing structure
 *49.00;8x10 matte|99.00;12x16 gloss |300.00;16x20 black and white |1000.00;100 cm x 80 poster
 *a very simple but smart shopping cart  
 * @package plugins
 */

$plugin_is_filter = 9|THEME_PLUGIN;

$plugin_author = "Joseph Philbert";
$plugin_version = '2.2';
$plugin_URL = 'http://philbertphotos.github.com/ZenSimpleCart';
$plugin_description = gettext("Integrates a shopping basket/cart into Zenphoto CMS that uses Simplecart.js which allows you to turn your gallery into a shop for selling your images.");

$option_interface = 'ZenSimpleCartOptions';

zp_register_filter('theme_head','ZenSimpleCartHead');
/**
 * Plugin option handling class
 *
 */
class ZenSimpleCartOptions {
  /**
* Handles custom formatting of options for Admin
*
* @param string $option the option name of the option to be processed
* @param mixed $currentValue the current value of the option (the "before" value)
*/
function handleOption($option, $currentValue) {
if (getOption('zensimplecart_checkout') == "PayPal") {
echo 'Enter your paypal email address';
} elseif (getOption('zensimplecart_checkout') == "GoogleCheckout") {
echo 'Enter your google Info email address';
}

if ($option == 'zensimplecart_update') {
			echo '<select style="width:200px;" id="' . $option . '" name="' . $option . '"' . ">\n";
			
			echo '<option value="dark"';
				if ($currentValue == "dark") { 
				echo ' selected="selected">dark</option>\n';
				} else {
				echo '>dark</option>\n';
				}
			
			echo '<option value="light"';
				if ($currentValue == "light") { 
				echo ' selected="selected">light</option>\n';
				} else {
				echo '>light</option>\n';
				}
				
			echo "</select>\n";
		}
}
	function ZenSimpleCartOptions() {
		setOptionDefault('zensimplecart_email', 'none@none.com');
		setOptionDefault('zensimplecart_checkout', 'PayPal');
		setOptionDefault('zensimplecart_layoutstyle', 0);
		setOptionDefault('zensimplecart_asignature', '');
		setOptionDefault('zensimplecart_aid', '');
		setOptionDefault('zensimplecart_akey_id', '');
		setOptionDefault('zensimplecart_gid', '');	
		setOptionDefault('zensimplecart_css', 'lightcart.css');
	}

	function getOptionsSupported() {
		return array(
	gettext('Layout Style') => array(
										'key' => 'zensimplecart_layoutstyle',
										'order'=>0,
										'type' => 5,
										'selections' => array(
										gettext('Simple') => 0,
										gettext('Standard') => 1,
																),
										'desc' => gettext('This will determine if you show the image and price vale or just an "add item" button')),
										
	gettext('CSS Style') => array('key' => 'zensimplecart_css', 
										'type' => OPTION_TYPE_SELECTOR, 
										'order' => 1,
										'selections' => array(gettext('dark') => 'darkcart.css', gettext('light') => 'lightcart.css'),
										'desc' => gettext('Select a dark or light overall color style for your cart')),
		
	gettext('Checkout Type') => array('key' => 'zensimplecart_checkout', 'type' => OPTION_TYPE_SELECTOR,
										'order'=>2,
										'selections' => array(
										gettext('PayPal') => 'PayPal', 
										gettext('GoogleCheckout') => 'GoogleCheckout',
										gettext('Amazon Payments') => 'AmazonPayments'),
										
										'desc' => gettext('Choose a payment type.')),
																				
	gettext('Paypal: Email ') => array('key' => 'zensimplecart_email',
										'order'=> 3, 
										'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your paypal email address.")),	
										
	gettext('Amazon: Merchant Signature') => array('key' => 'zensimplecart_asignature',
										'order'=> 4,	
										'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter merchant Signature.")),
	gettext('Amazon: ID') => array('key' => 'zensimplecart_aid',
										'order'=> 5,	
										'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your Amazon.")),										
	gettext('Amazon: Access Key ID ') => array('key' => 'zensimplecart_akey_id',
										'order'=> 6,	
										'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your amazon access key ID.")),		
	gettext('Google: Merchant ID') => array('key' => 'zensimplecart_gid',
										'order'=> 7,	
										'type' => OPTION_TYPE_TEXTBOX,
										'desc' => gettext("Enter your google merchant ID.")),	
	
	gettext('Currency') => array('key' => 'zensimplecart_currency', 
										'type' => OPTION_TYPE_SELECTOR,
										'order'=> 8,
										'selections' => array(
			gettext('US Dollar') => 'USD', 
			gettext('Australian Dollar') => 'AUD', 
			gettext('Brazilian Real') => 'BRL', 
			gettext('Canadian Dollar') => 'CAD',
			gettext('Czech Koruna') => 'CZK',
			gettext('Danish Krone') => 'DKK',
			gettext('Euro') => 'EUR',
			gettext('Pound Sterling') => 'GBP',
			gettext('Hong Kong Dollar') => 'HKD',
			gettext('Hungarian Forint') => 'HUF',
			gettext('Japanese Yen') => 'JPY',
			gettext('Mexican Peso') => 'MXN',
			gettext('Swiss Franc') => 'CHF',
			gettext('Israeli New Sheqel') => 'ILS',
			gettext('Thai Baht') => 'THB',
			gettext('Bitcoin') => 'BTC'),
			
	        'desc' => gettext('Select the currency type')),	
	gettext(' Flat Shipping Rate') => array('key' => 'zensimplecart_flatship', 'type' => OPTION_TYPE_TEXTBOX,
											'order'=> 9,
											'desc' => gettext("Enter your flatshiping rate")),

			
		);
	}
}

/*shippingFlatRate, 
shippingQuantityRate, 
shippingTotalRate.  
shippingCustom*/

/**
	* Parses a price list element string and returns an array
	* 
	* @param string $prices A text string of price list elements in the form
	*	<price>;<description>|<price>;<description>|<price>;<description>|<price>;<description>|
	* @return array
	*/
	 function getPriceList($prices) {
		$i = 0;
		if(!empty($prices) && preg_match('|;q|', $prices));
	
		$array = explode('|', trim($prices));

		if(!empty($array) && is_array($array)) {
			
			foreach($array as $value) {
			
				$x = explode(';', trim($value));
				
				$price_list['price'.$i] = $x[0];
				$price_list['desc'.$i] = $x[1];
				//$price_list['option'.$i] = $x[2];	//TODO
				
				$i++;
			}
		}
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
	marchantID: "<?php echo getOption('zensimplecart_gid'); ?>",
	merchant_signature: "<?php echo getOption('zensimplecart_asignature'); ?>",
	merchant_id: "<?php echo getOption('zensimplecart_aid'); ?>",
	aws_access_key_id: "<?php echo getOption('zensimplecart_akey_id'); ?>",
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
 
function getCompleteCodeblock($number=0) {
	global $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_news, $_zp_current_zenpage_page, $_zp_gallery, $_zp_gallery_page;
	$getcodeblock = NULL;
	if ($_zp_gallery_page == 'index.php') {
		$getcodeblock = $_zp_gallery->getCodeblock();
	}
	if (in_context(ZP_ALBUM)) {
		$getcodeblock = $_zp_current_album->getCodeblock();
	}
	if (in_context(ZP_IMAGE)) {
		$getcodeblock = $_zp_current_image->getCodeblock();
	}
	if (in_context(ZP_ZENPAGE_PAGE)) {
		if ($_zp_current_zenpage_page->checkAccess()) {
			$getcodeblock = $_zp_current_zenpage_page->getCodeblock();
		} else {
			$getcodeblock = NULL;
		}
	}
	if (in_context(ZP_ZENPAGE_NEWS_ARTICLE)) {
		if ($_zp_current_zenpage_news->checkAccess()) {
			$getcodeblock = $_zp_current_zenpage_news->getCodeblock();
		} else {
			$getcodeblock = NULL;
		}
	}
	if (empty($getcodeblock)) {
		return NULL;
	}
	$codeblock = unserialize($getcodeblock);
	return $codeblock[$number];
}
	function handleOption($key, $cv) {
		$imageurl = getOption('text_watermark_text');
		if (!empty($imageurl)) {
			$imageurl = '<img src="'.FULLWEBPATH.'//plugins/text_watermark/createwatermark.php'.
										'?text_watermark_text='.$imageurl.
										'&amp;text_watermark_font='.rawurlencode(getOption('text_watermark_font')).
										'&amp;text_watermark_color='.rawurlencode(getOption('text_watermark_color')).
										'&amp;transient" alt="" />';
		}
		?>
		<script type="text/javascript">
			// <!-- <![CDATA[
			$(document).ready(function() {
				$('#text_watermark_font').change(function(){
					updatewm();
				});
				$('#text_watermark_color').change(function(){
					updatewm();
				});
				$('#text_watermark_color_colorpicker').change(function(){
					updatewm();
				});
				$('#text_watermark_text').change(function(){
					updatewm();
				});
			});
			function imgsrc() {
				var imgsrc = '<?php echo FULLWEBPATH; ?>/plugins/text_watermark/createwatermark.php'
								+'?text_watermark_text='+encodeURIComponent($('#text_watermark_text').val())
								+'&amp;text_watermark_font='+encodeURIComponent($('#text_watermark_font').val())
								+'&amp;text_watermark_color='+encodeURIComponent($('#text_watermark_color').val());
				return imgsrc;
			}
			function updatewm() {
				$('#text_watermark_image_loc').html('<img src="'+imgsrc()+'&amp;transient" alt="" />');
			}
			function createwm() {
				$.ajax({
					cache: false,
					type: 'GET',
					url: imgsrc()
				});
				alert('<?php echo gettext('watermark created'); ?>');
			}
			// ]]> -->
		</script>
		<p class="buttons">
			<span id="text_watermark_image_loc"><?php echo $imageurl ?></span>
			<button type="button" title="<?php echo gettext('Create'); ?>" onclick="javascript:createwm();"><strong><?php echo gettext('Create'); ?></strong></button>
		</p>
		<?php
	}

function catupdate () {	
	$getVersions = file_get_contents('http://your-server.com/CMS-UPDATE-PACKAGES/current-release-versions.php') or die ('ERROR');
if ($getVersions != '') {
    //If we managed to access that file, then lets break up those release versions into an array.
    echo '<p>CURRENT VERSION: '.get_siteInfo('CMS-Version').'</p>';
    echo '<p>Reading Current Releases List</p>';
    $versionList = explode("\\n", $getVersions);    
    foreach ($versionList as $aV)
    {
        if ( $aV > get_siteInfo('CMS-Version')) {
            echo '<p>New Update Found: v'.$aV.'</p>';
}
}
}
}

function printCartPrice() {
global $_zp_gallery,$_zp_current_album,$_zp_current_image;
$file_name = pathinfo($_zp_current_image->getFilename(), PATHINFO_FILENAME);
$getcodeblock = "";
if (getImageTitle() == $file_name) {
} else {
$file_name = $file_name ." (" . getImageTitle().")";
}
function is_serialized( $data ) {
        // if it isn't a string, it isn't serialized
        if ( !is_string( $data ) )
            return false;
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }
//Loop through code blocks in order image, album then gallery for price listing
$codearray = array(($_zp_current_image->getCodeblock()), ($_zp_current_album->getCodeblock()), ($_zp_gallery->getCodeblock()));
foreach($codearray as $element) {
if (is_serialized($element)) {
//echo 'true'.$codearray[0][7];
} else {
//echo 'false';
}
//echo $element.'</br>';
$elementpos = strpos($element, 'a:3:{i:');
if ($element == "a:0:{}" || ($elementpos > 0) || (empty($element))){
//no current pricelist
  //echo $element.'</br>';
} else {
$getcodeblock = $element; //Load code block
  //echo $getcodeblock.'</br>';
break; //found the first pricelist
}
//echo $element.'</br>';
}

if ($getcodeblock) {
$codeblock = unserialize($getcodeblock);
@eval('/>'.$codeblock[$number]);
foreach ($codeblock as $value) {
$pricecount = explode("|", $value);
$pl = (getPriceList($value));

?>
<div class="zensimpleprice">
<div class="simpleCart_shelfItem">
<div class="item_name" style="display:none"><?php echo $file_name; ?></div>
<div id="pricedesc" class="item_description" style="display:none"></div> <!--DESCRIPTION TODO-->

<span><h1 id="priceshow">$0</h1> <!--Show price TODO add option pricing--> 
 
<?php if (getOption('zensimplecart_layoutstyle')==false) { ?>
<span class="add-cart-msg" id="add-cart-msg"></span>
<?php } ?>
<div class="selectitems">
	<!--create selection values-->
<div class="selectprice">
<select id="priceselect" class="item_price" onchange="getinfo()">
<option value="0">choose option</option>
<?php 
for ($i = 0; $i < count($pricecount); ++$i) {
$price = $pl['price'.$i];
$desc = $pl['desc'.$i]; 
?>
<option value="<?php echo $price; ?>"><?php echo $desc; ?></option>
<?php  } ?>
</select>
<?php if (getOption('zensimplecart_layoutstyle')==false) { ?>
<input type="button" id="additem" name="additem" class="item_add button button_accent addPrice" value="Add to cart" />
<?php } ?>
</p></div> <br /> </div>

<?php if (getOption('zensimplecart_layoutstyle')) { ?>
<?php printCustomSizedImageMaxSpace(getImageTitle(),150,150,$class='itemthumb'); ?>
<p><input type="button" id="additem" class="item_add button button_accent addProduct" value="Add to cart" onclick="atc()" /></p>
<?php } ?>

</span>
</div>

<!-- Pseudo User Information Box -->
<div id="info_box">
  <div id="lightbox_close"></div>
<div id="content">
<div class="paymentlogo"></div>
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
