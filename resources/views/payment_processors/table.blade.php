<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="payment-processors-table">
    <thead>
        <th>Name</th>
        <th>Url</th>
        <th>Slug</th>
        <th>Meta Title</th>
        <th>Meta Description</th>
        <th>Meta Keywords</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($paymentProcessors as $paymentProcessor)
        <tr>
            <td>{!! $paymentProcessor->name !!}</td>
            <td>{!! $paymentProcessor->url !!}</td>
            <td>{!! $paymentProcessor->slug !!}</td>
            <td>{!! $paymentProcessor->meta_title !!}</td>
            <td>{!! $paymentProcessor->meta_description !!}</td>
            <td>{!! $paymentProcessor->meta_keywords !!}</td>
            <td>
                <div class='btn-group'>
                    <a href="{!! route('payment-processors.show', [$paymentProcessor->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @if(Auth::user() != null)
                        @if(Auth::user()->is_admin == true)
                            <a href="{!! route('payment-processors.edit', [$paymentProcessor->slug]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            {!! Form::open(['route' => ['payment-processors.destroy', $paymentProcessor->slug], 'method' => 'delete']) !!}
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