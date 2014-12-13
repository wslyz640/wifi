<?php
class LoginAction extends BaseApiAction{
	private $gw_address = null;
	private $gw_port = null;
	private $gw_id = null;
	private $mac = null;
	private $url = null;
	private $logout = null;

	
	public function index(){
		
		if (isset($_REQUEST["gw_address"])) {
		    $this->gw_address = $_REQUEST['gw_address'];
		    cookie('gw_address',$_REQUEST['gw_address']);
		}

		if (isset($_REQUEST["gw_port"])) {
		    $this->gw_port = $_REQUEST['gw_port'];
		    cookie('gw_port', $_REQUEST['gw_port']);
		}
		
		if (isset($_REQUEST["gw_id"])) {
		    $this->gw_id = $_REQUEST['gw_id'];
		    cookie('gw_id', $_REQUEST['gw_id']);
		}
		if (isset($_REQUEST["url"])) {
		    $this->url = $_REQUEST['url'];
		    cookie('gw_url', $_REQUEST['url']);
		    
		}
		
		if (isset($_REQUEST["mac"])) {
		    cookie('mac', $_REQUEST['mac']);
		    $mac = $_REQUEST['mac'];
		}
		
		if(!empty($this->gw_id)){
			//寻找网关ID
			$sql="select a.*,b.shopname,b.authmode,b.maxcount,b.linkflag,b.sh,b.eh,b.pid from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
			$sql.=" where a.gw_id='".$this->gw_id."' limit 1";
			$db=D('Routemap');
			$addb=D('Ad');
			//$info=$db->where($where)->find();
			$info=$db->query($sql);
			
			if($info!=false){
				$show=1;
				$max=$info[0]['maxcount'];
				$limit=$info[0]['linkflag'];
				if($limit==0){
					$where['shopid']=$info[0]['shopid'];
					$count=D('member')->where($where)->count();
					if($count>$max){
						$show=0;
					}
				}
				cookie('shopid', $info[0]['shopid']);
				
				$authmode=$info[0]['authmode'];
				$where['uid']=$info[0]['shopid'];
				$where['ad_pos']=0;
				
				$ad=$addb->where($where)->field('id,ad_thumb,mode')->limit(5)->select();
				$ids="";
				foreach ($ad as $k=>$v){
					$ids.=$v['id'].",";
				}
				/*统计展示*/
				///////////////////////////
				/*
				$tr=new Model();
				$time=time();
				$tr->startTrans();
				$arrdata['showup']=1;
				$arrdata['hit']=0;
				$arrdata['shopid']=$info[0]['shopid'];
				$arrdata['add_time']=$time;
				$arrdata['add_date']=getNowDate();
				$arrdata['mode']=1;
				foreach($ad as $k=>$v){
					
					$arrdata['aid']=$v['id'];
					$tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
				}
				$tr->commit();
				*/
				///////////////////////////
				
				$this->assign('ad',$ad);
				$this->assign('adid',$ids);
				$this->assign('show',$show);
				$hour=(int)date("H");
				if(!empty($info[0]['sh'])&&!empty($info[0]['eh'])&&$info[0]['sh']!=""&&$info[0]['eh']!=""){
					$sh=(int)$info[0]['sh'];
					$eh=(int)$info[0]['eh'];
					$auth['opensh']=$sh;
					$auth['openeh']=$eh;
					$auth['openflag']=true;//未设置
					if($hour>=$sh&&$hour<=$eh){
						$auth['open']=true;
						
					}else{
						$auth['open']=false;
						
					}
					
				}else{
					$auth['open']=true;
					$auth['openflag']=false;//未设置
				}
				if($authmode==null||$authmode==""){
					$auth['reg']=1;
					
				}else{
					
				$tmp=explode('#', $authmode);
				foreach($tmp as $v){
					if($v!='#'&&$v!=''){
						$arr[]=$v;
					}
				}
				foreach($arr as $v){
					$temp=explode('=', $v);
					if(count($temp)>1&&$temp[0]=='3'){
						$auth['wx']=1;
					}else{
						if($v=='0'){
							$auth['reg']=1;
						}
						if($v=='1'){
							$auth['phone']=1;
						}
						if($v=='2'){
							$auth['allow']=1;
						}
                        if($v=='4'){
                            $auth['wxash']=1;
                        }
                        if($v=='5'){
                            $auth['sms']=1;
                        }
					}
				}

					
				}
				
				
				
				$this->assign("authmode",$auth);
				
				$this->assign("shopinfo",$info);
				$this->display();
				//$this->display("indexweibo");
			}else{
				echo '参数不正确';
			}
		}else{
			//没有网关ID
			echo '参数不正确';
		}
		
	}

	public  function countad(){
		$gid=cookie('gw_id');
		$sid=cookie('shopid');
		if(empty($gid)||empty($sid)){
			
			exit;
		}
		
		$ids=I('post.ids');
		
		$idarr=explode(',', $ids);
		/*统计展示*/
				///////////////////////////
				$tr=new Model();
				$time=time();
				$tr->startTrans();
				$arrdata['showup']=1;
				$arrdata['hit']=0;
				$arrdata['shopid']=$sid;
				$arrdata['add_time']=$time;
				$arrdata['add_date']=getNowDate();
				$arrdata['mode']=1;
				foreach($idarr as $v){
					if($v!=""){
						$arrdata['aid']=$v;
						$tr->table(C('DB_PREFIX')."adcount")->add($arrdata);
					}
				}
				$tr->commit();
				///////////////////////////
	}
	
	public function apple(){
		//log::write("签到");
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN"><HTML><HEAD><TITLE>Success</TITLE></HEAD><BODY>Success</BODY></HTML>';
	}
}