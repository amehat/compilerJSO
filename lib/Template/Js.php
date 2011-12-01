<?php
class Template_Js {
	
	public function getContent ($options){
		$content = '';
		$content .= $this->getConstructer ($options);
		$content .= $this->getMethod ($options);
		$content .= "\n";
		$content .= '}';
		return $content;
	}
	
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
		//$content .= '}';
		//$content .=  "\n". "\n";
		$content .= '	' . $options['dataConstructer'];
		$content .= "\n";
		return $content;
	}
	
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
				//$content .= '		}';
				$content .= "\n";
				//$content .= "\n";
			}
		}
		$content .= '		'.$options['className'].'.initialized = true; ';
		$content .= "\n";
		$content .= '	}';
		
		return $content;
	}
}