<?php
defined('ABSPATH') or exit;

class WC_Paysera_Gateway extends WC_Payment_Gateway
{
    /**
     * Paysera image location
     */
    const PAYSERA_LOGO = 'assets/images/paysera.png';

    /**
     * Paysera backend JS script location
     */
    const PAYSERA_BACKEND_ACTION_JS = 'assets/js/backend/action.js';

    /**
     * Paysera frontend JS script location
     */
    const PAYSERA_FRONTEND_ACTION_JS = 'assets/js/frontend/action.js';

    /**
     * Paysera stylesheet location
     */
    const PAYSERA_STYLESHEET = 'assets/css/paysera.css';

    /**
     * Local language delimiter
     */
    const LANG_DELIMITER = '_';

    /**
     * @var integer
     */
    protected $projectID;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var boolean
     */
    protected $paymentType;

    /**
     * @var boolean
     */
    protected $gridView;

    /**
     * @var string|array
     */
    protected $countriesSelected;

    /**
     * @var boolean
     */
    protected $test;

    /**
     * @var string
     */
    protected $paymentNewOrderStatus;

    /**
     * @var string
     */
    protected $paymentCompletedStatus;

    /**
     * @var string
     */
    protected $paymentPendingStatus;

    /**
     * @var object
     */
    protected $pluginSettings;

    /**
     * @var boolean
     */
    protected $buyerConsent;

    /**
     * WC_Gateway_Paysera constructor.
     */
    public function __construct()
    {
        if(!class_exists('WebToPay')) {
            require_once 'libraries/WebToPay.php';
        }

        $this->id = 'paysera';
        $this->has_fields = true;
        $this->method_title = __('All popular payment methods', 'woo-payment-gateway-paysera');
        $this->method_description = __( 'Choose a payment method on the Paysera page', 'woo-payment-gateway-paysera' );
        $this->icon = apply_filters(
            'woocommerce_paysera_icon',
            WCGatewayPayseraPluginUrl.$this::PAYSERA_LOGO
        );

        $this->init_form_fields();
        $this->init_settings();

        $this->title                  = $this->get_option('title');
        $this->description            = $this->get_option('description');
        $this->projectID              = $this->get_option('projectid');
        $this->password               = $this->get_option('password');
        $this->paymentType            = $this->get_option('paymentType') === 'yes';
        $this->gridView               = $this->get_option('style') === 'yes';
        $this->countriesSelected      = $this->get_option('countriesSelected');
        $this->test                   = $this->get_option('test') === 'yes';
        $this->paymentNewOrderStatus  = $this->get_option('paymentNewOrderStatus');
        $this->paymentCompletedStatus = $this->get_option('paymentCompletedStatus');
        $this->paymentPendingStatus   = $this->get_option('paymentPendingStatus');
        $this->buyerConsent           = $this->get_option('buyerConsent') === 'yes';

        add_action('woocommerce_thankyou_paysera', array($this, 'thankyou'));
        add_action('woocommerce_api_wc_gateway_paysera', array($this, 'check_callback_request'));
        add_action(
            'woocommerce_update_options_payment_gateways_paysera',
            array($this, 'process_admin_options')
        );

    }

    public function init_form_fields()
    {
        if(!class_exists('Wc_Paysera_Settings')) {
            require_once 'class-wc-paysera-settings.php';
        }

        $this->setPluginSettings(Wc_Paysera_Settings::create());

        $this->form_fields = $this->getPluginSettings()->getFormFields();
    }

    public function admin_options()
    {
        $this->getPluginSettings()->setLang($this->getLocalLang($this::LANG_DELIMITER));
        $this->getPluginSettings()->setCurrency(get_woocommerce_currency());
        $this->getPluginSettings()->setProjectID($this->getProjectID());
        $this->updateAdminSettings($this->getPluginSettings()->generateNewSettings());
        $all_fields = $this->get_form_fields();
        $tabs = $this->generateTabs(array(
            [
                'name'  => __('Main Settings', 'woo-payment-gateway-paysera'),
                'slice' => array_slice($all_fields, 0, 4)
            ],
            [
                'name'  => __('Extra Settings', 'woo-payment-gateway-paysera'),
                'slice' => array_slice($all_fields, 4, -3)
            ],
            [
                'name'  => __('Order Status', 'woo-payment-gateway-paysera'),
                'slice' => array_slice($all_fields, -3, count($all_fields))
            ]
        ));

        $this->getPluginSettings()->buildAdminFormHtml($tabs);

        wp_enqueue_script(
            'custom-backend-script',
            WCGatewayPayseraPluginUrl.$this::PAYSERA_BACKEND_ACTION_JS,
            array('jquery')
        );
    }

