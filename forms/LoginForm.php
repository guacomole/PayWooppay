<?php


namespace app\forms;
use app\components\CoreProxy;
use yii\base\Model;
use Yii;
use yii\web\UnprocessableEntityHttpException;

class LoginForm extends Model
{
    public $login;
    public $password;

    public function attributeLabels()
    {
        return [
            'login' => 'Номер телефона',
            'password' => 'Пароль'
        ];
    }

    public function rules()
    {
        return [
                [ ['login','password'], 'required'],
                [ ['login', 'password'], 'trim'],
                [ ['login', 'password'], 'login'],
                //['password', 'length', ['length' => 20]],
                [ 'login', 'match', 'pattern' => '/^((8|7|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
                ['password', 'match', 'pattern' => '/^\S*(?=\S{9,20})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/'],
        ];
    }

    public function login()
    {
        $this->login = substr(preg_replace("/[^0-9,.]/", "", $this->login), 0);
        try {
            $response = CoreProxy::auth($this->login, $this->password);
            Yii::$app->session['phone'] = $this->login;
            Yii::$app->session['token'] = substr(json_decode($response->content, true)['token'], 7);
        }
        catch (UnprocessableEntityHttpException $e) {
            $this->addErrors(['login' => 'Неверный номер телефона или пароль', 'password' => 'Неверный номер телефона или пароль']);
        }
    }
}