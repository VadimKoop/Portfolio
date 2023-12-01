<?php
defined('ABSPATH') or exit;

class Wc_Paysera_Init
{
    protected $errors;

    const PAYSERA_DOC_LINK = 'https://developers.paysera.com/en/checkout/basic';
    
    const ADMIN_SETTINGS_LINK = 'admin.php?page=wc-settings&tab=checkout&section=paysera';
    
    const ERRORS_GLUE_STRING = PHP_EOL;

    public function __construct()
    {
        $this->errors = array();
    }

    public function hooks()
    {
        add_action('plugins_loaded', array($this, 'loadPayseraGateway'));

        add_action('admin_notices', array($this, 'displayAdminNotices'));

        add_filter('woocommerce_payment_gateways', array($this, 'addPayseraGatewayMethod'));

        add_filter(
            'plugin_action_links_'.WCGatewayPayseraPluginPath.'/paysera.php',
            array($this, 'addPayseraGatewayActionLinks')
        );
    }

    public function loadPayseraGateway()
    {
        load_plugin_textdomain(
            'woo-payment-gateway-paysera',
            FALSE,
            WCGatewayPayseraPluginPath. '/languages/'
        );

        if(!class_exists('woocommerce')) {
            $this->addError('WooCommerce is not active');
            return false;
        }

        require_once "class-wc-paysera-gateway.php";

        return true;
    }

    public function getInstallErrors()
    {
        $messages = null;
        
        $messages_prefix = __('WooCommerce Payment Gateway - Paysera', 'woo-payment-gateway-paysera');

        $errors = $this->getErrors();
        
        $errorsSplitString = $this::ERRORS_GLUE_STRING;
        $messages = implode($errorsSplitString, $errors);

        return array(
            'prefix'   => $messages_prefix,
            'messages' => $messages
        );
    }

    public function displayAdminNotices()
    {
        $notices = $this->getInstallErrors();

        if (!empty($notices['messages'])) {
            echo '<div class="error"><p><b>'.$notices['prefix'].': </b><br>'.$notices['messages'].'</p></div>';
        }
    }

    public function addPayseraGatewayMethod($methods)
    {
        $methods[] = 'WC_Paysera_Gateway';

        return $methods;
    }

    public function addPayseraGatewayActionLinks($links)
    {
        $adminSettingsLink = admin_url($this::ADMIN_SETTINGS_LINK);
        $adminSettingsTranslations = __('Main Settings', 'woo-payment-gateway-paysera');

        $htmlSettingsLink      = '<a href="' . $adminSettingsLink . '">' . $adminSettingsTranslations . '</a>';
        $htmlDocumentationLink = '<a href="' . $this::PAYSERA_DOC_LINK . '" target="_blank">Docs</a>';

        array_unshift($links, $htmlSettingsLink, $htmlDocumentationLink);

        return $links;
    }

    /**
     * @param array $errors
     *
     * @return $self
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param string $errorText
     * @param string $pluginPath
     *
     * @return $self
     */
    public function addError($errorText, $pluginPath = 'woo-payment-gateway-paysera')
    {
        $error = __($errorText, $pluginPath);

        $this->errors[] = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
