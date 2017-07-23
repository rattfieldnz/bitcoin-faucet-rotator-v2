<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="users-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->isAnAdmin())
                <th>Id</th>
            @endif
        @endif
        <th>User Name</th>

        @if(Auth::user() != null && Auth::user()->isAnAdmin())
            <th>Role</th>
            <th>Is Admin</th>
            <th>Deleted?</th>
        @endif
        <th>Faucets</th>
        <th>No. of Faucets</th>
        <th>Payment Processors</th>
        @if(Auth::user() != null)
        <th colspan="3">Action</th>
        @endif
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->isAnAdmin())
                    <td>{!! $user->id !!}</td>
                @endif
            @endif
            <td>{!! link_to_route('users.show', $user->user_name, ['slug' => $user->slug]) !!}</td>
            @if(Auth::user() != null && Auth::user()->isAnAdmin())
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
                    <td>{!! $user->isAnAdmin() == true ? "Yes" : "No" !!}</td>
                    <td>{!! $user->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>{!! link_to_route('users.faucets', "View " . $user->user_name . "'s Faucets",['slug' => $user->slug]) !!}</td>
            <td>{{ count($user->faucets()->get()) }}</td>
            <td>{!! link_to_route('users.payment-processors', "View " . $user->user_name . "'s Faucets Grouped by Payment Processors", ['userSlug' => $user->slug]) !!}</td>
            @if(Auth::user() != null)
            <td>
                <div class='btn-group'>
                    @if($user == Auth::user() || Auth::user()->isAnAdmin())
                    <a href="{!! route('users.edit', ['slug' => $user->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    @endif
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
                </div>
            </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>