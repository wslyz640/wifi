<?php
/*
 * 高级收费功能 3G
 */
class AdvAction extends BaseAction{
 	private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }    
        $this->assign('a','advfun');
    }
	public function index(){
		
	}
	
	public function set(){
		$this->isLogin();
		$db=D('Smscfg');
		$uid = session('uid');
		$where['uid']=$uid;
		$info=$db->where($where)->find();
		$this->assign('info',$info);
		$this->display();
	}
	
	public function saveset(){
		$this->isLogin();
		if(IS_POST){
			$db=D('Smscfg');
			$uid = session('uid');
			$where['uid']=$uid;
			$info=$db->where($where)->find();
			if($info==false){
				//do add
				$_POST['uid']=$uid;
				if($db->create()){
					if($db->add()){
					$this->success("保存成功");
					}else{
						$this->error("保存失败");
					}
				}else{
					$this->error($db->getError());
				}
				
			}else{
				//do update
				if($db->create()){
					if($db->where($where)->save()){
						$this->success("保存成功");
					}else{
						$this->error("保存失败");
					}
				}else{
					$this->error($db->getError());
				}
			}
		}
		
	}
	
	/*
	public function  test(){
		import('@.ORG.XCSMS');
		$server='http://localhost:8088/sms.asmx?WSDL';
		$u='76c5069c8921470d9605e516e9372cb7';
		$p='PO7DJCVM';
		$client=new XCSMS($server, $u, $p);
		//$client = new SoapClient('http://localhost:8088/xcws.asmx?WSDL'); 
		//$client->soap_defencoding='utf-8';
		//$client->decode_utf8=false;
		echo $client->GetSmsAccount()."<br/>";
		echo $client->GetSmsPrice()."<br/>";
		echo $client->SendSms('13956989651', "我的短信内容");
	}
	*/
	/*
	 * 手机号码列表
	 */
	public function phonelist(){
		$this->isLogin();
      	import('@.ORG.UserPage');
        $this->assign('a','advfun');
        $uid = session('uid');
        $where['shopid']=$uid;
        $where['mode']  = 1;
        
        $ad = D('Member');
        $count=$ad->where($where)->count();
      
		$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('login_time desc')->select();
 
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	/*
	 * 手机号码下载
	 */
	public function downphone(){
		$this->isLogin();
		$uid = session('uid');
        $where['uid']=$uid;
        $where['mode']  = array('in','1');
        
        $ad = D('Member');
        $data=$ad->where($where)->field('phone')->select();
        exportexcel($data,array('手机号码'),'手机号码');
	}
	
	public function sms(){
		$this->isLogin();
		$this->assign('a','advfun');
		$this->display();
	}
	
	public  function doSend($phone,$msg){
		
	}
	
	
	
	public  function addsms(){
		$this->isLogin();
		if(IS_POST){
			$smsdb=D('Smscfg');
			$uid = session('uid');
			$where['uid']=$uid;
			$info=$smsdb->where($where)->find();
			if($info==false){
					$back['error']=1;
						$back['msg']='请先配置好短信帐号信息';
						$this->ajaxReturn($back);
						exit;
			}
			$phones=I('post.phones');
			$msg=I('post.info');
			$list=explode(',', $phones);
			$len=mb_strlen($msg,'UTF-8');//短信长度
			$ut=ceil($len/70);//计算短信数量
			
			$uid = session('uid');
			$time=time();
			foreach($list as $v){
				if($v!=''){
					if(!isPhone($v)){
						$back['error']=1;
						$back['msg']='手机号码'.$v.'不正确';
						$this->ajaxReturn($back);
						exit;
						break;
						
					}else{
						$datalist[]=array('uid'=>$uid,'mode'=>0,'phone'=>$v,
						'info'=>$msg,'lens'=>$len,'unit'=>$ut,
						'add_time'=>$time,'update_time'=>$time,'send_time'=>$time,
						'ready_time'=>$time,'state'=>1,'lostcount'=>0
						);
					}
				}
			}
			//$sms=D('Sms');
			//$sms->addAll($datalist);
			import('@.ORG.XCSMS');
			$server=C('SMSURL');
			$u=$info['user'];
			$p=$info['password'];
			$client=new XCSMS($server, $u, $p);
	
		
			$rs=$client->SendSms($phones,$msg);
			
			if($rs==1){
				$sms=D('Sms');
				$sms->addAll($datalist);
				$back['error']=0;
				$back['msg']='操作成功';
				$this->ajaxReturn($back);
			}else{
				$back['error']=1;
				$back['msg']=$this->getsmsstate($rs);
				$this->ajaxReturn($back);
			}
		
			
			
			
		
			
			
		}
	}
	
	private function getsmsstate($rs){
		//1 成功 -1 失败 -2 帐号密码不正确 -3 金额不足 -4 手机号码或其他参数不正确
		switch ($rs){
			case -1:
				return "短信提交失败";
			case -2:
				return "发送短信的帐号密码不正确";
			case -3:
				return "短信帐号余额不足";
			case -4:
				return "提交的手机号码有错";
			default:
				return '短信提交失败';
				
		}
	}
	
	public function smslist(){
		import('@.ORG.UserPage');
        $this->assign('a','advfun');
        $uid = session('uid');
        $where['uid']=$uid;
      
        
        $ad = D('Sms');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	
}