<?php

namespace App\Models;

use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Base\Resource\DomainResource\Practitioner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    public function practitioner(): ?Practitioner
    {
        if ($this->authable_type !== Practitioner::class) {
            return null;
        }

        return Practitioner::query()->where('id', $this->authable_id)->first();
    }

    public function patient(): ?Patient
    {
        if ($this->authable_type !== Patient::class) {
            return null;
        }

        return Patient::query()->where('id', $this->authable_id)->first();
    }
}
