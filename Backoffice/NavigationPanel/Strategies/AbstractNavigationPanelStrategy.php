<?php

namespace OpenOrchestra\Backoffice\NavigationPanel\Strategies;

use OpenOrchestra\Backoffice\NavigationPanel\NavigationPanelInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class AbstractNavigationPanelStrategy
 */
abstract class AbstractNavigationPanelStrategy implements NavigationPanelInterface
{
    const ROOT_MENU = 'root_menu';

    /**
     * @var EngineInterface
     */
    protected $templating;

    protected $name;
    protected $parent;
    protected $weight = 0;
    protected $role = null;

    /**
     * @param string $name
     * @param string $role
     * @param int    $weight
     * @param string $parent
     */
    public function __construct($name, $role, $weight, $parent)
    {
        $this->name = $name;
        $this->role = $role;
        $this->weight = $weight;
        $this->parent = $parent;
    }

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * return string
     */
    public function getParent()
    {
        if (!is_null($this->parent)) {
            return $this->parent;
        }

        return self::ROOT_MENU;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     *
     * @return string
     */
    protected function render($view, array $parameters = array())
    {
        return $this->templating->render($view, $parameters);
    }
}
