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

/* menus-wrap.twig */
class __TwigTemplate_01baf00e30eee13009573f76be98d19fa8cf61a7df05c501b812331a96b09ac2 extends \WPML\Core\Twig\Template
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
        echo "<div class=\"wrap\">
    <h1>";
        // line 2
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "title", []), "html", null, true);
        echo "</h1>
    <nav class=\"wcml-tabs wpml-tabs\">
        <a class=\"nav-tab ";
        // line 4
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "products", []), "active", []), "html", null, true);
        echo "\" href=\"";
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "products", []), "url", []), "html", null, true);
        echo "\">";
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "products", []), "title", []), "html", null, true);
        echo "</a>
        ";
        // line 5
        if (($context["can_operate_options"] ?? null)) {
            // line 6
            echo "            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["menu"] ?? null), "taxonomies", []));
            foreach ($context['_seq'] as $context["key"] => $context["taxonomy"]) {
                // line 7
                echo "                ";
                if ($this->getAttribute($context["taxonomy"], "is_translatable", [])) {
                    // line 8
                    echo "                    <a class=\"js-tax-tab-";
                    echo \WPML\Core\twig_escape_filter($this->env, $context["key"], "html", null, true);
                    echo " nav-tab ";
                    echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["taxonomy"], "active", []), "html", null, true);
                    echo "\" href=\"";
                    echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["taxonomy"], "url", []), "html", null, true);
                    echo "\" title=\"";
                    echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["taxonomy"], "title", []), "html", null, true);
                    echo "\">
                    ";
                    // line 9
                    echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["taxonomy"], "name", []), "html", null, true);
                    echo "
                    ";
                    // line 10
                    if (($this->getAttribute($context["taxonomy"], "translated", []) == false)) {
                        echo "<i class=\"otgs-ico-warning\"></i>";
                    }
                    // line 11
                    echo "                    </a>
                ";
                }
                // line 13
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['taxonomy'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 14
            echo "            ";
            if ($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "custom_taxonomies", []), "show", [])) {
                // line 15
                echo "            <a class=\"nav-tab tax-custom-taxonomies ";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "custom_taxonomies", []), "active", []), "html", null, true);
                echo "\" href=\"";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "custom_taxonomies", []), "url", []), "html", null, true);
                echo "\">
                ";
                // line 16
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "custom_taxonomies", []), "name", []), "html", null, true);
                echo "
                ";
                // line 17
                if (($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "custom_taxonomies", []), "translated", []) == false)) {
                    echo "<i class=\"otgs-ico-warning\"></i>";
                }
                // line 18
                echo "            </a>
            ";
            }
            // line 20
            echo "            <a class=\"nav-tab tax-product-attributes ";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "attributes", []), "active", []), "html", null, true);
            echo "\" href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "attributes", []), "url", []), "html", null, true);
            echo "\">
                ";
            // line 21
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "attributes", []), "name", []), "html", null, true);
            echo "
                ";
            // line 22
            if (($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "attributes", []), "translated", []) == false)) {
                echo "<i class=\"otgs-ico-warning\"></i>";
            }
            // line 23
            echo "            </a>
            ";
            // line 24
            if ($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "is_translatable", [])) {
                // line 25
                echo "                <a class=\"js-tax-tab-product_shipping_class nav-tab ";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "active", []), "html", null, true);
                echo "\" href=\"";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "url", []), "html", null, true);
                echo "\"
                   title=\"";
                // line 26
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "title", []), "html", null, true);
                echo "\">";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "name", []), "html", null, true);
                echo "
                   ";
                // line 27
                if (($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "shipping_classes", []), "translated", []) == false)) {
                    echo "<i class=\"otgs-ico-warning\"></i>";
                }
                // line 28
                echo "                </a>
            ";
            }
            // line 30
            echo "        ";
        }
        // line 31
        echo "
        ";
        // line 32
        if (($context["can_manage_options"] ?? null)) {
            // line 33
            echo "            <a class=\"nav-tab ";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "settings", []), "active", []), "html", null, true);
            echo "\" href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "settings", []), "url", []), "html", null, true);
            echo "\">";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "settings", []), "name", []), "html", null, true);
            echo "</a>
        ";
        }
        // line 35
        echo "        ";
        if (($context["can_operate_options"] ?? null)) {
            // line 36
            echo "            <a class=\"nav-tab ";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "multi_currency", []), "active", []), "html", null, true);
            echo "\" href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "multi_currency", []), "url", []), "html", null, true);
            echo "\">";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "multi_currency", []), "name", []), "html", null, true);
            echo "</a>
            <a class=\"nav-tab ";
            // line 37
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "slugs", []), "active", []), "html", null, true);
            echo "\" href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "slugs", []), "url", []), "html", null, true);
            echo "\">";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "slugs", []), "name", []), "html", null, true);
            echo "</a>
        ";
        }
        // line 39
        echo "        ";
        if (($context["can_manage_options"] ?? null)) {
            // line 40
            echo "            <a class=\"nav-tab ";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "status", []), "active", []), "html", null, true);
            echo "\" href=\"";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "status", []), "url", []), "html", null, true);
            echo "\">";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "status", []), "name", []), "html", null, true);
            echo "</a>
            ";
            // line 41
            if ($this->getAttribute($this->getAttribute(($context["menu"] ?? null), "troubleshooting", []), "active", [])) {
                // line 42
                echo "                <a class=\"nav-tab troubleshooting ";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "troubleshooting", []), "active", []), "html", null, true);
                echo "\" href=\"";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "troubleshooting", []), "url", []), "html", null, true);
                echo "\">";
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["menu"] ?? null), "troubleshooting", []), "name", []), "html", null, true);
                echo "</a>
            ";
            }
            // line 44
            echo "        ";
        }
        // line 45
        echo "    </nav>

    <div class=\"wcml-wrap\">
    ";
        // line 48
        echo ($context["content"] ?? null);
        echo "
    </div>

    ";
        // line 51
        if ($this->getAttribute(($context["rate"] ?? null), "on", [])) {
            // line 52
            echo "        <div class=\"wcml-wrap wcml-notice otgs-is-dismissible\">
            <p>";
            // line 53
            echo $this->getAttribute(($context["rate"] ?? null), "message", []);
            echo "</p>
            <button class=\"notice-dismiss hide-rate-block\" data-setting=\"rate-block\">
                    <span class=\"screen-reader-text\">";
            // line 55
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["rate"] ?? null), "hide_text", []), "html", null, true);
            echo "</span>
            </button>
            ";
            // line 57
            echo $this->getAttribute(($context["rate"] ?? null), "nonce", []);
            echo "
        </div>
    ";
        }
        // line 60
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "menus-wrap.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  248 => 60,  242 => 57,  237 => 55,  232 => 53,  229 => 52,  227 => 51,  221 => 48,  216 => 45,  213 => 44,  203 => 42,  201 => 41,  192 => 40,  189 => 39,  180 => 37,  171 => 36,  168 => 35,  158 => 33,  156 => 32,  153 => 31,  150 => 30,  146 => 28,  142 => 27,  136 => 26,  129 => 25,  127 => 24,  124 => 23,  120 => 22,  116 => 21,  109 => 20,  105 => 18,  101 => 17,  97 => 16,  90 => 15,  87 => 14,  81 => 13,  77 => 11,  73 => 10,  69 => 9,  58 => 8,  55 => 7,  50 => 6,  48 => 5,  40 => 4,  35 => 2,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "menus-wrap.twig", "/home/u954156438/domains/mr-ant.ee/public_html/wp-content/plugins/woocommerce-multilingual/templates/menus-wrap.twig");
    }
}
