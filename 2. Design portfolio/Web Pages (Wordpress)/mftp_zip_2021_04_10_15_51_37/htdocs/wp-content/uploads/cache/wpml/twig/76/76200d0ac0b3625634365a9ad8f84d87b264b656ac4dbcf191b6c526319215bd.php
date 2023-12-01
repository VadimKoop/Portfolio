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

/* currency-switcher-options.twig */
class __TwigTemplate_1602e2f04db2c9c783f3a7c479f5c5d6ebd1f2df132f8ad9b8dcef4f01a6c60d extends \WPML\Core\Twig\Template
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
        echo "<div class=\"wcml-section\" id=\"currency-switcher\" ";
        if (twig_test_empty(($context["multi_currency_on"] ?? null))) {
            echo "style=\"display:none\"";
        }
        echo ">
    <div class=\"wcml-section-header\">
        <h3>";
        // line 3
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "main", []), "html", null, true);
        echo "</h3>
        <p>";
        // line 4
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "main_desc", []), "html", null, true);
        echo "</p>
    </div>

    <div class=\"wcml-section-content\">

        <div class=\"wcml-section-content-inner\">
            <h4>
                ";
        // line 11
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "order", []), "html", null, true);
        echo "
                <span style=\"display:none;\" class=\"wcml_currencies_order_ajx_resp\"></span>
            </h4>
            <p class=\"explanation-text\">";
        // line 14
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["currency_switcher"] ?? null), "order_tip", []), "html", null, true);
        echo "</p>
            <ul id=\"wcml_currencies_order\" class=\"wcml-cs-currencies-order\">
                ";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["currency_switcher"] ?? null), "order", []));
        foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
            // line 17
            echo "                    <li class=\"wcml_currencies_order_";
            echo \WPML\Core\twig_escape_filter($this->env, $context["code"], "html", null, true);
            echo "\" cur=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $context["code"], "html", null, true);
            echo "\">";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["wc_currencies"] ?? null), $context["code"]), "html", null, true);
            echo " (";
            echo call_user_func_array($this->env->getFunction('get_currency_symbol')->getCallable(), [$context["code"]]);
            echo ")</li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        echo "            </ul>
            <input type=\"hidden\" id=\"wcml_currencies_order_order_nonce\" value=\"";
        // line 20
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["currency_switcher"] ?? null), "order_nonce", []), "html", null, true);
        echo "\"/>
        </div>

        <div class=\"wcml-section-content-inner\">
            <h4>";
        // line 24
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "additional_css", []), "html", null, true);
        echo "</h4>
            <textarea class=\"large-text\" name=\"currency_switcher_additional_css\" rows=\"5\">";
        // line 25
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["currency_switcher"] ?? null), "additional_css", []), "html", null, true);
        echo "</textarea>
        </div>
    </div>
</div>

<div class=\"wcml-section\" id=\"currency-switcher-widget\" ";
        // line 30
        if (twig_test_empty(($context["multi_currency_on"] ?? null))) {
            echo "style=\"display:none\"";
        }
        echo ">
    <div class=\"wcml-section-header\">
        <h3>";
        // line 32
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "widget", []), "html", null, true);
        echo "</h3>
    </div>
    <div class=\"wcml-section-content\">
        <div class=\"wcml-section-content-inner\">
            <table class=\"wcml-cs-list\" ";
        // line 36
        if (twig_test_empty($this->getAttribute(($context["currency_switcher"] ?? null), "widget_currency_switchers", []))) {
            echo " style=\"display: none;\" ";
        }
        echo ">
                <thead>
                    <tr>
                        <th>";
        // line 39
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "preview", []), "html", null, true);
        echo "</th>
                        <th>";
        // line 40
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "position", []), "html", null, true);
        echo "</th>
                        <th>";
        // line 41
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "actions", []), "html", null, true);
        echo "</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 45
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["currency_switcher"] ?? null), "widget_currency_switchers", []));
        foreach ($context['_seq'] as $context["_key"] => $context["widget_currency_switcher"]) {
            // line 46
            echo "                        <tr>
                            <td class=\"wcml-cs-cell-preview\">
                                <div class=\"wcml-currency-preview-wrapper\">
                                    <div id=\"wcml_curr_sel_preview\" class=\"wcml-currency-preview ";
            // line 49
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), "html", null, true);
            echo "\">
                                        ";
            // line 50
            echo $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "preview", []), $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), [], "array");
            echo "
                                    </div>
                                </div>
                            </td>
                            <td class=\"wcml-cs-widget-name\">
                               ";
            // line 55
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "name", [], "array"), "html", null, true);
            echo "
                            </td>
                            <td class=\"wcml-cs-actions\">
                                <a title=\"";
            // line 58
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "edit", []), "html", null, true);
            echo "\"
                                   class=\"edit_currency_switcher js-wcml-cs-dialog-trigger\"
                                   data-switcher=\"";
            // line 60
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), "html", null, true);
            echo "\"
                                   data-dialog=\"wcml_currency_switcher_options_";
            // line 61
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), "html", null, true);
            echo "\"
                                   data-content=\"wcml_currency_switcher_options_";
            // line 62
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), "html", null, true);
            echo "\"
                                   data-height=\"800\" data-width=\"700\">
                                    <i class=\"otgs-ico-edit\"></i>
                                </a>
                                <a title=\"";
            // line 66
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "delete", []), "html", null, true);
            echo "\" class=\"delete_currency_switcher\" data-switcher=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["widget_currency_switcher"], "id", [], "array"), "html", null, true);
            echo "\" href=\"#\">
                                    <i class=\"otgs-ico-delete\" title=\"";
            // line 67
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "delete", []), "html", null, true);
            echo "\"></i>
                                </a>
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['widget_currency_switcher'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 72
        echo "                    <tr class=\"wcml-cs-empty-row\" style=\"display: none\">
                        <td class=\"wcml-cs-cell-preview\">
                            <div class=\"wcml-currency-preview-wrapper\">
                                <div id=\"wcml_curr_sel_preview\" class=\"wcml-currency-preview\"></div>
                            </div>
                        </td>
                        <td class=\"wcml-cs-widget-name\">
                        </td>
                        <td class=\"wcml-cs-actions\">
                            <a title=\"";
        // line 81
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "edit", []), "html", null, true);
        echo "\"
                               class=\"edit_currency_switcher js-wcml-cs-dialog-trigger\"
                               data-switcher=\"\"
                               data-dialog=\"\"
                               data-content=\"\"
                               data-height=\"800\" data-width=\"700\">
                                <i class=\"otgs-ico-edit\"></i>
                            </a>
                            <a title=\"";
        // line 89
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "delete", []), "html", null, true);
        echo "\" class=\"delete_currency_switcher\" data-switcher=\"\" href=\"#\">
                                <i class=\"otgs-ico-delete\" title=\"";
        // line 90
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "delete", []), "html", null, true);
        echo "\"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class=\"tablenav top clearfix\">
                <button type=\"button\" class=\"button button-secondary alignright wcml_add_cs_sidebar js-wcml-cs-dialog-trigger\"
                        data-switcher=\"new_widget\"
                        data-dialog=\"wcml_currency_switcher_options_new_widget\"
                        data-content=\"wcml_currency_switcher_options_new_widget\"
                        data-height=\"800\" data-width=\"700\"
                        ";
        // line 102
        if (twig_test_empty($this->getAttribute(($context["currency_switcher"] ?? null), "available_sidebars", []))) {
            echo "style=\"display:none\"";
        }
        echo ">
                    <i class=\"otgs-ico-add otgs-ico-sm\"></i>
                    ";
        // line 104
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "add_widget", []), "html", null, true);
        echo "
                </button>
            </div>
            <input type=\"hidden\" id=\"wcml_delete_currency_switcher_nonce\" value=\"";
        // line 107
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["currency_switcher"] ?? null), "delete_nonce", []), "html", null, true);
        echo "\"/>
        </div>
    </div>
