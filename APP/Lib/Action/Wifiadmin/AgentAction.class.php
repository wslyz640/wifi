<?php
class AgentAction extends  AdminAction{
	public function index(){
		import('@.ORG.AdminPage');
		$db=D('Agent');
		
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result = $db->where($where)->field('id,account,name,add_time,linker,phone,money')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
		
		
	}
	
	private  function ShowAjaxError($msg){
				$data['msg']=$msg;
				$data['error']=1;
				$this->ajaxReturn($data);
	} 
	
	public function add(){
		if(IS_POST){
			if(empty($_POST['level'])){
				$this->ShowAjaxError('请选择代理商级别');
			}
			$lvid=intval( $_POST['level']);
			if($lvid<0){
				$this->ShowAjaxError('请选择代理商级别');
			}
			$db=D('Agent');
			if($db->create()){
				$insertid=$db->add();
				if($insertid){
					$data['url']=U('index');
					$data['error']=0;
					$this->ajaxReturn($data);
				}else{
					$this->ShowAjaxError('添加代理商操作失败');
				}
			}else{
				$this->ShowAjaxError($db->getError());
			}
			
			
		}else{
			$lvdb=D('Agentlevel');
			$where['state']=1;
			$lvinfo=$lvdb->where($where)->field('id,title') ->select();
			$this->assign('lvlist',$lvinfo);
			$this->display();
		}
	}
	
	public function edit(){
		$db=D('Agent');
		if(IS_POST){
				if(empty($_POST['level'])){
					$this->ShowAjaxError('请选择代理商级别');
				}
				$lvid=intval( $_POST['level']);
				if($lvid<0){
					$this->ShowAjaxError('请选择代理商级别');
				}
			     if(!empty($_POST['password']) ){
	               	$password = $_POST['password'];
	               	$_POST['password']=md5($password);
	            }else{
	            	unset(	$_POST['password']);
	            }
				
				$id=I('post.id','0','int');
				$where['id']=$id;
				$info=$db->where($where)->find();
				if($info!=false){
					if($db->create($_POST,2)){
						if($db->where($where)->save()){
							
							$data['url']=U('index');
							$data['error']=0;
							$this->ajaxReturn($data);
						}else{
							$this->ShowAjaxError('编辑失败');
						}
					}else{
						$this->ShowAjaxError($db->getError());
						
					}
				}else{
					$this->ShowAjaxError('没有此代理商信息');
				}
		}else{
			$lvdb=D('Agentlevel');
			$where['state']=1;
			$lvinfo=$lvdb->where($where)->field('id,title') ->select();
			$this->assign('lvlist',$lvinfo);
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				
				$this->error("没有此等级信息");
			}
		}
	}
	
	public  function del(){
			$id=I('get.id','0','int');
			$db=D('Agent');
			$dbshop=D('shop');
			$agwhere['pid']=$id;
			$count=$dbshop->where($agwhere)->count();
			if($count>0){
				$this->error("当前代理商包含商户账号，不能删除");
			}
			$where['id']=$id;
			$db->where($where)->delete();
			$this->success("操作成功",U('index'));
			
	}
	
	public function level(){
		import('@.ORG.AdminPage');
		$db=D('Agentlevel');
		
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result = $db->where($where)->field('id,title,openpay,add_time,state')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	
	
	public function addlevel(){
		if(IS_POST){
			$db=D('Agentlevel');
			
			if($db->create()){
				if($db->add()){
					$this->success("添加成功",U('level'));
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
	
	public function editlevel(){
		$db=D('Agentlevel');
		if(IS_POST){
			
			$id=I('post.id','0','int');
				$where['id']=$id;
				$info=$db->where($where)->find();
				if($info!=false){
					if($db->create($_POST,2)){
						if($db->where($where)->save()){
							$this->success("操作成功",U('level'));
						}else{
							$this->error("没有此角色信息");
						}
					}else{
						$this->error($db->getError());
					}
				}else{
					$this->error("没有此等级信息");
				}
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				
				$this->error("没有此等级信息");
			}
		}
	}
	
	public function delevel(){
		$id=I('get.id','0','int');
		$db=D('Agentlevel');
		$dbag=D(Agent);
		$agwhere['level']=$id;
		$count=$dbag->where($agwhere)->count();
		if($count>0){
			$this->error("当前等级包含代理商账号，不能删除");
		}
		$where['id']=$id;
		$db->where($where)->delete();
		$this->success("操作成功",U('level'));
	}

	public function paylist(){
		import('ORG.Util.Page');
		$db=D('Agentpay');
		
		$count=$db->where()->count();
		$page=new Page($count,C('ADMINPAGE'));
		$sql="select a.*,b.account,b.name from ".C('DB_PREFIX')."agentpay a left join ".C('DB_PREFIX')."agent b on a.aid=b.id order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
        //$result = $db->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
		$result=$db->query($sql);
		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	public function dopay(){
		if(IS_POST){
			$dbagent=D('Agent');
			$id=I('post.aid','0','int');
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
			if(!$info){
				$this->error("没有此代理商信息");
			}
			
			$db=D('Agentpay');
			$do=$_POST['do'];
			$money=$info['money']==null?0:$info['money'];	
			$pay=$_POST['paymoney']==null?0:$_POST['paymoney'];
			
			$paydata['oldmoney']=$oldmoney;
	        $paydata['nowmoney']=$nowmoney;
	        if($db->create()){
	        	if($do=="0"){
					if($money<$pay){
						$this->error("当前帐号余额不足，无法扣款");
					}
					$oldmoney=$money;
					$nowmoney=$money-$pay;
				
				}else{
					$oldmoney=$money;
					$nowmoney=$money+$pay;
				}
				
				if($db->add()){
					if($do=="0"){
						$dbagent->where($where)->setDec('money',$pay);
					}else{
						$dbagent->where($where)->setInc('money',$pay);
					}
					$this->success("操作成功",U('index'));
				}else{
					$this->error("操作失败");
				}
	        }else{
	        	$this->error($db->getError());
	        }
	        
			
		}else{
			$dbagent=D('Agent');
			$id=I('get.id','0','int');
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
			if(!$info){
				$this->error("没有此代理商信息");
			}
			
			$this->assign('info',$info);
			$this->display();
		}
	}
}