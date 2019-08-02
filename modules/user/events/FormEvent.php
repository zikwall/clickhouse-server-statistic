<?php

/*
 * 
 *
 * 
 *
 * 
 * 
 */

namespace app\modules\user\events;

use yii\base\Event;
use yii\base\Model;

/**
 * @property Model $model
 * >
 */
class FormEvent extends Event
{
    /**
     * @var Model
     */
    private $_form;

    /**
     * @return Model
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @param Model $form
     */
    public function setForm(Model $form)
    {
        $this->_form = $form;
    }
}