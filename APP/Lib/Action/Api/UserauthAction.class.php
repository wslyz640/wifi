<?php
class UserAuthAction extends BaseApiAction{
	private  $shop=false;
	public function index(){
		
	}

    /*
	 * 加载商户信息
	 */
    private  function load_shopinfo(){
        if( cookie('gw_id')!=null){
            $sql="select a.id,a.gw_id,a.shopid,a.routename,b.shopname,b.authmode,b.timelimit ,b.pid from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
            $sql.=" where a.gw_id='".cookie('gw_id')."' limit 1";
            $dbmap=D('Routemap');
            $info=$dbmap->query($sql);

            if($info!=false){
                cookie('shopid',$info[0]['id']);//代理商
                cookie('pid',$info[0]['pid']);//代理商
                $this->shop=$info;
                $this->assign("shopinfo",$info);

            }

            $dbmap=null;
        }
    }

    /**
	* 广告显示
	*/
    public function showad(){
        $this->load_shopinfo();
        $id=I('get.id','0','int');
        $db=D('Ad');
        $where['id']=$id;
        $where['uid']=$this->shop[0]['shopid'];

        $info=$db->where($where)->find();

        /*统计展示*/
        ///////////////////////////
        $tr=new Model();
        $time=time();
        $tr->startTrans();
        $arrdata['showup']=0;
        $arrdata['hit']=1;
        $arrdata['shopid']=$this->shop[0]['shopid'];
        $arrdata['add_time']=$time;
        $arrdata['add_date']=getNowDate();
        $arrdata['mode']=1;

        $arrdata['aid']=$id;
        $tr->table(C('DB_PREFIX')."adcount")->add($arrdata);

        $tr->commit();
        ///////////////////////////
        $this->assign('adinfo',$info);
        $this->display();

    }


    private  function getLimitTime(){
        if($this->shop[0]['timelimit']!=""&&$this->shop[0]['timelimit']!="0"){
            import("ORG.Util.Date");
            $dt=new Date(time());
            $date=$dt->dateAdd($this->shop[0]['timelimit'],'n');//默认7天试用期
            return strtotime($date);
        }
        return "";

    }


    public function showtoken(){
        $this->load_shopinfo();
        $url="http://".cookie('gw_address').":".cookie('gw_port')."/wifidog/auth?token=".cookie('token');
        $jump=U('User/index');

        /*
        if(cookie('gw_url')!=null){
            $jump=cookie('gw_url');
        }else{
            $jump=U('User/index');
        }*/
        $wait=C('WAITSECONDS');
        $open=C('OPENPUSH');
        $way=C('SHOWWAY');//展示时间标准
        $pid=$this->shop[0]['pid'];
        $agentpush=false;

        if(empty($pid)||$pid<=0){
            //无代理
            $wait=C('WAITSECONDS');
        }else{
            //获取代理商广告信息
            $adset=D('Agentpushset');
            $awhere['aid']=$pid;
            $adinfo=$adset->where($awhere)->find();
            if($adinfo==false){
                //无设定
            }else{
                if($adinfo['pushflag']==1){
                    $agentpush=true;
                }
                if($way==1){
                    $wait=$adinfo['showtime'];//展示时间
                }
            }
        }

        if($open==1){
            $where['state']=1;
            $where['startdate']= array('elt',time());
            $where['enddate']= array('egt',time());

            if($agentpush){
                $where['aid']=array('in','0,'.$pid);
            }else{
                $where['aid']=0;
            }
            $ads=D('Pushadv')->where($where)->field("id,pic,aid")->select();


            /*统计展示*/
            ///////////////////////////
            $tr=new Model();
            $time=time();
            $tr->startTrans();
            $arrdata['showup']=1;
            $arrdata['hit']=0;
            $arrdata['shopid']=$this->shop[0]['shopid'];
            $arrdata['add_time']=$time;
            $arrdata['add_date']=getNowDate();



            foreach($ads as $k=>$v){
                if($v['aid']>0){
                    $arrdata['mode']=50;
                    $arrdata['agent']=$v['aid'];
                }else{
                    $arrdata['mode']=99;
                    $arrdata['agent']=0;
                }
                $arrdata['aid']=$v['id'];
                $tr->table(C('DB_PREFIX')."adcount")->add($arrdata);

            }
            $tr->commit();
            ///////////////////////////

            $this->assign('ad',$ads);

        }

        $this->assign('waitsecond',$wait);
        $this->assign('wifiurl',$url);
        $this->assign('jumpurl',$jump);
        $this->display();
    }


