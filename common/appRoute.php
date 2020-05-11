<?php

namespace common;

class appRoute
{
	public $ctrl;

	public $action;

	public function __construct(){

		if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
			$path = $_SERVER['REQUEST_URI'];
			if(strpos($path,'/index.php') !== false){		 
				$path=str_replace('/index.php','',$path);
				$path = trim($path,'/');
				if(strlen($path)=='0'){
        			$this->ctrl = 'Index';
        			$this->action = 'index';
					return;
				} 							
			}else{
				$path = trim($path,'/');				
			}			 
			$patharr = explode('/', $path) ;
			if(count($patharr)%2!=0){
				$patharr[]='index';
			}	
			if(STRICT){
				if(isset($patharr[0])){						
			    	$this->ctrl = $patharr[0];
			    }				
			}else{
				if(isset($patharr[0])){						
			    	$this->ctrl = ucfirst($patharr[0]);
			    }				
			}	
			unset($patharr[0]);
			if(isset($patharr[1])){
				$this->action = $patharr[1];
			}else{
				$this->action  = 'index';
			}
			unset($patharr[1]);
			$count = count($patharr)+2;
			$i = 2;
			while ( $i < $count) {
				if(isset($patharr[$i+1])){
					$_GET[$patharr[$i]] = $patharr[$i+1];
					$i = $i+2;
				}				
			}
		}else{
			$this->ctrl = 'Index';
			$this->action = 'index';
		}		
	}
}