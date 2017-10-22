
<div class="row">
    @if(empty($adBlock))
        {!! Form::open(['route' => 'ad-block.store']) !!}

        @include('main_meta.fields')

        {!! Form::close() !!}
    @else
        {!! Form::model($adBlock, ['route' => ['ad-block.update', $adBlock->id], 'method' => 'patch']) !!}

        @include('ad_block.fields')

        {!! Form::close() !!}
    @endif
</div>