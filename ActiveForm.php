<?php

namespace pso\yii2\widgets;

use yii\bootstrap4\ActiveForm as BaseActiveForm;
use yii\helpers\Html;


class ActiveForm extends BaseActiveForm
{
    public $messageContainerId;

    public $contentContainerId;
    public $contentBackButtonOptions = ['class' => 'btn btn-link', 'style' => 'margin-top: 20px'];

    public $hideFormOnContent = true;

    public $redirectTimeout = 3000;

    public $submittingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait...';

    public $enableAjaxSubmit = false;

    public function registerClientScript()
    {
        parent::registerClientScript();
        // this may not be needed in other installations but for dashforge, had to not use alert-danger
        $this->getView()->registerCss('.alert-danger-x {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }', [], 'custom-active-form-alert-x');
        if($this->enableAjaxSubmit){
            $id = $this->options['id'];
            $prepareContentContainer  = "";
            if(!$this->contentContainerId){
                $this->contentContainerId = "$id-content";
                $prepareContentContainer = "jQuery('#$id').before(jQuery('<div>', {id: '$this->contentContainerId'}))";
            }
            $hideForm = '';
            if($this->hideFormOnContent){
                $hideForm = "jQuery(yiiform).hide()";
            }
            $bbOptions = json_encode($this->contentBackButtonOptions);
            $contentBackButton = "jQuery('<button>', {...$bbOptions, ...{
                text: 'Go Back',
                click: function(){
                    jQuery('#$this->contentContainerId').empty();
                    jQuery('#$id').fadeIn()
                }
            }})";
            $script = "
                $prepareContentContainer
                jQuery('#$id').on('beforeSubmit', function(event, xhr, settings){
                    let yiiform = jQuery(this);
                    jQuery('#$this->messageContainerId').empty();
                    const button = jQuery(event.target).find('button[type=\"submit\"]').first();
                    const buttonText = button.html();
                    button.html('$this->submittingText').attr('disabled', true);
                    jQuery.ajax({
                        type: yiiform.attr('method'),
                        url: yiiform.attr('action'),
                        data: yiiform.serializeArray()
                    })
                    .done(function(data){
                        button.html(buttonText).attr('disabled', false);
                        let nContainer = jQuery('<div>', {role: 'alert'});
                        if(data.success !== undefined){
                            if(data.content){
                                $hideForm
                                let contentContainer = jQuery('#$this->contentContainerId');
                                contentContainer.html(data.content);
                                contentContainer.append($contentBackButton);
                                return;
                            }
                            const message = data.message || (data.success? 'Success!':'Error');
                            if(data.success){
                                nContainer.addClass('alert alert-success');
                                setTimeout(()=>{
                                    nContainer.fadeOut();
                                },10000);
                                yiiform.trigger('success');
                            } else {
                                nContainer.addClass('alert alert-danger-x');
                            }
                            nContainer.html(message);
                            jQuery('#$this->messageContainerId').html(nContainer);
                            yiiform.trigger('reset');
                            if(data.redirectUrl){
                                setTimeout(()=>{
                                    window.location.href = data.redirectUrl;
                                }, $this->redirectTimeout);
                            }
                        } 
                        if(data.validation){
                            yiiform.yiiActiveForm('updateMessages', data.validation, true)
                        }
                    })
                    .fail(function(){
                        let nContainer = jQuery('<div>');
                        nContainer.addClass('alert alert-danger-x');
                        nContainer.html('Failed to load data');
                        jQuery('#$this->messageContainerId').html(nContainer);
                        button.html(buttonText).attr('disabled', false);
                    })
                    return false;
                })
            ";
            $this->getView()->registerJs($script);
        }
    }

    public static function getErrors(array $models) {
        $result = [];
        // The code below comes from ActiveForm::validate(). We do not need to validate the model
        // again, as it was already validated by save(). Just collect the messages.
        foreach($models as $model){
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }
        }
        return ['validation' => $result];
    }
}