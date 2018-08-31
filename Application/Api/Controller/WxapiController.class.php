<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Api\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class WxapiController extends Controller {
    public function __construct() {

    }

	public function JsSignPackage(){
	    $url = I('url');
	    $sp = getSignPackage($url);
        unset($sp['rawString']);
        header("Access-Control-Allow-Origin:*");
        $this->ajaxReturn($sp);
	}

	public function test(){
	    echo 'tikcket:';
	    var_dump(getJsApiTicket());
        echo 'token:';
        var_dump(get_token());
        echo 'accesstoken';
        var_dump(get_access_token());
    }

    public function getUser(){
        header("Access-Control-Allow-Origin:*");
        $info = getUser();
        $this->ajaxReturn($info);
    }

}
