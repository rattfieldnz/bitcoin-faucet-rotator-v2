
<div class="row">
    @if(empty($twitterConfig))
        {!! Form::open(['route' => 'twitter-config.store']) !!}

        @include('twitter_config.fields')

        {!! Form::close() !!}
    @else
        {!! Form::model($twitterConfig, ['route' => ['twitter-config.update', $twitterConfig->id], 'method' => 'patch']) !!}

        @include('twitter_config.fields')

        {!! Form::close() !!}
    @endif
</div>