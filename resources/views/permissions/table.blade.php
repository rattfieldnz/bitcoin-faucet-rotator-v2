<table class="table table-responsive" id="permissions-table">
    <thead>
        <th>Name</th>
        <th>Display Name</th>
        <th>Description</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($permissions as $permission)
        <tr>
            <td>{!! $permission->name !!}</td>
            <td>{!! $permission->display_name !!}</td>
            <td>{!! $permission->description !!}</td>
            <td>
                {!! Form::open(['route' => ['permissions.destroy', $permission->slug], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('permissions.show', [$permission->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('permissions.edit', [$permission->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>