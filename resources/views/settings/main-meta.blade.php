
<div class="row">
@if(empty($mainMeta))
    {!! Form::open(['route' => 'main-meta.store']) !!}

    @include('main_meta.fields')

    {!! Form::close() !!}
@else
    {!! Form::model($mainMeta, ['route' => ['main-meta.update', $mainMeta->id], 'method' => 'patch']) !!}

    @include('main_meta.fields')

    {!! Form::close() !!}
@endif
</div>