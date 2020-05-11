<?php

namespace app\m;

use \common\appModel;

class UserModel extends appModel
{
	public $table = 'pre_common_member';

	public function adduser($data)
	{
		return $this->insert($this->table,$data);		 		 
	}

	public function finduser($where)
	{
		$columns = ['uid'];
		return $this->get($this->table,$columns,$where);		 		 
	}

	public function lists()
	{
		$ret = $this->select($this->table,"*");
		return $ret;
	}

	public function getOne($id){
		$ret = $this->get($this->table,'*',['uid'=>$id]);
		return $ret;
	}

	public function updateOne($id,$data){
		$ret = $this->update($this->table,$data,['uid'=>$id]);
		return $ret;
	}

	public function delOne($id){
		$ret = $this->delete($this->table,['uid'=>$id]);
		return $ret;
	}
}