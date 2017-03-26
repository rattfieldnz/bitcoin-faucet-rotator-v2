<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="faucets-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Url</th>
        <th>Interval Minutes</th>
        <th>Payment Processors</th>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true)
                <th>Has Been Deleted</th>
            @endif
        @endif
        <th>Action</th>
    </thead>
    <tbody>
    @foreach($faucets as $faucet)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true)
                    <td>{!! $faucet->id !!}</td>
                @endif
            @endif
            <td>{!! $faucet->name !!}</td>
            <td>{!! $faucet->url !!}</td>
            <td>{!! $faucet->interval_minutes !!}</td>
            <td>
                <ul>
                @foreach($faucet->paymentProcessors as $p)
                    <li>{{ $p->name }}</li>
                @endforeach
                </ul>
            </td>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true)
                    <td>{!! $faucet->isDeleted() == true ? "Yes" : "No" !!}</td>
                @endif
            @endif
            <td>

                <div class='btn-group'>
                    <a href="{!! route('faucets.show', ['slug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true)
                            <a href="{!! route('faucets.edit', ['slug' => $faucet->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            @if($faucet->isDeleted())
                                {!! Form::open(['route' => ['faucets.delete-permanently', $faucet->slug], 'method' => 'delete']) !!}
                                {!! csrf_field() !!}
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"]) !!}
                                {!! Form::close() !!}
                                {!! Form::open(['route' => ['faucets.restore', $faucet->slug], 'method' => 'patch']) !!}
                                {!! csrf_field() !!}
                                {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"]) !!}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['faucets.destroy', 'slug' => $faucet->slug], 'method' => 'delete']) !!}
                                {!! csrf_field() !!}
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                {!! Form::close() !!}
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