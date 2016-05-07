<?php

namespace GetResponse;

/**
 * GetResponse API v3 client library
 *
 * @see http://apidocs.getresponse.com/en/v3/resources
 */
class GetResponse
{
    /*
     * Methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';

    private $apiKey;
    private $endpoint = 'https://api.getresponse.com/v3';
    private $timeout = 8;

    /**
     * X-Domain header value if empty header will be not provided
     *
     * @var string|null
     */
    private $domain = null;

    /**
     * X-APP-ID header value if empty header will be not provided
     *
     * @var string|null
     */
    private $appId = null;

    /**
     * Set api key and other options
     *
     * @param string $apiKey
     * @param array $options
     */
    public function __construct($apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;

        if (isset($options['endpoint']) && $options['endpoint']) {
            $this->endpoint = $options['endpoint'];
        }

        if (isset($options['domain']) && $options['domain']) {
            $this->domain = $options['domain'];
        }

        if (isset($options['app_id']) && $options['app_id']) {
            $this->appId = $options['app_id'];
        }

        if (isset($options['timeout']) && $options['timeout']) {
            $this->timeout = $options['timeout'];
        }
    }

    /**
     * Get account details
     *
     * @return array
     */
    public function getAccounts()
    {
        return $this->call('accounts');
    }

    /**
     * Return all campaigns
     *
     * @param array $params
     * @return array
     */
    public function getCampaigns(array $params = array())
    {
        return $this->call('campaigns', self::METHOD_GET, $params);
    }

    /**
     * Get single campaign
     *
     * @param int $campaignId - retrieved using API
     * @return array
     */
    public function getCampaign($campaignId)
    {
        return $this->call('campaigns/' . $campaignId);
    }

    /**
     * Add campaign
     *
     * @param array $params
     * @return array
     */
    public function createCampaign(array $params)
    {
        return $this->call('campaigns', self::METHOD_POST, $params);
    }

    /**
     * List all RSS newsletters
     *
     * @param array $params
     * @return array
     */
    public function getRssNewsletters(array $params)
    {
        $this->call('rss-newsletters', self::METHOD_GET, $params);
    }

    /**
     * Send one newsletter
     *
     * @param array $params
     * @return array
     */
    public function sendNewsletter(array $params)
    {
        return $this->call('newsletters', self::METHOD_POST, $params);
    }

    /**
     * Add draft of a newsletter
     *
     * @param array $params
     * @return array
     */
    public function sendDraftNewsletter(array $params)
    {
        return $this->call('newsletters/send-draft', self::METHOD_POST, $params);
    }

    /**
     * Add single contact into your campaign
     *
     * @param array $params
     * @return array
     */
    public function addContact(array $params)
    {
        return $this->call('contacts', self::METHOD_POST, $params);
    }

    /**
     * Retrieving contact by id
     *
     * @param string $contactId - contact id obtained by API
     * @return array
     */
    public function getContact($contactId)
    {
        return $this->call('contacts/' . $contactId);
    }

    /**
     * Search contacts
     *
     * @param $params
     * @return array
     */
    public function searchContacts(array $params = array())
    {
        return $this->call('search-contacts', self::METHOD_GET, $params);
    }

    /**
     * Retrieve segment
     *
     * @param int $contactId
     * @return array
     */
    public function getContactsSearch($contactId)
    {
        return $this->call('search-contacts/' . $contactId);
    }

    /**
     * Add contacts search
     *
     * @param $params
     * @return array
     */
    public function addContactsSearch(array $params)
    {
        return $this->call('search-contacts/', self::METHOD_POST, $params);
    }

    /**
     * Delete contacts search
     *
     * @param int $contactId
     * @return array
     */
    public function deleteContactsSearch($contactId)
    {
        return $this->call('search-contacts/' . $contactId, self::METHOD_DELETE);
    }

    /**
     * Get contact activities
     *
     * @param int $contactId
     * @return array
     */
    public function getContactActivities($contactId)
    {
        return $this->call('contacts/' . $contactId . '/activities');
    }

    /**
     * Retrieving contact by params
     *
     * @param array $params
     * @return array
     */
    public function getContacts(array $params = array())
    {
        return $this->call('contacts', self::METHOD_GET, $params);
    }

    /**
     * Updating any fields of your subscriber (without email of course)
     *
     * @param int $contactId
     * @param array $params
     * @return array
     */
    public function updateContact($contactId, array $params = array())
    {
        return $this->call('contacts/' . $contactId, self::METHOD_POST, $params);
    }

    /**
     * Drop single contact by ID
     *
     * @param string $contactId - obtained by API
     * @return array
     */
    public function deleteContact($contactId)
    {
        return $this->call('contacts/' . $contactId, self::METHOD_DELETE);
    }

    /**
     * Retrieve account custom fields
     *
     * @param array $params
     * @return array
     */
    public function getCustomFields(array $params = array())
    {
        return $this->call('custom-fields', self::METHOD_GET, $params);
    }

    /**
     * Add custom field
     *
     * @param $params
     * @return array
     */
    public function setCustomField(array $params)
    {
        return $this->call('custom-fields', self::METHOD_POST, $params);
    }

    /**
     * Retrieve single custom field
     *
     * @param int $customFieldId - obtained by API
     * @return array
     */
    public function getCustomField($customFieldId)
    {
        return $this->call('custom-fields/' . $customFieldId);
    }

    /**
     * Retrieving billing information
     *
     * @return array
     */
    public function getBillingInfo()
    {
        return $this->call('accounts/billing');
    }

    /**
     * Get single web form
     *
     * @param int $webFormId
     * @return array
     */
    public function getWebForm($webFormId)
    {
        return $this->call('webforms/' . $webFormId);
    }

    /**
     * Retrieve all webforms
     *
     * @param array $params
     * @return array
     */
    public function getWebForms(array $params = array())
    {
        return $this->call('webforms', self::METHOD_GET, $params);
    }

    /**
     * Get single form
     *
     * @param int $formId
     * @return array
     */
    public function getForm($formId)
    {
        return $this->call('forms/' . $formId);
    }

    /**
     * Retrieve all forms
     *
     * @param array $params
     * @return array
     */
    public function getForms(array $params = array())
    {
        return $this->call('forms', self::METHOD_GET, $params);
    }

    /**
     * Curl run request
     *
     * @param string $apiMethod
     * @param string $httpMethod
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function call($apiMethod = null, $httpMethod = self::METHOD_GET, array $params = array())
    {
        if (empty($apiMethod)) {
            return array(
                'httpStatus' => '400',
                'code' => '1010',
                'codeDescription' => 'Error in external resources',
                'message' => 'Invalid api method'
            );
        }

        $url = $this->endpoint . '/' . $apiMethod;

        if ($httpMethod == self::METHOD_GET && $params) {
            $url .= '?'. urldecode(http_build_query($params));
        }

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => 'PHP GetResponse client',
            CURLOPT_HTTPHEADER => array(
                'X-Auth-Token: api-key ' . $this->apiKey,
                'Content-Type: application/json'
            )
        );

        if (!empty($this->domain)) {
            $options[CURLOPT_HTTPHEADER][] = 'X-Domain: ' . $this->domain;
        }

        if (!empty($this->appId)) {
            $options[CURLOPT_HTTPHEADER][] = 'X-APP-ID: ' . $this->appId;
        }

        if ($httpMethod == self::METHOD_POST) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        } else if ($httpMethod == self::METHOD_DELETE) {
            $options[CURLOPT_CUSTOMREQUEST] = $httpMethod;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response = json_decode(curl_exec($curl), true);

        $this->http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;
    }
}
