@props([
'defaultKey' => '--',
'defaultValue' => '',
'checkedList' => '',
'list' => [],
'groupList' => [],
'kbnList' => [],
'for',
])


@php
    $checkBoxHtml = '';
    $checkBoxElements = array();

    foreach ($list as $index => $fEntMst) {
        $isChecked = in_array($index, $checkedList);
        $inputTag = '<input type="checkbox" name="'. $for . '[]" value="'.$index.'" ' . ($isChecked ? 'checked': '') . '/>';
        $spanTag = '<span class="checkboxLabelText">' . $fEntMst . '</span>';
        $checkBoxElements[] = '<label>' .$inputTag . $spanTag . '</label>';
    }

    if(count($checkBoxElements) > 0){
        $checkBoxHtml = implode("", $checkBoxElements);
    }
@endphp

<div class="checkbox-group">
    {!! $checkBoxHtml ?? '' !!}
</div>
