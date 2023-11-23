<?php

defined('ABSPATH') or exit;

/**
 * Paysera Gateway Admin settings
 */
class Wc_Paysera_Settings extends WC_Payment_Gateway
{
    /**
     * Default project id
     */
    const DEFAULT_PROJECT_ID = 0;

    /**
     * Default currency
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Default language
     */
    const DEFAULT_LANG = 'en';

    /**
     * @var integer
     */
    protected $projectID;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var array
     */
    protected $formFields;


    /**
     * @return self
     */
    static public function create()
    {
        return new self();
    }

    /**
     * Wc_Paysera_Settings constructor.
     */
    public function __construct()
    {
        $this->projectID  = $this::DEFAULT_PROJECT_ID;
        $this->currency   = $this::DEFAULT_CURRENCY;
        $this->lang       = $this::DEFAULT_LANG;
        $this->formFields = array(
            'enabled' => array(
                'title'       => __('Enable Paysera', 'woo-payment-gateway-paysera'),
                'label'       => __('Enable Paysera payment', 'woo-payment-gateway-paysera'),
                'type'        => 'checkbox',
                'description' => '',
                'default'     => 'no'
            ),
            'projectid' => array(
                'title'       => __('Project ID', 'woo-payment-gateway-paysera'),
                'type'        => 'number',
                'description' => __('Project id', 'woo-payment-gateway-paysera'),
                'default'     => __('', 'woo-payment-gateway-paysera')
            ),
            'password' => array(
                'title'       => __('Sign', 'woo-payment-gateway-paysera'),
                'type'        => 'text',
                'description' => __('Paysera sign password', 'woo-payment-gateway-paysera'),
                'default'     => __('', 'woo-payment-gateway-paysera')
            ),
            'test' => array(
                'title'       => __('Test', 'woo-payment-gateway-paysera'),
                'type'        => 'checkbox',
                'label'       => __('Enable test mode', 'woo-payment-gateway-paysera'),
                'default'     => 'yes',
                'description' => __('Enable this to accept test payments', 'woo-payment-gateway-paysera'),
            ),
            'title' => array(
                'title'       => __('Title', 'woo-payment-gateway-paysera'),
                'type'        => 'text',
                'description' => __(
                    'Payment method title that the customer will see on your website.',
                    'woo-payment-gateway-paysera'
                ),
                'default'     => __('All popular payment methods', 'woo-payment-gateway-paysera')
            ),
            'description' => array(
                'title'       => __('Description', 'woo-payment-gateway-paysera'),
                'type'        => 'textarea',
                'css'               => 'width: 400px;',
                'description' => __(
                    'This controls the description which the user sees during checkout.',
                    'woo-payment-gateway-paysera'
                ),
                'default'     => __('Choose a payment method on the Paysera page', 'woo-payment-gateway-paysera')
            ),
            'paymentType' => array(
                'title'       => __('List of payments', 'woo-payment-gateway-paysera'),
                'type'        => 'checkbox',
                'label'       => __('Display payment methods list', 'woo-payment-gateway-paysera'),
                'default'     => 'no',
                'description' => __(
                    'Enable this to display payment methods list at checkout page',
                    'woo-payment-gateway-paysera'
                ),
            ),
            'countriesSelected' => array(
                'title'             => __('Specific countries', 'woo-payment-gateway-paysera'),
                'type'              => 'multiselect',
                'class'	            => 'wc-enhanced-select',
                'css'               => 'width: 400px;',
                'default'           => '',
                'description'       => __('Select which country payment methods to display (empty means all)', 'woo-payment-gateway-paysera'),
                'options'           => array(),
                'custom_attributes' => array(
                    'data-placeholder' => __('All countries', 'woo-payment-gateway-paysera'),
                ),
            ),
            'style' => array(
                'title'       => __('Grid view', 'woo-payment-gateway-paysera'),
                'type'        => 'checkbox',
                'label'       => __('Enable grid view', 'woo-payment-gateway-paysera'),
                'default'     => 'no',
                'description' => __('Enable this to use payment methods grid view', 'woo-payment-gateway-paysera'),
            ),
            'buyerConsent' => array(
                'title'       => __('Buyer consent', 'woo-payment-gateway-paysera'),
                'type'        => 'checkbox',
                'label'       => __('Enable buyer consent', 'woo-payment-gateway-paysera'),
                'default'     => 'yes',
                'description' => __('Enable this to skip additional step when using Pis payment methods', 'woo-payment-gateway-paysera'),
            ),
            'paymentNewOrderStatus' => array(
                'title'       => __('New Order Status', 'woo-payment-gateway-paysera'),
                'type'        => 'select',
                'class'	      => 'wc-enhanced-select',
                'default'     => 'wc-processing',
                'description' => __('New order creation status', 'woo-payment-gateway-paysera'),
                'options'     => array(),
            ),
            'paymentCompletedStatus' => array(
                'title'       => __('Paid Order Status', 'woo-payment-gateway-paysera'),
                'type'        => 'select',
                'class'	      => 'wc-enhanced-select',
                'default'     => 'wc-completed',
                'description' => __('Order status after completing payment', 'woo-payment-gateway-paysera'),
                'options'     => array(),
            ),
            'paymentPendingStatus' => array(
                'title'       => __('Pending checkout', 'woo-payment-gateway-paysera'),
                'type'        => 'select',
                'class'	      => 'wc-enhanced-select',
                'default'     => 'wc-pending',
                'description' => __('Order status with not finished checkout', 'woo-payment-gateway-paysera'),
                'options'     => array(),
            )
        );
    }

