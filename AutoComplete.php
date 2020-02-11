<?php

namespace pso\yii2\widgets;

use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;

class AutoComplete extends Select2
{
    public $url;

    public function init()
    {
        if(!empty($this->url)){
            if(empty($this->pluginOptions['ajax'])){
                $this->pluginOptions['ajax'] = [];
            }
            $this->pluginOptions['ajax']['url'] = Url::toRoute($this->url);
            $this->pluginOptions['ajax']['dataType'] = 'json';
        }
        $this->theme = SELF::THEME_KRAJEE_BS4;
        if(!isset($this->pluginOptions['allowClear'])){
            $this->pluginOptions['allowClear'] = true;
        }
        if(!isset($this->pluginOptions['minimumInputLength'])){
            $this->pluginOptions['minimumInputLength'] = 3;
        }
        $this->pluginOptions['language'] = [
            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
        ];
        parent::init();
    }
}