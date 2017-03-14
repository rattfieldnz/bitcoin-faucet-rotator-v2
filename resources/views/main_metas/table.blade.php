<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="mainMetas-table">
    <thead>
        <th>Title</th>
        <th>Description</th>
        <th>Keywords</th>
        <th>Google Analytics Id</th>
        <th>Yandex Verification</th>
        <th>Bing Verification</th>
        <th>Page Main Title</th>
        <th>Page Main Content</th>
        <th>Addthisid</th>
        <th>Twitter Username</th>
        <th>Feedburner Feed Url</th>
        <th>Disqus Shortname</th>
        <th>Prevent Adblock Blocking</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($mainMetas as $mainMeta)
        <tr>
            <td>{!! $mainMeta->title !!}</td>
            <td>{!! $mainMeta->description !!}</td>
            <td>{!! $mainMeta->keywords !!}</td>
            <td>{!! $mainMeta->google_analytics_id !!}</td>
            <td>{!! $mainMeta->yandex_verification !!}</td>
            <td>{!! $mainMeta->bing_verification !!}</td>
            <td>{!! $mainMeta->page_main_title !!}</td>
            <td>{!! $mainMeta->page_main_content !!}</td>
            <td>{!! $mainMeta->addthisid !!}</td>
            <td>{!! $mainMeta->twitter_username !!}</td>
            <td>{!! $mainMeta->feedburner_feed_url !!}</td>
            <td>{!! $mainMeta->disqus_shortname !!}</td>
            <td>{!! $mainMeta->prevent_adblock_blocking !!}</td>
            <td>
                {!! Form::open(['route' => ['main-metas.destroy', $mainMeta->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('main-metas.show', [$mainMeta->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('main-metas.edit', [$mainMeta->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>