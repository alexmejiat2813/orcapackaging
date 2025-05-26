<?php

namespace App\Routes\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AutoSettingsRouter
{
    public static function register()
    {
        
        $baseNamespace = 'App\\Http\\Controllers\\Settings';
        $basePath = app_path('Http/Controllers/Settings');

        foreach (File::allFiles($basePath) as $file) {
            if (Str::endsWith($file->getFilename(), 'Controller.php')) {
                $relativePath = Str::replaceFirst($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativeClass = str_replace(['/', '\\', '.php'], ['\\', '\\', ''], $relativePath);
                $fullClass = $baseNamespace . '\\' . $relativeClass;

                $segments = collect(explode('\\', $relativeClass))
                    ->map(fn($seg) => Str::kebab(Str::replaceLast('Controller', '', $seg)))
                    ->toArray();

                $url = 'settings/' . implode('/', $segments);
                $routeName = 'settings.' . implode('.', $segments);
                //dump($fullClass, $url, $routeName);
                Route::get($url, [$fullClass, 'index'])
                    ->name($routeName);
                
            }
        }
    }
}
