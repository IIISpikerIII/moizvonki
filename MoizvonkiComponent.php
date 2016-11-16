<?php

namespace iiispikeriii\moizvonki;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class MoizvonkiComponent extends Component
{
    protected $baseUrl = 'https://%s/api/v1';
    public $user_name = false;
    public $app_name = false;
    public $api_key = false;
    public $api_url = false;

    protected function request($data = [])
    {
        $this->cleaningParams($data);
        $dataBase = [
            'user_name' => $this->user_name,
            'api_key' => $this->api_key,
        ];

        $client = new Client([
            'baseUrl' => $this->getBaseUrl(),
        ]);
        $body = json_encode(array_merge($dataBase, $data));
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($body),
        ];

        $response =  $client->post('', $body, $headers)->send();

        if($response->statusCode != 200){
            $this->loging('Error: '.$response->getContent());
            return false;
        }

        return json_decode($response->getContent(), JSON_OBJECT_AS_ARRAY);
    }

    protected function getBaseUrl()
    {
        return sprintf($this->baseUrl, $this->api_url);
    }

    protected function cleaningParams(&$params)
    {
        $params = array_filter($params, function($val) { return $val !== false;});
        return true;
    }

    protected function loging($message)
    {
        Yii::warning($message, 'moizvonkiError');
    }

    /**
     * Получить список звонков из базы данных
     *
     * @param      $from_date указывает дату, с которой нужно получить звонки, UTC timestamp.
     * @param bool|mixed $to_date указывает дату, по которую нужно получить звонки, UTC timestamp.
     * @param bool|mixed $from_offset указывает начальный индекс в выборке. Т.к. размер списка звонков в ответе ограничен,
     * используйте данный параметр, чтобы последовательно получить весь список.
     * @param bool|mixed $max_results указывает начальный индекс в выборке. Т.к. размер списка звонков в ответе ограничен,
     * @param bool|mixed $supervised Если указано значение 1 - вернутся все звонки, доступные данному пользователю исходя из его роли.
     * Если указано значение 0 или атрибут на задан, то вернутся только звонки данного пользователя.
     *
     * @link http://www.moizvonki.ru/guide/api/
     * @return bool|mixed
     */
    public function getCallList($from_date, $to_date = false, $from_offset = false, $max_results = false, $supervised = false)
    {
        $params = [
            'action' => 'calls.list',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'from_offset' => $from_offset,
            'max_results' => $max_results,
            'supervised' => $supervised,
        ];

        return $this->request($params);
    }

    /**
     * @param $to номер телефона
     * @return bool|mixed
     */
    public function getMakeCall($to)
    {
        $params = [
            'action' => 'calls.make_call',
            'to' => $to,
        ];

        return $this->request($params);
    }

    /**
     *
     * @param bool $max_results  максимальное количество возвращаемых событий в одном ответе, допустимы значения 1 - 100.
     * @return bool|mixed
     */
    public function getCrmEvent($max_results = false)
    {
        $params = [
            'action' => 'calls.get_crm_event',
            'app_name' => $this->app_name,
            'max_results' => $max_results,
        ];

        return $this->request($params);
    }
}