<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

public function boot()
{
    parent::boot();

    Route::macro('autoSettingsRoutes', function () {
        $baseNamespace = 'App\\Http\\Controllers\\Settings';
        $basePath = app_path('Http/Controllers/Settings');

        foreach (File::allFiles($basePath) as $file) {
            if (Str::endsWith($file->getFilename(), 'Controller.php')) {
                $relativePath = Str::replaceFirst($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativeClass = str_replace(['/', '.php'], ['\\', ''], $relativePath);
                $fullClass = $baseNamespace . '\\' . $relativeClass;

                $segments = collect(explode('\\', $relativeClass))
                    ->map(fn($seg) => Str::kebab(Str::replaceLast('Controller', '', $seg)))
                    ->toArray();

                $url = 'settings/' . implode('/', $segments);
                $routeName = 'settings.' . implode('.', $segments);

                Route::get($url, [$fullClass, 'index'])->name($routeName);
            }
        }
    });
}
