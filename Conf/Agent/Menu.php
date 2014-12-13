<?php
return array(
	array( "model"=>"Index",
				"action"=>"index",
				"name"=>"首页",
				"ico"=>"icon-home",
				
		),
		array( "model"=>"Index",
				"action"=>"account",
				"name"=>"帐号管理",
				"ico"=>"icon-user",  
		),
		array( "model"=>"Index",
				"action"=>"shoplist",
				"name"=>"商户管理",
				"ico"=>"icon-group",  
				"nodes"=>array(
						array("model"=>"Index","action"=>"shoplist","name"=>"商户列表"),
						array("model"=>"Index","action"=>"shopadd","name"=>"添加商户"),
				)
		),
		array( "model"=>"Admanage",
				"action"=>"index",
				"name"=>"广告管理",
				"ico"=>"icon-group",  
				"nodes"=>array(
						array("model"=>"Admanage","action"=>"shopad","name"=>"广告列表"),
						array("model"=>"Admanage","action"=>"adrpt","name"=>"广告统计"),
				)
		),
		array( "model"=>"pushadv",
				"action"=>"index",
				"name"=>"广告推送管理",
				"ico"=>"icon-group",  
				"nodes"=>array(
						array("model"=>"pushadv","action"=>"set","name"=>"推送设置"),
						array("model"=>"pushadv","action"=>"index","name"=>"广告列表"),
						array("model"=>"pushadv","action"=>"add","name"=>"投放广告"),
						array("model"=>"pushadv","action"=>"rpt","name"=>"投放统计"),
				)
		),
		array( "model"=>"Index",
				"action"=>"report",
				"name"=>"资金报表",
				"ico"=>"icon-bar-chart",  
		),
				
		
		
);
?>