@props([
'defaultKey' => '--',
'defaultValue' => '',
'checked' => '',
'list' => [],
'groupList' => [],
'kbnList' => [],
'for',
])


@php
    $radioHtml = '';
    $radioElements = array();

    foreach ($list as $index => $fEntMst) {
        $isChecked = ((isset($checked) && $checked != '' && $index==$checked)) ? " checked " : "";
        $inputTag = '<input type="radio" name="'. $for . '" value="'.$index.'" ' . $isChecked . '/>';
        $spanTag = '<span class = "radio-label">' . $fEntMst . '</span>';
        $radioElements[] = '<label>' .$inputTag . $spanTag . '</label>';
    }

    if(count($radioElements) > 0){
        $radioHtml = implode("", $radioElements);
    }
@endphp

<div class="radio-group">
    {!! $radioHtml ?? '' !!}
</div>
