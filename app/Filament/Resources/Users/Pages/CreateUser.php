<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Doctor;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function afterCreate(): void
    {
        $user = $this->record;

        if ($user->hasRole('medico')){
            Doctor::create([
                'user_id' => $user->id,
                'name'=> $user->name,
                'last_name'=> 'N/A',
                'email'=> $user->email,
                'phone'=> '0000000',
            ]);
        }
    }
}

