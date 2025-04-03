@extends('admin.layouts.app')
@section('panel')
@push('style-include')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/theme/admin/css/iconpicker/fontawesome-iconpicker.css')}}">
@endpush
    <main class="main-body">
        <div class="container-fluid px-0 main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <h2>{{ $title }}</h2>
                    <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                        </ol>
                    </nav>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body pt-0">
                    @if($section_type && $section_type != 'type')
                    <form action="{{route('admin.frontend.sections.save.content', ['section_key' => $section_key, 'type' => $section_type])}}" method="POST" enctype="multipart/form-data" id="element_form">
                        @csrf
                        <input type="hidden" name="content_type" value="{{ $section_type }}.element_content">
                    @else
                    <form action="{{route('admin.frontend.sections.save.content', $section_key)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="content_type" value="element_content">
                    @endif
                    @if(@$frontendSectionElement)
                        <input type="hidden" name="id" value="{{$frontendSectionElement->id}}">
                        @php
                            $element_key = explode('.', $frontendSectionElement->section_key)[0];
                        @endphp
                    @endif
                    @foreach($sectionData['element_content'] ?? [] as $key => $item)
                        @if($key === 'item_group')

                            @foreach($item as $group_key => $group_value)
                            @php
                                $isFirstLoopLast = $loop->last;
                                $isFirstLoopEven = $loop->iteration % 2 == 0;
                            @endphp
                                <div class="form-element">
                                    <div class="row gy-4">
                                        <div class="col-xxl-2 col-xl-3">
                                            <h5 class="form-element-title">{{ textFormat(['_'], $group_key, ' ') }}</h5>
                                        </div>
                                        <div class="col-xxl-8 col-xl-9">
                                            <div class="row gy-4">
                                                @foreach($group_value as $group_item_key => $group_item_value)

                                                        @if($group_item_key === 'images')
                                                        @foreach($group_item_value as $group_item_image_key => $group_image_file)

                                                            <div class="{{ !$loop->last && !$loop->iteration % 2 == 0 ? ($isFirstLoopLast && $isFirstLoopEven ? 'col-lg-6' : 'col-lg-12') : 'col-lg-6' }}">
                                                                <div class="form-inner">
                                                                    <label for="{{$group_key.'_'.$group_item_key }}" class="form-label">{{ __(setInputLabel($group_item_key)) }} <sup class="text--danger">*</sup></label>
                                                                    <input type="file" class="form-control" id="{{$group_key.'_'.$group_item_key }}" name="images[{{ @$group_key }}][{{ array_key_first(@$group_item_value) }}]" value="{{ @$frontendSectionElement->section_value[$group_item_key] ?? '' }}" placeholder="{{ __(setInputLabel($group_key.$group_item_key)) }}">

                                                                    @if($frontendSectionElement)
                                                                        <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}
                                                                            <br>
                                                                            <a href="{{showImage(config("setting.file_path.frontend.element_content.$group_key.$element_key.$group_item_image_key.path").'/'.@$frontendSectionElement->section_value[$group_item_image_key],config("setting.file_path.frontend.element_content.$element_key.$group_item_image_key.size"))}}" target="__blank">{{translate('view image')}}</a>
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                        @endforeach
                                                        @else
                                                        <div class="{{ $isFirstLoopLast && !$isFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                            <div class="position-relative">
                                                                <label for="{{ $group_key.$group_item_key }}" class="form-label">{{ __(setInputLabel($group_item_key)) }} <sup class="text--danger">*</sup></label>
                                                                @switch($group_item_value)
                                                                    @case('icon')
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control iconpicker icon" autocomplete="off" name="{{ $group_key }}[{{ $group_item_key }}]" required>
                                                                            <span class="input-group-text input-group-addon" role="iconpicker">{{ translate("Icon") }}</span>
                                                                        </div>
                                                                        @break
                                                                    @case('text')
                                                                        <input type="text" class="form-control" id="{{ $group_key.$group_item_key }}" name="{{ $group_key }}[{{ $group_item_key }}]" value="{{ $frontendSectionElement->section_value[$group_key][$group_item_key] ?? '' }}" placeholder="{{ __(setInputLabel($group_item_key)) }}" required>
                                                                        @break
                                                                    @case('textarea')
                                                                        <textarea class="form-control" id="{{ $group_key.$group_item_key }}" name="{{ $group_key }}[{{ $group_item_key }}]" placeholder="{{ __(setInputLabel($group_item_key)) }}" required>{{ $frontendSectionElement->section_value[$group_key][$group_item_key] ?? '' }}</textarea>
                                                                        @break
                                                                    @case('texteditor')
                                                                        <textarea class="form-control" id="{{ $group_key.$group_item_key }}" name="{{ $group_key }}[{{ $group_item_key }}]" placeholder="{{ __(setInputLabel($group_item_key)) }}" required>{{ $frontendSectionElement->section_value[$group_key][$group_item_key] ?? '' }}</textarea>
                                                                    @break
                                                                @endswitch
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                        <div class="form-element">
                            <div class="row gy-4">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Content") }}</h5>
                                </div>
                                <div class="col-xxl-8 col-xl-9">
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            @if($key === 'images')
                                            @foreach($item as $image_key => $file)
                                                <div class="form-inner">

                                                    <label for="{{ $image_key }}" class="form-label">{{ __(setInputLabel($image_key)) }} <sup class="text--danger">*</sup></label>
                                                    <input type="file" class="form-control" id="{{ $image_key }}" name="images[{{ @$image_key }}]" value="{{ @$frontendSectionElement->section_value[$image_key] ?? '' }}" placeholder="{{ __(setInputLabel($key)) }}">

                                                    @if($frontendSectionElement)
                                                        <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}
                                                            <br>
                                                            <a href="{{showImage(config("setting.file_path.frontend.element_content.$element_key.$image_key.path").'/'.@$frontendSectionElement->section_value[$image_key],config("setting.file_path.frontend.element_content.$element_key.$image_key.size"))}}" target="__blank">{{translate('view image')}}</a>
                                                        </p>
                                                    @endif
                                                </div>
                                            @endforeach
                                                @else
                                            <div class="position-relative">
                                                <label for="{{ $key }}" class="form-label">{{ __(setInputLabel($key)) }} <sup class="text--danger">*</sup></label>
                                                @switch($item)
                                                    @case('icon')
                                                        <div class="input-group">
                                                            <input type="text" class="form-control iconpicker icon" autocomplete="off" name="{{ $key }}" required>
                                                            <span class="input-group-text input-group-addon" role="iconpicker">{{ translate("Icon") }}</span>
                                                        </div>
                                                        @break
                                                    @case('text')
                                                        <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $frontendSectionElement->section_value[$key] ?? '' }}" placeholder="{{ __(setInputLabel($key)) }}" required>
                                                        @break
                                                    @case('textarea')
                                                        <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" placeholder="{{ __(setInputLabel($key)) }}" required>{{ $frontendSectionElement->section_value[$key] ?? '' }}</textarea>
                                                        @break
                                                    @case('texteditor')
                                                        <textarea class="form-control" id="{{ $section_key == "policy-pages" ? "policy_pages" : $item }}" name="{{ $key }}" placeholder="{{ __(setInputLabel($key)) }}" {{ $section_key == "policy-pages" ? "" : 'required' }} >{{ $frontendSectionElement->section_value[$key] ?? '' }}</textarea>
                                                    @break
                                                @endswitch
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    @endforeach

                    <div class="row">
                        <div class="col-xxl-10">
                            <div class="form-action justify-content-end">
                                <button type="reset" class="i-btn btn--danger outline btn--md"> {{ translate("Reset") }} </button>
                                <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script-include')
    <script src="{{ asset('assets/theme/admin/js/iconpicker/fontawesome-iconpicker.js') }}"></script>

@endpush

@push('script-push')
    <script>
        "use strict";
        $(document).ready(function() {
            if ($('.iconpicker').length > 0) {
                const iconPicker = document.querySelector('.iconpicker');

                iconPicker.addEventListener('click', function() {
                    const iconPopover = document.querySelector('.iconpicker-popover');
                    iconPopover.style.display = 'contents';
                });

                $('.iconpicker').iconpicker().on('iconpickerSelected', function(e) {
                    $(this).closest('.input-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
                });
            }
        });


        $(document).ready(function() {

            var isPolicyPage = {{ $section_key == "policy-pages" ? 'true' : 'false' }};

            if (isPolicyPage) {
                ck_editor("#policy_pages");
            }
        });

    </script>
@endpush