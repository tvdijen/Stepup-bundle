<?php

namespace Surfnet\Stepup\Tests\Helper;

use PHPUnit_Framework_TestCase as TestCase;
use Surfnet\StepupBundle\Exception\InvalidArgumentException;
use Surfnet\StepupBundle\Exception\JsonException;
use Surfnet\StepupBundle\Http\JsonHelper;

class JsonHelperTest extends TestCase
{
    /**
     * @test
     * @group json
     *
     * @dataProvider nonStringProvider
     * @param $nonString
     */
    public function json_helper_can_only_decode_strings($nonString)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        JsonHelper::decode($nonString);
    }

    /**
     * @test
     * @group json
     */
    public function json_helper_decodes_strings_to_arrays()
    {
        $expectedDecodedResult = ['hello' => 'world'];
        $json                  = '{ "hello" : "world" }';
        $actualDecodedResult = JsonHelper::decode($json);
        $this->assertSame($expectedDecodedResult, $actualDecodedResult);
    }

    /**
     * @test
     * @group json
     */
    public function json_helper_throws_an_exception_when_there_is_a_syntax_error()
    {
        $this->setExpectedException(JsonException::class, 'Syntax error');
        $jsonWithMissingDoubleQuotes = '{ hello : world }';
        JsonHelper::decode($jsonWithMissingDoubleQuotes);
    }

    public function nonStringProvider()
    {
        return [
            'null'    => [null],
            'boolean' => [true],
            'array'   => [[]],
            'integer' => [1],
            'float'   => [1.2],
            'object'  => [new \StdClass()],
        ];
    }
}
