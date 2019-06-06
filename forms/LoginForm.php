<?php


namespace app\forms;
use app\components\CoreProxy;
use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $phone;
    public $password;

    public function attributeLabels()  # к каждому атрибуту формы соответствует своё название в виде
    {
        return [
            'phone' => 'Номер телефона',
            'password' => 'Пароль'
        ];
    }

    public function rules()  # правила валидации
    {
        return [
                [ ['phone','password'], 'required'],
                [ ['phone', 'password'], 'trim'],
                ['phone', 'string', 'length' => 11],
                [ 'phone', 'match', 'pattern' => '/^7\\d{10}$/'],///'/^\+?7(\d{3})(\d{3})(\d{4})$/'],
                ['password', 'match', 'pattern' => '/^\S*(?=\S{9,20})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/'],
        ];
    }

    public function login()
    {
        $response = CoreProxy::auth($this->phone, $this->password);
        Yii::$app->session['token'] = substr(json_decode($response->content, true)['token'], 7);

    }
}