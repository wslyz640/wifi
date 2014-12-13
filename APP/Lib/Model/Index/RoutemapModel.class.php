<?php
class RoutemapModel extends Model{
    
    protected $_validate = array(
   
        array('shopid','require','路由所属帐号不能为空',1),
        array('routename','require','路由名称不能为空',1),
        array('gw_id','require','网关ID不能为空',1),
		array('gw_id','/^\w+?/','网关ID由数字，字母或下划线组成','regex',3),
        array('gw_id','','网关不能重复',1,'unique',1),
		array('sortid','require','排序不能为空',1),
		array('sortid','number','排序必须是数字类型'),
    );
    
    protected $_auto = array(
        array('add_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
        array('state','1',self::MODEL_INSERT),
    );
}