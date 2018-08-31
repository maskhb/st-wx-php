<?php
namespace JSON;
/**
 * AES128加解密类
 * @author dy
 *
 */
class JSON{
    var $header, $AES_BASE_KEY, $APP_ID, $APP_SECURITY, $DEFAULT_TOKEN;
    function __construct($token='',$type=1){
        $this->jsonconfig = include ('config.php');

        $this->AES_BASE_KEY = "Jq2VtktMAyqnMqenGH/FDQ==";
        $this->APP_ID = "app110887164";
        $this->APP_SECURITY = "454b42cf-3447-4782-8b62-38b933c2524d";
        $this->DEFAULT_TOKEN = "JCeFOs2lw2myA1N31AlEeRKhBKMW4JexdWpilBuA";
        $this->TOKEN = ($token)?$token:$this->DEFAULT_TOKEN;

        $this->header = array(
            'Content-Type: application/x-json',
            'x-security-version:1.0',
            'x-security-timestamp:'.time()*1000,
            'x-client-id:',
            'x-client-type:wechat',
            'x-client-os:wechat',
            'x-client-os-version:0',
            'x-client-channel:0',
            'x-client-hardware:0',
            'x-client-version-name:wechat',
            'x-client-version-code:0',
            'x-request-time:'.time()*1000,
            'x-client-source:7',
            'x-client-appId:' . $this->APP_ID,
            'x-client-fruit:watermelon'
        );
        if($token){
            switch ($type){
                case 1:
                    $this->header[] = 'x-security-token:'.$token;break;
                case 2:
                    $this->header[] = 'x-manager-token:'.$token;break;
                case 3:
                    $this->header[] = 'x-supplier-token:'.$token;break;
                default:
                    $this->header[] = 'x-security-token:'.$token;break;
            }
        }


    }

    /*生成密钥*/
    function calculateKey($token = '', $key = ''){
        if(!$token){
            $token = $this->TOKEN;
        }
        if(!$key){
            $key = $this->AES_BASE_KEY;
        }
        return substr(hash('sha256', $token.$key), 0, 16);
    }

    /*header*/
    function toPost($url, $jsonData){
        $curl = curl_init ();
        curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $curl, CURLOPT_POST, 1 );
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $jsonData );
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $this->header);
        $result = curl_exec ( $curl );
        if (curl_errno ( $curl )) {
            $this->ErrorLogger ( 'curl falied. Error Info: ' . curl_error ( $curl ) );
        }
        curl_close ( $curl );
        return $result;
    }

    function signature($data){
        return strtoupper(md5(json_encode2($data) . $this->APP_ID . $this->APP_SECURITY));
    }

    function post($api, $method, $postdata = array(), $header = array()){
        $signature = $this->signature($postdata);
        $aes = new \Aes\Aes();
        $key =  $this->calculateKey();
        $allParams = $aes->encrypt(json_encode2(array('signature'=>$signature, 'params'=>$postdata)), $key);
        $jsonconfig = $this->jsonconfig;
        $current = $this->jsonconfig['current'];

        /*修改头*/
        if($header){
            if($header['x-supplier-token']){
                $this->header[] = 'x-supplier-token:' . $header['x-supplier-token'];
            }else{
                $this->header[] = 'x-supplier-token:';
            }
            if($header['x-security-token']){
                $this->header[] = 'x-security-token:' . $header['x-security-token'];
            }else{
                $this->header[] = 'x-security-token:';
            }
            if($header['x-manager-token']){
                $this->header[] = 'x-manager-token:' . $header['x-manager-token'];
            }else{
                $this->header[] = 'x-manager-token:';
            }
        }

        $backdata = $this->toPost('http://' . $jsonconfig[$current]['prefix'] . $api . $jsonconfig[$current]['suffix'] . '/'.$method, $allParams);
        return json_decode($backdata, true,512, JSON_BIGINT_AS_STRING);

    }

    function Decrypt($str){
        $data = \Aes\Aes::decrypt($str, $this->calculateKey());
        $data = json_decode($data,true,512, JSON_BIGINT_AS_STRING);
        return $data;
    }


    /* 错误日志记录 */
    function ErrorLogger($errMsg) {
        $logger = fopen ( './ErrorLog.txt', 'a+' );
        fwrite ( $logger, date ( 'Y-m-d H:i:s' ) . " Error Info : " . $errMsg . "\r\n" );
        dump ( $errMsg );
    }
}