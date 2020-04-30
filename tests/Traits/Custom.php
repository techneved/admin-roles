<?php
namespace Techneved\Admin\Login\Tests\Traits;

use Illuminate\Support\Arr;
use Illuminate\Testing\Assert;

trait Custom
{
    /** 
     * Exact Json Structure
     * 
     * @param array $structure
     *
     * @param array $response
     * 
     * @param string $responseKey
     * 
     * @return $this
     **/ 
    public function assertExactJsonStructure(array $structure, $response, $responseKey)
    {
        $actualResponse = array_keys( Arr::sortRecursive(
            (array) $response->decodeResponseJson()[$responseKey]
        ));

        $actualStructure = Arr::sortRecursive(
            $structure
        );

        if (count($actualStructure) < count($actualResponse)) {
            
            $actualResponse = $actualStructure;
            $actualStructure =  array_keys(Arr::sortRecursive(
                (array) $response->decodeResponseJson()[$responseKey]
            ));
        }

        foreach($actualStructure as $key) {

            Assert::assertContains($key, $actualResponse);
        }

        return $this;
    }
}
