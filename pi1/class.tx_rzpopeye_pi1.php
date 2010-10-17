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

require_once(PATH_tslib.'class.tslib_pibase.php');
 
class tx_rzpopeye_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_rzpopeye_pi1';
	var $scriptRelPath = 'pi1/class.tx_rzpopeye_pi1.php';
	var $extKey        = 'rzpopeye';
	var $pi_checkCHash = true;	
	 		 
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		// Read Flexform
		$this->pi_initPIflexForm();		
		$text = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'text', 'sDEF');
		
		// Get images
		$images_all = $this->images();			
    
    // Output
		$content = '
      '.$images_all.'
      '.$this->pi_RTEcssText($text).'
      <div style="clear:both;"></div>'
    ;		                                                      
	
		return $content;	
  } 
  
  function images() {
  		$this->pi_loadLL();
  	    
      // Read Flexform
  		$this->pi_initPIflexForm();
  		$width = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'width', 'options'); 
  		$direction = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'direction', 'options'); 
      $duration = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'duration', 'options');
      $opacity = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'opacity', 'options');   
      $type = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'type', 'options');
      $show_caption = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'show_caption', 'options'); 
      $show_navigation = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'show_navigation', 'options'); 
      
      // Get maxWidth config
      $maxWidth = $this->conf['maxWidth'];
      $maxW = $this->conf['maxW'];
      
      // Read section      
      $sec = $this->cObj->data['pi_flexform']['data']['popeye']['lDEF']['imageOption']['el'];
      
      // Get images and caption in an array
      $images_array = array();
      $cap_arr = array();      
      foreach($sec as $s) {
        $images_array[] = $s['imageOptionContainer']['el']['image']['vDEF'];
        $cap_arr[] = $this->pi_RTEcssText($s['imageOptionContainer']['el']['caption']['vDEF']);
      } 
                    
      if(isset($duration) && is_numeric($duration)) $duration = $duration;
      else  $duration = '300';
            
      // Images and captions as arrays    
      $imgTSConfig = $this->conf['images.'];
      
      // Counter
      $i = 0;    
      foreach($images_array as $p) {     
        $imgTSConfig['titleText'] = $cap_arr[$i];
        $imgTSConfig['altText'] = $cap_arr[$i];       
        $imgTSConfig['file'] = 'uploads/pics/'.$p;    
        $imgTSConfig['file.']['width'] = $width;   	
        
        // If maxWidth is set use {$styles.content.imgtext.maxW}
        if($maxWidth == '1' && is_numeric($maxW)) {
          $imgConfigBig['file'] = 'uploads/pics/'.$p;
          $imgConfigBig['file.']['width'] = $maxW;
          $imgConfigOut = $this->cObj->IMG_RESOURCE($imgConfigBig);
          
          $imgTSConfig['imageLinkWrap.']['typolink.']['parameter'] = $imgConfigOut;
        } 
        // Or else show the original image       
        else {        			
    		  $imgTSConfig['imageLinkWrap.']['typolink.']['parameter'] = 'uploads/pics/'.$p;
    		}
        
        // Process the image
        $images_array .= $this->cObj->IMAGE($imgTSConfig);        
        
        $i++;    
      }
            
      // Get content element ID
      $ce_id = $this->cObj->data['uid'];
      
      // Set popeye div            
      $content = '
        <div id="ppy'.$type.'" class="ppy-'.$ce_id.'">
          <ul class="ppy-imglist">'.$images_array.'</ul>
          <div class="ppy-outer">
            <div class="ppy-stage">
              <div class="ppy-nav">
                <a class="ppy-prev" title="'.$this->pi_getLL('previousLabel').'">'.$this->pi_getLL('previousLabel').'</a>
                <a class="ppy-switch-enlarge" title="'.$this->pi_getLL('enlargeLabel').'">'.$this->pi_getLL('enlargeLabel').'</a>
                <a class="ppy-switch-compact" title="'.$this->pi_getLL('closeLabel').'">'.$this->pi_getLL('closeLabel').'</a>
                <a class="ppy-next" title="'.$this->pi_getLL('nextLabel').'">'.$this->pi_getLL('nextLabel').'</a>
              </div>
            </div>
          </div>
          <div class="ppy-caption">
            <div class="ppy-counter">
              '.$this->pi_getLL('imageLabel').' <strong class="ppy-current"></strong> '.$this->pi_getLL('ofLabel').' <strong class="ppy-total"></strong> 
            </div>
            <span class="ppy-text"></span>
          </div>
        </div>
      ';
      
      // Set JS for content element
      $content .= "     
        <script type=\"text/javascript\">            
          jQuery(document).ready(function () {
            var options = {
                direction: '$direction',
                opacity: '$opacity',
                caption: '$show_caption',
                navigation: '$show_navigation',
                duration: $duration 
            }             
            jQuery('.ppy-".$ce_id."').popeye(options);         
          });              
        </script>      
      ";
      
      return $content;                  
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzpopeye/pi1/class.tx_rzpopeye_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rzpopeye/pi1/class.tx_rzpopeye_pi1.php']);
}

?>