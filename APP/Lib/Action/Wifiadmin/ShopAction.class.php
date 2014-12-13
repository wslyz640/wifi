<?php
class ShopAction extends  AdminAction{
	public function index(){
		import('@.ORG.AdminPage');
		$db=D('Shop');
		
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		$sql=" select a.id,a.shopname,a.add_time,a.linker,a.phone,a.account,a.maxcount,a.linkflag,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
        $result = $db->query($sql);
     
        
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	public function editshop()
	{
		if(IS_POST){
			$user = D('Shop');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	        
	       $_POST['update_time']=time();
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save($_POST)){
	        		$data['error']=0;
		    		$data['url']=U('index');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        }
		}else{
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('Shop');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("shop",$info);
			
	
			include CONF_PATH.'enum/enum.php';//$enumdata
	        $this->assign('enumdata',$enumdata);
	     
	        
	        
	        $this->display();        
		}
		
	}

	public function addshop(){
		if(IS_POST){
			$user=D('Shop');
			$now=time();
			$_POST['pid']=0;
			$_POST['authmode']='#0#';
		
	        if($user->create($_POST,1)){
	        	
	        	$id=$user->add();
	        	if($id>0){
	
	        		$rs['shopid']=$id;
		    		$rs['sortid']=0;
		    		$rs['routename']=$_POST['shopname'];
		    		$rs['gw_id']=$_POST['account'];
		    			
			        M("Routemap")->data($rs)->add();
	        		$data['error']=0;
		    		$data['url']=U('index');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getDbError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        }
		}else{
			include CONF_PATH.'enum/enum.php';//$enumdata
	        $this->assign('enumdata',$enumdata);
	        $this->display();      
		}  
	}
}