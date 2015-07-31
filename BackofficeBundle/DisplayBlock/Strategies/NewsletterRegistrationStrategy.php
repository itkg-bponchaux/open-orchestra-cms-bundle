<?php

namespace OpenOrchestra\BackofficeBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\NewsletterRegistrationStrategy as BaseNewsletterRegistrationStrategy;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsletterRegistratioStrategy
 */
class NewsletterRegistrationStrategy extends AbstractStrategy
{
    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return BaseNewsletterRegistrationStrategy::NEWSLETTER_REGISTRATION == $block->getComponent();
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        return $this->render(
            'OpenOrchestraBackofficeBundle:Block/NewsletterRegistration:show.html.twig',
            array(
                'id' => $block->getId(),
                'class' => $block->getClass(),
            )
        );
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
