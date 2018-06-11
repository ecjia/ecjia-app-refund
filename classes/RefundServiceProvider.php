<?php

namespace Ecjia\App\Refund;

use Royalcms\Component\App\AppServiceProvider;

class RefundServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-refund');
    }
    
    public function register()
    {
        
    }
    
    
    
}