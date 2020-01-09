<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class ManufacturerTest extends TestCase
{

    public function testGetSingleActiveManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "dc84430f5673d3c1a560d19fffc3b1fc") {
                id
                active
                icon
                title
                shortdesc
                url
                timestamp
            }
        }');
        $this->assertEquals(
            200,
            $result['status']
        );
    }
}
