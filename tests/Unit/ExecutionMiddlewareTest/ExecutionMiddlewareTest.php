<?php

declare(strict_types = 1);
namespace Rebing\GraphQL\Tests\Unit\ExecutionMiddlewareTest;

use Rebing\GraphQL\Tests\TestCase;

class ExecutionMiddlewareTest extends TestCase
{
    public function testMiddlewareCanReturnResponse(): void
    {
        $this->app['config']->set('graphql.execution_middleware', [
            CacheMiddleware::class,
        ]);

        $result = $this->httpGraphql($this->queries['examplesWithVariables'], [
            'variables' => [
                'index' => 1,
            ],
        ]);

        $expected = [
            'data' => [
                'examples' => [
                    [
                        'test' => 'Cached response',
                    ],
                ],
            ],
        ];
        self::assertSame($expected, $result);
    }

    public function testMiddlewareCanMutateArgs(): void
    {
        $this->app['config']->set('graphql.execution_middleware', [
            ChangeVariableMiddleware::class,
        ]);

        $result = $this->httpGraphql($this->queries['examplesWithVariables'], [
            'variables' => [
                'index' => '1',
            ],
        ]);

        $expected = [
            'data' => [
                'examples' => [
                    [
                        'test' => 'Example 2',
                    ],
                ],
            ],
        ];
        self::assertSame($expected, $result);
    }

    public function testMiddlewareCanMutateQueryAndSendParsedQueryAlong(): void
    {
        $this->app['config']->set('graphql.execution_middleware', [
            ChangeQueryArgTypeMiddleware::class,
        ]);

        $result = $this->httpGraphql($this->queries['examplesWithWrongTypeOfArgument'], [
            'variables' => [
                'indexVariable' => 1,
            ],
        ]);

        $expected = [
            'data' => [
                'examples' => [
                    [
                        'test' => 'Example 2',
                    ],
                ],
            ],
        ];
        self::assertSame($expected, $result);
    }
}