    /**
     * 认证模式0，注册认证start
     */

    /*
     * 本平台会员注册界面
     */
    public function reg(){
        $this->load_shopinfo();
        $this->display();
    }

    /*
     * 本平台添加会员函数
     */
    public function regu(){
        $this->load_shopinfo();
        if(IS_POST){
            $user=I('post.user');
            $pwd=I('post.password');
            $phone=I('post.phone');
            $qq=I('post.qq');
            if(!validate_user($user)){
                $data['msg']="用户名必须是3到20位数字或字母组成";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }

            if(!isPhone($phone)){
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if(!isQQ($qq)){
                $data['msg']="请填写有效的QQ号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            $db=D('Member');

            $where['user']=$user;
            $where['routeid']=$this->shop[0]['id'];

            $info=$db->where($where)->find();
            if($info!=false){
                $data['msg']="当前帐号已存在";
                $data['error']=1;
                $this->ajaxReturn($data);
            }
            $token=md5(uniqid());
            $now=time();
            $tranDb=new Model();

            $_POST['token']=$token;

            unset($_POST['__hash__']);
            unset($_POST['smscode']);

            $_POST['password']=md5($_POST['password']);
            $_POST['shopid']=$this->shop[0]['shopid'];
            $_POST['routeid']=$this->shop[0]['id'];
            $_POST['browser']=$this->browser;
            $_POST['mode']='0';//注册认证
            $_POST['add_time']=$now;
            $_POST['update_time']=$now;
            $_POST['login_time']=$now;
            $_POST['add_date']=getNowDate();
            $_POST['online_time']=$this->getLimitTime();
            $tranDb->startTrans();
            $flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);
            $newid=$tranDb->getLastInsID();
            $arrdata['uid']=$newid;
            $arrdata['add_date']=getNowDate();
            $arrdata['over_time']=$this->getLimitTime();
            $arrdata['update_time']=$now;//更新时间
            $arrdata['login_time']=$now;//首次登录时间
            $arrdata['last_time']=$now;//最后在线时间
            $arrdata['shopid']=$this->shop[0]['shopid'];
            $arrdata['routeid']=$this->shop[0]['id'];
            $arrdata['browser']=$this->browser;
            $arrdata['token']=$token;
            $arrdata['mode']='0';//
            $arrdata['agent']=$this->agent;
            $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);

            if($flag&$flagauth){
                $tranDb->commit();
                cookie('token',$_POST['token']);
                cookie('mid',$newid);
                $data['error']=0;
                $data['url']=U("userauth/showtoken");
                $this->ajaxReturn($data);
            }else{
                $tranDb->rollback();
                Log::write("注册认证错误:".$tranDb->getLastSql());
                $data['msg']="注册失败，请稍候再试";
                $data['error']=1;
                $this->ajaxReturn($data);
            }


        }
    }

    /*
     * 本平台会员认证界面
     */

    public function login(){
        $this->load_shopinfo();

        $this->display();
    }

