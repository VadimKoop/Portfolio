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

/* /setup/translation-options.twig */
class __TwigTemplate_a1ed7b503b8b43fa2e11b3ce4cae64cf52a173ae5b1bb9af6aa5e9372212e6b4 extends \WPML\Core\Twig\Template
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
        echo "<span id=\"";
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "step_id", []), "html", null, true);
        echo "\">
<h1>";
        // line 2
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "heading", []), "html", null, true);
        echo "</h1>

<p>";
        // line 4
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "description", []), "html", null, true);
        echo "</p>

<ul class=\"no-bullets\">
    <li>
        <label class=\"display-as-translated-yes\">
            <input type=\"radio\" value=\"1\" name=\"translation-option\" ";
        // line 9
        if (($context["is_display_as_translated_checked"] ?? null)) {
            echo "checked=\"checked\"";
        }
        echo " />
            ";
        // line 10
        echo $this->getAttribute(($context["strings"] ?? null), "label_display_as_translated", []);
        echo "
        </label>
    </li>
    <li>
        <label class=\"display-as-translated-no\">
            <input type=\"radio\" value=\"2\" name=\"translation-option\" />
            ";
        // line 16
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "label_translated", []), "html", null, true);
        echo "
        </label>
    </li>
</ul>

<p>";
        // line 21
        echo sprintf($this->getAttribute(($context["strings"] ?? null), "description_footer", []), "<strong>", "</strong>");
        echo "</p>

<p class=\"wcml-setup-actions step\">
    <a href=\"";
        // line 24
        echo \WPML\Core\twig_escape_filter($this->env, ($context["continue_url"] ?? null), "html", null, true);
        echo "\" class=\"button button-large button-primary submit\">";
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "continue", []), "html", null, true);
        echo "</a>
</p>
</span>";
    }

    public function getTemplateName()
    {
        return "/setup/translation-options.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 24,  73 => 21,  65 => 16,  56 => 10,  50 => 9,  42 => 4,  37 => 2,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/setup/translation-options.twig", "/home/u954156438/domains/mr-ant.ee/public_html/wp-content/plugins/woocommerce-multilingual/templates/setup/translation-options.twig");
    }
}
