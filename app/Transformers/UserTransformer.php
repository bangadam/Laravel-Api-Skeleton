<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function transform(User $d)
    {
        return [
            'id'    => (int) $d->id,
            'name'  => $d->name,
            'email' => $d->email
        ];
    }

    // public function includeTransporter(User $d)
    // {
    //     $transporter = $d->transporter;
    //     if ($transporter != null) {
    //         return $this->item($transporter, new TransporterTransformer, 'transporters');
    //     }
    // }
}
