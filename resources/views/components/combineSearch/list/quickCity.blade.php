@props(['cityAxis','isCustomSearch','isDispCount'=>false])

@if($cityAxis)
<span id="city">
    <select id="quickSearchCity" title="エリア選択" class="locked" disabled>
        <option value="-1">市区町村</option>
        @foreach($cityAxis As $city)
            <option class="parent" data-parent="{{$city->parent}}" value="{{$city->type}}{{ $isCustomSearch ? '=' : '_' }}{{$city->value}}">{{$city->name}} @if($isDispCount && $city->cnt)({{$city->cnt}}) @endif</option>
        @endforeach
    </select>
</span>
@endif
