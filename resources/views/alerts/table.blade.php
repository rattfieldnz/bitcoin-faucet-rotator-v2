<table class="table table-responsive" id="alerts-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Summary</th>
            <th>Alert Type Id</th>
            <th>Alert Icon Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($alerts as $alert)
        <tr>
            <td>{!! $alert->title !!}</td>
            <td>{!! $alert->summary !!}</td>
            <td>{!! $alert->alert_type_id !!}</td>
            <td>{!! $alert->alert_icon_id !!}</td>
            <td>
                {!! Form::open(['route' => ['alerts.delete-permanently', $alert->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('alerts.show', [$alert->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('alerts.edit', [$alert->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>