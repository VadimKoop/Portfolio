<?php
defined('ABSPATH') or exit;

if(!class_exists('Wc_Paysera_Html_Form')) {
    require_once 'class-wc-paysera-html-form.php';
}

/**
 * Build Paysera payment methods list
 */
class Wc_Paysera_Payment_Methods
{
    /**
     * Code used for empty fields
     */
    const EMPTY_CODE = '';

    /**
     * HTML NewLine break
     */
    const LINE_BREAK = '<div style="clear:both"><br /></div>';

    /**
     * Min. number of countries in list
     */
    const COUNTRY_SELECT_MIN = 1;

    /**
     * Default language if not in the list
     */
    const DEFAULT_LANG = 'en';

    /**
     * Default bool answer
     */
    const DEFAULT_ANSWER = false;

    /**
     * Default bool answer
     */
    const DEFAULT_ANSWER_TRUE = true;

    /**
     * Default total
     */
    const DEFAULT_TOTAL = 0;

    /**
     * Default currency
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Default project id
     */
    const DEFAULT_PROJECT_ID = 0;

    /**
     * @var int
     */
    protected $projectID;

    /**
     * @var string
     */
    protected $billingCountry;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var boolean
     */
    protected $displayList;

    /**
     * @var array
     */
    protected $countriesSelected;

    /**
     * @var boolean
     */
    protected $gridView;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var double
     */
    protected $cartTotal;

    /**
     * @var string
     */
    protected $cartCurrency;

    /**
     * Available languages of payments
     */
    protected $availableLang;

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
     * Wc_Paysera_Payment_Methods constructor
     */
    public function __construct() {
        $this->projectID         = $this::DEFAULT_PROJECT_ID;
        $this->lang              = $this::DEFAULT_LANG;
        $this->billingCountry    = $this::EMPTY_CODE;
        $this->displayList       = $this::DEFAULT_ANSWER;
        $this->countriesSelected = $this::EMPTY_CODE;
        $this->gridView          = $this::DEFAULT_ANSWER;
        $this->description       = $this::DEFAULT_ANSWER;
        $this->cartTotal         = $this::DEFAULT_TOTAL;
        $this->cartCurrency      = $this::DEFAULT_CURRENCY;
        $this->buyerConsent      = $this::DEFAULT_ANSWER_TRUE;
    }

    /**
     * @param boolean [Optional] $print
     *
     * @return boolean|string
     */
    public function build($print = true)
    {
        $buildHtml = Wc_Paysera_Html_Form::create();

        if ($this->isDisplayList()) {
            $payseraCountries = $this->getPayseraCountries(
                $this->getProjectID(),
                $this->getCartTotal(),
                $this->getCartCurrency(),
                $this->listLang()
            );

            $countries = $this->getCountriesList($payseraCountries);

            if (count($countries) > $this::COUNTRY_SELECT_MIN) {
                $paymentsHtml = $buildHtml->buildCountriesList(
                    $countries,
                    $this->getBillingCountry()
                );
                $paymentsHtml .= $this::LINE_BREAK;
            } else {
                $paymentsHtml = $this::EMPTY_CODE;
            }

            $paymentsHtml .= $buildHtml->buildPaymentsList(
                $countries,
                $this->isGridView(),
                $this->getBillingCountry()
            );
            $paymentsHtml .= $this::LINE_BREAK;
        } else {
            $paymentsHtml = $this->getDescription();
        }

        if ($this->isBuyerConsent()) {
            $paymentsHtml .= $this::LINE_BREAK;
            $paymentsHtml .= sprintf(
                __('Please be informed that the account information and payment initiation services will be provided to you by Paysera in accordance with these %s. By proceeding with this payment, you agree to receive this service and the service terms and conditions.', 'woo-payment-gateway-paysera'),
                '<a href="' . __('https://www.paysera.lt/v2/en-LT/legal/pis-rules-2020', 'woo-payment-gateway-paysera') . '
                        "> ' . __('rules', 'woo-payment-gateway-paysera')  .'</a>'
            );
        }

        if ($print) {
            print_r($paymentsHtml);
            return $print;
        } else {
            return $paymentsHtml;
        }
    }

    /**
     * @param integer $project
     * @param string  $currency
     * @param string  $lang
     *
     * @return WebToPay_PaymentMethodCountry[]
     */
    protected function getPayseraCountries($project, $amount, $currency, $lang)
    {
        try {
            $countries = WebToPay::getPaymentMethodList($project, $currency)
                ->filterForAmount($amount, $currency)
                ->setDefaultLanguage($lang)
                ->getCountries()
            ;
        } catch (WebToPayException $exception) {
            error_log('[Paysera] Got an exception: ' . $exception);

            return [];
        }

        return $countries;
    }

    /**
     * @param array $countries
     *
     * @return array
     */
    protected function getCountriesList($countries)
    {
        $countriesList = [];
        $showSelectedCountries = is_array($this->getCountriesSelected());
        $selectedCountriesCodes = $this->getCountriesSelected();

        foreach ($countries as $country) {
            $checkForCountry = true;
            if ($showSelectedCountries) {
                $checkForCountry = in_array($country->getCode(), $selectedCountriesCodes);
            }

            if ($checkForCountry) {
                $countriesList[] = [
                    'code'   => $country->getCode(),
                    'title'  => $country->getTitle(),
                    'groups' => $country->getGroups()
                ];
            }
        }

        return $countriesList;
    }

    /**
     * @return string
     */
    protected function listLang()
    {
        if (in_array($this->getLang(), $this->getAvailableLang())) {
            $listLang = $this->getLang();
        } else {
            $listLang = $this::DEFAULT_LANG;
        }

        return $listLang;
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->billingCountry;
    }


    /**
     * @param string $billingCountry
     *
     * @return self
     */
    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = $billingCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     *
     * @return self
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisplayList()
    {
        return $this->displayList;
    }

    /**
     * @param boolean $displayList
     *
     * @return self
     */
    public function setDisplayList($displayList)
    {
        $this->displayList = $displayList;
        return $this;
    }

    /**
     * @return array
     */
    public function getCountriesSelected()
    {
        return $this->countriesSelected;
    }

    /**
     * @param array $countriesSelected
     *
     * @return self
     */
    public function setCountriesSelected($countriesSelected)
    {
        $this->countriesSelected = $countriesSelected;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isGridView()
    {
        return $this->gridView;
    }

    /**
     * @param boolean $gridView
     *
     * @return self
     */
    public function setGridView($gridView)
    {
        $this->gridView = $gridView;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return double
     */
    public function getCartTotal()
    {
        return $this->cartTotal;
    }

    /**
     * @param double $cartTotal
     *
     * @return self
     */
    public function setCartTotal($cartTotal)
    {
        $this->cartTotal = $cartTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getCartCurrency()
    {
        return $this->cartCurrency;
    }

    /**
     * @param string $cartCurrency
     *
     * @return self
     */
    public function setCartCurrency($cartCurrency)
    {
        $this->cartCurrency = $cartCurrency;
        return $this;
    }

    /**
     * @return int
     */
    public function getProjectID()
    {
        return $this->projectID;
    }

    /**
     * @param int $projectID
     *
     * @return self
     */
    public function setProjectID($projectID)
    {
        $this->projectID = $projectID;
        return $this;
    }

    /**
     * @return array
     */
    public function getAvailableLang()
    {
        return $this->availableLang;
    }

    /**
     * @param array $availableLang
     *
     * @return self
     */
    public function setAvailableLang($availableLang)
    {
        $this->availableLang = $availableLang;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isBuyerConsent()
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
