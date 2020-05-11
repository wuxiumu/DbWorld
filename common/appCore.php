<?php

namespace common;

class appCore 
{
	public static $classMap = array();

	public $assign;

   /* 这是一个魔术方法，当一个对象或者类获取其不存在的属性的值时，
    * 如：$obj = new BaseController ;
    * $a = $obj -> a ;
    * 该方法会被自动调用,这样做很友好，可以避免系统报错
    */
    public function __get($property_name){
        $msg = "属性 $property_name 不存在\n";
        self::reportingDog($msg);	
    }

   /* 这是一个魔术方法，当一个对象或者类给其不存在的属性赋值时，
    * 如：$obj = new BaseController ;
    * $obj -> a = 12 ;
    * 该方法(__set(属性名,属性值))会被自动调用,这样做很友好，可以避免系统报错
    */
    public function __set($property_name,$value){
        $msg = "属性 $property_name 不存在\n";
		self::reportingDog($msg);	
    }

   /* 这是一个魔术方法，当一个对象或者类的不存在属性进行isset()时，
    * 注意：isset 用于检查一个量是否被赋值 如果为NULL会返回false
    * 如：$obj = new BaseController ;
    * isset($obj -> a) ;
    * 该方法会被自动调用,这样做很友好，可以避免系统报错
    */
    public function __isset($property_name){
        $msg = "属性 $property_name 不存在\n";
        self::reportingDog($msg);	
    }

   /* 这是一个魔术方法，当一个对象或者类的不存在属性进行unset()时，
    * 注意：unset 用于释放一个变量所分配的内存空间
    * 如：$obj = new BaseController ;
    * unset($obj -> a) ;
    * 该方法会被自动调用,这样做很友好，可以避免系统报错
    */
    public function __unset($property_name){
        $msg = "属性 $property_name 不存在\n";
        self::reportingDog($msg);	
    }

    /* 当对这个类的对象的不存在的实例方法进行“调用”时，会自动调用该方法，
     * 这个方法有2个参数（必须带有的）：
     * $methodName 表示要调用的不存在的方法名;
     * $argument 是一个数组，表示要调用该不存在的方法时，所使用的实参数据，
     */
    public function __call($methodName,$argument){
		$msg = "实例方法 $methodName 不存在\n";
        self::reportingDog($msg);	
    }
    
	public static function run()
	{				 
        include('appRoute.php');
        $route = new appRoute();
    	$ctrlClass = $route->ctrl;
    	$action = $route->action;		
		$ctrlfile = APP.'/c/'.$ctrlClass.'Ctrl.php';
		$ctrlClass = '\\'.MODULE.'\c\\'.$ctrlClass.'Ctrl';
		if(is_file($ctrlfile)){			
			include $ctrlfile;			
			$ctrl = new $ctrlClass();
			$ctrl->$action();					
		}else{	
			echo "控制器 $ctrlClass 不存在\n";		 
		}
	}

	public static function load($class)
	{
		if(isset($classMap[$class])){
			return true;
		}else{
			$class  = str_replace('\\', '/', $class);
			$file = PHPMSFRAME.'/'.$class.'.php';
			if(is_file($file)){
				include $file;
				self::$classMap[$class] = $class; 
			}else{
				return false;
			}
		}
	}

	public function assign($name,$value){
		$this->assign[$name]=$value;
	}

	public function display($file){
		$file_path = PHPMSFRAME.'/templates/'.$file;
      	if(is_file($file_path)){		
            $loader = new \Twig\Loader\FilesystemLoader(PHPMSFRAME.'/templates');
            $twig = new \Twig\Environment($loader, [
                'cache' => PHPMSFRAME.'/cache',
                'auto_reload' => true,  //根据文件更新时间，自动更新缓存
            ]);
            $template = $twig->load($file);
            $template->display($this->assign?$this->assign:'');
    	}else{
    	    echo '没有<code>'.$file_path.'</code>文件';die;
    	}
	}
	
	private static function reportingDog($msg){
		echo $msg."\n";				
		exit;
	}
}