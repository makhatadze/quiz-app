<?php


namespace app\widgets;


use Yii;

class gridAction extends \yii\grid\ActionColumn
{

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open', [
            'class' => 'btn btn-info',
            'style' => 'padding: 7px 8.4px;'
        ]);
        $this->initDefaultButton('update', 'pencil', [
            'class' => 'btn btn-warning',
            'style' => 'padding: 7px 8.5px;'
        ]);
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
            'class' => 'btn btn-danger',
            'style' => ' padding: 7px 8.5px; 
            '
        ]);
    }

}