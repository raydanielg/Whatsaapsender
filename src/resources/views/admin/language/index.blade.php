@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush
@extends('admin.layouts.app')
@section('panel')

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

        <div class="table-filter mb-4">
            <form action="{{route(Route::currentRouteName())}}" class="filter-form">
                
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="filter-search">
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search for languages by Name") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-lg-8 offset-xxl-2">
                        <div class="filter-action">
                            <div class="input-group">
                                <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                                <span class="input-group-text" id="filterByDate">
                                    <i class="ri-calendar-2-line"></i>
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="filter-action-btn ">
                                    <i class="ri-equalizer-line"></i> {{ translate("Filters") }}
                                </button>
                                <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                                    <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h4 class="card-title">{{ translate("Language List") }}</h4>
                </div>
                <div class="card-header-right">
                    <button class="i-btn btn--primary btn--sm add-language" type="button" data-bs-toggle="modal" data-bs-target="#addLanguage">
                        <i class="ri-add-fill fs-16"></i> {{ translate("Add Language") }}
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Name") }}</th>
                                <th scope="col">{{ translate("Code") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                                <th scope="col">{{ translate("Default") }}</th>
                                <th scope="col">{{ translate("Option") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($languages as $language)

                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Name')}}">
                                        <p class="text-dark fw-semibold">
                                            {{$language->name}}
                                        </p>
                                    </td>

                                    <td data-label="{{ translate('Code')}}">
                                        <p class="text-dark fw-semibold">{{$language->code}}</p>
                                    </td>

                                    <td data-label="{{ translate('Status')}}">
                                        <div class="switch-wrapper checkbox-data">
                                            <input {{ $language->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                    type="checkbox"
                                                    class="switch-input statusUpdate"
                                                    data-id="{{ $language->id }}"
                                                    data-column="status"
                                                    data-value="{{ $language->status }}"
                                                    data-route="{{route('admin.system.language.status.update')}}"
                                                    id="{{ 'status_'.$language->id }}"
                                                    name="status"/>
                                            <label for="{{ 'status_'.$language->id }}" class="toggle">
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td data-label="{{ translate('Default')}}">
                                        @if($language->is_default == \App\Enums\StatusEnum::TRUE->status())
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="i-badge dot success-soft pill">{{ translate("Default") }}</span>
                                            </div>
                                        @else
                                            <div class="switch-wrapper checkbox-data">
                                                <input {{ $language->is_default == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                                        type="checkbox"
                                                        class="switch-input statusUpdate"
                                                        data-id="{{ $language->id }}"
                                                        data-column="is_default"
                                                        data-value="{{ $language->is_default }}"
                                                        data-route="{{route('admin.system.language.status.update')}}"
                                                        id="{{ 'default_'.$language->id }}"
                                                        name="is_default"/>
                                                <label for="{{ 'default_'.$language->id }}" class="toggle">
                                                    <span></span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>

                                    <td data-label={{ translate('Option')}}>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="icon-btn btn-ghost btn-sm success-soft circle update-language"
                                                    type="button"
                                                    data-language-id="{{ $language->id }}"
                                                    data-language-name="{{ $language->name }}"
                                                    data-language-ltr="{{ $language->ltr }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateLanguage">
                                                <i class="ri-edit-line"></i>
                                                <span class="tooltiptext"> {{ translate("Edit Language") }} </span>
                                            </button>
                                            <a href="{{route('admin.system.language.translate', $language->code)}}" class="icon-btn btn-ghost btn-sm info-soft circle text-danger">
                                                <i class="ri-translate"></i>
                                                <span class="tooltiptext"> {{ translate("Translate") }} </span>
                                            </a>
                                            <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-language"
                                                    type="button"
                                                    data-language-id="{{ $language->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteLanguage">
                                                <i class="ri-delete-bin-line"></i>
                                                <span class="tooltiptext"> {{ translate("Delete Language") }} </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('admin.partials.pagination', ['paginator' => $languages])
            </div>
        </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade modal-select2" id="addLanguage" tabindex="-1" aria-labelledby="addLanguage" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.system.language.store')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Language") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-lg-custom-height">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-inner">
                                <label for="name" class="form-label">{{ translate("Select Country") }}</label>
                                <select data-placeholder="{{translate('Select a flag for a country')}}" class="form-select select2-search" data-show="5" id="name" name="name">
                                    <option value=""></option>
                                    @foreach ($countries as $codes)
                                        <option value="{{$codes['name']}}//{{$codes['isoAlpha2']}}">
                                            {{$codes['name']}}
                                        </option>
							        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-inner">
                              <label class="form-label"> {{ translate("LTR/RTL Compatibility") }} </label>
                              <div class="form-inner-switch">
                                <label class="pointer" for="ltr" >{{ translate("Is the language ltr (Left-to-Right) compatible? ") }}</label>
                                <div class="switch-wrapper mb-1 checkbox-data">
                                  <input type="checkbox" class="switch-input" id="ltr" name="ltr"/>
                                  <label for="ltr" class="toggle">
                                    <span></span>
                                  </label>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateLanguage" tabindex="-1" aria-labelledby="updateLanguage" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('admin.system.language.update')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <input type="text" hidden name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Language") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height ">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="name" class="form-label"> {{ translate('Language Name')}} </label>
                                <input type="text" id="name" name="name" placeholder="{{ translate('Enter language name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-inner">
                              <label class="form-label"> {{ translate("LTR/RTL Compatibility") }} </label>
                              <div class="form-inner-switch">
                                <label class="pointer" for="update_ltr" >{{ translate("Is the language ltr/rtl compatible? ") }}</label>
                                <div class="switch-wrapper mb-1 checkbox-data">
                                  <input type="checkbox" class="switch-input" id="update_ltr" name="ltr"/>
                                  <label for="update_ltr" class="toggle">
                                    <span></span>
                                  </label>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade actionModal" id="deleteLanguage" tabindex="-1" aria-labelledby="deleteLanguage" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.system.language.delete')}}" method="POST">
            @csrf
            <div class="modal-body">

                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this language?") }}</h5>
                    <p>{{ translate("By clicking on 'Delete', you will permanently remove the language from the application") }}</p>
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

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush
@push('script-push')
<script>
	"use strict";

        select2_search($('.select2-search').data('placeholder'), $('.modal-select2'));
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });
		$('.update-language').on('click', function() {

            const modal = $('#updateLanguage');
            modal.find('input[name=id]').val($(this).data('language-id'));
			modal.find('input[name=name]').val($(this).data('language-name'));
            if($(this).data('language-ltr') == {{ \App\Enums\StatusEnum::TRUE->status() }}) {

                modal.find('input[name=ltr]').prop('checked', true);
            }
			modal.modal('show');
		});

        $('.add-language').on('click', function() {

            const modal = $('#addLanguage');
            modal.modal('show');
        });

		$('.delete-language').on('click', function() {

            const modal = $('#deleteLanguage');
            modal.find('input[name=id]').val($(this).data('language-id'));
			modal.modal('show');
		});

		$('#flag').on('change', function() {

            const countryCode = this.value.toLowerCase();
            $('#flag-icon').html('').html('<i class="flag-icon flag-icon-squared rounded-circle fs-4 me-1 flag-icon-'+countryCode+'"></i>');
		});

        $('form').on('submit', function(e) {
          $('.checkbox-data').each(function() {
              var $checkbox = $(this).find('.switch-input');
              var $hiddenInput = $(this).find('input[type="hidden"]');

              if ($checkbox.is(':checked')) {
                  if ($hiddenInput.length === 0) {
                      $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::TRUE->status() }}">');
                  } else {
                      $hiddenInput.val('{{ \App\Enums\StatusEnum::TRUE->status() }}');
                  }
              } else {
                  if ($hiddenInput.length === 0) {
                      $(this).append('<input type="hidden" name="' + $checkbox.attr('name') + '" value="{{ \App\Enums\StatusEnum::FALSE->status() }}">');
                  } else {
                      $hiddenInput.val('{{ \App\Enums\StatusEnum::FALSE->status() }}');
                  }
              }
          });
      });
</script>
@endpush
