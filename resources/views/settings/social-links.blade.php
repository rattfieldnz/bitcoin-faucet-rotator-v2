<div class="row">
    <p>These social links are displayed in the bottom of every single page. They are also listed in the main navigations for both logged-in and guest visitors.</p>
    @if(empty($socialLinks))
        {!! Form::open(['route' => 'social-networks.store']) !!}

        @include('social_networks.fields')

        {!! Form::close() !!}
    @else
        {!! Form::model($socialLinks, ['route' => ['social-networks.update', $socialLinks->id], 'method' => 'patch']) !!}

        @include('social_networks.fields')

        {!! Form::close() !!}
    @endif
</div>