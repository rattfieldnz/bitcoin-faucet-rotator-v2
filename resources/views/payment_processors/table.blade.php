<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="payment-processors-table">
    <thead>
        @if(Auth::user() != null)
            @if(Auth::user()->is_admin == true)
                <th>Id</th>
            @endif
        @endif
        <th>Name</th>
        <th>Url</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($paymentProcessors as $paymentProcessor)
        <tr>
            @if(Auth::user() != null)
                @if(Auth::user()->is_admin == true)
                    <td>{!! $paymentProcessor->id !!}</td>
                @endif
            @endif
            <td>{!! $paymentProcessor->name !!}</td>
            <td>{!! $paymentProcessor->url !!}</td>
            <td>

                <div class='btn-group'>
                    <a href="{!! route('payment-processors.show', ['slug' => $paymentProcessor->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true)
                            <a href="{!! route('payment-processors.edit', ['slug' => $paymentProcessor->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            @if($paymentProcessor->isDeleted())
                                {!! Form::open(['route' => ['payment-processors.delete-permanently', $paymentProcessor->slug], 'method' => 'delete']) !!}
                                {!! csrf_field() !!}
                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure? The payment processor will be PERMANENTLY deleted!')"]) !!}
                                {!! Form::close() !!}
                                {!! Form::open(['route' => ['payment-processors.restore', $paymentProcessor->slug], 'method' => 'patch']) !!}
                                {!! csrf_field() !!}
                                {!! Form::button('<i class="glyphicon glyphicon-refresh"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-xs', 'onclick' => "return confirm('Are you sure you want to restore this deleted payment processor?')"]) !!}
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['route' => ['payment-processors.destroy', 'slug' => $paymentProcessor->slug], 'method' => 'delete']) !!}
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