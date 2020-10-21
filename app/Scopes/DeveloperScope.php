<?php

namespace App\Scopes;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class DeveloperScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(!request()->isMethod('post')) {
            $builder->has('properties')
                ->where('active', User::ACTIVE)
                ->orHas('ads')
                ->where('active', User::ACTIVE)
                ->orHas('activeAgreements')
                ->where('active', User::ACTIVE);
        }
    }
}