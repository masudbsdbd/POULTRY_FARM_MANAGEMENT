@if (isset($demo) && $demo == 'creative')

    @vite(['resources/scss/config/creative/bootstrap.scss','resources/scss/config/creative/app.scss'])

@elseif(isset($demo) && $demo == 'modern')

    @vite(['resources/scss/config/modern/bootstrap.scss','resources/scss/config/modern/app.scss'])

@elseif (isset($demo) && $demo == 'material')

    @vite(['resources/scss/config/material/bootstrap.scss','resources/scss/config/material/app.scss'])

@elseif (isset($demo) && $demo == 'purple')

    @vite(['resources/scss/config/purple/bootstrap.scss','resources/scss/config/purple/app.scss'])

@elseif (isset($demo) && $demo == 'saas')

    @vite(['resources/scss/config/saas/bootstrap.scss','resources/scss/config/saas/app.scss'])

@else
    @vite(['resources/scss/config/default/bootstrap.scss', 'resources/scss/config/default/app.scss'])
@endif
