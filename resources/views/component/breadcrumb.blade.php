<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    @php
                        $segment = [Request::segment(1), Request::segment(2), Request::segment(3), Request::segment(4)]
                    @endphp
                    @if ($segment[0] != '')
                        <li class="breadcrumb-item"><a href="{{ url($segment[0]) }}">{{ ucwords(str_replace("-"," ",$segment[0])) }}</a></li>
                    @endif
                    @if ($segment[1] != '')
                        <li class="breadcrumb-item"><a href="{{ url($segment[0].'/'.$segment[1]) }}">{{ ucwords(str_replace("-"," ",$segment[1])) }}</a></li>
                    @endif
                    @if ($segment[2] != '')
                        <li class="breadcrumb-item"><a href="{{ url($segment[0].'/'.$segment[1].'/'.$segment[2]) }}">{{ ucwords(str_replace("-"," ",$segment[2])) }}</a></li>
                    @endif
                </ol>
            </div>
            {{-- <h4 class="page-title">{{ ucwords(str_replace("-"," ",Request::segment(count(request()->segments())))) }}</h4> --}}
        </div>
    </div>
</div>
