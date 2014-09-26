<?php

namespace PHPOrchestra\BackofficeBundle\FunctionalTest\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomepageControllerTest
 */
class HomepageControllerTest extends WebTestCase
{
    /**
     * Test fixture_home
     */
    public function testHomepageWithTree()
    {
        $this->markTestSkipped();
        $client = static::createClient();
        $client->setServerParameters(
            array(
                'PHP_AUTH_USER' => 'nicolas',
                'PHP_AUTH_PW'   => 'nicolas',
            )
        );
        $client->followRedirects();

        $crawler = $client->request('GET', '/admin/');

        $this->assertEquals(1, $crawler->filter('html:contains("Editorial")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Administration")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Fixture full sample")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Content")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture Home")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture full sample")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture About Us")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture Contact Us")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture Directory")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Fixture Search")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Home")')->count());

    }
}
