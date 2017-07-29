<section>

</section>



<!-- User Name Field -->
<div class="form-group">
    {!! Form::label('user_name', 'User Name:') !!} <span id="user_name">{!! $user->user_name !!}</span>
</div>

<!-- First Name Field -->
<div class="form-group">
    {!! Form::label('first_name', 'First Name:') !!} <span id="first_name">{!! $user->first_name !!}</span>
</div>

<!-- Last Name Field -->
<div class="form-group">
    {!! Form::label('last_name', 'Last Name:') !!} <span id="last_name">{!! $user->last_name !!}</span>
</div>

@if(Auth::user() != null)
    @if(Auth::user()->isAnAdmin() || $user == Auth::user())
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('email', 'Email:') !!} <span id="email">{!! $user->email !!}</span>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            {!! Form::label('password', 'Password:') !!} <span id="password">***************</span>
        </div>

        <!-- Is Admin Field -->
        <div class="form-group">
            {!! Form::label('is_admin', 'Is Admin:') !!}  <span id="is_admin">{!! $user->isAnAdmin() == true ? "Yes" : "No" !!}</span>
        </div>
    @endif
    @if(Auth::user()->isAnAdmin())

        <!-- Roles -->
        <div class="form-group">
            {!! Form::label('roles', 'Role:') !!}
            @if(!empty($user))
                <ul>
                    @foreach ($user->roles()->get() as $role)
                        <li>{!! ucfirst($role->name) !!}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Permissions -->
        <div class="form-group">
            {!! Form::label('roles', 'Permissions:') !!}
            @if(count($user->permissions) > 0)
                <ul>
                    @foreach($user->permissions as $permission)
                        <li>{!! $permission->display_name !!}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
@endif

<!-- Bitcoin Address Field -->
<div class="form-group">
    {!! Form::label('bitcoin_address', 'Bitcoin Address:') !!} <span id="bitcoin_address">{!! $user->bitcoin_address !!}</span>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Registered:') !!} <span id="created_at">{!! $user->created_at !!}</span>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Last Updated:') !!} <span id="updated_at">{!! $user->updated_at !!}</span>
</div>