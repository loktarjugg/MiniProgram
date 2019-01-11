<h1 align="center">MiniProgram</h1>
<p align="center">Laravel 微信小程序登录扩展</p>

## 安装

```sh
$ composer require loktarjugg/mini-program
```

## 配置

在 `config/services.php` 下增加配置项 `miniProgram`

```php
    'miniProgram' => [
        'appId' => env('MINI_PROGRAM_APPID'),
        'appKey' => env('MINI_PROGRAM_SECRET')
    ],
```

## 使用

```php
use LoktarJugg\MiniProgram\MiniProgram;
...
try{
    $session = \MiniProgram::session($request->get('code'));
    $userInfo = \MiniProgram::decryptData($session['session_key'], $request->get('iv'), $request->get('encryptedData'));
}catch (\Exception $e){
    ...
}
```

or

```php
...
use LoktarJugg\MiniProgram\MiniProgram;

class LoginController extends Controller{
    protected $miniProgram;

    public function __construct(MiniProgram $miniProgram)
    {
        $this->miniProgram = $miniProgram;
    }

    public function test(Request request){
        try{
            $session = $this->miniProgram->session($request->get('code'));
            $userInfo = $this->miniProgram->decryptData($session['session_key'], $request->get('iv'), $request->get('encryptedData'));
        }catch (\Exception $e){
            ...
        }
    }
    ...
}

```
> 如果返回的userInfo 没有 UnionID 参考 https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/union-id.html


## 声明

这个项目解密部分引用了 `overtrue` 的 [EasyWechat](https://github.com/overtrue/wechat) 包
