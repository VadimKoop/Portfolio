<?php

namespace WPML\Core;

use \WPML\Core\Twig\Environment;
use \WPML\Core\Twig\Error\LoaderError;
use \WPML\Core\Twig\Error\RuntimeError;
use \WPML\Core\Twig\Markup;
use \WPML\Core\Twig\Sandbox\SecurityError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedTagError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFilterError;
use \WPML\Core\Twig\Sandbox\SecurityNotAllowedFunctionError;
use \WPML\Core\Twig\Source;
use \WPML\Core\Twig\Template;

/* /setup/header.twig */
class __TwigTemplate_4a894f6ef066be6f98474f20651a9fed8a456e732be6912fe20fc303579f0005 extends \WPML\Core\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\" ";
        // line 2
        echo \WPML\Core\twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('language_attributes')->getCallable(), []), "html", null, true);
        echo ">
<head>
    <meta name=\"viewport\" content=\"width=device-width\"/>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
    <title>";
        // line 6
        echo \WPML\Core\twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    ";
        // line 7
        echo \WPML\Core\twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('wp_print_scripts')->getCallable(), ["wcml-setup"]), "html", null, true);
        echo "
    ";
        // line 8
        echo \WPML\Core\twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('wp_do_action')->getCallable(), ["admin_print_styles"]), "html", null, true);
        echo "
    ";
        // line 9
        echo \WPML\Core\twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('wp_do_action')->getCallable(), ["admin_head"]), "html", null, true);
        echo "
</head>
<body class=\"wcml-setup wp-core-ui\">
<h1 id=\"wcml-logo\"><a href=\"https://wpml.org/woocommerce-multilingual\"><img
                src=\"";
        // line 13
        echo \WPML\Core\twig_escape_filter($this->env, ($context["WCML_PLUGIN_URL"] ?? null), "html", null, true);
        echo "/res/images/banner-772x120.png\"
                alt=\"WooCommerce Multilingual\"/></a></h1>

";
        // line 16
        if (($context["has_handler"] ?? null)) {
            // line 17
            echo "<form class=\"wcml-setup-form\" method=\"post\">
    <input type=\"hidden\" name=\"nonce\" value=\"";
            // line 18
            echo \WPML\Core\twig_escape_filter($this->env, ($context["nonce"] ?? null), "html", null, true);
            echo "\"/>
    <input type=\"hidden\" name=\"handle_step\" value=\"";
            // line 19
            echo \WPML\Core\twig_escape_filter($this->env, ($context["step"] ?? null), "html", null, true);
            echo "\"/>
";
        }
    }

    public function getTemplateName()
    {
        return "/setup/header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 19,  72 => 18,  69 => 17,  67 => 16,  61 => 13,  54 => 9,  50 => 8,  46 => 7,  42 => 6,  35 => 2,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/setup/header.twig", "/home/u954156438/domains/mr-ant.ee/public_html/wp-content/plugins/woocommerce-multilingual/templates/setup/header.twig");
    }
}