    /*
     * 本平台会员认证函数
     */
    public function dologin(){
        import("ORG.Util.String");
        $this->load_shopinfo();
        if(IS_POST){
            $db=D('Member');
            $user=I('post.user');
            $pwd=I('post.password');
            if(empty($user)||empty($pwd)||$user==""||$pwd==""){
                $data['msg']="提交参数不完整，登录失败";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if(!validate_user($user)){
                $data['msg']="用户名不合法";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            $where['user']=$user;
            $info=$db->where($where)->find();
            if($info==false){
                $data['msg']="用户名不存在";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }else{
                if($info['password']!=md5($pwd)){
                    $data['msg']="用户名密码不正确";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                    exit;
                }

                $token=md5(uniqid());
                $tranDb=new Model();
                $save['token']=$token;
                $save['browser']=$this->browser;
                $save['online_time']=$this->getLimitTime();
                $save['update_time']=$now;
                $save['login_time']=$now;
                $arrdata['uid']=$info['id'];
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='0';//无需认证
                $arrdata['agent']=$this->agent;
                $flag=$tranDb->table(C('DB_PREFIX').'member')->where($where)->save($save);
                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);
                if($flag&&$flagauth){
                    $tranDb->commit();
                    cookie('token',$token);
                    cookie('mid',$info['id']);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    $data['msg']="登录失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }
                //Log::write($token);


            }

        }
    }


    /**
     * 认证模式0，注册认证end
     */

    /**
     * 认证模式1，手机认证start
     */
    /*
     * 手机号验证界面
     */
	public function mobile(){
		$this->load_shopinfo();
		import("ORG.Util.String");
		
		cookie('smscode',String::randString(6,'1'));
		$this->assign('smscode',cookie('smscode'));
		$this->display();
	}

    /*
    * 网络验证码验证函数
    */
    public function smslogin(){

        if(IS_POST){
            $this->load_shopinfo();
            $userdb=D('Member');
            $user=I('post.user');
            $pwd=I('post.smscode');
            if(empty($user)||empty($pwd)||$user==""||$pwd==""){
                $data['msg']="提交参数不完整，登录失败";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if(!isPhone($user)){
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if($pwd==""||!isSmsCode($pwd)){
                $data['msg']="验证码不正确";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            $se=cookie('smscode');
            if(empty($se)){
                $data['msg']="验证码不正确,请重新输入";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if($se!=$pwd){
                $data['msg']="验证码不正确,请重新输入";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            /*
            $se=session('smscode');
            if(empty($se)){
                    $data['msg']="验证码不正确,请重新获取";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                    exit;
            }
            $code=json_decode(session('smscode'));
            if($code->phone!=$user||$code->code!=$pwd){
                    $data['msg']="验证码不正确,请重新获取";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                    exit;
            }
            */
            $where['user']=$user;
            $where['routeid']=$this->shop[0]['id'];
            $info=$userdb->where($where)->find();

            if($info==false){
                //添加用户
                C('TOKEN_ON',false);
                $token=md5(uniqid());
                $now=time();
                $tranDb=new Model();

                $_POST['token']=$token;
                $_POST['password']=md5($user);
                $_POST['phone']=$user;
                $_POST['browser']=$this->browser;
                $_POST['mode']='1';//注册认证
                $_POST['add_time']=$now;
                $_POST['update_time']=$now;
                $_POST['login_time']=$now;
                $_POST['add_date']=getNowDate();
                unset($_POST['__hash__']);
                unset($_POST['smscode']);
                if($this->shop!=false){
                    $_POST['shopid']=$this->shop[0]['shopid'];
                    $_POST['routeid']=$this->shop[0]['id'];
                    $_POST['online_time']=$this->getLimitTime();
                }
                $tranDb->startTrans();
                $flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);
                $newid=$tranDb->getLastInsID();
                $arrdata['uid']=$newid;
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='1';//无需认证
                $arrdata['agent']=$this->agent;
                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);


                if($flag&$flagauth){
                    $tranDb->commit();
                    cookie('token',$_POST['token']);
                    cookie('mid',$newid);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    Log::write("手机认证错误:".$tranDb->getLastSql());
                    $data['msg']="验证失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }


            }else{
                //更新用户
                $token=md5(uniqid());
                $now=time();
                $tranDb=new Model();

                $save['token']=$token;
                $save['online_time']=$this->getLimitTime();
                $save['update_time']=$now;
                $save['login_time']=$now;
                $arrdata['uid']=$info['id'];
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='1';//无需认证
                $arrdata['agent']=$this->agent;
                $flag=$tranDb->table(C('DB_PREFIX').'member')->where($where)->save($save);
                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);
                if($flag&&$flagauth){
                    $tranDb->commit();
                    cookie('token',$token);
                    cookie('mid',$info['id']);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    $data['msg']="验证失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }


            }


        }
    }


    /*
     * 暂时没用的函数，本方法想通过session方式用验证码
     */

    public function smsCode(){
        import("ORG.Util.String");
        if(IS_POST){

            $this->load_shopinfo();
            $phone=I('post.phone');

            if(isPhone($phone)){

                $userdb=D('Member');
                $where['user']=$phone;
                $where['routeid']=$this->shop[0]['id'];
                $info=$userdb->where($where)->find();
                $code=String::randString(6,'1');


                if($info!=false){
                    //注册
                }
                $sdata['phone']=$phone;
                $sdata['code']=$code;
                session('smscode',json_encode($sdata));
                $data['msg']=$code;
                $data['error']=0;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
        }
    }

    /**
     * 认证模式1，手机认证end
     */


    /**
	 * 认证模式2，无需认证start
	 */
    /*
     * 一键上网
     */
    public function noAuth(){
        $this->load_shopinfo();
        $now=time();
        $token=md5(uniqid());
        $tranDb=new Model();

        //$db=M('Member');
        $_POST['user']=uniqid();
        $_POST['password']=md5('123456');
        $_POST['phone']='1399999999';
        $_POST['shopid']=$this->shop[0]['shopid'];
        $_POST['routeid']=$this->shop[0]['id'];
        $_POST['browser']=$this->browser;
        $_POST['token']=$token;
        $_POST['mode']='2';//无需认证
        $_POST['online_time']=$this->getLimitTime();
        $_POST['add_time']=$now;
        $_POST['update_time']=$now;
        $_POST['login_time']=$now;
        $_POST['add_date']=getNowDate();
        C('TOKEN_ON',false);
        $tranDb->startTrans();

        $flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);
        $newid=$tranDb->getLastInsID();
        $arrdata['uid']=$newid;
        $arrdata['add_date']=getNowDate();
        $arrdata['over_time']=$this->getLimitTime();
        $arrdata['update_time']=$now;//更新时间
        $arrdata['login_time']=$now;//首次登录时间
        $arrdata['last_time']=$now;//最后在线时间
        $arrdata['shopid']=$this->shop[0]['shopid'];
        $arrdata['routeid']=$this->shop[0]['id'];
        $arrdata['browser']=$this->browser;
        $arrdata['token']=$token;
        $arrdata['mode']='2';//无需认证
        $arrdata['agent']=$this->agent;
        $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);


        if($flag&$flagauth){
            $tranDb->commit();
            cookie('token',$_POST['token']);
            cookie('mid',$newid);
            $this->redirect("index.php/api/userauth/showtoken");
        }else{
            $tranDb->rollback();
            Log::write("自动认证错误:".$tranDb->getLastSql());
            $this->error('服务器异常，请稍候再试');
        }

        /*
            if($db->create()){
                C('TOKEN_ON',true);
                if($db->add()){
                    $newid=$db->getLastInsID();
                    cookie('token',$_POST['token']);
                    cookie('mid',$newid);
                    $this->redirect("index.php/api/userauth/showtoken");
                }else{

                    $this->error('服务器异常，请稍候再试');
                }
            }else{
                Log::write($db->getError());
                $this->error('服务器异常，请稍候再试');
            }
            */
    }


    /**
	 * 认证模式2，无需认证end
	 */



    /**
     * 认证模式3，微信认证start
     */
    /*
     * 微信密码认证页面
     */
    public function wxauth(){
        $this->load_shopinfo();

        $authmode=$this->shop[0]['authmode'];
        $tmp=explode('#', $authmode);
        foreach($tmp as $v){
            if($v!='#'&&$v!=''){
                $arr[]=$v;
            }
        }
        foreach($arr as $v){

            $temp=explode('=', $v);

            if(count($temp)>1&&$temp[0]=='3'){

                $auth['wx']=$temp[1];
                break;
            }
        }
        $wx=json_decode($auth['wx']);

        $this->assign('wxname',$wx->user);
        $this->display();
    }
    /*
     * 微信密码认证处理
     */
    public function dowxauth(){
        $this->load_shopinfo();
        $pwd=I('post.password');
        if($pwd==""){
            $data['msg']="请输入上网认证密码";
            $data['error']=1;
            $this->ajaxReturn($data);
            exit;
        }

        $authmode=$this->shop[0]['authmode'];
        $tmp=explode('#', $authmode);
        foreach($tmp as $v){
            if($v!='#'&&$v!=''){

                $arr[]=$v;
            }
        }

        foreach($arr as $v){

            $temp=explode('=', $v);
            if(count($temp)>1&&$temp[0]=='3'){

                $auth['wx']=$temp[1];
            }
        }

        if(!empty($auth['wx'])){
            $wx=json_decode($auth['wx']);
            if($pwd!=$wx->pwd){
                $data['msg']="上网认证密码不正确";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            $token=md5(uniqid());
            $now=time();
            $tranDb=new Model();

            $_POST['user']=uniqid();
            $_POST['password']=md5('123456');
            $_POST['phone']='1399999999';
            $_POST['shopid']=$this->shop[0]['shopid'];
            $_POST['routeid']=$this->shop[0]['id'];
            $_POST['browser']=$this->browser;
            $_POST['add_time']=$now;
            $_POST['update_time']=$now;
            $_POST['login_time']=$now;
            $_POST['add_date']=getNowDate();
            unset($_POST['__hash__']);
            unset($_POST['smscode']);

            $_POST['mode']='3';//无需认证
            $_POST['online_time']=$this->getLimitTime();
            C('TOKEN_ON',false);


            $tranDb->startTrans();
            $flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);
            $newid=$tranDb->getLastInsID();
            $arrdata['uid']=$newid;
            $arrdata['add_date']=getNowDate();
            $arrdata['over_time']=$this->getLimitTime();
            $arrdata['update_time']=$now;//更新时间
            $arrdata['login_time']=$now;//首次登录时间
            $arrdata['last_time']=$now;//最后在线时间
            $arrdata['shopid']=$this->shop[0]['shopid'];
            $arrdata['routeid']=$this->shop[0]['id'];
            $arrdata['browser']=$this->browser;
            $arrdata['token']=$token;
            $arrdata['mode']='3';//微信
            $arrdata['agent']=$this->agent;
            $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);


            if($flag&$flagauth){
                $tranDb->commit();
                cookie('token',$token);
                cookie('mid',$newid);
                $data['error']=0;
                $data['url']=U("userauth/showtoken");
                $this->ajaxReturn($data);
            }else{
                $tranDb->rollback();
                Log::write("微信认证错误:".$tranDb->getLastSql());
                $data['msg']="认证操作失败，请稍候再试";
                $data['error']=1;
                $this->ajaxReturn($data);
            }



        }else{

            $data['error']=1;
            $data['msg']='认证操作失败,请稍候再试';
            $this->ajaxReturn($data);

        }

    }

    /**
     * 认证模式3，微信认证end
     */



    /**
     * 认证模式4，我爱生活网会员认证start
     */
    //新添加的函数，用于与我爱生活网用户名密码登录界面
    public function wxashlogin(){
        $this->display();
    }

    //新添加的函数，用于与我爱生活网用户名密码登录
    public function dowxashlogin(){
        import("ORG.Util.String");
        $this->load_shopinfo();
        if(IS_POST){
            $user=I('post.user');
            $pwd=I('post.password');

            //用户名和密码提交到另个一平台的接口
            $ch = curl_init();
            $str ="http://www.5ilife.cn:88/android.php/customer?req=0102&username=".$user."&password=".$pwd;
            curl_setopt($ch, CURLOPT_URL, $str);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            $output = curl_exec($ch);
            $obj = json_decode($output);
            if($obj->status==0)
            {
                $data['error']=1;
                $data['msg']="登录错误";
                $this->ajaxReturn($data);
                exit;
                //未完待续
            }
            else{

                $token=md5(uniqid());
                $now=time();
                $tranDb=new Model();
                $save['user']=uniqid();
                $save['password']=md5('123456');
                $save['phone']='13999999999';
                $save['shopid']=$this->shop[0]['shopid'];
                $save['routeid']=$this->shop[0]['id'];
                $save['token']=$token;
                $save['browser']=$this->browser;
                $save['mode']='5';
                $save['online_time']=$this->getLimitTime();
                $save['update_time']=$now;
                $save['login_time']=$now;
                C('TOKEN_ON',false);
                $tranDb->startTrans();
                $flag=$tranDb->table(C('DB_PREFIX').'member')->add($save);
                $newid=$tranDb->getLastInsID();

                $arrdata['uid']=$newid;
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='5';//无需认证
                $arrdata['agent']=$this->agent;

                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);
                if($flag&&$flagauth){
                    $tranDb->commit();
                    cookie('token',$token);
                    cookie('mid',$newid);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    $data['msg']="登录失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }


            }
        }

    }

    /**
     * 认证模式4，我爱生活网会员认证end
     */

    /**
     * 认证模式5，短信认证start
     */

    /*
     *短信验证码界面
     */
    public function sms(){
        $this->load_shopinfo();
        $this->display();
    }

    /*
     * 获取短信验证码函数
     */
    public function getSmsCode(){
        $this->load_shopinfo();
        $Shop=M("Shop");
        $smsNum=$Shop->where('id='.cookie("shopid"))->getField('sms_num');
        if($smsNum<=0){
            $data['msg']="本店已无法发送短信验证码！";
            $data['error']=1;
            $this->ajaxReturn($data);
            exit;
        }
        else{
            //cookie('smscode',String::randString(6,'1'));
            cookie('smscode',"111111");
            $sms=cookie('smscode');
            //然后通过短信平台发送给用户


            $Shop->where('id='.cookie("shopid"))->setDec('sms_num');
            $data['msg']="短信验证码获取成功";
            $data['error']=0;
            $this->ajaxReturn($data);
            exit;

        }
    }

    /*
     * 进行短信验证码验证
     */
    public function dosmslogin(){
        if(IS_POST){
            $this->load_shopinfo();
            $userdb=D('Member');
            $user=I('post.user');
            $pwd=I('post.smscode');
            if(empty($user)||empty($pwd)||$user==""||$pwd==""){
                $data['msg']="提交参数不完整，登录失败";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if(!isPhone($user)){
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if($pwd==""||!isSmsCode($pwd)){
                $data['msg']="验证码不正确";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            $se=cookie('smscode');
            if(empty($se)){
                $data['msg']="验证码不正确,请重新输入";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }
            if($se!=$pwd){
                $data['msg']="验证码不正确,请重新输入";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }

            $where['user']=$user;
            $where['routeid']=$this->shop[0]['id'];
            $info=$userdb->where($where)->find();

            if($info==false){
                //添加用户
                C('TOKEN_ON',false);
                $token=md5(uniqid());
                $now=time();
                $tranDb=new Model();

                $_POST['token']=$token;
                $_POST['password']=md5($user);
                $_POST['phone']=$user;
                $_POST['browser']=$this->browser;
                $_POST['mode']='1';//注册认证
                $_POST['add_time']=$now;
                $_POST['update_time']=$now;
                $_POST['login_time']=$now;
                $_POST['add_date']=getNowDate();
                unset($_POST['__hash__']);
                unset($_POST['smscode']);
                if($this->shop!=false){
                    $_POST['shopid']=$this->shop[0]['shopid'];
                    $_POST['routeid']=$this->shop[0]['id'];
                    $_POST['online_time']=$this->getLimitTime();
                }
                $tranDb->startTrans();
                $flag=$tranDb->table(C('DB_PREFIX').'member')->add($_POST);
                $newid=$tranDb->getLastInsID();
                $arrdata['uid']=$newid;
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='1';//无需认证
                $arrdata['agent']=$this->agent;
                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);


                if($flag&$flagauth){
                    $tranDb->commit();
                    cookie('token',$_POST['token']);
                    cookie('mid',$newid);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    Log::write("手机认证错误:".$tranDb->getLastSql());
                    $data['msg']="验证失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }


            }else{
                //更新用户
                $token=md5(uniqid());
                $now=time();
                $tranDb=new Model();

                $save['token']=$token;
                $save['online_time']=$this->getLimitTime();
                $save['update_time']=$now;
                $save['login_time']=$now;
                $arrdata['uid']=$info['id'];
                $arrdata['add_date']=getNowDate();
                $arrdata['over_time']=$this->getLimitTime();
                $arrdata['update_time']=$now;//更新时间
                $arrdata['login_time']=$now;//首次登录时间
                $arrdata['last_time']=$now;//最后在线时间
                $arrdata['shopid']=$this->shop[0]['shopid'];
                $arrdata['routeid']=$this->shop[0]['id'];
                $arrdata['browser']=$this->browser;
                $arrdata['token']=$token;
                $arrdata['mode']='1';//无需认证
                $arrdata['agent']=$this->agent;
                $flag=$tranDb->table(C('DB_PREFIX').'member')->where($where)->save($save);
                $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);
                if($flag&&$flagauth){
                    $tranDb->commit();
                    cookie('token',$token);
                    cookie('mid',$info['id']);
                    $data['error']=0;
                    $data['url']=U("userauth/showtoken");
                    $this->ajaxReturn($data);
                }else{
                    $tranDb->rollback();
                    $data['msg']="验证失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }


            }


        }

    }

    /**
     * 认证模式5，短信认证end
     */


    /**
     * 客户留言start
     */
    public function comments(){
		$this->load_shopinfo();
		$this->display();
	}

    /*
	 * 客户留言
	 */
    public function addmsg(){
        $this->load_shopinfo();
        if(IS_POST){
            $user=I('post.user');

            $phone=I('post.phone');
            $info=I('post.content');


            if(!isPhone($phone)){
                $data['msg']="请填写有效的11位手机号码";
                $data['error']=1;
                $this->ajaxReturn($data);
                exit;
            }

            $db=D('Comment');
            $_POST['shopid']=$this->shop[0]['shopid'];
            $_POST['routeid']=$this->shop[0]['id'];
            if($db->create()){
                if($db->add()){
                    $newid=$db->getLastInsID();
                    $data['error']=0;
                    $this->ajaxReturn($data);
                }else{
                    $data['msg']="提交留言失败，请稍候再试";
                    $data['error']=1;
                    $this->ajaxReturn($data);
                }
            }else{
                $data['msg']=$db->getError();
                $data['error']=1;
                $this->ajaxReturn($data);
            }
        }
    }

    /**
     * 客户留言end
     */

}