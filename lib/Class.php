<?php
/**
  * Recuperation des donnees de la classe
  * @author Arnaud Mehat
  * @version 1.0
  */
class Classe {
	
	private $content;
	private $aContent = array();
	private $aContentLine = array ();
	private $option;
	private $functions = array ();
	private $lineFirstFunction = 0;
	
	/**
	  * constructeur
	  * @param String $content
	  * @return void
	  * @access public
	  */
	public function __construct ( $content ){
		$this->content = $content;	
		
		$aContent = explode ("\n", $content);
		$this->aContentLine = $aContent;
		$this->aContent = array ();
		for ($i=0; $i<count($aContent); $i++){
			$c = trim($aContent[$i]);
			$c = str_replace (')', ' ) ', $c);
			$c = str_replace ('(', ' ( ', $c);
			$c = str_replace ('}', ' } ', $c);
			$this->aContent[] = explode (' ', $c);	
		}
		
		$cTemp = array ();
		for ( $i=0; $i <count($this->aContent); $i++ ){
			$cTemp[$i] = array ();
			for ($j=0; $j < count($this->aContent[$i]); $j++){
					if ( (' ' != $this->aContent[$i][$j]) && ('' != $this->aContent[$i][$j]) ){
						$cTemp[$i][] = $this->aContent[$i][$j]; 	
					}
			}
		}
		$this->aContent = $cTemp;
	
		$this->setOption ('className', $this->getClassName());
		$this->setOption ('extends', $this->getClassExtends());
		$this->setOption ('function', $this->getFunctionName());
		$this->setOption ('firstFunction', $this->lineFirstFunction);
		$this->setOption ('dataConstructer', $this->getDataConstructer());
		$this->setOption ('sourceArray', $this->aContent);
	}
	
	/**
	  * retourne le nom de la classe
	  * @access public
	  * @return void
	  * @access public
	  */
	public function getClassName (){
		foreach ($this->aContent as $line){
			if ( 'class' == $line[0]){
				return $line[1];	
			}
		}
	}	
	
	/**
	  * Retourne le nom de la classe etendue
	  * @access public
	  * @return void
	  */
	public function getClassExtends (){
		foreach ($this->aContent as $line){
			if ( ('class' == $line[0]) && ('extends' == $line[2]) ){
				return $line[3];	
			} else {
				return '';	
			}
		}
	}
	
	/**
	  * retourne le contenu du constructeur
	  * @return void
	  * @access public
	  */
	public function getDataConstructer (){
		$start;
		$end = $this->lineFirstFunction;
		for ($i=0; $i < count($this->aContent); $i++){
			for ($j=0; $j < count($this->aContent[$i]); $j++){
				if ('__construct' == $this->aContent[$i][$j] ){
					$start = $i;
				}	
			}	
		}
		
		$content = '';
		$start++;
		for ($i=$start; $i < ($this->lineFirstFunction-1); $i++){
			if ( $i != $start ){
				$content .= '	';	
			}
			$content .= trim($this->aContentLine[$i]) . "\n";
		}
		$content = rtrim($content);
		if ( '}' == substr($content, strlen($content)-1, strlen($content)) ){
			$content = substr($content, 0, strlen($content)-1);
		}
		return $content;	
	}
	
	/**
	  * retourne le nom du fonction
	  * @access public
	  * @return String $this->functions
	  */
	public function getFunctionName (){
		for($i=0; $i < count($this->aContent); $i++){
			$content = $this->aContent[$i];	
			for ($j=0; $j<count($content);$j++){
				$k = $j+1;
				if ( ('function' == $content[$j]) && ('__construct' != $content[$k])){
					if (0 == $this->lineFirstFunction ){
						$this->lineFirstFunction = $i;
					}
				}
				if ( 'function' == $content[$j]){
					$data = $this->getDataFunction ($i);
					
					$aLineBegin = explode ('(', $this->aContentLine[$i]);
					$aParams = explode (')', $aLineBegin[1]);
					$aParametersBruts = explode (',', $aParams[0]);
					$aParameters = array ();
					foreach ($aParametersBruts as $keyParam => $valueParam){
						if (( '' != $valueParam) && (' ' != $valueParam) ){
								$aParameters[] = trim($valueParam);
						}
					}
					$this->functions[] = array('name' => $content[$k], 'parameters'=>$aParameters, 'data' => $data);
					$iFunctions = count($this->functions);
				}	
			}
		} 
		return $this->functions;		
	}
	
	/**
	  * Retourne le contenu des fonctions
	  * @param Int $iCurrent
	  * @access public
	  * @return Array $data
	  */
	public function getDataFunction ($iCurrent){
		$openTag = 1;
		$closeTag = 0;
		$data = '';
		$iCurrent++;
		
		for ($i=$iCurrent; $i<count($this->aContentLine); $i++){
			if ( $i == $iCurrent ){
				$data = '';	
			}
			
			$countCloseTag = strcspn($this->aContentLine[$i], '}');
			$closeTag = $closeTag + $countCloseTag;
			if ( $i != $iCurrent ){
				$data .= '	';	
			}
			$data .= $this->aContentLine[$i] . "\n";
			if ( $openTag == $closeTag ){
				$data = rtrim($data);
				if ( '}' == substr($data, strlen($data)-1, strlen($data)) ){
					$data = substr($data, 0, strlen($data)-1);
					$data .= "}";
				}
				return $data;
			}
			
			$countOpenTag = strcspn($this->aContentLine[$i], '{');
			$openTag = $openTag + $countOpenTag;

		}
		return $data;
	}
	
	/**
	  * Permet l'ajout d'une option
	  * @param String $key
	  * @param String $value
	  * @return void
	  * @access public
	  */
	public function setOption ($key, $value){
		$this->option[$key] = $value;	
	}
	
	/**
	  * Retourne la liste des options
	  * @return Array $this->option
	  * @access public
	  */
	public function getOption (){
		return $this->option;	
	}
}