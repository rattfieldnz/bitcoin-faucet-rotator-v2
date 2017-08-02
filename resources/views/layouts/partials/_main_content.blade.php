<main id="content">
@if (!Auth::guest())
    @include('layouts.partials.content._auth_content')
@else
    @include('layouts.partials.content._guest_content')
@endif
</main>