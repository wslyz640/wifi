<?php
class NewsAction extends  AdminAction{
	public function index(){
		import('@.ORG.AdminPage');
		$db=D('News');
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result = $db->where($where)->field('id,title,info,mode,add_time')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();

	}
	
	public function del(){
			$id=I('get.id','0','int');
			$where['id']=$id;
			$db=D('News');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("没有此系统消息");
			}
			$db->where($where)->delete();
			$this->success('操作成功',U('index'));
     
	}
	
	public function edit()
	{
		if(IS_POST){
			$user = D('News');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	       
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save()){
	        				 $this->success('更新成功',U('index'));
	        	}else{
	        		$this->error("操作失败");
	        	}
	        }else{
	        			$this->error($user->getError());
	        }
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('News');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("info",$info);
			
	     
	        
	        
	        $this->display();        
		}
		
	}

	public function add(){
		if(IS_POST){
			$user=D('News');

	        if($user->create($_POST,1)){
	        	
	        	$id=$user->add();
	        	if($id>0){
					 $this->success('添加成功',U('index'));
	        		 	
	        	}else{
	        		$this->error('操作出错');
	        	}
	        }else{
	        	$this->error($user->getError());
	        }
		}else{
			
	        $this->display();      
		}  
	}
}