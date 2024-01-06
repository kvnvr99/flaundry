<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    @php
                        $segments = request()->segments();
                    @endphp
                    @foreach($segments as $index => $segment)
                        @if ($segment != '')
                            @php
                                $url = url(implode('/', array_slice($segments, 0, $index + 1)));
                                $formattedSegment = ucwords(str_replace("-", " ", $segment));
                            @endphp
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $formattedSegment }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </div>
            {{-- <h4 class="page-title">{{ ucwords(str_replace("-"," ",end($segments))) }}</h4> --}}
        </div>
    </div>
</div>
