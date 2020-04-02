<ul class="breadcrumb">
    <li>
        <i class="fa fa-home"></i>
        <a href="{{ URL::to('/') }}">Home</a>
    </li>

    @php
        $bread = URL::to('/');
        $link = Request::path();
        $subs = explode("/", $link)
    @endphp

    @if (Request::path() != '/')

        @for($i = 0; $i < count($subs); $i++)

            @php
                $bread = $bread."/".$subs[$i];
                $title = urldecode($subs[$i]);
                $title = str_replace("-", " ", $title);
                $title = title_case($title)
            @endphp

            <li>
            <a href="{{ $bread }}"><span>{{ $title }}</span></a>
            </li>

        @endfor

    @endif
</ul>