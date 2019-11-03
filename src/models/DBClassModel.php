<?php

namespace Corm\Models;

use Corm\Models\DaoGetter;

use Corm\Models\EntityModel;

class DBClassModel
{

    /**
     * @var string
     */

    public $fullName;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $className;

    /**
     * @var EntityModel[];
     */
    public $entities;

    /**
     * @var DaoGetter[]
     */
    public $daoInterfaces;

    private $entitiesMap = null;

    public function getEntitiesMap() {

        if($this->entitiesMap != null){
            return $this->entitiesMapl;
        }

        $entMap = [];
        foreach ($this->entities as $entity){
            $entMap[$entity->namespace.'\\'.$entity->className] = $entity;
        }
        $this->entitiesMapl = $entMap;
        return $this->entitiesMap;
    }
}
