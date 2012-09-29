<?php

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
 * Grabs all the songs wrapped in their associated artist
 *
 * @return mixed|null
 */
function get_artists_with_songs()
{
    $endpoint = 'artist?_relations=song';

    return api_request($endpoint, "GET");
}

/**
 * Get all the SFXes
 *
 * @return mixed|null
 */
function get_sfxes()
{
    $endpoint = 'sfx';

    return api_request($endpoint, "GET");
}

/**
 * Get our current song queue
 *
 * @return mixed|null
 */
function get_song_queue()
{
    $endpoint = 'queue';

    return api_request($endpoint, "GET");
}

/**
 * Skip the currently playing song
 */
function skip()
{
    control('skip');
}

/**
 * Envoke controls
 *
 * @param string $action
 * @return mixed|null
 */
function control($action = 'skip')
{
    $endpoint = 'queue';

    $payload = json_encode(array(
        "control" => $action
    ));

    return api_request($endpoint, "POST", $payload);
}

/**
 * Play the requrested file immediately
 *
 * @param string $file
 * @param string $file_type
 */
function play($file = '', $file_type = 'sfx')
{
    $endpoint = 'play';

    $payload = json_encode(array(
        "file" => $file,
        "file_type" => $file_type,
    ));

    api_request($endpoint, "POST", $payload);
}

/**
 * Put the file in queue to be played
 *
 * @param string $file
 * @param string $file_type
 */
function queue($file = '', $file_type = 'song')
{
    $endpoint = 'queue';

    $payload = json_encode(array(
        "file" => $file,
        "file_type" => $file_type,
    ));

    api_request($endpoint, "POST", $payload);
}

/**
 * Make an api request
 *
 * @param string $endpoint
 * @param string $method
 * @param array $payload
 * @return mixed|null
 */
function api_request($endpoint = '', $method = 'GET', $payload = array())
{
    // Grab our config
    $config = get_config();

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
            curl_setopt($process, CURLOPT_POSTFIELDS, $payload);
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