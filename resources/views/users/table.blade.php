<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="users-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true)
                <th>Id</th>
            @endif
        @endif
        <th>User Name</th>
        <th>First Name</th>
        <th>Last Name</th>

        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true)
                <th>Email</th>
                <th>Password</th>
                <th>Bitcoin Address</th>
                <th>Is Admin</th>
            @endif
        @endif
        <th>Slug</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true)
                    <td>{!! $user->id !!}</td>
                @endif
            @endif
            <td>{!! $user->user_name !!}</td>
            <td>{!! $user->first_name !!}</td>
            <td>{!! $user->last_name !!}</td>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true)
                    <td>{!! $user->email !!}</td>
                    <td> ************ </td>
                    <td>{!! $user->bitcoin_address !!}</td>
                    <td>{!! $user->isAnAdmin() !!}</td>
                @endif
            @endif
            <td>{!! $user->slug !!}</td>
            <td>
                <div class='btn-group'>
                    <a href="{!! route('users.show', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true)
                            <a href="{!! route('users.edit', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            {!! Form::open(['route' => ['users.destroy', 'slug' => $user->slug], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            {!! Form::close() !!}
                        @endif
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>