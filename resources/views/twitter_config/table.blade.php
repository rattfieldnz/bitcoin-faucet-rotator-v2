<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="twitterConfigs-table">
    <thead>
        <th>Consumer Key</th>
        <th>Consumer Key Secret</th>
        <th>Access Token</th>
        <th>Access Token Secret</th>
        <th>User Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($twitterConfigs as $twitterConfig)
        <tr>
            <td>{!! $twitterConfig->consumer_key !!}</td>
            <td>{!! $twitterConfig->consumer_key_secret !!}</td>
            <td>{!! $twitterConfig->access_token !!}</td>
            <td>{!! $twitterConfig->access_token_secret !!}</td>
            <td>{!! $twitterConfig->user_id !!}</td>
            <td>
                {!! Form::open(['route' => ['twitter-config.destroy', $twitterConfig->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('twitter-config.show', [$twitterConfig->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('twitter-config.edit', [$twitterConfig->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>