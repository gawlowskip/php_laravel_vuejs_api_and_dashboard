<?php

namespace App\Scopes;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class AdScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(!request()->isMethod('post')) {
            $builder->whereHas('developer', function($q) {
                $q->where('active', User::ACTIVE);
            });
        }
    }
}