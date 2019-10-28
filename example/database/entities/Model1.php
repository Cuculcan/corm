<?php

namespace Example\Database\Entities;

/**
 * @entity(table_name = model_1 )
 */
class Model1
{
    /**
     * @column_info(name = id)
     * @var integer
     */
    private $id;

    /**
     * @column_info(name = name)
     * @var string
     */
    private $name;

    /**
     * @column_info(name = value)
     * @var string
     */
    private $value;

    /**
     * Поле без привязки к таблице
     * @var ololo pew-pew-prpew dsdfsdf
     */
    private $extra_value;


    public function __construc($id, $name, $value)
    { }
}
