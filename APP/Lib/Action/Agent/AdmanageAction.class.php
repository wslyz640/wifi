<?php
class AdmanageAction extends BaseagentAction{
	public function shopad(){
		import('@.ORG.AdminPage');
		$db=D('Ad');
		$sql="select a.*,b.shopname from ".C('DB_PREFIX')."ad  a LEFT JOIN ".C('DB_PREFIX')."shop  b on a.uid=b.id ";
		$where=" b.pid=".$this->aid;
		$sqlcount="select count(*) as ct from ".C('DB_PREFIX')."ad  a LEFT JOIN ".C('DB_PREFIX')."shop  b on a.uid=b.id where $where";
		$rs=$db->query($sqlcount);
		$count=$rs[0]['ct'];
		
		$page=new AdminPage($count,C('ADMINPAGE'));
		
		$sql.=" where ".$where." limit ".$page->firstRow.','.$page->listRows." ";
		$result=$db->query($sql);
		
		$this->assign('page',$page->show());
        $this->assign('lists',$result);
       
        $this->display();
	}
	
	public function editad(){
		if(IS_POST){
		 
	        $id = I('post.id','0','int');
	        $where['id']=$id;
	      
	        $db=D('Ad');
	        $result =$db->where($where)->field('id')->find();
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
       
         $_POST['update_time']=time();
            if($result)
            {

                if($db->create()){
                	 if($db->where($where)->save($_POST)){
                	 
                	     $this->success('修改成功',U('shopad'));
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
	            $result = D('Ad')
	                    ->where($where)
	                    ->find();
	          
	        if($result){
	            $this->assign('info',$result);
	            
	            $this->display();
	        }else{
	        	$this->error('无此广告信息');
	        }
	
		}
	}

	public function adrpt(){
		$way=I('get.mode');
			if(!empty($way)){
				$this->getadrpt();
				exit;
			}
			$this->display();
	
	}
private   function getadrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(ad.add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount ad";
				$sql.=" left join ".C('DB_PREFIX')."shop sp on ad.shopid=sp.id ";
				$sql.=" where ad.add_date='".date("Y-m-d")."' and ad.mode=1 and pid=".$this->aid;
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(ad.add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount ad ";
				$sql.=" left join ".C('DB_PREFIX')."shop sp on ad.shopid=sp.id ";
				$sql.=" where ad.add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ad.mode=1 ";
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
    			$sql.="( select ad.add_date,sum(showup) as showup ,sum(hit) as hit from ".C('DB_PREFIX')."adcount ad ";
				$sql.=" left join ".C('DB_PREFIX')."shop sp on ad.shopid=sp.id ";
    			$sql.=" where   ad.add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and ad.mode=1 and pid=".$this->aid." GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(ad.add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount  ad ";
				$sql.=" left join ".C('DB_PREFIX')."shop sp on ad.shopid=sp.id ";
				$sql.=" where   ad.add_date >= '".date("Y-m-01")."' and ad.mode=1 and pid=".$this->aid." GROUP BY  add_date";
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
    			
    
				$sql.="( select ad.add_date,sum(showup) as showup ,sum(hit) as hit  from ".C('DB_PREFIX')."adcount as ad ";
				$sql.=" left join ".C('DB_PREFIX')."shop sp on ad.shopid=sp.id ";
				$sql.=" where  ad.add_date between '$sdate' and '$edate'  and ad.mode=1 and pid=".$this->aid." GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";

			
    			break;
    	}

    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}