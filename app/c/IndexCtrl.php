<?php
namespace app\c;

class IndexCtrl extends \common\appCore
{	
    public function __construct(){
		session_start();
	}
    public function index(){
		$url = 'http://yee.nbdon.com/reg.php';;
		$this->assign('data',['name' => 'Tom','url'=>$url]);
        $this->display('index.html');
	}
	
	public function login(){
	    $data = ['a','bb','cc'];
		echo 'login';
	}
	public function reg(){
		$this->assign('data',['name' => 'Fabien']);
        $this->display('index.html');
	}
	public function list(){
	    $name = 'admin';
	    $model = new \app\m\UserModel();
		$re = $model->getOne(2);
		$data = $model->select('pre_common_block_style', '*', [
            'styleid' => 1
        ]);


		dump($data);
		$this->assign('data',['name' => 'Fabien']);
        $this->display('list.html');
	}
	public function edit(){
		echo 'edit';
	}
	public function logout(){
// 		echo 'logout-TEST';
		$this->assign('data',['name'=>'Tom']);
        $this->display('log.html');
	}
}