<?php

class HorarioReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id_tipo', 'integer', null, array(
                'primary' => true
            )
        );

        $this->hasColumn('id_teoria', 'integer', null, array(
                'primary' => true
            )
        );
    }
}
