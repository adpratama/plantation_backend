<?php

namespace App\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        // return parent::userField();
        $name = auth()->user()->name;
        $name_slug = Str::slug($name, '_');
        $role = auth()->user()->role;

        if($role == "superadmin"){
          return 'media';
        }else{
          return 'media/'.$name_slug;
        }
    }
}
