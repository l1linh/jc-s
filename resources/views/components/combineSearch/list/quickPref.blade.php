@props(['prefAxis','isCustomSearch','isDispCount'=>false])

@if($prefAxis)
<span id="pref" class="title">
    <select id="quickSearchPref" title="エリア選択">
        <option value="-1">都道府県</option>
        @foreach($prefAxis As $pref)
            <option class="parent" data-parent="{{$pref->parent}}" value="{{$pref->type}}{{ $isCustomSearch ? '=' : '_' }}{{$pref->value}}">{{$pref->name}} @if($isDispCount && $pref->cnt)({{$pref->cnt}}) @endif</option>
{{--            <option class="parent" value="{{$pref->type}}{{ $isCustomSearch ? '=' : '_' }}{{$pref->value}}">{{$pref->name}} @if($isDispCount && $pref->cnt)({{$pref->cnt}}) @endif</option>--}}
{{--            @if($pref->children)--}}
{{--            @foreach($pref->children As $children)--}}
{{--                <option class="child" value="{{$children->type}}{{ $isCustomSearch ? '=' : '_' }}{{$children->value}}">　{{$children->name}} @if($isDispCount && $children->cnt)({{$children->cnt}}) @endif</option>--}}
{{--            @endforeach--}}
{{--            @endif--}}
        @endforeach
    </select>
</span>
@endif
