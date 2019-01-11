<?php
namespace LoktarJugg\MiniProgram;
use \Illuminate\Support\ServiceProvider;
class MiniProgramServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(MiniProgram::class, function(){
            return new MiniProgram(new Encryptor());
        });

        $this->app->alias(MiniProgram::class, 'miniProgram');
    }

    public function provides()
    {
        return [MiniProgram::class, 'miniProgram'];
    }
}