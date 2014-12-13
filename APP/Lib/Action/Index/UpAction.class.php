<?php
class UpAction extends BaseAction{
	public function index(){
		echo "<a href='".U('up/doup')."'>开始更新</a>";
	}
	
	public function doup(){
		$db=new Model();
		//
		$sql="Describe ".C('DB_PREFIX')."shop sh";
		$info=$db->query($sql);
		if($info==false){
			//add
			$sql="ALTER TABLE `".C('DB_PREFIX')."shop` ADD `sh` int(11) NOT NULL DEFAULT '0'";
			$t=$db->execute($sql);
		}
		
		$sql="Describe ".C('DB_PREFIX')."shop eh";
		$info=$db->query($sql);
		if($info==false){
			//add
			$sql="ALTER TABLE `".C('DB_PREFIX')."shop` ADD `eh` int(11) NOT NULL DEFAULT '23'";
			$t=$db->execute($sql);
		}
		$sql="Describe ".C('DB_PREFIX')."pushadv aid";
		$info=$db->query($sql);
		if($info==false){
			//add
			$sql="ALTER TABLE `".C('DB_PREFIX')."pushadv` ADD `aid` int(11) NOT NULL DEFAULT '0'";
			$t=$db->execute($sql);
		}
		$this->success("更新完成");
	}
}