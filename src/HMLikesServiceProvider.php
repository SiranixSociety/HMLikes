<?php

namespace SiranixSociety\HMLikes;

use Illuminate\Support\ServiceProvider;

class HMLikesServiceProvider extends ServiceProvider{
    protected $defer = false;

    public function boot(){
        $TimeStamp = date('Y_m_d_His', time());
        $MHTableFileName = "HMLikeTables";
        $this->publishes([
            __DIR__.'/Database/Migrations/'.$MHTableFileName.'.php' => $this->app->databasePath().'/migrations/'.$TimeStamp.'_'.$MHTableFileName.'.php',
        ], 'migrations');
    }

    public function register(){

    }
}