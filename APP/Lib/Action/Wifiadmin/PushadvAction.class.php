<?php
class PushadvAction extends AdminAction{

	public  function index(){
		import('@.ORG.AdminPage');
		$db=D('Pushadv');
		
		$count=$db->where()->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
  		
        //$result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        $sql="select a.*,b.name as agentname from ".C('DB_PREFIX').'pushadv a left join '.C('DB_PREFIX').'agent b on a.aid=b.id order by add_time desc limit '.$page->firstRow.','.$page->listRows;
		$result=$db->query($sql);
		
		$this->assign('page',$page->show());
        $this->assign('lists',$result);
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			 import('ORG.Net.UploadFile');       
	        $upload             = new UploadFile();
	        $upload->maxSize    = C('AD_SIZE') ;
	        $upload->allowExts  = C('AD_IMGEXT');
	        $upload->savePath   =  C('AD_PUSHSAVE');
	        if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	        if(!$upload->upload()) {
	            $this->error($upload->getErrorMsg());
	        }else{
	            $info           =  $upload->getUploadFileInfo();
	            $ad             = D('Pushadv');
	           
	     
	            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
	            $_POST['sort'] = isset($_POST['sort']) ? $_POST['sort'] : 0;
	            
	            $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
	            if($ad->create()){
		            if($ad->add())
		            {
		                $this->success('添加广告成功',U('pushadv/index'));
		            }else{
		                $this->error('添加失败，请重新添加');
		            }
	            }else{
	
	            	 $this->error($ad->getError());
	            }
	            
	        }
			
			
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		if(IS_POST){
		  	if($_POST['startdate']==""||$_POST['enddate']==""){
	        	$this->error('请选择广告投放时间段');
	        }
	        $id = I('post.id','0','int');
	        $where['id']=$id;
	      
	        $db=D('Pushadv');
	        $result =$db
	                    ->where($where)
	                    ->field('id,pic')
	                    ->find();
	         if($result==false){
	         	$this->error('无此广告信息');
	         	exit;
	         }
	        
	        import('ORG.Net.UploadFile');      
	       
	        $upload             = new UploadFile();
	        $upload->maxSize    = C('AD_SIZE');
	        $upload->allowExts  = C('AD_IMGEXT');
	        $upload->savePath   =  C('AD_PUSHSAVE');
	    
	      	if (!is_null($_FILES['img']['name'])&& $_FILES['img']['name']!="") {
				
	        	if(!$upload->upload()) {
	            	$this->error($upload->getErrorMsg());
		        }else{
		            $info =  $upload->getUploadFileInfo();
		            $_POST['pic'] = trim( $info[0]['savepath'],'.').$info[0]['savename'];
		        }
	    	}else{
	    		$_POST['pic']=$result['pic'];
	    	}
       
         
            if($result)
            {
            
                $_POST['startdate']=strtotime($_POST['startdate']);
	            $_POST['enddate']=strtotime($_POST['enddate']);
	           
                if($db->create()){
                	 if($db->where($where)->save()){
                	     $this->success('修改成功',U('index'));
                	 }else{
                		 $this->error('操作出错');
                	 }
                }else{
                	 $this->error($db->getError());
                }
               
            }
		}else{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	      
	        $where['id']=$id;
	            $result = D('Pushadv')
	                    ->where($where)
	                    ->find();
	                     if($id)
	        if($result){
	            $this->assign('info',$result);
	            $this->display();
	        }else{
	        	$this->error('无此广告信息');
	        }
	
		}
	}
	
	public function  del(){
	$id = isset($_GET['id']) ? intval($_GET[id]) : 0;       
        
        if($id)
        {
            $thumb = D('Pushadv')->where("id={$id}")->field("id,pic")->select();
            if(D('Pushadv')->delete($id))
            {
                if(file_exists( ".{$thumb[0]['pic']}"))
                {
                    unlink(".{$thumb[0]['pic']}");
                }
                
                $this->success('删除成功',U('index'));
            }else{
                $this->error('操作出错');
            }
        }
	}
	
	public function set(){
		if(IS_POST){
			$wt=$_POST['WAITSECONDS'];
			if(!isNumber($wt)){
				$this->error("广告展示时间以秒为单位,请输入展示的时间");
			}
			if($wt<3){
				$this->error("最低展示时间不能小于3秒");
			}
			$this->configsave();
		}else{
			$this->display();
		}
	}
	private  function configsave(){
	
		
		$act=$this->_post('action');
		unset($_POST['files']);
		unset($_POST['action']);
		unset($_POST[C('TOKEN_NAME')]);
	
		if(update_config($_POST,CONF_PATH."adv.php")){
	
			$this->success('操作成功',U('Pushadv/'.$act));
	
		}else{
	
			$this->success('操作失败',U('Pushadv/'.$act));
	
		}
	
	}
	public function rpt(){
		$way=I('get.mode');
			if(!empty($way)){
				$this->getadrpt();
				exit;
			}
			$this->display();
	}
	private  function getadrpt(){
    	
    	$way=I('get.mode');
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql.="sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql.="sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql.="sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql.="sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql.="  (select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit,mode from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and (mode=99 or mode=50) ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql.="sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql.="sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql.="sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql.="sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and (mode=99 or mode=50) ";
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
				$sql.=" where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ";
				$sql.=" where   add_date >= '".date("Y-m-01")."' and (mode=99 or mode=50) GROUP BY  add_date";
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
				$sql.=" where  add_date between '$sdate' and '$edate'  and (mode=99 or mode=50) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}
    	
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
	
	
}