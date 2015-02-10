<?php

namespace PHPOrchestra\BackofficeBundle\DisplayIcon\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;

/**
 * Class HeaderIconStrategy
 */
class HeaderStrategy extends AbstractStrategy
{
    /**
     * Check if the strategy support this block
     *
     * @param string $block
     *
     * @return boolean
     */
    public function support($block)
    {
        return DisplayBlockInterface::HEADER == $block;
    }

    /**
     * Display an icon for a block
     *
     * @return string
     */
    public function show()
    {
        return $this->render('PHPOrchestraBackofficeBundle:Block/Header:showIcon.html.twig');
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'header';
    }
}
