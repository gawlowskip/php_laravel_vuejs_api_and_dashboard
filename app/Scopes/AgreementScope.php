<?php

namespace App\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class AgreementScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $now = Carbon::now()->toDateTimeString();
        if(!request()->isMethod('post')) {
            $builder->where(function($query) use ($now) {
                $query->where('to', '>=', $now);
                $query->orWhere('trial_ends_at', '>=', $now);
            });
        }
    }
}