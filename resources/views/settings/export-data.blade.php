
<div class="row zero-margin">
    <div class="row zero-margin buttons-row">

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Users',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('users.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Faucets',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('faucets.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Payment Processors',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('payment-processors.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-3 col-md-3 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

    </div>
    <div class="row zero-margin buttons-row">

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Main Meta',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('main-meta.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Ad Block',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('ad-block.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-2 col-md-2 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Privacy Policy',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('privacy-policy.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-3 col-md-3 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

    </div>
    <div class="row zero-margin buttons-row">

        {!! Form::button(
            '<i class="fa fa-2x fa-download" style="vertical-align: middle; margin-right:0.25em;"></i>Export Terms and Conditions',
            [
                'type' => 'button',
                'onClick' => "location.href='" . route('terms-and-conditions.export-as-csv') . "'",
                'class' => 'btn btn-primary btn-success col-lg-3 col-md-3 col-sm-3 col-xs-12',
                'style' => 'margin:0.5em 0.5em 0 0; color: white; min-width:12em;'
            ])
        !!}

    </div>
</div>