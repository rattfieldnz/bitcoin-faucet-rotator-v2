<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="users-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole(['owner', 'administrator']))
                <th>Id</th>
            @endif
        @endif
        <th>User Name</th>

        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole(['owner', 'administrator']))
                <th>Email</th>
                <th>Password</th>
                <th>Is Admin</th>
                <th>Has Been Deleted</th>
            @endif
        @endif
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole(['owner', 'administrator']))
                    <td>{!! $user->id !!}</td>
                @endif
            @endif
            <td>{!! $user->user_name !!}</td>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true && Auth::user()->hasRole(['owner', 'administrator']))
                    <td>{!! $user->email !!}</td>
                    <td> ************ </td>
                    <td>{!! $user->isAnAdmin() !!}</td>
                    <td>{!! $user->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>
                <div class='btn-group'>
                    <a href="{!! route('users.show', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin  == true && Auth::user()->hasRole(['owner', 'administrator']))
                            <a href="{!! route('users.edit', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            @if($user->isDeleted())
                                @if(Auth::user()->hasRole('owner'))
                                    {!! Form::open(['route' => ['users.delete-permanently', $user->slug], 'method' => 'delete']) !!}
                                    {!! csrf_field() !!}
                                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"]) !!}
                                    {!! Form::close() !!}
                                @endif
                                @if(Auth::user()->hasRole('owner') || Auth::user()->hasRole('administrator'))
                                    @if(Auth::user()->hasPermission('restore-users'))
                                        {!! Form::open(['route' => ['users.restore', $user->slug], 'method' => 'patch']) !!}
                                        {!! csrf_field() !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted user?')"]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                @endif
                            @else
                                @if(Auth::user()->hasRole('owner') || Auth::user()->hasRole('administrator'))
                                    @if(Auth::user()->hasPermission('soft-delete-faucets'))
                                        {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete']) !!}
                                        {!! csrf_field() !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>