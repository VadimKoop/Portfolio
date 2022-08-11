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

/* /setup/attributes.twig */
class __TwigTemplate_a213ae0837e1744294d0c7b423029b9c3a75fc5ae3daef5dc3bc34d7945d1f94 extends \WPML\Core\Twig\Template
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
        echo $this->getAttribute(($context["strings"] ?? null), "heading", []);
        echo "</h1>

";
        // line 4
        if (($context["attributes"] ?? null)) {
            // line 5
            echo "    <ul class=\"no-bullets\" >
    ";
            // line 6
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["attributes"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["attribute"]) {
                // line 7
                echo "    <li>
        <label>
            <input type=\"hidden\" name=\"attributes[";
                // line 9
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["attribute"], "name", []), "html", null, true);
                echo "]\" value=\"0\" />
            <input type=\"checkbox\" name=\"attributes[";
                // line 10
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["attribute"], "name", []), "html", null, true);
                echo "]\" value=\"1\" ";
                if ($this->getAttribute($context["attribute"], "translated", [])) {
                    echo "checked=\"cehcked\"";
                }
                echo " />
            ";
                // line 11
                echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute($context["attribute"], "label", []), "html", null, true);
                echo "
        </label>
    </li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attribute'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "    </ul>
";
        } else {
            // line 17
            echo "    <p><i>";
            echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "no_attributes", []), "html", null, true);
            echo "</i></p>
";
        }
        // line 19
        echo "
<p class=\"wcml-setup-actions step\">
    <a href=\"";
        // line 21
        echo \WPML\Core\twig_escape_filter($this->env, ($context["continue_url"] ?? null), "html", null, true);
        echo "\" class=\"button button-large button-primary submit\">";
        echo \WPML\Core\twig_escape_filter($this->env, $this->getAttribute(($context["strings"] ?? null), "continue", []), "html", null, true);
        echo "</a>
</p>
</span>
";
    }

    public function getTemplateName()
    {
        return "/setup/attributes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 21,  87 => 19,  81 => 17,  77 => 15,  67 => 11,  59 => 10,  55 => 9,  51 => 7,  47 => 6,  44 => 5,  42 => 4,  37 => 2,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/setup/attributes.twig", "/home/u954156438/domains/mr-ant.ee/public_html/wp-content/plugins/woocommerce-multilingual/templates/setup/attributes.twig");
    }
}
