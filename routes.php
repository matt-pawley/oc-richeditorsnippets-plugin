<?php
use RainLab\Pages\Classes\SnippetManager;
use Cms\Classes\Theme;

Route::get('/toughdeveloper/snippets/list.js', function () {
    $user = BackendAuth::getUser();

    if (!BackendAuth::getUser() || !BackendAuth::getUser()->hasAccess('rainlab.pages.access_snippets'))
    {
        return response('Forbidden', 401);
    }

    $snippetManager = SnippetManager::instance();
    $theme = Theme::getActiveTheme();
    $snippets = $snippetManager->listSnippets($theme);

    // Transform to a collection, set the data we need and orgnaise with array keys.
    $snippets = collect($snippets)
        ->transform(function($item, $key){
            return [
                'component'     => $item->getComponentClass(),
                'snippet'       => $item->code,
                'name'          => $item->getName()
            ];
        })
        ->keyBy('snippet');



    return '$.oc.snippets = ' . $snippets;
});
