@extends('layouts.app')

@section('title', $page->title ?? '')

@section('content')

    <div id="contentArea">

        {{--html埋め込み部分 start--}}

        <main id="main_wrap">

          <link rel="stylesheet" media="all" href="./css/message.css"/>
          <script type="text/javascript" src="./js/message.js"></script>

          <div id="message" class="message">
          </div>
        </main>

        {{--html埋め込み部分 end--}}

    </div>

    <div id="contentLinkBoxAbout">
        <x-linkBoxAbout />
    </div>

@endsection
