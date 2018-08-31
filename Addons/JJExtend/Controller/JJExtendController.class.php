<?php

namespace Addons\JJExtend\Controller;
use Home\Controller\AddonsController;

class JJExtendController extends AddonsController{
    function _initialize() {
        parent::_initialize();
    }
    function test(){
        $this->ajaxReturn(array('name'=>'sk'));
    }

    /*获取accessToken*/
    public function getAccessToken(){
        $pwd = I('pwd');
        $accesstoken = '';
        if($pwd == 'ht123'){
            $accesstoken = get_access_token();
        }
        $this->ajaxReturn($accesstoken);
    }

    /*更新accessToken*/
    public function updateAccessToken(){
        $pwd = I('pwd');
        $accesstoken = '';
        if($pwd == 'ht123'){
            $accesstoken = get_access_token(null, true);
        }
        $this->ajaxReturn($accesstoken);
    }

    public function getOpenId(){
        $pwd = I('pwd');
        $openid = '';
        if($pwd == 'ht123'){
            $openid = get_openid();
        }
        $this->ajaxReturn($openid);
    }

    public function getuid(){
        $pwd = I('pwd');
        $uid = '';
        if($pwd == 'ht123'){
            $uid = get_uid_by_openid();
        }
        $this->ajaxReturn($uid);
    }

    public function sendOrder(){
        $data['openid'] = I('openid');
        $data['goodsName'] = I('goodsName');
        $data['totalAmount'] = I('totalAmount')/100;
        $data['finalPayAmount'] = I('finalPayAmount')/100;
        $data['remark'] = I('remark');

        /*链接*/
        $data['url'] = '';
        $data['orderId'] = I('orderId');
        $data['env'] = I('env');
        if($data['env'] &&  $data['orderId']){
            switch($data['env']){
                case 'dev':
                    $data['url'] = 'http://ht-jj-h5-dev.hd/Order/detail/' . $data['orderId'];
                    break;
                case 'beta':
                    $data['url'] = 'http://ht-jj-h5-test.htmimi.com/Order/detail/' . $data['orderId'];
                    break;
                case 'fix':
                    $data['url'] = 'http://ht-jj-h5-fix.hd/Order/detail/' . $data['orderId'];
                    break;
                case 'stg':
                    $data['url'] = 'http://ht-jj-h5-stg.htmimi.com/Order/detail/' . $data['orderId'];
                    break;
                case 'release':
                    $data['url'] = 'http://ht-jj-h5.htmimi.com/Order/detail/' . $data['orderId'];
                    break;
                default:
                    $data['url'] = 'http://ht-jj-h5-dev.hd/Order/detail/' . $data['orderId'];
            }
        }
//        $data['url'] = 'http://ht-jj-h5-dev.hd/Order/detail/201805100841';
//        $this->ajaxReturn($data);
        $this->ajaxReturn($this->sendOrderTemplate($data));
    }

    private function sendOrderTemplate($data){
        header('Access-Control-Allow-Origin:*');

        $access_token = get_access_token();
//        $access_token = '9_iEPy4hCW2miU44Mg-wTIkAn2IPEtpUubto0GkWtLpkcVUqIQdXHixQw214IvTg2MME9ijShng_96Dlk4wFuLLMpQS_oQA-65zcu6ZACwvDw7_-qm4WVZt2U7R_IiKMgQvmvMa37wBz8UmK88RCXgABAALG';
        $posturl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $postdata = array('touser'=>$data['openid'],
            'template_id'=>'8lmglyfSs2RGLlOR2rM_w-6d6sc8mQUa5VYtacpmPA0',
            'url'=>$data['url'],
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array('value'=>'您购买的【' . $data['goodsName'] . '】已审核完毕',
                    'color'=>'#173177'
                ),
                'keyword1'=>array('value'=>'￥' . $data['totalAmount'] . '元',
                    'color'=>'#173177'
                ),
                'keyword2'=>array('value'=>'￥' . $data['finalPayAmount'] . '元',
                    'color'=>'#FF3333'
                ),
                'remark'=>array('value'=>$data['remark'],
                    'color'=>'#00A6FF'
                )
            )
        );
        $content = post_data ( $posturl, json_encode($postdata), false, false );
        $res = json_decode ( $content, true );
        return $res;
    }
}