    public function validate_projectid_field($key, $value) {
        if(filter_var($value, FILTER_VALIDATE_INT) === FALSE) {
            WC_Admin_Settings::add_error(esc_html__(
                'Project ID must be Not Empty',
                'woo-payment-gateway-paysera'
            ));
        }

        return $value;
    }

    public function validate_password_field($key, $value) {
        if (1 > strlen($value)) {
            WC_Admin_Settings::add_error(esc_html__(
                'Password (sign) must be Not Empty',
                'woo-payment-gateway-paysera'
            ));
        }

        return $value;
    }

    public function payment_fields()
    {
        if(!class_exists('Wc_Paysera_Payment_Methods')) {
            require_once 'class-wc-paysera-payment-methods.php';
        }

        $localLang = $this->getLocalLang($this::LANG_DELIMITER);
        $billingCountry = WC()->customer->get_billing_country();
        $cartTotal = round(WC()->cart->total * 100);
        $currency = get_woocommerce_currency();

        $htmlFields = Wc_Paysera_Payment_Methods::create()
            ->setProjectID($this->getProjectID())
            ->setLang($localLang)
            ->setBillingCountry(strtolower($billingCountry))
            ->setDisplayList($this->getPaymentType())
            ->setCountriesSelected($this->getCountriesSelected())
            ->setGridView($this->getGridView())
            ->setDescription($this->getDescription())
            ->setCartTotal($cartTotal)
            ->setCartCurrency($currency)
            ->setAvailableLang(array('lt', 'lv', 'ru', 'en', 'pl', 'bg', 'et'))
            ->setBuyerConsent($this->getBuyerConsent())
        ;

        $htmlFields->build(true);

        wp_enqueue_style(
            'custom-frontend-style',
            WCGatewayPayseraPluginUrl.$this::PAYSERA_STYLESHEET
        );

        wp_enqueue_script(
            'custom-frontend-script',
            WCGatewayPayseraPluginUrl.$this::PAYSERA_FRONTEND_ACTION_JS,
            array('jquery')
        );
    }

    public function process_payment($order_id)
    {
        if(!class_exists('Wc_Paysera_Request')) {
            require_once 'class-wc-paysera-request.php';
        }

        $order = wc_get_order($order_id);
        $order->add_order_note(__('Paysera: Order checkout process is started', 'woo-payment-gateway-paysera'));
        $this->updateOrderStatus($order, $this->getPaymentPendingStatus());

        $paysera_request = Wc_Paysera_Request::create()
            ->setProjectID($this->getProjectID())
            ->setSignature($this->getPassword())
            ->setReturnUrl($this->get_return_url($order))
            ->setCallbackUrl(trailingslashit(get_bloginfo('wpurl')) . '?wc-api=wc_gateway_paysera')
            ->setTest($this->getTest())
            ->setLocale($this->getLocalLang($this::LANG_DELIMITER))
            ->setTranslationLang(array(
                'lt' => 'LIT',
                'lv' => 'LAV',
                'et' => 'EST',
                'ru' => 'RUS',
                'de' => 'GER',
                'pl' => 'POL',
                'en' => 'ENG'
            ))
            ->setBuyerConsent($this->getBuyerConsent())
        ;


        if ($this->getPaymentType()) {
            $selectedPayment = $_REQUEST['payment']['pay_type'];
        } else {
            $selectedPayment = '';
        }
        $parameters = $paysera_request->getWooParameters($order, $selectedPayment);
        $url = $paysera_request->buildUrl($parameters);

        wc_maybe_reduce_stock_levels($order_id);

        return array(
            'result'   => 'success',
            'redirect' => $url,
        );
    }

    public function thankyou($order_id)
    {
	    $order = wc_get_order($order_id);
	    $currentStatus = 'wc-' . $order->get_status();

	    $validToChange =
		    $currentStatus == $this->getPaymentPendingStatus()
		    && $currentStatus != $this->getPaymentNewOrderStatus()
	    ;

        if ($validToChange) {
            $message = 'Paysera: Customer came back to page';
            $this->getOrderLogMsg($order, $message);
            $order->add_order_note( __('Paysera: Customer came back to page', 'woo-payment-gateway-paysera'));
            $this->updateOrderStatus($order, $this->getPaymentNewOrderStatus());
        }
    }