</div>

<div class=\"wcml-section\" id=\"currency-switcher-product\" ";
        // line 112
        if (twig_test_empty(($context["multi_currency_on"] ?? null))) {
            echo "style=\"display:none\"";
        }
        echo ">
    <div class=\"wcml-section-header\">
        <h3>";
        // line 114
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "product_page", []), "html", null, true);
        echo "</h3>
    </div>
    <div class=\"wcml-section-content\">
        <div class=\"wcml-section-content-inner\">
            <ul class=\"wcml_curr_visibility\">
                <li>
                    <label>
                        <input type=\"checkbox\" name=\"currency_switcher_product_visibility\" value=\"1\" ";
        // line 121
        if ($this->getAttribute(($context["currency_switcher"] ?? null), "visibility_on", [])) {
            echo "checked=\"checked\"";
        }
        echo ">
                        ";
        // line 122
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["currency_switcher"] ?? null), "visibility_label", []), "html", null, true);
        echo "
                    </label>
                </li>
            </ul>
            <div>
                <table class=\"wcml-cs-list\" ";
        // line 127
        if ( !$this->getAttribute(($context["currency_switcher"] ?? null), "visibility_on", [])) {
            echo " style=\"display:none\" ";
        }
        echo ">
                    <thead>
                        <tr>
                            <th>";
        // line 130
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "preview", []), "html", null, true);
        echo "</th>
                            <th>";
        // line 131
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "action", []), "html", null, true);
        echo "</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class=\"wcml-cs-cell-preview\">
                                <div class=\"wcml-currency-preview-wrapper\">
                                    <div id=\"wcml_curr_sel_preview\" class=\"wcml-currency-preview product\">
                                        ";
        // line 139
        echo $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "preview", []), "product", [], "array");
        echo "
                                    </div>
                                </div>
                            </td>

                            <td class=\"wcml-cs-actions\">
                                <a title=\"";
        // line 145
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["currency_switcher"] ?? null), "headers", []), "edit", []), "html", null, true);
        echo "\"
                                   class=\"edit_currency_switcher js-wcml-cs-dialog-trigger\"
                                   data-switcher=\"product\"
                                   data-dialog=\"wcml_currency_switcher_options_product\"
                                   data-content=\"wcml_currency_switcher_options_product\"
                                   data-height=\"800\" data-width=\"700\">
                                    <i class=\"otgs-ico-edit\"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "currency-switcher-options.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  332 => 145,  323 => 139,  312 => 131,  308 => 130,  300 => 127,  292 => 122,  286 => 121,  276 => 114,  269 => 112,  261 => 107,  255 => 104,  248 => 102,  233 => 90,  229 => 89,  218 => 81,  207 => 72,  196 => 67,  190 => 66,  183 => 62,  179 => 61,  175 => 60,  170 => 58,  164 => 55,  156 => 50,  152 => 49,  147 => 46,  143 => 45,  136 => 41,  132 => 40,  128 => 39,  120 => 36,  113 => 32,  106 => 30,  98 => 25,  94 => 24,  87 => 20,  84 => 19,  69 => 17,  65 => 16,  60 => 14,  54 => 11,  44 => 4,  40 => 3,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "currency-switcher-options.twig", "/data01/virt101227/domeenid/www.mr-ant.ee/htdocs/wp-content/plugins/woocommerce-multilingual/templates/multi-currency/currency-switcher-options.twig");
    }
}
