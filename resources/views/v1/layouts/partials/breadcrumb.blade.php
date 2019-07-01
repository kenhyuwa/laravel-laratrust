@if (Route::current()->uri() != '/')
<section class="content-header" style="display: list-item;">
    <ol class="breadcrumb">
        @php
            $path = explode(".", Route::current()->getName());
        @endphp
        @if (count($path) > 1)
            <li><a href="{{ url('/') }}"><i class="fa fa-windows"></i> Dashboard</a></li>
            @for ($i = 0; $i < count($path); $i++)
                @if ($i == count($path) - 1)
                    <li class="active">{{ title_case($path[$i]) }}</li>
                @else 
                    <li><a href="{{ url(str_slug($path[$i], '-')) }}">{{ title_case($path[$i]) }}</a></li>
                @endif
            @endfor
        @else 
            <li class="active"><i class="fa fa-windows"></i> Dashboard</li>
        @endif
    </ol>
</section>
@endif