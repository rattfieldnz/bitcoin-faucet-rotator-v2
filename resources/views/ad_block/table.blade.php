<div class="table-responsive">
<table class="table table-striped bordered tablesorter" id="ad-blocks-table">
    <thead>
        <th>Ad Content</th>
        <th>User Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($adBlocks as $adBlock)
        <tr>
            <td>{!! $adBlock->ad_content !!}</td>
            <td>{!! $adBlock->user_id !!}</td>
            <td>
                {!! Form::open(['route' => ['ad-block.destroy', $adBlock->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('ad-block.show', [$adBlock->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('ad-block.edit', [$adBlock->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>