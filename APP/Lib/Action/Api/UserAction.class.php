<?php
class UserAction extends BaseApiAction{
	private $userinfo;
	private  function CheckUser(){
		if(cookie('token')!=""&&cookie('mid')!=""){
			$db=D('Member');
			$where['token']=session('token');
			$info=$db->where($where)->find();
			if($info==false){
				$this->userinfo=$info;
				$this->assign('user',$info);
			}else{
				$this->redirect(U('Api/login',array('gw_address'=>cookie('gw_address'),'gw_id'=>cookie('gw_id'),'gw_port'=>cookie('gw_port'))));
			}
		}else{
			$this->redirect(U('Api/login',array('gw_address'=>cookie('gw_address'),'gw_id'=>cookie('gw_id'),'gw_port'=>cookie('gw_port'))));
		}
	}
	
	private  function load_shopinfo(){
		if( cookie('gw_id')!=null){
			$sql="select a.*,b.shopname,b.authaction,b.jumpurl from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
			$sql.=" where a.gw_id='".cookie('gw_id')."' limit 1";
			$dbmap=D('Routemap');
			$info=$dbmap->query($sql);

			if($info!=false){
				cookie('shopid',$info[0]['id']);
				
				$this->shop=$info;
				$this->assign("shopinfo",$info);
			}
			$dbmap=null;
		}
	}
	public function index(){
		$this->CheckUser();
		$this->load_shopinfo();
	
		switch($this->shop[0]['authaction']){
			case "1":
				$jump=$this->shop[0]['jumpurl'];
				
				break;
			case "0":
				
				break;
			case "2":
				if(cookie('gw_url')!=null){
			    	$jump=cookie('gw_url');
			    }
				break;
			case "3":
				$jump=U('api/wap/index',array('sid'=>$this->shop[0]['shopid']));
				break;
		}
		
	    $this->assign('jumpurl',$jump);
		$this->display();
	}
}