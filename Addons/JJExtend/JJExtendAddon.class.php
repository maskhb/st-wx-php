<?php

namespace Addons\JJExtend;
use Common\Controller\Addon;

/**
 * 新家居功能扩展插件
 * @author 无名
 */

class JJExtendAddon extends Addon{

    public $info = array(
        'name'=>'JJExtend',
        'title'=>'新家居功能扩展',
        'description'=>'新家居功能扩展，如发送模版消息等',
        'status'=>1,
        'author'=>'无名',
        'version'=>'0.1',
        'has_adminlist'=>0
    );

    public function install() {
        $install_sql = './Addons/JJExtend/install.sql';
        if (file_exists ( $install_sql )) {
            execute_sql_file ( $install_sql );
        }
        return true;
    }
    public function uninstall() {
        $uninstall_sql = './Addons/JJExtend/uninstall.sql';
        if (file_exists ( $uninstall_sql )) {
            execute_sql_file ( $uninstall_sql );
        }
        return true;
    }

    //实现的weixin钩子方法
    public function weixin($param){

    }

}