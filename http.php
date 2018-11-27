<?php

class Http_response
{
    private $_status = null;
    private $_out   = null;
    
    function __construct($status, $out)
    {
        $this->_status = $status;
        $this->_out    = $out;
    }
    
    function getStatus()
    {
        return $this->_status;
    }
    
    function getOutput()
    {
        return $this->_out;
    }
}

class Http
{
    private $_host = null;
    private $_port = null;
    private $_protocol = null;

    const HTTP  = 'http';
    const HTTPS = 'https';
    
    /**
     * Factory of the class. Lazy connect
     *
     * @param string  $host
     * @param integer $port
     * @param string  $protocol
     * 
     * @return Http
     */
    static public function connect($host, $port = 80, $protocol = self::HTTP)
    {
        return new self($host, $port, $protocol);
    }

    
    protected function __construct($host, $port, $protocol)
    {
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_protocol = $protocol;
    }

    const POST   = 'POST';
    const GET    = 'GET';
    const DELETE = 'DELETE';
    const PUT = 'PUT';
    
    /**
     * Generic request
     *
     * @param string $verb
     * @param string $url
     * @param array  $params
     * 
     * @return string
     */
    public function exec($verb, $url, $params=array())
    {
        return $this->_exec($verb, $this->_url($url), $params);
    }

    private $_headers = array();
    /**
     * setHeaders
     *
     * @param array $headers
     * 
     * @return Http
     */
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }

    /**
     * Builds absolute url 
     *
     * @param unknown_type $url
     * 
     * @return unknown
     */
    private function _url($url=null)
    {
        if (substr($url, 0, 1) !== "/") {
            $url = "/" . $url;
        }
        return "{$this->_protocol}://{$this->_host}:{$this->_port}{$url}";
    }

    /**
     * Performing the real request
     *
     * @param string $type
     * @param string $url
     * @param array  $params
     * 
     * @return string
     */
    private function _exec($type, $url, $params = array())
    {
        $headers = $this->_headers;
        $s = curl_init();

        switch ($type) {
        case self::DELETE:
            curl_setopt($s, CURLOPT_URL, $url . '?' . http_build_query($params));
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, self::DELETE);
            break;
        case self::PUT:
            curl_setopt($s, CURLOPT_URL, $url);
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, self::PUT);
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $params);
            break;
        case self::POST:
            curl_setopt($s, CURLOPT_URL, $url);
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $params);
            break;
        case self::GET:
            curl_setopt($s, CURLOPT_URL, $url . '?' . http_build_query($params));
            break;
        }

        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
        
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        $out = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);
        
        if (!$status) {
            $out = "Error connecting to URL: " . $url;
        }
        
        return new Http_response($status, $out);
    }
}