<?php 

namespace Example\Database\Entities;

/**
 * @entity(table_name = model_1 )
 */
class Model1
{
    /**
     * @ColumnInfo(name = "id")
     * @var integer
     */
    private $id;

    /**
     * @ColumnInfo(name = "name")
     * @var string
     */
    private $name;

    /**
     * @ColumnInfo(name = "value")
     * @var string
     */
    private $value;

    public function __construc($id, $name, $value)
    {
        
    }
}