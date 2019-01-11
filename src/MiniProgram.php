<?php

namespace LoktarJugg\MiniProgram;

use GuzzleHttp\Client;
use LoktarJugg\MiniProgram\Exceptions\HttpException;
use LoktarJugg\MiniProgram\Encryptor;

class MiniProgram {
    protected $appId;
    protected $appKey;
    const BASE_REQUEST_URL = 'https://api.weixin.qq.com';

    protected $encryptor;

    public function __construct(Encryptor $encryptor) {
        $this->appId = config('services.miniProgram.appId');
        $this->appKey = config('services.miniProgram.appKey');
        $this->encryptor = $encryptor;
    }

    public function decryptData(string $sessionKey, string $iv, string $encrypted) {
        return $this->encryptor->decryptData($sessionKey, $iv, $encrypted);
    }

    public function session(string $code) {
        $params = array_filter([
            'appid' => $this->appId,
            'secret' => $this->appKey,
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ]);
        try{
            $client = $this->getHttpClient();

            $response = $client->request(
                'GET', 
                '/sns/jscode2session', 
                ['query' => $params])
                ->getBody()->getContents();
            $response = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
            
            if(isset($response['errcode']) && $response['errcode'] !== 0){
                throw new HttpException($response['errmsg'], $response['errcode']);
            }

            // http://www.php.net/manual/zh/function.json-last-error.php
            // 判断解析是否成功
            if (JSON_ERROR_NONE === json_last_error()) {
                return (array) $response;
            }
            return false;
        }catch(Exception $e){
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

    }

    protected function getHttpClient() {
        return new Client([
            'base_uri' => self::BASE_REQUEST_URL
        ]);
    }
}