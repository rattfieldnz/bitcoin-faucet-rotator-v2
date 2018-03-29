@php
$alert = \App\Models\Alert::where('hide_alert', '==', 1)->first();
@endphp

@if(!empty($alert))

<section id="sitewide-alert-content" class="content-header">
    @php
        $alertType = $alert->alertType()->first();
        $alertClass = $alertType->bootstrap_alert_class;
        $alertIcon = $alert->alertIcon()->first();
    @endphp

    <p>
        <strong>
        <i class="fa fa-2x {!!  $alertIcon->icon_class !!} alert {!! str_replace('.', '', $alertClass) !!} alert-custom-icon" title="{!! str_replace('fa-', '', $alertIcon->icon_class) !!} ({!! $alertType->name !!})"></i>
        {!! $alert->summary !!}
        <a href="{!! route('alerts.show', [$alert->slug]) !!}" title="{!! $alert->title !!}">Read more</a>...
        </strong>
    </p>
</section>
@endif