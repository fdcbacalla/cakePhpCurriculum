<?php
App::uses('ModelBehavior', 'Model');

class SoftDeleteBehavior extends ModelBehavior {

    public function beforeDelete(Model $model, $cascade = true) {
        $model->updateAll(array($model->alias . '.deleted' => true), array($model->alias . '.' . $model->primaryKey => $model->id));
        return false; // Prevent actual deletion from database
    }

}
