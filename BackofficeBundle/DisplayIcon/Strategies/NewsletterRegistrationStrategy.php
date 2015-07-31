<?php

namespace OpenOrchestra\BackofficeBundle\DisplayIcon\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\NewsletterRegistrationStrategy as BaseNewsletterRegistrationStrategy;

/**
 * Class NewsletterRegistrationIconStrategy
 */
class NewsletterRegistrationStrategy extends AbstractStrategy
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
        return BaseNewsletterRegistrationStrategy::NEWSLETTER_REGISTRATION == $block;
    }

    /**
     * Display an icon for a block
     *
     * @return string
     */
    public function show()
    {
        return $this->render('OpenOrchestraBackofficeBundle:Block/NewsletterRegistration:showIcon.html.twig');
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'newsletter_registration';
    }
}
