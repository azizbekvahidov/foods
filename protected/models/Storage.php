<?php

/**
 * This is the model class for table "storage".
 *
 * The followings are the available columns in table 'storage':
 * @property integer $storage_id
 * @property string $curDate
 * @property integer $prod_id
 * @property double $curCount
 */
class Storage extends CActiveRecord
{
    public function addToStorage($prodId,$cnt){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("storage")
            ->where("prod_id = :id",array(":id"=>$prodId))
            ->queryRow();
        if(!empty($model)){
            Yii::app()->db->createCommand()->update("storage",array(
                "cnt" => $model["cnt"] + $cnt
            ),"prod_id = :id",array(":id"=>$prodId));
        }
        else{
            Yii::app()->db->createCommand()->insert("storage",array(
                "cnt" => $cnt,
                "prod_id" => $prodId
            ));
        }
    }

    public function removeToStorage($prodId,$cnt){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("storage")
            ->where("prod_id = :id",array(":id"=>$prodId))
            ->queryRow();
        if(!empty($model)){
            Yii::app()->db->createCommand()->update("storage",array(
                "cnt" => $model["cnt"] - $cnt
            ),"prod_id = :id",array(":id"=>$prodId));
        }
        else{
            Yii::app()->db->createCommand()->insert("storage",array(
                "cnt" => (-1)*$cnt,
                "prod_id" => $prodId
            ));
        }
    }

    public function addToStorageDep($prodId,$cnt,$type,$depId){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("storage_dep")
            ->where("prod_id = :id AND prod_type = :t AND department_id = :depId",array(":id"=>$prodId,":t"=>$type,":depId"=>$depId))
            ->queryRow();
        if(!empty($model)){
            Yii::app()->db->createCommand()->update("storage_dep",array(
                "cnt" => $model["cnt"] + $cnt
            ),"prod_id = :id AND prod_type = :t AND department_id = :depId",array(":id"=>$prodId,":t"=>$type,":depId"=>$depId));
        }
        else{
            Yii::app()->db->createCommand()->insert("storage_dep",array(
                "cnt" => $cnt,
                "prod_id" => $prodId,
                "prod_type" => $type,
                "department_id" => $depId
            ));
        }
    }

    public function removeToStorageDep($prodId,$cnt,$type,$depId){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("storage_dep")
            ->where("prod_id = :id AND prod_type = :t AND department_id = :depId",array(":id"=>$prodId,":t"=>$type,":depId"=>$depId))
            ->queryRow();
        if(!empty($model)){
            Yii::app()->db->createCommand()->update("storage_dep",array(
                "cnt" => $model["cnt"] - $cnt
            ),"prod_id = :id AND prod_type = :t AND department_id = :depId",array(":id"=>$prodId,":t"=>$type,":depId"=>$depId));
        }
        else{
            Yii::app()->db->createCommand()->insert("storage_dep",array(
                "cnt" => (-1)*$cnt,
                "prod_id" => $prodId,
                "prod_type" => $type,
                "department_id" => $depId
            ));
        }
    }

}