    /**
     * @param object $tabs
     * @param boolean [Optional] $print
     *
     * @return boolean|string
     */
    public function buildAdminFormHtml($tabs, $print = true)
    {
        $htmlData = $this->generateFormFields($tabs);

        $html  = '<div class="plugin_config">';
        $html .= '<h2>' . $htmlData['links'] . '</h2>';
        $html .= '<div style="clear:both;"><hr /></div>';
        $html .= $htmlData['tabs'];
        $html .= '</div>';

        if ($print) {
            print_r($html);
            return $print;
        } else {
            return $html;
        }
    }

    /**
     * @return array
     */
    public function generateNewSettings()
    {
        return [
            'countries' => $this->getPayseraListCountries(),
            'statuses'  => $this->getStatusList()
        ];
    }

    /**
     * @return array
     */
    protected function getPayseraListCountries()
    {
        $validProjectID = $this->getValidProject($this->getProjectID());

        try {
            $pmethods = WebToPay::getPaymentMethodList($validProjectID, $this->getCurrency())
                ->setDefaultLanguage($this->getLang())
                ->getCountries()
            ;
        } catch (WebToPayException $exception) {
            error_log('[Paysera] Got an exception: ' . $exception);

            return [];
        }

        $langList = [];
        foreach ($pmethods as $country) {
            $langList[$country->getCode()] = $country->getTitle();
        }

        return $langList;
    }

    /**
     * @return array
     */
    protected function getStatusList()
    {
        $wcStatus = array_keys(wc_get_order_statuses());
        $orderStatus = array();
        foreach ($wcStatus as $key => $value) {
            $orderStatus[$wcStatus[$key]] = wc_get_order_status_name($wcStatus[$key]);
        }

        return $orderStatus;
    }

    /**
     * @param integer $projectID
     *
     * @return integer
     */
    protected function getValidProject($projectID)
    {
        if (filter_var($projectID, FILTER_VALIDATE_INT) !== FALSE) {
            $definedProjectID = $projectID;
        } else {
            $definedProjectID = $this::DEFAULT_PROJECT_ID;
        }

        return $definedProjectID;
    }

    /**
     * @param object $tabs
     *
     * @return array
     */
    protected function generateFormFields($tabs)
    {
        $tabsLink = '';
        $tabsContent = '';
        foreach ($tabs as $key => $value) {
            $tabsLink .= '<a href="javascript:void(0)"';
            $tabsLink .= ' id="tab' . $key . '" class="nav-tab"';
            $tabsLink .= ' data-cont="content' . $key . '">';
            $tabsLink .=  $value['name'] . '</a>';

            $tabsContent .= '<div id="content' . $key . '" class="tabContent">';
            $tabsContent .= '<table class="form-table">' . $value['slice'] . '</table>';
            $tabsContent .= '</div>';
        }

        return [
            'links' => $tabsLink,
            'tabs'  => $tabsContent
        ];
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
     * @return array
     */
    public function getFormFields()
    {
        return $this->formFields;
    }

    /**
     * @param array $formFields
     *
     * @return self
     */
    public function setFormFields($formFields)
    {
        $this->formFields = $formFields;

        return $this;
    }
}
