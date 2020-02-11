<?php

namespace pso\yii2\widgets;

use kartik\grid\GridView as BaseGridView;
use kartik\export\ExportMenu;

class GridView extends BaseGridView
{
    public $fullExport = false;

    private $rawColumns = [];

    public function init()
    {
        if($this->fullExport){
            $this->rawColumns = $this->columns;
        }
        parent::init();
    }

    public function renderExport()
    {
        if(!$this->fullExport){
            return parent::renderExport();
        }
        return ExportMenu::widget([
            'dataProvider' => $this->dataProvider,
            'columns' => $this->rawColumns,
            'clearBuffers' => true
        ]);
    }
}