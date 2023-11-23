<?php

defined('ABSPATH') or exit;

/**
 * Build payment request
 */
class Wc_Paysera_Request
{
    /**
     * Default language for Paysera checkout page
     */
    const DEFAULT_LANG = 'ENG';

    /**
     * Code used for empty fields
     */
    const EMPTY_CODE     = '';

    /**
     * Default project id
     */
    const DEFAULT_PROJECT_ID = 0;

    /**
     * Default bool answer
     */
    const DEFAULT_ANSWER = false;

    /**
     * Default language
     */
    const DEFAULT_LOCAL = 'en';

    /**
     * @var integer
     */
    protected $projectID;

    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $returnUrl;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @var boolean
     */
    protected $test;

    /**
     * @var string
     */
    protected $locale;

    /**
     * Available languages for Paysera checkout page
     */
    protected $translationLang;

    /**
     * @var boolean
     */
    protected $buyerConsent;

    /**
     * @return self
     */
    static public function create()
    {
        return new self();
    }

    /**
     * Wc_Paysera_Request constructor.
     */
    public function __construct() {
        $this->projectID   = $this::DEFAULT_PROJECT_ID;
        $this->signature   = $this::EMPTY_CODE;
        $this->returnUrl   = $this::EMPTY_CODE;
        $this->callbackUrl = $this::EMPTY_CODE;
        $this->test        = $this::DEFAULT_ANSWER;
        $this->locale      = $this::DEFAULT_LOCAL;
    }

    /**
     * Create request url
     *
     * @param array $parameters
     *
     * @return string
     */
    public function buildUrl($parameters)
    {
        if ($parameters['prebuild']) {
            $parameters = $this->buildParameters($parameters);
        }

        $request = WebToPay::buildRequest($parameters);
        $url = WebToPay::PAY_URL . '?' . http_build_query($request);

        return preg_replace('/[\r\n]+/is', '', $url);
    }

    /**
     * Get WooCommerce parameters for request
     *
     * @param object $order
     * @param string $payment
     *
     * @return array
     */
    public function getWooParameters($order, $payment)
    {
        if ($this->getTranslationLang()[$this->getLocale()]) {
            $lang = $this->getTranslationLang()[$this->getLocale()];
        } else {
            $lang = $this::DEFAULT_LANG;
        }

        return array (
            'prebuild'      => true,
            'order'         => $order->get_id(),
            'amount'        => intval(number_format($order->get_total(),2,'','')),
            'currency'      => $order->get_currency(),
            'country'       => $order->get_billing_country(),
            'cancel'        => htmlspecialchars_decode($order->get_cancel_order_url()),
            'payment'       => $payment,
            'firstname'     => $order->get_billing_first_name(),
            'lastname'      => $order->get_billing_last_name(),
            'email'         => $order->get_billing_email(),
            'street'        => $order->get_billing_address_1(),
            'city'          => $order->get_billing_city(),
            'state'         => $order->get_billing_state(),
            'zip'           => $order->get_billing_postcode(),
            'countrycode'   => $order->get_billing_country(),
            'lang'          => $lang,
        );
    }

    /**
     * Build parameters array, which meets Paysera requirements
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function buildParameters($parameters)
    {
        return array(
            'projectid'     => $this->limitLenght($this->getProjectID(), 11),
            'sign_password' => $this->limitLenght($this->getSignature()),
            'orderid'       => $this->limitLenght($parameters['order'], 40),
            'amount'        => $this->limitLenght($parameters['amount'], 11),
            'currency'      => $this->limitLenght($parameters['currency'], 3),
            'country'       => $this->limitLenght($parameters['country'], 2),
            'accepturl'     => $this->limitLenght($this->getReturnUrl()),
            'cancelurl'     => $this->limitLenght($parameters['cancel']),
            'callbackurl'   => $this->limitLenght($this->getCallbackUrl()),
            'p_firstname'   => $this->limitLenght($parameters['firstname']),
            'p_lastname'    => $this->limitLenght($parameters['lastname']),
            'p_email'       => $this->limitLenght($parameters['email']),
            'p_street'      => $this->limitLenght($parameters['street']),
            'p_countrycode' => $this->limitLenght($parameters['country'], 2),
            'p_city'        => $this->limitLenght($parameters['city']),
            'p_state'       => $this->limitLenght($parameters['state'], 20),
            'payment'       => $this->limitLenght($parameters['payment'], 20),
            'p_zip'         => $this->limitLenght($parameters['zip'], 20),
            'lang'          => $this->limitLenght($parameters['lang'], 3),
            'test'          => $this->limitLenght((int)$this->getTest(), 1),
            'buyer_consent' => $this->limitLenght((int)$this->getBuyerConsent(), 1),
        );
    }

    /**
     * Limit lenght of the string
     *
     * @param  string  $string
     * @param  integer $limit
     *
     * @return string
     */
    protected function limitLenght($string, $limit = 255) {
        if (strlen($string) > $limit) {
            $string = substr($string, 0, $limit);
        }

        return $string;
    }

    /**
     * @return integer
     */
    public function getProjectID()
    {
        return $this->projectID;
    }

    /**
     * @param integer $projectID
     *
     * @return self
     */
    public function setProjectID($projectID)
    {
        $this->projectID = $projectID;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     *
     * @return self
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     *
     * @return self
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param string $callbackUrl
     *
     * @return self
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }


    /**
     * @return boolean
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param boolean $test
     *
     * @return self
     */
    public function setTest($test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return array
     */
    public function getTranslationLang()
    {
        return $this->translationLang;
    }

    /**
     * @param array $translationLang
     *
     * @return self
     */
    public function setTranslationLang($translationLang)
    {
        $this->translationLang = $translationLang;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getBuyerConsent()
    {
        return $this->buyerConsent;
    }

    /**
     * @param boolean $buyerConsent
     *
     * @return self
     */
    public function setBuyerConsent($buyerConsent)
    {
        $this->buyerConsent = $buyerConsent;
        return $this;
    }
}
