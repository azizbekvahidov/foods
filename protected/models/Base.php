<?php

class Base extends CActiveRecord
{
    public function afterSave(){
        echo "<pre>";
        print_r($this->getScenario());
        echo "</pre>";
    }

}