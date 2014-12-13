<?php
class IndexAction extends  BaseAction{
	
	private  $aid;//代理商用户ID
	protected  function _initialize(){
		parent::_initialize();
		if(!session('aid')||session('aid')==null||session('aid')==''){
			$this->redirect('index/index/alog');
		}else{
			$this->aid=session('aid');
			$this->loadMenu();
		}
	}
	
	
	
	private  function  loadMenu(){
		$path=CONF_PATH.GROUP_NAME."/Menu.php";
		if(is_file($path)){
			$config = require $path;
		}
		$this->assign("menu",$config);
	}
	public function index(){
		$nav['m']=$this->getActionName();
		$nav['a']='index';
		$this->assign('nav',$nav);
		$this->display();
	}
	
	public function shoplist(){
		$nav['m']=$this->getActionName();
		$nav['a']='shoplist';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Shop');
		$where['pid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag')->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        

	}
	
	public function account(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		$db=D('Agent');
		$sql="select a.id,a.name, a.money,a.linker,a.phone,a.level,a.province,a.city,a.area, b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
		$sql.=" where a.id=".$this->aid;
		$info=$db->query($sql);
		$this->assign('info',$info[0]);
		
		
		$this->display();
	}
	
	public function saveaccount(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		
		$db=D('Agent');
		$where['id']=$this->aid;
		
		C('TOKEN_ON',false);
		if($db->create($_POST,2)){
			if($db->where($where)->save()){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		return $this->ajaxReturn($data);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
			}
		}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
		}
		
	}
	
	public function shopedit(){
		$id=I('get.id','0','int');
		$where['pid']=$this->aid;
		$where['id']=$id;
		$db=D('Shop');
		$info=$db->where($where)->find();
		if(!$info){
			$this->error("参数不正确");
		}
		$this->assign("shop",$info);

		$nav['m']=$this->getActionName();
		$nav['a']='shoplist';
		$this->assign('nav',$nav);
		
		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
     
        
        
        $this->display();        

	}
	public function shopadd(){
		$nav['m']=$this->getActionName();
		$nav['a']='shoplist';
		$this->assign('nav',$nav);
		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
		
     
        $this->display();        

	}
	
	public function pwd(){
		$this->display();
	}

	public function dopwd(){
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
			$db=D('Agent');
			$info=$db->where(array('id'=>$this->aid))->field('id,account,password')->find();
			if(md5($_POST['oldpwd'])!=$info['password']){
					$data['error']=1;
		    		$data['msg']="旧密码不正确";
		    		return $this->ajaxReturn($data);
			}
		}
		
		$_POST['update_time']=time();
		$_POST['password']=md5($_POST['password']);
		$where['id']=$this->aid;
			if($db->where($where)->save($_POST)){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		return $this->ajaxReturn($data);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
			}
		
	}
	
	public function saveshop(){
		if(IS_AJAX){
			$user = D('Shop');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $where['pid']=$this->aid;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	        /*
	        $lv="";
	        foreach($_POST['shoplevel'] as $K=>$v )
	        {
	        	$lv.="#".$v."#";
	        }
	        $_POST['shoplevel']=$lv;
	        $trade="";
	        foreach($_POST['trade'] as $K=>$v )
	        {
	        	$trade.="#".$v."#";
	        }
	        $_POST['trade']=$trade;
	        */
	        $_POST['linkflag']=1;//不限制
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save()){
	        		$data['error']=0;
		    		$data['url']=U('shoplist');
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
			$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
		}
	}

	/*
	 * 开户
	 */
	public function openshop(){
		if(IS_POST){
			$db=D('Agent');
			$sql="select a.id,a.money,a.level,b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
			$sql.=" where a.id=".$this->aid;
			$where['id']=$this->aid;
			$info=$db->query($sql);
			if(!$info){
				$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
			}
			
			$money=$info[0]['money']==null?0:$info[0]['money'];
			$pay=$info[0]['openpay']==null?0:$info[0]['openpay'];
			if($money<$pay){
				$data['error']=1;
	    		$data['msg']="当前帐号余额不足，无法添加商户";
	    		return $this->ajaxReturn($data);
			}
			$user=D('Shop');
			$now=time();
			$_POST['pid']=$this->aid;
			$_POST['authmode']='#0#';
			$_POST['maxcount']=C('OpenMaxCount');
	        if($user->create($_POST,1)){
	        	$aid=$user->add();
	        	if($aid>0){
	        		 $rs['shopid']=$aid;
		    			$rs['sortid']=0;
		    			$rs['routename']=$_POST['shopname'];
		    			$rs['gw_id']=$_POST['account'];
		    			
			        	M("Routemap")->data($rs)->add();
	        		//扣款
	        		$db->where($where)->setDec('money',$pay);
	        		//添加消费记录
	        		$paydata['aid']=$this->aid;
	        		$paydata['paymoney']=$pay;
	        		$paydata['oldmoney']=$money;
	        		$paydata['nowmoney']=$money-$pay;
	        		$paydata['do']=0;
	        		$paydata['desc']='商户开户扣款';
	        		$paydata['add_time']=$now;
	        		$paydata['update_time']=$now;
	        		D('Agentpay')->add($paydata);
	        		
	        		$data['error']=0;
		    		$data['url']=U('shoplist');
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
			$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
		}
	}
	
	public function report(){
		$nav['m']=$this->getActionName();
		$nav['a']='report';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Agentpay');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}
}