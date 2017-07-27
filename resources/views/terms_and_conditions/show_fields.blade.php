{!! $termsAndConditions->content !!}
<hr>
<p>These were created on {{ date('l jS \of F Y\, \a\t H:i:s A T', strtotime($termsAndConditions->created_at)) }}.</p>
<p>These were last updated on {{ date('l jS \of F Y\, \a\t H:i:s A T', strtotime($termsAndConditions->updated_at)) }}.</p>