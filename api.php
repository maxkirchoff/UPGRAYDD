<?php
class API_Thingy
{
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

    function get($endpoint = '')
    {
        return $this->api($endpoint, "GET");
    }

    function post($endpoint = '', $payload = array())
    {
        return $this->api($endpoint, "POST", $payload);
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
            curl_setopt($process, CURLOPT_USERPWD, $config['username'] . ":" . $config['password']);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

            if ($method == "POST" && (! empty($payload)))
            {
                curl_setopt($process, CURLOPT_POST, 1);

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