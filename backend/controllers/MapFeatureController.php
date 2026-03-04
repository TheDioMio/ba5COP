<?php
namespace backend\controllers;

use yii\web\Controller;
use yii\web\Response;

class MapFeatureController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        // para já devolve mock
        return [
            [
                'id' => 1,
                'geojson' => [
                    'type' => 'Feature',
                    'properties' => ['title' => 'Ponto 1'],
                    'geometry' => ['type' => 'Point', 'coordinates' => [-9.10, 38.65]],
                ],
            ],
        ];
    }
}