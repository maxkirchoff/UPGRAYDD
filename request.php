<?php
class Request_Thingy
{
    /**
     * Grabs all the songs wrapped in their associated artist
     *
     * @return mixed|null
     */
    function get_artists_with_songs()
    {
        $endpoint = 'artist?_relations=song';

        $api = new API_Thingy();
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

        $api = new API_Thingy();
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

        $api = new API_Thingy();
        return $api->get($endpoint);
    }

    /**
     * Skip the currently playing song
     */
    function skip()
    {
        $this->control('skip');
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

        $payload = array(
            "control" => $action
        );
        $api = new API_Thingy();
        return $api->post($endpoint, $payload);
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

        $api = new API_Thingy();
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

        $api = new API_Thingy();
        $api->post($endpoint, $payload);
    }
}