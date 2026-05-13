@extends('layouts.app')

@section('title', $page->title ?? '')

@section('content')

    <div id="contentArea">

        {{--html埋め込み部分 start--}}

        <main id="main_wrap">

          <link rel="stylesheet" media="all" href="./css/step.css"/>
          <script type="text/javascript" src="./js/step.js"></script>

          <div id="step" class="step">
          </div>
        </main>

        {{--html埋め込み部分 end--}}

    </div>

    <div id="contentLinkBoxAbout">
        <x-linkBoxAbout />
    </div>

@endsection
