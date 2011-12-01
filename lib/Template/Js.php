<?php
/**
  * Formattage du contenu du fichier JS
  * @package Template
  * @author Arnaud Mehat
  * @version 1.0
  */
class Template_Js {
	
	/** 
	  * Retourne le contenu
	  * @param Array $options
	  * @return String $content
	  * @access public
	  */
	public function getContent ($options){
		$content = '';
		$content .= $this->getConstructer ($options);
		$content .= $this->getMethod ($options);
		$content .= "\n";
		$content .= '}';
		return $content;
	}
	
	/**
	  * Retourne le constructeur 
	  * @param Array $options
	  * @return String $content
	  * @access public
	  */
	public function getConstructer ($options){
		$content  = 'function '.$options['className']. ' (';
		$fn = $options['function'];
		for ( $j=0; $j<count($fn); $j++){
			if ('__construct' == $fn[$j]['name'] ){ 
				for ($i=0; $i<count($fn[$j]['parameters']); $i++){
					$content .= $fn[$j]['parameters'][$i] . ', ';
				}
				if ( $j != 0 ){
					$content = substr($content, 0, (strlen($content)-2));
				}
			}
		}
		$content .= '){ ' . "\n";
		$content .= '	'.$options['data'] . "\n";
		$content .= '	' . $options['dataConstructer'];
		$content .= "\n";
		return $content;
	}
	
	/**
	  * Retourne les methodes
	  * @param Array $options
	  * @access public
	  * @return String $content
	  */
	public function getMethod ($options){
		$content  = '	if ( typeof '.$options['className'].'.initialized == "undefined" ) {';
		$content .= "\n\n";
		$fn = $options['function'];
		for ( $j=0; $j<count($fn); $j++){
			if ('__construct' != $fn[$j]['name'] ){ 
				$content .= '		' . $options['className'] .'.prototype.';
				$content .= $fn[$j]['name'] . ' = function (';
				for ($i=0; $i<count($fn[$j]['parameters']); $i++){
					$content .= $fn[$j]['parameters'][$i] . ', ';
				}
				if ( 0 != count($fn[$j]['parameters']) ){
					$content = substr($content, 0, (strlen($content)-2));
				}
				$content .= '){ '. "\n";
				$content .= '	'.$fn[$j]['data'] . "\n";
				$content .= "\n";
			}
		}
		$content .= '		'.$options['className'].'.initialized = true; ';
		$content .= "\n";
		$content .= '	}';
		
		return $content;
	}
}