<?php

namespace pso\yii2\widgets\googleanalytics;

use Yii;
use yii\base\Widget;

class EmbedWidget extends Widget
{
    public $service_account = "@app/config/service-account.json";
    public $client;

    public function init()
    {
        parent::init();
        $this->client = $this->prepareClient();
    }

    public function prepareClient(){
        $scope = 'https://www.googleapis.com/auth/analytics.readonly';
        $client = new \Google_Client();
        $client->setScopes([$scope]);
        $client->setAuthConfigFile(Yii::getAlias($this->service_account));
        $client->useApplicationDefaultCredentials();
        return $client;
    }


    public function getAccessToken(){
        $this->client->fetchAccessTokenWithAssertion();
        return $this->client->getAccessToken();
    }

    public function run()
    {
        return $this->render('page',[
            'embed' => $this
        ]);
    }
}