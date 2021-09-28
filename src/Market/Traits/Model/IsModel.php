<?php
namespace TokenPrice\Market\Traits\Model;

trait IsModel {
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

        return self::$_instance->filter();
    }
}
?>