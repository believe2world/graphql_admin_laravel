<?php

declare(strict_types = 1);
namespace Rebing\GraphQL\Tests\Support\Queries;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Tests\Support\Models\Post;

class PostWithSelectFieldsAndModelQuery extends Query
{
    protected $attributes = [
        'name' => 'postWithSelectFieldsAndModel',
    ];

    public function type(): Type
    {
        return GraphQL::type('PostWithModel');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Post::select($getSelectFields()->getSelect())
            ->findOrFail($args['id']);
    }
}
