<?php

namespace PHPOrchestra\ApiBundle\FunctionalTest\Controller;

/**
 * Class AreaControllerTest
 */
class AreaControllerTest extends AbstractControllerTest
{
    /**
     * test reverse transform
     */
    public function testAreaReverseTransform()
    {
        $crawler = $this->client->request('GET', '/admin/');
        $crawler = $this->client->request('GET', '/api/context/site/1/front-phporchestra.dev');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/api/node/root');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $json = json_decode($this->client->getResponse()->getContent(), true);
        $area = $json['areas'][0];
        $this->assertSame('main', $area['area_id']);
        $block = $area['blocks'][3];
        $update = $area['links']['_self_block'];


        // Remove ref of area in block 3
        $formData = json_encode(array('blocks' => array(
            array('node_id' => $block['node_id'], 'block_id' => 0),
            array('node_id' => $block['node_id'], 'block_id' => 1),
            array('node_id' => $block['node_id'], 'block_id' => 2),
        )));

        $crawler = $this->client->request('POST', $update, array(), array(), array(), $formData);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $nodeAfter = $this->nodeRepository->find($block['node_id']);
        $this->assertSame(array(), $nodeAfter->getBlock(3)->getAreas());

        // Add ref of area in block 3
        $formData = json_encode(array('blocks' => array(
            array('node_id' => $block['node_id'], 'block_id' => 0),
            array('node_id' => $block['node_id'], 'block_id' => 1),
            array('node_id' => $block['node_id'], 'block_id' => 2),
            array('node_id' => $block['node_id'], 'block_id' => 3),
        )));

        $crawler = $this->client->request('POST', $update, array(), array(), array(), $formData);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
