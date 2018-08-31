<?php
        	
namespace Addons\JJExtend\Model;
use Home\Model\WeixinModel;
        	
/**
 * JJExtend的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'JJExtend' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	