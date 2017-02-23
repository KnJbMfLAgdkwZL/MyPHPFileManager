<?php

class Test extends ActiveRecord
{
    public function selectAll()
    {
        $sql = "SELECT * FROM `Test`";
        return $this->execute($sql);
    }
}