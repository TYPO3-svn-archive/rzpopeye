<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Raphael Zschorsch <rafu1987@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'jQuery Popeye' for the 'rzpopeye' extension.
 *
 * @author	Raphael Zschorsch <rafu1987@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_rzpopeye
 */
 
class tx_rzpopeye_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_rzpopeye_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_rzpopeye_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'rzpopeye';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */   	 	
	 	
function images() {
		$this->pi_loadLL();
	    
    // Read Flexform
		$this->pi_initPIflexForm();
		$images = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'images', 'popeye');
		$caption = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'caption', 'popeye');
		$width = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'width', 'options'); 
		$align = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'align', 'options'); 
		$direction = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'direction', 'options'); 
    $duration = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'duration', 'options');
    $opacity = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'opacity', 'options');  
    $countpos = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'countpos', 'options');  
    $noscript = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'noscript', 'options'); 
    $oflabel = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'ofLabel', 'options'); 
  
    if($duration == '') {
      $duration = '300';
    }
    else {
      $duration = $duration;
    } 
    
    if($noscript == '') {
      $noscript = 'This element will be deleted once the gallery loads';
    }
    else {
      $noscript = $noscript;
    }
  
    $caption_array = explode("\n", $caption); 

    // Images and captions as arrays    
    $images_array = '';
    $imgTSConfig = $this->conf['images.'];
    $pics = explode(',',$images);
    $i = 0;    
    foreach($pics as $p) {
    
      $imgTSConfig['titleText'] = $caption_array[$i];
      $imgTSConfig['altText'] = $caption_array[$i];       
      $imgTSConfig['file'] = 'uploads/pics/'.$p;    
      $imgTSConfig['file.'][width] = $width;
 				
  		$imgTSConfig['imageLinkWrap.']['typolink.']['parameter'] = 'uploads/pics/'.$p;
      $images_array .= $this->cObj->IMAGE($imgTSConfig);
      
      $i++;

    }
    
    // If ofLabel in Flexform is empty, use LL Value
    if ($oflabel == '') {
      $oflabel = $this->pi_getLL('ofLabel');
    }    
    else {
      $oflabel = $oflabel;  
    }
                
    return "<div id=\"popeye$align\" class=\"ppy\"><ul>".$images_array."</ul><div class=\"ppy-no-js\">
    ".$noscript."</div></div> 

    
<script type=\"text/javascript\">
    <!--//<![CDATA[
    
    $(document).ready(function () {
        var options = {
            direction: '$direction',
            countpos: '$countpos', 
            duration: '$duration', 
            opacity: '$opacity', 
            oflabel: '".$oflabel."'
        }
    
        $('#popeye$align').popeye(options);

    });
    
    //]]>-->
</script>
    
    "; 
                  
  }
	 
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		$this->pi_initPIflexForm();
		
		$text = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'text', 'sDEF');
		$images_all = $this->images();			
    
		$content = "".$images_all."".$this->pi_RTEcssText($text)."";		                                                      
	
		return "<div style=\"clear:both;\">".$content."</div>";
	
} 

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzpopeye/pi1/class.tx_rzpopeye_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzpopeye/pi1/class.tx_rzpopeye_pi1.php']);
}

?>