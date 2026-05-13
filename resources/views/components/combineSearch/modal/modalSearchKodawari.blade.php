@props(['fEntSearchAxisData'])

@php
    $isSetParent = true;
@endphp

@if(is_array($fEntSearchAxisData->fEntSearchAxis->kodawari) && count($fEntSearchAxisData->fEntSearchAxis->kodawari) > 0)

    @php
        if($isSetParent === true) {
            $arrrayKodawariAxis = array();
            foreach($fEntSearchAxisData->fEntSearchAxis->kodawari As $kodawariAxis) {
                if($kodawariAxis->parent) {
                    $arrrayKodawariAxis[$kodawariAxis->parent][] = $kodawariAxis; //親要素毎に再配置
                }
            }

            $kodawariGroupList = array(
                '1' => '人気の特徴',
                '2' => '勤務形態',
                '3' => '福利厚生・待遇',
            );
        }
    @endphp

<div class="childrenBox">
    <ul>
        @if($isSetParent == true && count($arrrayKodawariAxis) > 0)
        @foreach($arrrayKodawariAxis As $parentIndex => $kodawariAxisList)
        <li class="parent">
            <ul class="parentInfo">
                <li class="parentName">{{$kodawariGroupList[$parentIndex] ?? ''}}</li>
                <li class="title">
                    <label for="jmod5-{{$parentIndex}}-modal-search">
                        <input type="checkbox" name="modal-kodawariCheckboxSearch" class="jmod-areacheck areaOverlayLabel" value="{{$parentIndex}}" id="jmod5-{{$parentIndex}}-modal-search">
                        全て選択
                    </label>
                </li>
            </ul>
            <ul>
                @if(count($kodawariAxisList) > 0)
                @foreach($kodawariAxisList As $kodawariIndex => $kodawariAxis)
                <li class="child" data-id="{{$parentIndex}}">
                    <label for="jmod5-{{$parentIndex}}-{{$kodawariIndex}}-modal-search">
                        <input type="checkbox" name="modal-kodawariCheckboxSearch" class="jmod-areacheck areaOverlayLabel" value="{{$kodawariAxis->value}}" id="jmod5-{{$parentIndex}}-{{$kodawariIndex}}-modal-search">
                        {{$kodawariAxis->name}}
                    </label>
                </li>
                @endforeach
                @endif
            </ul>
        </li>
        @endforeach
        @endif
    </ul>
</div>
@endif
