<?php

namespace Example\Database\Entities;

/**
 * @entity(table_name = model_1 )
 */
class Model1
{
    /**
     * @autoincr
     * @column_info(name = id)
     * @var integer
     */
    public $id;

    /**
     * @column_info(name = name)
     * @var string
     */
    public $name;

    /**
     * @column_info(name = value)
     * @var string
     */
    public $value;

    /**
     * Поле без привязки к таблице
     * @var ololo pew-pew-prpew dsdfsdf
     */
    public $extra_value;


    public function __construct($id, $name, $value)
    { 
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the value of id
     *
     * @return  integer
     */ 
    public function getId()
    {
        return $this->id;
    }
}
