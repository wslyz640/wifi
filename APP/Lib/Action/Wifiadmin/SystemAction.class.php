<?php
class SystemAction extends AdminAction{
	public function index(){
		if(IS_POST){
			$this->sitesave();
		}else{
			$this->display();
		}
		
		
		
	}
	
	
	private  function sitesave(){
	
		$file=$this->_post('files');
		$act=$this->_post('action');
		
		unset($_POST['files']);
		unset($_POST['action']);
		unset($_POST[C('TOKEN_NAME')]);
	
		if($this->update_config($_POST,CONF_PATH.$file)){
	
			$this->success('操作成功',U('System/'.$act));
	
		}else{
	
			$this->success('操作失败',U('System/'.$act));
	
		}
	
	}
	
	private function update_config($new_config, $config_file = '') {
	
		!is_file($config_file) && $config_file = CONF_PATH . 'site.php';
	
		if (is_writable($config_file)) {
	
			$config = require $config_file;
	
			$config = array_merge($config, $new_config);
	
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
	
			@unlink(RUNTIME_FILE);
	
			return true;
	
		} else {
	
			return false;
	
		}
	
	}
	
	public function role(){
		import('ORG.Util.Page');
		
		$db=D('Role');
		$count=$db->count();
		$page=new Page($count,C('ADMINPAGE'));
		$info=$db->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('lists',$info);
		$this->assign('page',$page->show());
		$this->display();
	}
	public function editrole(){
		$db=D('Role');
		if(IS_POST){
			$id=I('post.id','0','int');
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				if($db->create($_POST,2)){
					$db->where($where)->save();
					$this->success("操作成功",U('Role'));
					
				}else{
					$this->error($db->getError());
				}
			}else{
				$this->error("没有此角色信息");
			}
			
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				
				$this->error("没有此角色信息");
			}
		}
		
		
	}

	public  function delrole(){
		$db=D('Role');
		$id=I('get.id','0','int');
			
			$where['id']=$id;
			$info=$db->where($where)->find();
			$adminwhere['role']=id;
			$count=D('Admin')->where($adminwhere)->count();
			if($info!=false){
				
				if($count>0){
					$this->error("当前角色还有用户存在，不能删除");
					exit;
				}
				
				$db->where($where)->delete();
				$this->success("删除成功",U('system/role'));
			
			}else{
				
				$this->error("没有此角色信息");
			}
	}
	
	public function addrole(){
		$db=D('Role');
		if(IS_POST){
				if($db->create()){
					if($db->add()){
						$this->success("操作成功",U('Role'));
					}else{
						$this->error("操作失败");
					}
				}else{
					$this->error($db->getError());
				}
			
			
		}else{
			$this->display();
		}
	}

	public function roleaccess(){
		$accdb=D('access');
		if(IS_POST){
			//dump($_POST);
			$del['role_id']=I('post.roleid');
			$accdb->where($del)->delete();
			foreach ($_POST['nodeid'] as $v){
				
				$data['role_id']=$del['role_id'];
				$data['node_id']=$v;
				$accdb->add($data);
				
			}
			$this->success('操作成功',U('Role'));
		}else{

//			/dump($trees);

			$id=I('get.id','0','int');
			
			$rolewhere['id']=$id;
			$roledb=D('Role');
			$info=$roledb->where($rolewhere)->find();
			if($info==false){
				$this->error("没有此角色信息");
			}else{
				$this->assign('role',$info);
			}
			$accwhere['role_id']=$id;
			$acc=$accdb->where($accwhere)->select();
			$rs="";
			foreach($acc as $k=>$v){
				$rs.="#".$v['node_id']."#";
			}
			$this->assign('acc',$rs);
			
			$db=D('treenode');
			$where['status']=1;
			$trees=$db->where($where)->select();
			//dump($rs);
			$this->assign('trees',$trees);
			$this->display();
		}
	}

	public function userlist(){
		 $role = M('Role')->getField('id,name');
		 $this->assign('role',$role);
		import('ORG.Util.Page');
		$db=D('Admin');
		$where['user']= array('neq',C('SPECIAL_USER'));
		$count=$db->where($where)->count();
		$page=new Page($count,C('ADMINPAGE'));
		
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('lists',$info);
		$this->assign('page',$page->show());
		$this->display();
	}
	
	public function AddUser(){
		
		if(IS_POST){
			$db=D('Admin');
			$password = $_POST['password'];
            $repassword = $_POST['repassword'];
            if(empty($password) || empty($repassword)){
                $this->error('密码必须！');
            }
            if($password != $repassword){
                $this->error('两次输入密码不一致！');
            }
			 if($db->create()){
                $user_id = $db->add();
                if($user_id){
                 	$data['user_id'] = $user_id;
                    $data['role_id'] = $_POST['role'];
                    if (M("RoleUser")->data($data)->add()){
                        $this->assign("jumpUrl",U('userlist'));
                        $this->success('添加成功！');
                    }else{
                        $this->error('用户添加成功,但角色对应关系添加失败!');
                    }
			 	}else{
			 		$this->error('添加失败!');
			 	}
			 }else{
			 	$this->error($db->getError());
			 }
		}else{
			$roledb=D('Role');
			$where['status']=1;
			$role=$roledb->where($where)->field('id,name')->select();
		
			$this->assign('role',$role);
			$this->display();
		}

	}
	
	public function edituser(){
		$db=D('Admin');
		if(IS_POST){
			
			$password = $_POST['password'];
            $repassword = $_POST['repassword'];
            if(!empty($password) || !empty($repassword)){
                if($password != $repassword){
                    $this->error('两次输入密码不一致！');
                }
                $_POST['password'] = md5($password);
            }
           

            if(empty($password) && empty($repassword)) unset($_POST['password']);   //不填写密码不修改
			$id=I('post.id','0','int');
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				if($db->create($_POST,2)){
					if($db->where($where)->save()){
						$rwhere['user_id'] = $_POST['id'];
	                    $data['role_id'] = $_POST['role'];
	                    M("RoleUser")->where($rwhere)->save($data);
	                    $this->assign("jumpUrl",U('userlist'));
	                    $this->success('编辑成功！');
					}else{
						$this->error("错误");
					}
				}else{
					$this->error($db->getError());
				}
			}else{
				$this->error("没有此用户信息");
			}
			
		}else{
			$id=I('get.id','0','int');
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				$this->assign('info',$info);
				$roledb=D('Role');
				$role=$roledb->field('id,name')->select();
				$this->assign('role',$role);
				$this->display();
			}else{
				$this->error("没有此用户信息");
			}
		}
	}
	
	public function deluser(){
		$db=D('Admin');
		$id=I('get.id','0','int');
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				$db->where($where)->delete();
				
				$this->error("删除成功",U('system/userlist'));
			}else{
				$this->error("没有此用户信息");
			}
	}
	
	public function setting(){
		if(IS_POST){
			$file=$this->_post('files');
			$act=$this->_post('action');
			
			unset($_POST['files']);
			unset($_POST['action']);
			unset($_POST[C('TOKEN_NAME')]);
		
			if($this->update_config($_POST,CONF_PATH.$file)){
		
				$this->success('操作成功',U('System/'.$act));
		
			}else{
		
				$this->success('操作失败',U('System/'.$act));
		
			}
		}else{
			$this->display();
		}
	}
}