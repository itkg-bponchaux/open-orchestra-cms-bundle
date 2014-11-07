<?php

namespace PHPOrchestra\Backoffice\Context;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use PHPOrchestra\ModelBundle\Model\SiteInterface;
use PHPOrchestra\ModelBundle\Repository\SiteRepository;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Centralize app contextual datas
 */
class ContextManager implements CurrentSiteIdInterface
{
    const KEY_LOCALE = '_locale';
    const KEY_SITE = '_site';

    protected $session;
    protected $siteRepository;

    /**
     * Constructor
     *
     * @param Session        $session
     * @param SiteRepository $siteRepository
     * @param string         $defaultLocale
     */
    public function __construct(Session $session, SiteRepository $siteRepository, $defaultLocale = 'en')
    {
        $this->session = $session;
        if ($this->getCurrentLocale() == '') {
            $this->setCurrentLocale($defaultLocale);
        }

        $this->siteRepository = $siteRepository;
    }

    /**
     * Get current locale value
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->session->get(self::KEY_LOCALE);
    }

    /**
     * Set current locale
     *
     * @param string $locale
     */
    public function setCurrentLocale($locale)
    {
        $this->session->set(self::KEY_LOCALE, $locale);
    }

    /**
     * Get availables sites on platform
     *
     * @return array
     */
    public function getAvailableSites()
    {
        $documentSites = $this->siteRepository->findAll();

        /** @var SiteInterface $site */
        return array_filter($documentSites, function ($site) {
            return $site->getSiteId() != '' && $site->getDomain() != '';
        });
    }

    /**
     * Set current site
     *
     * @param string $siteId
     * @param string $siteDomain
     * @param string $siteDefaultLanguage
     */
    public function setCurrentSite($siteId, $siteDomain, $siteDefaultLanguage)
    {
        $this->session->set(
            self::KEY_SITE,
            array(
                'siteId' => $siteId,
                'domain' => $siteDomain,
                'defaultLanguage' => $siteDefaultLanguage,
            )
        );
    }

    /**
     * Get the current site id
     *
     * @return string
     */
    public function getCurrentSiteId()
    {
        $site = $this->getCurrentSite();

        return $site['siteId'];
    }

    /**
     * Get the current domain
     *
     * @return string
     */
    public function getCurrentSiteDomain()
    {
        $site = $this->getCurrentSite();

        return $site['domain'];
    }

    /**
     * Get the current default language of the current site
     *
     * @return string
     */
    public function getCurrentSiteDefaultLanguage()
    {
        $site = $this->getCurrentSite();

        return $site['defaultLanguage'];
    }

    /**
     * Get current selected site (BO Context)
     *
     * @return array
     */
    protected function getCurrentSite()
    {
        $currentSite = $this->session->get(self::KEY_SITE);

        if ($currentSite) {
            return $currentSite;
        } else {
            $sites = $this->getAvailableSites();

            if (isset($sites[0])) {
                $this->setCurrentSite($sites[0]->getSiteId(), $sites[0]->getDomain(), $sites[0]->getDefaultLanguage());
            } else {
                $this->setCurrentSite(0, 'No site available', $this->getCurrentLocale());
            }
        }

        return $this->session->get(self::KEY_SITE);
    }
}
