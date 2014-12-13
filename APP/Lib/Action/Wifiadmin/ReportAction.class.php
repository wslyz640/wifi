<?php
class ReportAction extends AdminAction{
	public function user(){
		
		$sqljson="";
		if(IS_POST){
			//查询
			$sqljson=json_encode($_POST);
			session('userwhere',$sqljson);
			
		}else{
			//首次进入或分页
			$sqljson=session('userwhere');
			
			if(empty($sqljson)||$sqljson==""||!isset($_GET['p'])){
				//默认为空
				$where['sdate']=date("Y-m-01");
				$where['edate']=date("Y-m-d");
				$where['uname']="";
				$where['uphone']="";
				$where['mode']="-1";
				$sqljson=json_encode($where);
			}else{
				//有参数条件
				
			}
		}
		
		//组装查询条件
		$js=json_decode($sqljson);
		
		
		$this->assign("qjson",$js);
		$sqlwhere=" 1=1 ";
		if($js->sdate!=""&&$js->edate!=""){
			$sqlwhere.=" and a.add_date between '$js->sdate' and '$js->edate' ";
		}
		if($js->mode!="-1"){
			$sqlwhere.=" and a.mode=$js->mode";
		}else{
			$sqlwhere.=" and a.mode in(0,1) ";
		}
		if($js->uname!=""){
			$sqlwhere.=" and a.user like '%$js->uname%'";
		}
		if($js->uphone!=""){
			$sqlwhere.=" and a.phone like '%$js->uphone%'";
		}
		
		
		import('@.ORG.AdminPage');
		$db=D('Member');
		$where['mode']=array('in','0,1');
		$rs=$db->query(" select count(*) as ct from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere ");
		
		$count=$rs[0]['ct'];
		
		$page=new AdminPage($count,C('ADMINPAGE'));
		$sql="select a.*,b.account as shopaccount,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere order by a.login_time desc,a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";

		
		$result=$db->query($sql);
		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	
	public function downuser(){
		$sqljson="";
		if(IS_POST){
			//查询
			$sqljson=json_encode($_POST);
			session('userwhere',$sqljson);
			
		}else{
			//首次进入或分页
			$sqljson=session('userwhere');
			
			if(empty($sqljson)||$sqljson==""||!isset($_GET['p'])){
				//默认为空
				$where['sdate']=date("Y-m-01");
				$where['edate']=date("Y-m-d");
				$where['uname']="";
				$where['uphone']="";
				$where['mode']="-1";
				$sqljson=json_encode($where);
			}else{
				//有参数条件
				
			}
		}
		
		//组装查询条件
		$js=json_decode($sqljson);
		
		
		$this->assign("qjson",$js);
		$sqlwhere=" 1=1 ";
		if($js->sdate!=""&&$js->edate!=""){
			$sqlwhere.=" and a.add_date between '$js->sdate' and '$js->edate' ";
		}
		if($js->mode!="-1"){
			$sqlwhere.=" and a.mode=$js->mode";
		}else{
			$sqlwhere.=" and a.mode in(0,1) ";
		}
		if($js->uname!=""){
			$sqlwhere.=" and a.user like '%$js->uname%'";
		}
		if($js->uphone!=""){
			$sqlwhere.=" and a.phone like '%$js->uphone%'";
		}
		
		
		
		$db=D('Member');
	

		$sql="select a.user,a.phone,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere order by a.login_time desc,a.add_time desc ";

		
		$result=$db->query($sql);
		exportexcel($result,array('用户名','手机号码','所属商户'),'用户资料');
		
	}
	
	
	public function online(){
		import('@.ORG.AdminPage');
		$db=D('Member');
		
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		$sql="select a.*,b.account as shopaccount,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id order by a.login_time desc,a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
        //$result = $db->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
		$result=$db->query($sql);
		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	public function userchart(){
		$way=I('get.mode');
		if(!empty($way)){
			$this->getuserchart();
			exit;
		}
		$this->display();
	}
	private  function getuserchart(){
    	
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";
				$sql.=" where add_date='".date("Y-m-d")."' and ( mode=0 or mode=1 ) ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 )  ";
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
				$sql.=" where  add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where    add_date >= '".date("Y-m-01")."' and ( mode=0 or mode=1 ) GROUP BY  add_date";
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
				$sql.=" where  add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    
    	$db=D('Member');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
	public function authchart(){
		$way=I('get.mode');
		if(!empty($way)){
			$this->getauthrpt();
			exit;
		}
		$this->display();
	}
	
	/*
     * 上网统计
     */
    
	private function getauthrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select shopid,mode,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist ) a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "yestoday":
    			$sql=" select t, CONCAT(CURDATE(),' ',t,'点') as showdate,COALESCE(ct,0)  as ct,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select shopid,mode,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist ) a ";
				$sql.=" where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ".C('DB_PREFIX')."authlist ";
				$sql.=" where  add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(ct,0) as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(*) as ct,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log  from ".C('DB_PREFIX')."authlist  ";
				$sql.=" where    add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
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
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
    			
    
				$sql.="( select add_date,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where   add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }
	
    public function routechart(){
    	$way=I('get.mode');
		if(!empty($way)){
			$this->getroutechart();
			exit;
		}
		$list=I('get.info');   
    	if(!empty($list)){
			$this->getroutelist();
			exit;
		} 	
		$this->display();
    }
    
    private function getroutechart(){
    	$sql=" select count(*) as total,count(case when last_heartbeat_time >= unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) ) then 1 else null end)  as livecount,count(case when last_heartbeat_time <unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) ) then 1 when last_heartbeat_time is null then 1  else null end)  as diecount  from ".C('DB_PREFIX')."routemap";
    	$db=D("Routemap");
    	$info=$db->query($sql);
    	
    	return $this->ajaxReturn($info);
    }
    
	private function getroutelist(){
		import('@.ORG.AdminAjaxPage');
		$tp=I('get.flag');
		if($tp=="a"){
			//在线
			$where="where last_heartbeat_time >= unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) )";
		}else if($tp=='d'){
			//离线
			$where="where last_heartbeat_time < unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) )";
		}else{
			exit;
		}
    	
    	$db=D("Routemap");
    	
    	$sql=" select a.id,a.shopid,FROM_UNIXTIME( last_heartbeat_time, '%Y-%m-%d %H:%i:%s' )  as last_heartbeat_time,b.shopname  from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
    	$sql.=$where;
    	
    	$sqlcount=" select count(*) as ct from ".C('DB_PREFIX')."routemap ";
    	$sqlcount.=$where;
    	$rs=$db->query($sqlcount);
    	$count=$rs[0]['ct'];
    	$page=new AdminAjaxPage($count,10);
    	$sql.=" limit ".$page->firstRow.','.$page->listRows." ";
    	$info=$db->query($sql);
    	$back['list']=$info;
    	$back['pg']=$page->show();
    	return $this->ajaxReturn($back);
    }
    
}