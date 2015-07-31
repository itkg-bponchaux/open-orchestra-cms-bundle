<?php

namespace OpenOrchestra\Backoffice\GenerateForm\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\NewsletterRegistrationStrategy as BaseNewsletterRegistrationStrategy;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class NewsletterRegistrationStrategy
 */
class NewsletterRegistrationStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return BaseNewsletterRegistrationStrategy::NEWSLETTER_REGISTRATION === $block->getComponent();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'newsletter_registration';
    }
}
