<?php
class IndexAction extends AdminAction{
	
	public function  index(){

		$this->display();
	}
	public function pwd(){
		if(IS_POST){
				$pwd=I('post.password');
				if($pwd==""){
					$data['error']=1;
			    	$data['msg']="新密码不能为空";
			    	return $this->ajaxReturn($data);
				}
				if(!validate_pwd($pwd)){
					$data['error']=1;
			    		$data['msg']="密码由4-20个字符 ，数字，字母或下划线组成";
			    		return $this->ajaxReturn($data);
				}
							
				$db=D('Admin');
				$info=$db->where(array('id'=>$this->userid))->field('id,user,password')->find();
				log::write($info['password']);
				if(md5($_POST['oldpwd'])!=$info['password']){

			
						$data['error']=1;
			    		$data['msg']="旧密码不正确";
			    		return $this->ajaxReturn($data);
				}
			
			
			$_POST['update_time']=time();
			$_POST['password']=md5($_POST['password']);
			$where['id']=$this->userid;
				if($db->where($where)->save($_POST)){
					$data['error']=0;
		    		$data['msg']="更新成功";
		    		return $this->ajaxReturn($data);
				}else{
					$data['error']=1;
		    		$data['msg']=$db->getError();
		    		return $this->ajaxReturn($data);
				}
		}else{
			$this->display();
		}
	}
}