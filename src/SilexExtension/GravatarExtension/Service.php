<?php

namespace SilexExtension\GravatarExtension;

/**
 * @see http://de.gravatar.com/site/implement/images/
 */
class Service
{
    const URL = '%s://www.gravatar.com/avatar/'
    
    protected $defaults = array(
        'size'   => 200,
        'rating' => 'g',
        'secure' => false
    );
    
    protected $options = array();
    
    public function __construct(array $options = array())
    {
        $this->options = array_merge($this->defaults, $options);
    }
    
    
    public function url($email)
    {
        
    }
    
    public function exist($email)
    {
    
    }
}