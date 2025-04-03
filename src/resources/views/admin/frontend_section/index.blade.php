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
                        <li class="breadcrumb-item">
                            <a>{{ textFormat(['-'], $section_key, ' ') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                    </ol>
                </nav>

            </div>
        </div>
      </div>
      
     
      <div class="pill-tab mb-4">
        <ul class="nav" role="tablist">
            @if(isset($sectionData['fixed_content']))
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#fixed_content" role="tab" aria-selected='true'>
                        <i class="ri-information-line"></i> {{ translate("Static Content") }} </a>
                </li>
            @endif
            @if(isset($sectionData['multiple_static_content']))
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{!isset($sectionData['fixed_content']) ? 'active' : '' }}" data-bs-toggle="tab" href="#multiple_static_content" role="tab" aria-selected='false' tabindex='-1'>
                    <i class="ri-information-line"></i> {{ translate("Multiple Static Content") }} </a>
                </li>
            @endif
            @if(isset($sectionData['element_content']))
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ !isset($sectionData['fixed_content']) && !isset($sectionData['multiple_static_content']) ? 'active' : '' }}" data-bs-toggle="tab" href="#element_content" role="tab" aria-selected='false' tabindex='-1'>
                    <i class="ri-information-line"></i> {{ translate("Element Content") }} </a>
                </li>
            @endif
        </ul>
      </div>
      <div class="tab-content">
        @if(isset($sectionData['fixed_content']))
        <div class="tab-pane active fade show" id="fixed_content" role="tabpanel">
            <div class="card">
                <div class="card-body pt-0">
                    
                    @if($section_type)
                        <form action="{{route('admin.frontend.sections.save.content', ['section_key' => $section_key, 'type' => $section_type])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="content_type" value="{{ $section_type }}.fixed_content">
                            @if($sectionFixedContent->id)
                                <input type="hidden" name="id" value="{{$sectionFixedContent->id}}">
                            @endif
                            
                    @else
                        <form action="{{route('admin.frontend.sections.save.content', $section_key)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="content_type" value="fixed_content">
                            <input type="hidden" name="id" value="{{@$sectionFixedContent?->id}}">
                    @endif

                        <div class="form-element">
                            <div class="row gy-3">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ translate("Static Elements") }}</h5>
                                </div>
                                <div class="col-xxl-7 col-xl-9">
                                    <div class="row gy-4 align-items-end">
                                        @foreach($sectionData['fixed_content'] as $key => $item)
                                        @php
                                            $isFirstLoopLast = $loop->last;
                                            $isFirstLoopEven = $loop->iteration % 2 == 0;

                                        @endphp
                                        @if($key === 'images')
                                        @foreach($item as $image_key => $file)
                                            <div class="{{ $loop->last && !$loop->iteration % 2 == 0 ? ($isFirstLoopLast && $isFirstLoopEven ? 'col-lg-6' : 'col-lg-12') : 'col-lg-6' }}">
                                                <div class="form-inner">
                                                    <label for="{{ $image_key }}" class="form-label">{{ __(setInputLabel($image_key)) }} <sup>*</sup></label>
                                                    <input type="file" class="form-control" id="{{ $image_key }}" name="images[{{ @$image_key }}]" value="{{ @$sectionFixedContent->section_value[$image_key] ?? '' }}" placeholder="{{ __(setInputLabel($key)) }}"/>
                                                </div>
                                            </div>
                                        @endforeach
                                        @else
                                        @switch($item)
                                        @case('icon')
                                            <div class="col-lg-12">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control iconpicker icon" autocomplete="off" name="{{ $key }}" required value="{{ $sectionFixedContent->section_value[$key] ?? '' }}">
                                                        <span class="input-group-text input-group-addon" role="iconpicker"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('text')
                                            <div class="{{ $isFirstLoopLast && !$isFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $sectionFixedContent->section_value[$key] ?? '' }}" placeholder="{{ __(setInputLabel($key)) }}" required>
                                                </div>
                                            </div>
                                            @break
                                        @case('textarea')
                                            <div class="{{ $isFirstLoopLast && !$isFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" placeholder="{{ __(setInputLabel($key)) }}" required>{{ $sectionFixedContent->section_value[$key] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            @break
                                        @endswitch
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-9">
                                <div class="form-action justify-content-end">
                                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Update") }} </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if(isset($sectionData['multiple_static_content']))
        <div class="tab-pane fade {{!isset($sectionData['fixed_content']) ? 'active show' : '' }}" id="multiple_static_content" role="tabpanel">
            <div class="card">
                <div class="card-body pt-0">
                    @if($section_type)
                    <form action="{{route('admin.frontend.sections.save.content', ['section_key' => $section_key, 'type' => $section_type])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="content_type" value="{{ $section_type }}.multiple_static_content">
                    @else
                    <form action="{{route('admin.frontend.sections.save.content', $section_key)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="content_type" value="multiple_static_content">
                    @endif
                        @foreach($sectionData['multiple_static_content'] as $item_key => $multiple_item_value)
                        <div class="form-element">

                            <div class="row gy-3">
                                <div class="col-xxl-2 col-xl-3">
                                    <h5 class="form-element-title">{{ textFormat(['_'], $item_key, ' ') }}</h5>
                                </div>
                                <div class="col-xxl-7 col-xl-9">
                                    <div class="row gy-4 align-items-end">
                                        @foreach($multiple_item_value as $multi_data_key => $multi_data_value)
                                        @php
                                            $isMultiFirstLoopLast = $loop->last;
                                            $isMultiFirstLoopEven = $loop->iteration % 2 == 0;
                                        @endphp
                                        @if($multi_data_key === 'images')
                                        @foreach($multi_data_key as $multi_data_image_key => $multi_data_image_file)
                                            <div class="{{ $loop->last && !$loop->iteration % 2 == 0 ? ($isMultiFirstLoopLast && $isMultiFirstLoopEven ? 'col-lg-6' : 'col-lg-12') : 'col-lg-6' }}">
                                                <div class="form-inner">
                                                    <label for="{{ $multi_data_image_key }}" class="form-label">{{ __(setInputLabel($multi_data_image_key)) }} <sup>*</sup></label>

                                                    <input type="file" class="form-control" id="{{ $multi_data_image_key }}" name="images[{{ $item_key }}][{{ $multi_data_key }}][]" value="{{ @$sectionMultiContent->section_value[$item_key][$multi_data_key][$multi_data_image_key] ?? '' }}" placeholder="{{ __(setInputLabel($multi_data_key)) }}"/>
                                                </div>
                                            </div>
                                        @endforeach
                                        @else
                                        @switch($multi_data_value)
                                        @case('icon')
                                            <div class="{{ $isMultiFirstLoopLast && !$isMultiFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$multi_data_key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control iconpicker icon" autocomplete="off" name="{{ $item_key }}[{{ $multi_data_key }}]" required value="{{ $sectionMultiContent->section_value[$item_key][$multi_data_key] ?? '' }}">
                                                        <span class="input-group-text input-group-addon" role="iconpicker"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('text')
                                            <div class="{{ $isMultiFirstLoopLast && !$isMultiFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$multi_data_key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" id="{{ $multi_data_key }}" name="{{ $item_key }}[{{ $multi_data_key }}]" value="{{ $sectionMultiContent->section_value[$item_key][$multi_data_key] ?? '' }}" placeholder="{{ __(setInputLabel($multi_data_key)) }}" required>
                                                </div>
                                            </div>
                                            @break
                                        @case('textarea')
                                            <div class="{{ $isMultiFirstLoopLast && !$isMultiFirstLoopEven ? 'col-lg-12' : 'col-lg-6'}}">
                                                <div class="form-inner">
                                                    <label for="subject" class="form-label">{{ translate(textFormat(['_'],$multi_data_key, ' ')) }} <sup class="text-danger">*</sup></label>
                                                    <textarea class="form-control" id="{{ $multi_data_key }}" name="{{ $item_key }}[{{ $multi_data_key }}]" placeholder="{{ __(setInputLabel($multi_data_key)) }}" required>{{ $sectionMultiContent->section_value[$item_key][$multi_data_key] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            @break
                                        @endswitch
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-xxl-9">
                                <div class="form-action justify-content-end">
                                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Update") }} </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @if(isset($sectionData['element_content']))
        <div class="tab-pane fade {{ !isset($sectionData['fixed_content']) && !isset($sectionData['multiple_static_content']) ? 'active show' : '' }}" id="element_content" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <h4 class="card-title">{{ translate('Element Contents')}}</h4>
                    </div>
                    <div class="card-header-right">
                        
                        <a href="{{route('admin.frontend.sections.element.content', ['section_key' => $section_key, 'type' => $section_type])}}" class="i-btn btn--primary btn--sm">
                            <i class="ri-add-line"></i> {{translate('Add New')}}
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-container">
                    <table>
                        <thead>
                        <tr>
                            <tr>
                                <th scope='col'>{{ translate('#') }}</th>
                                @if (Illuminate\Support\Arr::has($sectionData, 'element_content.images'))
                                    <th scope='col'>{{translate('Image')}}</th>
                                @endif

                                @foreach ($sectionData['element_content'] as $key => $typeItem)
                                    @if (in_array($typeItem, ['text', 'icon']))
                                        <th scope='col'>{{ __(setInputLabel($key)) }}</th>
                                    @endif
                                @endforeach

                                <th scope='col'>{{ translate('Option') }}</th>
                            </tr>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($elementContents as $element)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    @if (Illuminate\Support\Arr::has($sectionData, 'element_content.images'))
                                        @foreach($sectionData['element_content'] ?? [] as $key => $item)
                                            @if($key === 'images')
                                                @foreach($item as $image_key => $file)
                                                    @php
                                                        $element_key = explode('.', $element->section_key)[0];
                                                    @endphp
                                                    <td data-label="{{ translate('Image') }}">
                                                        <img src="{{showImage(config("setting.file_path.frontend.element_content.$element_key.$image_key.path").'/'.@$element->section_value[$image_key],config("setting.file_path.frontend.element_content.$element_key.$image_key.size"))}}" class="w-25">
                                                    </td>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                    @foreach ($sectionData['element_content'] as $key => $typeItem)
                                        @if (in_array($typeItem, ['text', 'icon']))
                                            <td data-label="{{__(setInputLabel($key)) }}">
                                                @if ($typeItem == 'icon')
                                                    @php echo $element->section_value[$key] ?? '' @endphp
                                                @else
                                                    {{ $element->section_value[$key] ?? '' }}
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                    <td data-label="{{ translate('Option') }}">
                                        <div class="d-flex align-items-center gap-1">

                                            <a href="{{route('admin.frontend.sections.element.content',['section_key' => $section_key, 'type' => $section_type ? $section_type : 'type', 'id' => $element->id])}}" class="icon-btn btn-ghost btn-sm info-soft circle"><i class="ri-edit-line"></i></a>
                                            <a href="javascript:void(0)" class="icon-btn btn-ghost btn-sm danger-soft circle delete-element"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete-element"
                                                    data-delete_id="{{$element->id}}"
                                                ><i class="ri-delete-bin-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
      </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade actionModal" id="delete-element" tabindex="-1" aria-labelledby="delete-element" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.frontend.sections.element.delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="element_id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this element?") }}</h5>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Delete") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>
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

                $('.iconpicker').iconpicker({
                    placement:"none",
                }).on('iconpickerSelected', function(e) {
                    $(this).closest('.input-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
                });
            }
        });
		$('.delete-element').on('click', function() {

            const modal = $('#delete-element');
            modal.find('input[name=element_id]').val($(this).data('delete_id'));
			modal.modal('show');
		});
</script>
@endpush
