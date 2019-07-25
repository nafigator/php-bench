<?php

namespace gateways\common;

/**
 * <h1>Класс управления заявками очереди</h1>
 * всё делает через консоль
 * Отправляет
 */
class Sender
{
    protected const CUT_HEADERS_CMD = 'sed \'0,/^\\r$/d\'';
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $gatewayName;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var array
     */
    protected $config = [];
    /**
     * @var string
     */
    private $command;

    public function __construct()
    {
        $this->config = [
            'defaultHost' => 'gateways.local',
            'remoteAddr' => '127.0.0.1',
        ];
    }

    /**
     * @param string $gatewayName
     * @return self;
     */
    public function setGatewayName($gatewayName)
    {
        $this->gatewayName = $gatewayName;
        return $this;
    }

    /**
     * @param array $params
     * @return Sender
     */
    public function setParams($params)
    {
        if (\is_array($params) && !empty($params)) {
            $this->params = $params;
        }
        return $this;
    }

    /** Прокидывает в отправляемый массив один параметр с указанным именем и значением
     * @param $key string
     * @param $value mixed
     * @return self
     */
    public function setOneParam(
        $key,
        $value
    ) {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return self
     */
    public function deleteParamByKey($key)
    {
        unset($this->params[$key]);
        return $this;
    }

    public function cleanParams()
    {
        $this->params = [];
        return $this;
    }

    /** Добавляет в отправляемый массив несколько<br>
     * <b>ПРИНИМАЮТСЯ ТОЛЬКО СТРОКОВЫЕ КЛЮЧИ</b>
     * @param $params array ['key' => 'value',..]
     * @return self
     */
    public function setSeveralParams($params)
    {
        foreach ($params as $key => $value) {
            if (\is_string($key) && (\is_string($value) || is_numeric($value))) {
                $this->setOneParam($key, $value);
            }
        }
        return $this;
    }

    /**Разбирает строку запроса к шлюзу из БД
     * @param $query string полный путь запроса из бд очереди
     * @return self
     */
    public function parseQuery($query): self
    {
        $this->params = [];
        $query = parse_url($query);
        $this->host = $query['host'];
        $this->setGatewayName(explode('/', $query['path'])[2]);
        parse_str($query['query'], $this->params);
        return $this;
    }

    /** Подготавливает запрос и сохраняет его в себя<br>
     * Для запуска запроса используй {@link QueueSender::exec() команду запуска}
     * @param string $path название страницы (нарпимер: config_json)
     * @param bool $hidden
     * @return self
     */
    public function prepareCommand(
        $path = 'send',
        $hidden = false
    ): self {
        $this->command = ($path === 'send' && $this->gatewayName === 'sovkom')
            ? $this->getFpmCommand()
            : "REMOTE_ADDR='{$this->config['remoteAddr']}' php /home/alex/dev/gateways/public/index.php --uri='/gateways/{$this->gatewayName}/$path' --request='" . http_build_query($this->params) . "' --host='sync.linkprofit.ru'" . ($hidden ? ' > /dev/null 2>&1 &' : '');
        // $this->command = "REMOTE_ADDR='{$this->config['remoteAddr']}' php " . __DIR__ . "/../../index.php --uri='/gateways/{$this->gatewayName}/$path' --request='" . http_build_query($this->params) . "' --host='sync.linkprofit.ru'" . ($hidden ? ' > /dev/null 2>&1 &' : '');
        return $this;
    }

    /**
     * Собираем команду для php-fpm
     *
     * @return string
     */
    protected function getFpmCommand(): string
    {
        $params = http_build_query($this->params);

        return 'SCRIPT_FILENAME=/home/alex/dev/gateways/public/index.php '
            . "QUERY_STRING='$params' "
            . 'REQUEST_METHOD=GET '
            . 'REMOTE_ADDR=127.0.0.1 '
            . 'SERVER_PORT=80 '
            . 'HTTP_HOST=gateways.local '
            . "REQUEST_URI=/gateways/$this->gatewayName/send "
            . 'cgi-fcgi -bind -connect /run/php/alex-fpm.sock | '
            . static::CUT_HEADERS_CMD;
    }

    /**
     * Получает указанный массив с указанного шлюза
     * @param $gatewayName
     * @param $arrayName
     * @return array|null
     */
    public function getArray(
        $gatewayName,
        $arrayName
    ) {
        return json_decode(
            $this->setGatewayName($gatewayName)
                ->cleanParams()
                ->prepareCommand($arrayName . '_json')
                ->exec(),
            true
        );
    }

    /**Подготавливает запрос и
     * @param string $gatewayName
     * @param array $params
     * @return string
     */
    public function sendToQueue(
        string $gatewayName = '',
        array $params = []
    ) {
        if (!empty($gatewayName)) {
            $this->setGatewayName($gatewayName);
        }
        if (!empty($params)) {
            $this->setParams($params);
        }

        unset($this->params['queue_triggered']);

        return $this->prepareCommand()->exec();
    }

    /**
     * @param string $gatewayName
     * @param array $params
     * @param bool $hidden
     * @return string
     */
    public function sendSimple(
        string $gatewayName = '',
        array $params = [],
        bool $hidden = false
    ) {
        if (!empty($gatewayName)) {
            $this->setGatewayName($gatewayName);
        }
        if (!empty($params)) {
            $this->setParams($params);
        }

        return $this->prepareCommand('send', $hidden)->exec();
    }

    /**Подготавливает запрос и отправляет заявку из очереди
     * @param array $params
     * @return string
     */
    public function sendFromQueue(
        array $params = []
    ) {
        return
            $this->setParams($params)
                ->setOneParam('queue_triggered', 1)
                ->prepareCommand()
                ->exec();
    }

    /**Выполняет подготовленый запрос, возвращает ответ запроса
     * @return string
     */
    protected function exec()
    {
        return exec($this->command);
    }

    /**Выполняет подготовленый запрос, возвращает ответ запроса
     * @return string
     */
    protected function execHidden()
    {
        return exec($this->command . ' &');
    }
}