    public function check_callback_request()
    {
        $projectId = $this->getProjectID();
        $projectSign = $this->getPassword();

        try {
            $response = WebToPay::validateAndParseData($_REQUEST, $projectId, $projectSign);

            if ($response['status'] == 1) {
                $order = wc_get_order($response['orderid']);

                if($this->checkPayment($order, $response)) {
                    $errorMsg = 'Payment confirmed with a callback';
                    $this->getOrderLogMsg($order, $errorMsg, true);

                    $order->add_order_note(__('Paysera: Callback order payment completed', 'woo-payment-gateway-paysera'));
                    $this->updateOrderStatus($order, $this->getPaymentCompletedStatus());

                    print_r('OK');
                }
            }
        } catch (Exception $e) {
            $errorMsg = get_class($e) . ': ' . $e->getMessage();
            error_log($errorMsg);
            print_r($errorMsg);
        }

        exit();
    }


    public function checkPayment($order, $response)
    {
        $orderTotal = (string) ($order->get_total() * 100);

        if ($orderTotal !== $response['amount']) {
            $errorMsg = 'Amounts do not match';
            throw new Exception($this->getOrderLogMsg($order, $errorMsg));
        }

        if ($order->get_currency() !== $response['currency']) {
            $errorMsg = 'Currencies do not match';
            throw new Exception($this->getOrderLogMsg($order, $errorMsg));
        }

        return true;
    }

    protected function getOrderLogMsg($order, $errorMsg, $sendLog = false)
    {
        $fullLog = $errorMsg . ':'
            . ' Order #' . $order->get_id() . ';'
            . ' Amount: ' . $order->get_total() . $order->get_currency();

        if ($sendLog) {
            error_log($fullLog);
            return $sendLog;
        } else {
            return $fullLog;
        }
    }

    protected function getLocalLang($delimiter)
    {
        $lang = explode($delimiter, get_locale());

        return $lang[0];
    }

    protected function generateTabs($tabs)
    {
        $data = [];
        foreach ($tabs as $key => $value) {
            $data[$key]['name']  =  $value['name'];
            $data[$key]['slice'] =  $this->generate_settings_html($value['slice'], false);
        }

        return $data;
    }

    protected function updateAdminSettings($data)
    {
        $this->form_fields['countriesSelected']['options']      = $data['countries'];
        $this->form_fields['paymentNewOrderStatus']['options']  = $data['statuses'];
        $this->form_fields['paymentCompletedStatus']['options'] = $data['statuses'];
        $this->form_fields['paymentPendingStatus']['options']  = $data['statuses'];
    }

    protected function updateOrderStatus($order, $status)
    {
        $orderStatusFiltered = str_replace("wc-", "", $status);
        $order->update_status(
            $orderStatusFiltered,
            __('Paysera: Status changed to ', 'woo-payment-gateway-paysera') . $orderStatusFiltered . '<br />',
            true
        );
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setProjectId($projectID)
    {
        $this->projectID = $projectID;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return boolean
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param boolean $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return boolean
     */
    public function getGridView()
    {
        return $this->gridView;
    }

    /**
     * @param boolean $gridView
     */
    public function setGridView($gridView)
    {
        $this->gridView = $gridView;
    }

    /**
     * @return string|array
     */
    public function getCountriesSelected()
    {
        return $this->countriesSelected;
    }

    /**
     * @param string $countriesSelected
     */
    public function setCountriesSelected($countriesSelected)
    {
        $this->countriesSelected = $countriesSelected;
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
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getPaymentNewOrderStatus()
    {
        return $this->paymentNewOrderStatus;
    }

    /**
     * @param string $paymentNewOrderStatus
     */
    public function setPaymentNewOrderStatus($paymentNewOrderStatus)
    {
        $this->paymentNewOrderStatus = $paymentNewOrderStatus;
    }

    /**
     * @return string
     */
    public function getPaymentCompletedStatus()
    {
        return $this->paymentCompletedStatus;
    }

    /**
     * @param string $paymentCompletedStatus
     */
    public function setPaymentCompletedStatus($paymentCompletedStatus)
    {
        $this->paymentCompletedStatus = $paymentCompletedStatus;
    }

    /**
     * @return string
     */
    public function getPaymentPendingStatus()
    {
        return $this->paymentPendingStatus;
    }

    /**
     * @param string $paymentPendingStatus
     */
    public function setPaymentPendingStatus($paymentPendingStatus)
    {
        $this->paymentPendingStatus = $paymentPendingStatus;
    }

    /**
     * @return object
     */
    public function getPluginSettings()
    {
        return $this->pluginSettings;
    }

    /**
     * @param object $pluginSettings
     */
    public function setPluginSettings($pluginSettings)
    {
        $this->pluginSettings = $pluginSettings;
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
