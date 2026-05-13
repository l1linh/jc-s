@props(['fEntSearchAxisData'])

@if(is_array($fEntSearchAxisData->fEntSearchAxis->jobbc) && count($fEntSearchAxisData->fEntSearchAxis->jobbc) > 0)

<div class="childrenBox">
    <ul>
        @foreach($fEntSearchAxisData->fEntSearchAxis->jobbc As $jobGroupAxis)
        <li class="parent">
            <ul class="parentInfo">
                <li class="parentName">{{$jobGroupAxis->name}}</li>
                <li class="title">
                    <label for="jmod2-{{$jobGroupAxis->value}}-modal-search">
                        <input type="checkbox" name="modal-jobGroupCheckboxSearch" class="jmod-jobcheck jobOverlayLabel" value="{{$jobGroupAxis->value}}" id="jmod2-{{$jobGroupAxis->value}}-modal-search">
                        全て選択
                    </label>
                </li>
            </ul>
            <ul>
                @if(count($jobGroupAxis->children) > 0)
                @foreach($jobGroupAxis->children As $childrenAxis)
                <li class="child" data-id="{{$jobGroupAxis->value}}-{{$childrenAxis->value}}">
                    <label for="jmod2-{{$jobGroupAxis->value}}-{{$childrenAxis->value}}-modal-search">
                        <input type="checkbox" name="modal-jobGroupCheckboxSearch" class="jmod-jobcheck jobOverlayLabel" value="{{$childrenAxis->value}}" id="jmod2-{{$jobGroupAxis->value}}-{{$childrenAxis->value}}-modal-search">
                        {{$childrenAxis->name}}
                    </label>
                </li>
                @endforeach
                @endif
            </ul>
        </li>
        @endforeach
    </ul>
</div>
@endif
