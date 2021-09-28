<?php
namespace TokenPrice;

use TokenPrice\Market\Interfaces\Model\ModelInterface;

class Model{

    protected static object $data;
    public static array $options;

    public static $_instance = null;

    public static function create($data, array $options)
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        
        self::$data = (object) $data;
        self::$options = $options;

        return self::$_instance;
    }


}
