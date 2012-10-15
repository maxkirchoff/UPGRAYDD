<?php
// cURL API wrapper class
class API_Thingy
{
    // credentials we need
    protected $credentials;

    /**
     * @param array $credentials
     */
    function __construct($credentials = array())
    {
        if (! empty($credentials))
        {
            $this->credentials = $credentials;
        }
    }

    /**
     * Grab our config
     *
     * @return mixed
     */
    function get_config()
    {
        // CONF
        if (! file_exists('conf/conf.php'))
        {
            die("You need a conf.php, bro.");
        }
        // REQUIRE!
        $config = include 'conf/conf.php';

        // RETURN!
        return $config;
    }

    /**
     * @param string $endpoint
     * @return mixed|null
     */
    function get($endpoint = '')
    {
        return $this->api($endpoint, "GET");
    }

    /**
     * @param string $endpoint
     * @param array $payload
     * @return mixed|null
     */
    function post($endpoint = '', $payload = array())
    {
        return $this->api($endpoint, "POST", $payload);
    }

    /**
     * @param string $endpoint
     * @param array $payload
     * @return mixed|null
     */
    function patch($endpoint = '', $payload = array())
    {
        return $this->api($endpoint, "PATCH", $payload);
    }

    /**
     * Make an api request
     *
     * @param string $endpoint
     * @param string $method
     * @param array $payload
     * @return mixed|null
     */
    function api($endpoint = '', $method = 'GET', $payload = array())
    {
        // Grab our config
        $config = $this->get_config();

        // construct our api full url
        $api_url = "http://{$config['host']}/api/v1/{$endpoint}";

        try
        {
            $process = curl_init($api_url);
            curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            if (! empty($this->credentials))
            {
                curl_setopt($process, CURLOPT_USERPWD, $this->credentials['username'] . ":" . $this->credentials['password']);
            }
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

            switch($method)
            {
                case "POST":
                    curl_setopt($process, CURLOPT_POST, 1);
                    break;
                case "PATCH":
                    curl_setopt($process, CURLOPT_CUSTOMREQUEST, $method);
                    break;
                case "PUT":
                    curl_setopt($process, CURLOPT_CUSTOMREQUEST, $method);
                    break;
            }

            if (! empty($payload))
            {
                $encoded_payload = json_encode($payload);
                curl_setopt($process, CURLOPT_POSTFIELDS, $encoded_payload);
            }

            $response = curl_exec($process);
            curl_close($process);
        }
        catch (Exception $e)
        {
            return null;
        }

        return json_decode($response, true);
    }
}