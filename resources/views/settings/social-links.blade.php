<div class="row">

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