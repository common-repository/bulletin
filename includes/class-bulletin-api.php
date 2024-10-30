<?php

/**
 * Define the API functionality.
 *
 * @link       https://eresults.nl
 * @since      1.0.0
 *
 * @package    Bulletin
 * @subpackage Bulletin/includes
 */

/**
 * Define the API functionality.
 *
 * This class defines all code necessary to communicate with the Bulletin API.
 *
 * @since      1.0.0
 * @package    Bulletin
 * @subpackage Bulletin/includes
 * @author     Martijn Gastkemper <martijngastkemper@eresults.nl>
 */
class Bulletin_API {

    /**
     * The Curl handle.
     *
     * @since    1.0.0
     * @access   private
     * @var      curl handle
     */
    private $ch;

    /**
     * The Bulletin API endpoint.
     *
     * @since    1.0.0
     * @access   private
     * @var      string
     */
    private $apiEndpoint = 'https://www.bulletin.email/api/';

    /**
     * Create a curl handle with authentication details.
     *
     * @since    1.0.0
     * @param    $apiToken  The Bulletin API token
     */
    public function connect($apiToken) {
        $this->ch = curl_init();

        curl_setopt_array($this->ch, [
            CURLOPT_HTTPHEADER => [
                "X-API-Token: $apiToken"
            ],
            CURLOPT_RETURNTRANSFER => true
        ]);
    }

    /**
     * Check the response and return when it's valid. When it's not throw an exception with the error from the Bulletin API.
     *
     * @since    1.0.0
     * @param    string $response
     * @return   array
     * @throws   Bulletin_Exception
     */
    private function handleResponse($response) {
        $responseCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        switch($responseCode) {
            case 200:
                return json_decode($response, true);

            default:
                $data = @json_decode($response, true);
                if(is_array($data)) {
                    $message = var_export($data['error'], true);
                } else {
                    $message = 'Communication with Bulletin failed';
                }
                throw new Bulletin_Exception($message, $responseCode);
        }
    }

    /**
     * Get a list of Bulletin Lists.
     *
     * @since    1.0.0
     * @return   array
     * @throws   Bulletin_Exception
     */
    public function getLists() {

        curl_setopt($this->ch, CURLOPT_URL, $this->apiEndpoint . 'lists');

        $response = curl_exec($this->ch);

        return $this->handleResponse($response);
    }

    /**
     * Subscribe someone to a specific list.
     *
     * @since    1.0.0
     * @param    $listId The list ID to which this person will be subscribed.
     * @param    $data
     * @return   array
     * @throws   Bulletin_Exception
     */
    public function subscribe($listId, $data) {
        curl_setopt_array($this->ch, [
            CURLOPT_URL => $this->apiEndpoint . 'lists/' . $listId . '/subscribers',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data
        ]);

        $response = curl_exec($this->ch);

        return $this->handleResponse($response);
    }

    /**
     * Close the connection with the Bulletin API.
     *
     * @since    1.0.0
     */
    public function __destruct() {
        curl_close($this->ch);
    }

}