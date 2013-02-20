<?php
/*
 * Simple shopping cart for Zenphoto CMS 
 * @package plugins
 * (c) Joseph Philbert
 * @package zenphoto.plugin.zensimplecart
*/

class zensimplecart {
	
	private $custom_datas;
	private $options = array(); 	// array to list ZenSimpleCart options...
	private $price_list = array();	// array to get price list
	private $zensimplecart = array(); 	// array to set all informations
	
	
	public function __destruct() {
	}
	
	/***
	*
	* Get Album Custom Data and Image Custom Datas
	* @return array
	*
	***/
	private function getCustomDatas() {
		$datas = array();
	    //Check image first if no data exsits then grab custom data from the album
		if(!empty($this->zensimplecart['image']['customdata'])) {
			$datas['image'] = $this->zensimplecart['image']['customdata'];
		}
		if(!empty($this->zensimplecart['album']['customdata'])) {
			$datas['album'] = $this->zensimplecart['album']['customdata'];
		}
		
		
		if(!empty($datas) && is_array($datas)) {
	
			foreach($datas as $key => $value) {

				if(strpos($value, ' ') === true) str_replace(' ', "\r", $value);

				$array = explode("\r", $value);

				foreach($array as $val) {
					$val = trim($val);
				
					if(preg_match('|^zensimplecart:|', $val)) {
					
						$string = substr($val, 10);
					
						if(strcmp($string, 'nopaypal') == 0) {
							$this->custom_datas[$key]['nopaypal'] = 1;
						}
						else {
							$array2 = explode(':', $string);
							$this->custom_datas[$key][$array2[0]] = str_replace('_', ' ',$array2[1]);
						}
					}
				}
				unset($val,$string,$array,$array2);
			}
			unset($datas);
		}
	}
	
	public function getOptions() {
		return $this->options;
	}
	
	
	public function printLinkCSS() {

		if(!empty($this->zensimplecart['image'])) {
			echo '<link rel="stylesheet" href="'.ZPP_PATH_CSS.'" type="text/css" />'."\n";
		}
		
	}

	
	private function setArrayOptions() {
		//require_once('array.options.php');
	}
	
	
	private function setConstants() {
		
	
	}
		
}
?>
