<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use yii\db\Query;


class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action){
        if($action->id == 'index'){
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionIndex($table_name)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $get_tables = Yii::$app->db->createCommand('SHOW TABLES')->queryAll();
            foreach ($get_tables as $table){
                $tables[] = $table['Tables_in_'.DB_NEME];
            }
            $rawPost = file_get_contents('php://input');
            if(!in_array($table_name,$tables) OR !$rawPost){
                Yii::$app->response->setStatusCode(400);
                $data_response = $this->getArrErrorResponse('not_found');
            }else{
                $data_request = json_decode($rawPost);
                $where = '';
                $params_to_query = [];
                $this->getParams($data_request,$where,$params_to_query);
                $data_response = (new Query())
                    ->select('*')
                    ->from($table_name)
                    ->where($where, $params_to_query)
                    ->all();
                if($data_response){
                    Yii::$app->response->setStatusCode(200);
                }else{
                    Yii::$app->response->setStatusCode(400);
                    $data_response = $this->getArrErrorResponse('not_found');
                }
            }
        }else{
            Yii::$app->response->setStatusCode(400);
            $data_response = $this->getArrErrorResponse('internal_error');
        }

        $data_response = json_encode($data_response);

        return $this->render('index',compact('data_response'));
    }

    private function getParams($params,&$where,&$params_to_query){
        foreach($params as $key=>$val){
            $where .= $key.'=:'.$key.' AND ';
            $params_to_query[':'.$key] = $val;
        }
        $where = substr($where,0,-5);
    }

    private function getArrErrorResponse($nameResponse){
        $not_found = [
            'code'=>'2',
            'message'=>'Not Found',
            'details'=>'Entity (or table) not found'
        ];
        $path_not_exist = [
            'code'=>'0',
            'message'=>'Path you requested not exist',
            'details'=>''
        ];
        $internal_error = [
            'code'=>'-1',
            'message'=>'Internal error',
            'details'=>'Please try again later'
        ];
        return $$nameResponse;
    }

}
