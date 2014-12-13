<?php
class BaseAction extends Action{
	protected function _initialize()
	{
		//读取模板主题路径
		$theme_path = $this->_getThemePath();
		$public = array('css'=>'/UI/Public/css','js'=>'/UI/Public/js','img'=>'/UI/Public/images/','root'=>'/UI/Public');
		$theme =	array('css'=>$theme_path.'/style/css','js'=>$theme_path.'/style/js','img'=>$theme_path.'/style/images','root'=>$theme_path.'/');
		$style = array('P' =>$public,'T' =>$theme);
		$Style =  $style;
		$this->assign('Theme',$Style);
		$this->assign('action', $this->getActionName());
	}
	
	private function _getThemePath()
	{
		$theme = C('DEFAULT_THEME');
		$group  = defined('GROUP_NAME')?GROUP_NAME.'/':'';
		if(1==C('APP_GROUP_MODE')){ // 独立分组模式
			return $theme_path = '/'.dirname(BASE_LIB_PATH).'/'.$group.basename(TMPL_PATH).'/'.$theme;
		}else{
			return $theme_path = '/'.basename(TMPL_PATH).'/'.$group.$theme;
		}
	}

}