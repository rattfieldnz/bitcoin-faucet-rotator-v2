<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="users-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
                <th>Id</th>
            @endif
        @endif
        <th>User Name</th>

        @if(Auth::user() != null)
            <th>Role</th>
        @endif

        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true && Auth::user()->hasRole('owner'))
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
                @if(Auth::user()->isAnAdmin() == true)
                    <td>{!! $user->id !!}</td>
                @endif
            @endif
            @if(Auth::guest() || !Auth::user()->isAnAdmin())
                <td>{!! $user->userName() !!}</td>
            @else
                <td>{!! $user->user_name !!}</td>
            @endif
            @if(Auth::user() != null)
                <td>
                    <ul>
                        @foreach ($user->roles()->get() as $role)
                            <li>{!! ucfirst($role->name) !!}</li>
                        @endforeach
                    </ul>
                </td>
            @endif
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <td>{!! $user->email !!}</td>
                    <td> ************ </td>
                    <td>{!! $user->isAnAdmin() == true ? "Yes" : "No" !!}</td>
                    <td>{!! $user->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>
                <div class='btn-group'>

                    @if(Auth::user() != null)
                        @if(Auth::user()->isAnAdmin())
                            <a href="{!! route('users.show', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        @else
                            <a href="{!! route('users.show', ['slug' => $user->userSlug()]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        @endif
                    @else
                        <a href="{!! route('users.show', ['slug' => $user->userSlug()]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @endif
                    @if(Auth::user() != null)
                        @if(Auth::user()->isAnAdmin() || Auth::user() == $user)
                            <a href="{!! route('users.edit', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            @if(Auth::user()->isAnAdmin())
                                @if(!$user->isAnAdmin())
                                    @if($user->isDeleted())
                                        {!! Form::open(['route' => ['users.delete-permanently', $user->slug], 'method' => 'delete']) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"]) !!}
                                        {!! Form::close() !!}
                                        {!! Form::open(['route' => ['users.restore', $user->slug], 'method' => 'patch']) !!}
                                        {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted user?')"]) !!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete']) !!}
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