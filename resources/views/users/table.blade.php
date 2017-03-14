<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="users-table">
    <thead>
        <th>User Name</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Bitcoin Address</th>
        <th>Is Admin</th>
        <th>Slug</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{!! $user->user_name !!}</td>
            <td>{!! $user->first_name !!}</td>
            <td>{!! $user->last_name !!}</td>
            <td>{!! $user->email !!}</td>
            <td> ************ </td>
            <td>{!! $user->bitcoin_address !!}</td>
            <td>{!! $user->isAnAdmin() !!}</td>
            <td>{!! $user->slug !!}</td>
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->slug], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('users.show', [$user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('users.edit', [$user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>