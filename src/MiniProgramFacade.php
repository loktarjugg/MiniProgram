<?php
namespace LoktarJugg\MiniProgram;
use \Illuminate\Support\Facades\Facade;

class MiniProgramFacade extends Facade {
    protected static function getFacadeAccessor() {
        return 'miniProgram';
    }
}