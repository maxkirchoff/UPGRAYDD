<?php
require('api.php');

class Request_Thingy
{
    protected $credentials;

    /**
     * Credential setter
     *
     * @param array $credentials
     */
    function set_credentials($credentials = array())
    {
        if (array_key_exists('username', $credentials))
        {
            $this->credentials['username'] = $credentials['username'];
        }

        if (array_key_exists('password', $credentials))
        {
            $this->credentials['password'] = $credentials['password'];
        }
    }

    /**
     * @param $email_address
     * @return bool|mixed|null
     */
    function create_account($email_address)
    {
        if (isset($email_address))
        {
            $api = new API_Thingy();
            $response = $api->post('createcredential', array("email" => $email_address));

            if (! array_key_exists("identifier", $response))
            {
                $response = false;
            }
        }

        return isset($response) ? $response : false;
    }

    /**
     * @throws Exception
     */
    function check_credentials()
    {
        $api = new API_Thingy($this->credentials);
        $cred_check = $api->get('validatecredential');

        if (in_array('Unauthorized', $cred_check))
        {
            throw new Exception("Invalid Credentials");
        }
    }

    /**
     * Grabs all the songs wrapped in their associated artist
     *
     * @return mixed|null
     */
    function get_artists()
    {
        $endpoint = 'artist';

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Grabs all the songs wrapped in their associated artists and albums and cover art
     *
     * @return mixed|null
     */
    function get_artists_with_everything()
    {
        $endpoint = 'artist?_recursive=true';

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Grabs all the songs wrapped in their album for a specific artist
     *
     * @param $artist_id
     * @return mixed|null
     */
    function get_artist_albums($artist_id = 0)
    {
        $endpoint = "artist/{$artist_id}/album?_relations=song,album_cover";

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Get all the SFXes
     *
     * @return mixed|null
     */
    function get_sfxes()
    {
        $endpoint = 'sfx';

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Get our current song queue
     *
     * @return mixed|null
     */
    function get_song_queue()
    {
        $endpoint = 'queue';

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Get available controls and volume
     *
     * @return mixed|null
     */
    function get_controls()
    {
        $endpoint = 'control';

        $api = new API_Thingy($this->credentials);
        return $api->get($endpoint);
    }

    /**
     * Set the volume
     *
     * @param int $volume
     * @return mixed|null
     */
    function set_volume($volume = 0)
    {
        $endpoint = 'control';

        $payload = array(
            "volume" => $volume
        );

        $api = new API_Thingy($this->credentials);
        return $api->patch($endpoint, $payload);
    }

    /**
     * Envoke controls
     *
     * @param string $action
     * @return mixed|null
     */
    function song_control($action = 'skip')
    {
        $endpoint = 'control';

        $payload = array(
            "song" => $action
        );
        $api = new API_Thingy($this->credentials);
        return $api->patch($endpoint, $payload);
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

        $payload = array(
            "file" => $file,
            "file_type" => $file_type,
        );

        $api = new API_Thingy($this->credentials);
        $api->post($endpoint, $payload);
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

        $payload = array(
            "file" => $file,
            "file_type" => $file_type,
        );

        $api = new API_Thingy($this->credentials);
        $api->post($endpoint, $payload);
    }
}