<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.head')

<body id="{{$page->id ?? ''}}">
    <div id="{{$page->id ?? ''}}Layout">
        <section class="container">
            @php
            $isSkipLayout = false;
            /**@var $page \App\Models\FEnt\FEntPage **/
            $independentLayoutList = env('VITE_INDEPENDENT_LAYOUT_LIST') ?? "";
            $arrayTargetId = explode(",", $independentLayoutList);
            foreach($arrayTargetId As $targetId) {
                if($targetId !== "" &&  str_starts_with($page->id, $targetId)) {
                    $isSkipLayout = true;
                    break;
                }
            }
            @endphp

            @if($isSkipLayout !== true)
            <div id="{{$page->id ?? ''}}Nav">
                @include('layouts.header')
            </div>
            @endif

                @yield('content')

            @if($isSkipLayout !== true)
                @include('layouts.footer')
            @endif

        </section>
    </div>

</body>
</html>
