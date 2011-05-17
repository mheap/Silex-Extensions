<?php


namespace SilexExtension;

use Silex\Application;
use Silex\ExtensionInterface;


class MandangoExtension implements ExtensionInterface
{
    public function register(Application $app)
    {  
        $app->get('/_mandango/{token}', function() use ($app) {
                
                
                return 'generating classes';
                
                
            
        });
        
        
        
    }
    
    
}
