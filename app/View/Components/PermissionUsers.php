<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class PermissionUsers extends Component
{
    public array $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function shouldRender(): bool
    {
        $fonction = Auth::user()?->fonction?->Fonction_Desc;
        return in_array($fonction, $this->allowedRoles);
    }

    public function render()
    {
        return function ($data) {
            return $data['slot']->toHtml();
        };
    }
}
