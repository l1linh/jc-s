@extends('layouts.app')

@section('title', $page->title ?? '')

@section('content')

    <div id="topMvSlider">
        <x-mainVisual />
    </div>

    @if(isset($fEntSearchAxisData))
    <div id="topCombineSearch">
        <x-combineSearch.combineBox :fEntSearchAxisData="$fEntSearchAxisData" :fEntConfig="$page->fEntConfig" />
    </div>
    @endif

    @if($page->fEntConfig->frontendSettings['isDispLatestJob']??null)
        <div id="topLatestJob">
            <x-latestJob.oneLineStyle />
        </div>
    @endif

    <div id="template01" class="topContentBox">
        <x-contentBox.contentOverview />
        <x-contentBox.contentPickup />
        <x-contentBox.contentMessage />
        <x-contentBox.contentJobseeker />
        <x-contentBox.contentAboutus />
        <x-contentBox.contentWay />
    </div>

    @if(isset($fEntSearchAxisData))
    <div id="topSearchBox">
        <x-searchBox :fEntSearchAxisData="$fEntSearchAxisData" :fEntConfig="$page->fEntConfig" />
    </div>
    @endif

    @if($page->fEntConfig->frontendSettings['isDispRecommendJob']??null)
    <div id="topRecommendJob">
        <x-recommendJob.index />
    </div>
    @endif

@endsection
