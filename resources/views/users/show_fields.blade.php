@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <!-- Id Field -->
        <div class="form-group">
            {!! Form::label('id', 'Id:') !!}
            <p>{!! $user->id !!}</p>
        </div>
    @endif
@endif

<!-- User Name Field -->
<div class="form-group">
    {!! Form::label('user_name', 'User Name:') !!}
    <p>{!! $user->user_name !!}</p>
</div>

<!-- First Name Field -->
<div class="form-group">
    {!! Form::label('first_name', 'First Name:') !!}
    <p>{!! $user->first_name !!}</p>
</div>

<!-- Last Name Field -->
<div class="form-group">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{!! $user->last_name !!}</p>
</div>

@if(Auth::user() != null)
    @if(Auth::user()->is_admin == true)
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('email', 'Email:') !!}
            <p>{!! $user->email !!}</p>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            {!! Form::label('password', 'Password:') !!}
            <p> ************ </p>
        </div>

        <!-- Bitcoin Address Field -->
        <div class="form-group">
            {!! Form::label('bitcoin_address', 'Bitcoin Address:') !!}
            <p>{!! $user->bitcoin_address !!}</p>
        </div>

        <!-- Is Admin Field -->
        <div class="form-group">
            {!! Form::label('is_admin', 'Is Admin:') !!}
            <p>{!! $user->isAnAdmin() !!}</p>
        </div>

        <!-- Created At Field -->
        <div class="form-group">
            {!! Form::label('created_at', 'Created At:') !!}
            <p>{!! $user->created_at !!}</p>
        </div>

        <!-- Updated At Field -->
        <div class="form-group">
            {!! Form::label('updated_at', 'Updated At:') !!}
            <p>{!! $user->updated_at !!}</p>
        </div>
    @endif
@endif

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{!! $user->slug !!}</p>
</div>

