<?php
Yii::$app->response->setStatusCode(404);

echo json_encode([
    'code'=>'0',
    'message'=>'Path you requested not exist',
    'details'=>''
]);