<?php
class Compiler {
	
	private $params;
	private $file;
	private $contentFile;
	private $output = false;
	private $stop = false;
	
	public function __construct ( $params ){
		$this->params = $params;
		echo '******* Compiler JSO *******';
		echo "\n";
		$this->run ();
	}	
	
	public function getFile (){
		if ( is_array($this->params) ){
			
			// help
			if ( false === array_search('-h', $this->params) ){
				//	
			} else {
				$this->stop = true;
				echo "Compiler JSO help \n";
				echo "Syntax : \n";
				echo "	php cli.php -f /home/me/myfile.jso \n";
				echo "list options : \n";
				echo "-f {file to compiler} \n";
				echo "	example : -f /home/me/myfile.jso\n";
				echo "-h help\n";
				echo "	example : -h \n";
				return false;
			}
			
			// version
			if ( false === array_search('-v', $this->params) ){
				//
			} else {
				$this->stop = true;
				echo "Compiler JSO version \n";	
				echo "Version : 1.0.0\n";
				return false;
			}
			
			// file (.jso) to transform (.js)
			if ( false === array_search('-f', $this->params) ){
				echo 'require option -f';	
			} else {
				$keyOption = array_search('-f', $this->params);
				if ( $this->params[$keyOption+1] ){
					$this->file = $this->params[$keyOption+1];
					$this->getContentFile ( $this->file );
				
				} else {
					if ( false === array_search('-h', $this->params) ){
						echo 'require value for option -f';	
					}
				}
			}
			
			// path destination
			if ( false === array_search('-p', $this->params) ){
				//	
			} else {
				$keyOption = array_search('-p', $this->params);
				if ( $this->params[$keyOption+1] ){
					$this->output = $this->params[$keyOption+1];
					if ( DIRECTORY_SEPARATOR != substr($this->output, strlen($this->output)-1, strlen($this->output) ) ){
						$this->output .= DIRECTORY_SEPARATOR;
					}
				} else {
					echo 'require value for option -p';	
				}
			}
			
			
		} else {
			echo 'Error parameters';	
		}	
	}
	
	public function getContentFile ( $file ){
		if ( file_exists ($file) ){
			if ($fp = @fopen ($file, 'r')){
				$this->contentFile = fread($fp, filesize($file));
				fclose($fp);
			}
		} else{
			echo "File not exist\n";	
		}
	} 
	
	public function createFile ( $file, $content ){
		if ( false == $this->output ){
			$aSource = explode(DIRECTORY_SEPARATOR, $file);
			
			$count = count($aSource);
			if (0 != $count ){
				$count = $count-1;
			}
			$path = str_replace('.jso', '.js', $file);
			$fileJs = $path;
		} else {
			$aOutput = explode (DIRECTORY_SEPARATOR, $this->file);
			$c = count($aOutput);
			if ( 0 != $c ){
				$c = $c-1;
			}
			$this->file = $aOutput[$c];
			$fileJs = $this->output . $this->file;	
			$fileJs = str_replace('.jso', '.js', $fileJs);
		}
		echo 'file JSO : ' .$file . "\n";
		echo 'file JS  : ' . $fileJs . "\n";
		if ($fp = @fopen ($fileJs, 'w+') ){
			fwrite($fp, $content);
			fclose ($fp);
			return true;
		} else {
			return false;	
		}
	}
	
	public function run (){
		$this->getFile ();	
		if ( false == $this->stop ){
			$class  = new Classe ( $this->contentFile );
			$js		= new Template_Js ();
			
			$content = $js->getContent ( $class->getOption () );
			//print_r($class->getOption ());
			if ( $this->createFile ($this->file, $content) ){
				echo 'Success create file';	
				echo "\n";
			} else {
				echo 'Error create file';
				echo "\n";	
			}
			echo '****************************';
			echo "\n";
		} else {
			echo '****************************';
			echo "\n";	
		}
	}
}