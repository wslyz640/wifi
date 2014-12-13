<?php
class UserAction extends BaseAction{
    
    public function __construct() {
        parent::__construct();
        header("Content-type:text/html;charset=utf-8");
    }
    
    public function info(){
    	 $this->isLogin();
        
        $uid = session('uid');
        $info = D('Shop')
                ->where("id = {$uid}")
                ->field('shopname,province,city,area,address,shopgroup,shoplevel,trade,linker,phone')
                ->find();
               
        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
        $this->assign('info',$info);
        $this->assign('a','info');
        $this->display();      
    }
    
    public function index()
    {
        $this->isLogin();
        $dbnote=D('Notice');
        $notes=$dbnote->limit(5)->order('add_time desc')->select();
                $this->assign('notice',$notes);
        
        $this->assign('a','index');
        $this->display();      
    }
    public function doindex()
    {
        $this->isLogin();
      
        //$data['']
        $user = D('Shop');
        $uid = session('uid');
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
        if($_POST['phone']){
        	if(!isPhone($_POST['phone'])){
        		$this->error('手机号码不正确');
        	}
        }
       $where['id']=$uid;
        if($user->create($_POST,2)){
       
	        if($user->where($where)->save())
	        {
	        	
	            $this->success('修改成功');
	        }else{
	        	Log::write($user->getError());
	            $this->error('操作出错，请重新操作');
	        }
        }else{
               $this->error($user->getError());
        }
    }
    private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }    
    }
    public function login()
    {
        $this->display();
    }
    public function dologin()
    {
    	if(IS_POST){
	    	$user = isset($_POST['user']) ? strval($_POST['user']) : '';
	        $pass = isset($_POST['password']) ? strval($_POST['password']) : '';
	        $userM = M('Shop');
	        $pass=md5($pass);
	        
	        $uid = $userM->where("account = '{$user}' AND password = '{$pass}'")->field('id,account,shopname')->find();
			//log::write($userM->getLastSql());
	        if($uid)
	        {
	            session('uid',$uid['id']);
	            session('user',$uid['account']);
	            session('shop_name',$uid['shopname']);
	            $data['error']=0;
	    		$data['msg']="";
	    		$data['url']=U('user/index');
	    		return $this->ajaxReturn($data);
	           // $this->success('登录成功','index.php?m=User');
	        }else{
	        	$data['error']=1;
	    		$data['msg']="帐号信息不正确";
	    		return $this->ajaxReturn($data);
	           
	        }
    	}else{
    		$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
    	}
        
    }
    public function register()
    {
        $this->display();
    }
    public function doregist()
    {
    	if(IS_POST){
    		C('TOKEN_ON',false);
	    	$hid = isset($_POST['doact']) ? strval($_POST['doact']) : '';
	        if($hid == 'doreg')
	        {
	            $user = D('Shop');
	            $_POST['mode']=0;//注册用户
				$_POST['authmode']='#0#';//注册用户
				$_POST['authaction']='1';//认证后调整模式
				//$_POST['password']=md5($_POST['password']);//注册用户
	            if ($user->create()){
	            	$aid=$user->add();
	                if($aid)
	                {
	                	session('uid',$aid);
			            session('user',$_POST['account']);
			            session('shop_name',$_POST['shopname']);
	                	
		                $rs['shopid']=$aid;
		    			$rs['sortid']=0;
		    			$rs['routename']=$_POST['shopname'];
		    			$rs['gw_id']=$_POST['account'];
		    			
			        	M("Routemap")->data($rs)->add();
			  
	               		
	                    $data['error']=0;
			    		$data['msg']="OK";
			    		$data['url']=U('user/index');
			    		
			    		return $this->ajaxReturn($data);
	                }else{
	                    $data['error']=1;
			    		$data['msg']="服务器忙，请稍候再试";
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
    	}else{
    		$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
    	}
        
    }
    public function logout()
    {
        session(null);
          	$this->redirect('index/index');
    }    
    public function account()
    {
        $this->isLogin();
        $this->assign('a','account');
        $this->display();
    }
    public function doaccount()
    {
        $this->isLogin();
        $uid = session('uid');
        $pass = isset($_POST['password']) ? $_POST['password'] : '';
        if($pass)
        {
            $user = D('Shop');
            if($user->create()){
	            if($user->where("id = {$uid}")->save())
	            {
	                $this->success('修改密码成功');
	            }else{
	                $this->error('修改密码错误');
	            }
            }
        }else{
            $this->error('密码不允许为空');
        }
        
    }
    public function application()
    {
        $this->isLogin();
        include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('authmode',$enumdata['authmode']);
        $db=D('Shop');
        $uid = session('uid');
     	$where['id']=$uid;
        $info=$db->where($where)->field('authmode,authaction,jumpurl,timelimit,sh,eh') ->find();
		
        $this->assign('a','application');
    	
        $this->assign('info',$info);
        $this->display();
    }
    public function doapp()
    {
        $this->isLogin();
        $uid = session('uid');
     	$db=D('Shop');
     	$where['id']=$uid;
        $info=$db->where($where)->find();
        $authmode="";
        $sh=(int)$_POST['sh'];
        $eh=(int)$_POST['eh'];
        if($sh>$eh){
        	$this->error('上网结束时段不能小于开始时段');
        }
        foreach($_POST['authmode'] as $K=>$v )
        {
        	if($v=='3'){
        		$pwd=$_POST['wxauthpwd'];
        		$ac=$_POST['wx'];
        		$wx['user']=$ac;
        		$wx['pwd']=$pwd;
        		$authmode.="#".$v."=".json_encode($wx)."#";
        	}else{
        		$authmode.="#".$v."#";
        	}
        	
        }
        $_POST['authmode']=$authmode;
        
        if($_POST['authmode']==null||$_POST['authmode']==''){
        	$_POST['authmode']="#0#";
        }
     	if(!$_POST['timelimit']==""){
        	if(!is_numeric($_POST['timelimit'])){
        		$this->error('输入的上网时间必须是数字类型');
        	}
        }
        if($_POST['authaction']==1&&$_POST['jumpurl']==""){
        	$this->error('请输入要跳转的网址');
        }
     
      	if($_POST['authaction']==1)
        	if(!isUrl($_POST['jumpurl'])){
        		$this->error('输入的网址必须以http://开始');
       }
        $_POST['update_time']=time();
        if($db->where($where)->save($_POST)){
        	   $this->success('操作成功');
        }else{

        	$this->error('操作失败');
        }

    }
    public function adv()
    {
        $this->isLogin();
        import('@.ORG.UserPage');
        
         
        $this->assign('a','adv');
        $uid = session('uid');
        $where['uid']=$uid;
        $ad = D('Ad');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,C('ad_page'));
		
		
        $result = $ad->where($where)->field('id,ad_pos,ad_thumb,ad_sort,mode')->limit($page->firstRow.','.$page->listRows)-> select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        
    }
    public function addadv()
    {
        $this->isLogin();
        $this->display();
    }
    public function delad()
    {
        $this->isLogin();
        $id = isset($_GET['id']) ? intval($_GET[id]) : 0;       
        
        if($id)
        {
            $thumb = D('ad')->where("id={$id}")->field("ad_thumb")->select();
            if(D('ad')->delete($id))
            {
                if(file_exists( ".{$thumb[0]['ad_thumb']}"))
                {
                    unlink(".{$thumb[0]['ad_thumb']}");
                }
                
                $this->success('删除成功',U('user/adv'));
            }else{
                $this->error('操作出错');
            }
        }
    }
    public function editad()
    {
        $this->isLogin();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $uid        = session('uid');
        $where['id']=$id;
        $where['uid']=$uid;
        if($id)
        {
            $result = D('Ad')
                    ->where($where)
                 
                    ->find();
            $this->assign('info',$result);
            $this->display();
        }else{
        	$this->error('无此广告信息');
        }
       
    }
    public function doeditad()
    {
        $this->isLogin();
        $uid = session('uid');
        $id = I('post.id','0','int');
        $where['id']=$id;
        $where['uid']=$uid;
        $db=D('Ad');
        $result =$db
                    ->where($where)
                    ->field('id')
                    ->find();
         if($result==false){
         	$this->error('无此广告信息');
         	exit;
         }
        
        import('ORG.Net.UploadFile');      
        
        $upload             = new UploadFile();
        $upload->maxSize    = C('AD_SIZE');
        $upload->allowExts  = C('AD_IMGEXT');
       $upload->savePath   =  C('AD_SAVE');

      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
				
        	if(!$upload->upload()) {
            	$this->error($upload->getErrorMsg());
	        }else{
	            $info =  $upload->getUploadFileInfo();
	            $_POST['ad_thumb'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
	        }
    	}
       
         
            if($result)
            {
                $_POST['uid']=$uid;
               
                if($db->create()){
                	 if($db->where($where)->save()){
                	 
                	     $this->success('修改成功',U('user/adv'));
                	 }else{
                		 $this->error('操作出错');
                	 }
                }else{
                	 $this->error($db->getError());
                }
               
            }
              
    }
    public function doadv()
    {
        $this->isLogin();
        $uid  = session('uid');
        import('ORG.Net.UploadFile');       
        $upload             = new UploadFile();
        $upload->maxSize    = C('AD_SIZE') ;
        $upload->allowExts  = C('AD_IMGEXT');
        $upload->savePath   =  C('AD_SAVE');
        if(!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        }else{
            $info           =  $upload->getUploadFileInfo();
            $ad             = D('Ad');
            $_POST['uid']    = $uid;
     
            $_POST['ad_thumb'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
            $_POST['ad_sort'] = isset($_POST['adsort']) ? $_POST['adsort'] : 0;
            if($ad->create()){
	            if($ad->add())
	            {
	                $this->success('添加广告成功',U('user/adv'));
	            }else{
	                $this->error('添加失败，请重新添加');
	            }
            }else{
            	 $this->error('添加失败，请重新添加');
            }
            
        }
    }
    
    public function route(){
    	$this->assign('a','route');
    	$this->isLogin();
    	$db= D('Routemap');
    	$uid=session('uid');
    	$where['shopid']=$uid;
    	$info= $db->where($where)->find();
    	$this->assign('info',$info);
    	$this->display();
    }
    
    public function saveroute(){
    	$this->isLogin();
    	$uid=session('uid');
    	$db= D('Routemap');
    	$where['shopid']=$uid;
    	$info= $db->where($where)->find();
    	if(!$info){
    		//添加
    			$_POST['shopid']=session('uid');
    			$_POST['sortid']=0;
	        	if($db->create()){
	        		if($db->add()){
	        		   $this->success('更新成功',U('user/route'));
	        		}else{
	        			$this->error("操作失败");
	        		}
	        	}else{
	        		$this->error($db->getError());
	        	}
    	}else{
    		//更新
    				$where['id']=$info['id'];
    				$_POST['shopid']=session('uid');
    				$_POST['sortid']=0;
	        		if($db->create($_POST,2)){
		        		if($db->where($where)->save()){
		        		   $this->success('更新成功',U('user/route'));
		        		}else{
		        			$this->error("操作失败");
		        		}
		        	}else{
		        		$this->error($db->getError());
		        	}
    	}
    	
    	
    }
    
    public function routemap()
    {
    	$this->isLogin();
        import('@.ORG.UserPage');
        $this->assign('a','routemap');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('routemap');
        $count=$ad->where($where)->count();

		$page=new UserPage($count,C('ad_page'));
        $result = $ad->where($where)->field('id,routename,sortid,gw_id,add_time')->limit($page->firstRow.','.$page->listRows)->order('sortid asc ,add_time asc')->select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        
      
       
    }
    public function editrout()
    {
        $this->isLogin();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         
        $uid = session('uid');
        $where['id']=$id;
        $where['shopid']=$uid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
         $this->assign('info',$r);
        	$this->display();
        }
       
    }
    public function doroute()
    {
        $this->isLogin();
        $db=D('Routemap');
        if(IS_POST){
        	$id=I('post.id','','int');
        	if(empty($id)&&$id==""){
        		$_POST['shopid']=session('uid');
	        	if($db->create()){
	        		if($db->add()){
	        		   $this->success('添加成功',U('user/routemap'));
	        		}else{
	        			$this->error("操作失败");
	        		}
	        	}else{
	        		$this->error($db->getError());
	        	}
        	}else{
        		$where['id']=$id;
        		$where['shopid']=session('uid');
        		$info=$db->where($where)->find();
        		if($info!=false){
        			$_POST['shopid']=session('uid');
	        		if($db->create()){
		        		if($db->where($where)->save()){
		        		   $this->success('更新成功',U('user/routemap'));
		        		}else{
		        			$this->error("操作失败");
		        		}
		        	}else{
		        		$this->error($db->getError());
		        	}
        		}else{
        			$this->error("没有此路由器信息");
        		}
        	}
        }
       
    }
    public function delrout()
    {
     	$this->isLogin();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         
        $uid = session('uid');
        $where['id']=$id;
        $where['shopid']=$uid;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
          if(D('Routemap')->where($where)->delete()){
          	$this->success('删除成功');
          }else{
          	$this->error('删除失败');
          }
        	
        }
    }
    
    public function online(){
    	$this->isLogin();
    	import('@.ORG.UserPage');
        $this->assign('a','online');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('Authlist');
        $count=$ad->where($where)->count();
       
      
		$page=new UserPage($count,10);
		$pg=$page->show();

        $result = $ad->where($where)->order('login_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		
 
        $this->assign('page',$pg);
        $this->assign('lists',$result);
        
        $this->display();        
    }

	 public function report(){
    	$this->isLogin();
    	import('@.ORG.UserPage');
        $this->assign('a','report');
        $uid = session('uid');
        $where['shopid']=$uid;
        $where['mode']  = array('in','0,1');
        
        $ad = D('Member');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,10);
        $result = $ad->where($where)->limit($page->firstRow.','.$page->listRows)->order('login_time desc')->select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
        
        $this->display();        
    }
    
    public function rpt(){
  		$this->isLogin();
		$this->assign('a','online');
    	$this->display();
    }
    
 	public function adrpt(){
 		$this->isLogin();
    	$db=D('Authlist');
    	//$sql=" select add_date ,count(*) as t from ".C('DB_PREFIX')."authlist where add_date BETWEEN '2014-03-07' and '2014-03-15' GROUP BY add_date ";
    	$sql="select add_date ,count(*) as t FROM(select FROM_UNIXTIME(add_time, '%Y-%m-%d' ) as add_date from ".C('DB_PREFIX')."test) t1 group by  add_date";
    	$rs=$db->query($sql);
		$this->assign('dt',$rs);
		
    	$this->display();
    }
    /*
     * 用户统计图表
     * 
     */
    public function userchart(){
    	$this->isLogin();
    	 $this->assign('a','report');
    	$this->display();
    }
    
    public  function getuserchart(){
    	$this->isLogin();
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";
				$sql.=" where add_date='".date("Y-m-d")."' and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 ) and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
				
    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from ".C('DB_PREFIX')."member ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
    
    /*
     * 上网统计
     */
    public function getrpt(){
    	$this->isLogin();
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "yestoday":
    			$sql=" select t, COALESCE(ct,0)  as ct from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct from ";
				$sql.="(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist $where) a ";
				$sql.=" where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "week":
    			$sql="  select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where shopid=".session('uid')." and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as t, COALESCE(ct,0) as ct from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(*) as ct  from ".C('DB_PREFIX')."authlist  ";
				$sql.=" where  shopid=".session('uid')." and  add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,count(*) as ct  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where  shopid=".session('uid')." and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
    
    public function advrpt(){
    	$this->isLogin();
    	$this->assign('a','adv');
    	$this->show();
    }
    public  function getadrpt(){
    	$this->isLogin();
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and mode=1 and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 and shopid=".session('uid');
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit ,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcount";
				$sql.=" where shopid=".session('uid')." and mode=1 and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where  shopid=".session('uid')." and mode=1 and  add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			$sdate=I('get.sdate');
    			$edate=I('get.edate');
    			import("ORG.Util.Date");
    			//$sdt=Date("Y-M-d",$sdate);
    			//$edt=Date("Y-M-d",$edate);
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount ";
				$sql.=" where  shopid=".session('uid')." and mode=1 and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
	public function comment()
    {
        $this->isLogin();
        import('@.ORG.UserPage');
        
         
        $this->assign('a','comment');
        $uid = session('uid');
        $where['shopid']=$uid;
        $ad = D('Comment');
        $count=$ad->where($where)->count();
		$page=new UserPage($count,C('ad_page'));
		
		
        $result = $ad->where($where)->field('id,user,phone,content,add_time')->limit($page->firstRow.','.$page->listRows)-> select();
       
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        
    }
    public function delcomment(){
    	$id=I('get.id');
    	$where['id']=$id;
    	 $uid = session('uid');
        $where['shopid']=$uid;
         $ad = D('Comment');
         $ad->where($where)->delete();
         $this->success('操作完成',U('user/comment'));
    }
    
}
