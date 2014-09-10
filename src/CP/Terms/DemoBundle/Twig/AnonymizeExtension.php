<?php

namespace CP\Terms\DemoBundle\Twig;

use \Twig_Extension;
use \Twig_Filter_Method;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AnonymizeExtension extends Twig_Extension
{
    protected $container;

    protected $environment;

    /**
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array();
//        return array(
//            'anonymize' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html')))
//        );
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'anonymize' => new Twig_Filter_Method($this, 'anonymize', array('is_safe' => array('html'))),
        );
    }

    public function anonymize($string)
    {
        if (strpos($string, '@') !== false) {
            $tokens = explode('@', $string);

            // String is not an email address
            if (count($tokens) > 2) {
                return $this->obfuscate($string);
            }

            $tokens[0] = $this->obfuscate($tokens[0]);

            $domain = $tokens[1];

            // All demo users will have an email in the example.com domain and can be viewed by all
            if ('example.com' == $domain) {
                return $string;
            }

            $tld = strrchr($domain, '.');

            // Not an email address
            if ($tld === false) {
                return $this->obfuscate($string);
            }

            $tokens[1] = $this->obfuscate(substr($domain, 0, -strlen($tld))) . $tld;

            return implode('@', $tokens);
        }

        return $this->obfuscate($string);
    }

    protected function obfuscate($string)
    {
        return str_repeat('&bull;', strlen($string));
    }
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'cpterms_anonymize';
    }
}
