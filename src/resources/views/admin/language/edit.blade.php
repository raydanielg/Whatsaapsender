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
								<li class="breadcrumb-item">
									<a href="{{ route("admin.system.language.index") }}">{{ translate("Language") }}</a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
							</ol>
						</nav>
					</div>
				</div>
				<div class="table-filter mb-4">
					<form action="{{route(Route::currentRouteName(), $code)}}" class="filter-form">
						@csrf
						<div class="row g-3">
							<div class="col-xl-3 col-lg-3">
								<div class="filter-search">
									<input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search for words") }}" />
									<span><i class="ri-search-line"></i></span>
								</div>
							</div>
							<div class="col-xl-2 col-lg-11 offset-xl-7">
								<div class="filter-action">
									<button type="submit" class="filter-action-btn ">
										<i class="ri-equalizer-line"></i> {{ translate("Filters") }} 
									</button>
									<a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName(), $code)}}">
										<i class="ri-refresh-line"></i> {{ translate("Reset") }} 
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<div class="card-header-left">
						<h4 class="card-title">{{ translate("Language List") }}</h4>
					</div>
				</div>
				<div class="card-body px-0 pt-0">
					<div class="table-container">
						<table>
							<thead>
								<tr>
									<th scope="col">{{ translate("Key") }}</th>
									<th scope="col">{{$language->name}}</th>
									<th scope="col">{{ translate("Option") }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($translations as $translation)
									
									<tr class="@if($loop->even)@endif">
										<td title="{{ $translation->key }}" data-label="{{ translate('Key')}}">
											{{truncate_string(textFormat(['_'], $translation->key, ' '), 40)}}
										</td>
										<td data-label="{{$language->name}}">
											<div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
												<input id="lang-key-value-{{ $translation->uid }}" class="form-control" value="{{ $translation->value }}" type="text">
												<button type="submit" 
														class="icon-btn btn-md hover success-soft circle flex-shrink-0"
														id="updatelanguage"
														data-value="{{$translation->value}}"
														data-uid="{{$translation->uid}}">
													<i class="ri-save-line"></i>
												</button>
											</div>
										</td>

										<td data-label={{ translate('Option')}}>
											<div class="d-flex align-items-center gap-1">
												
												<a 	href="javascript:void(0)" 
													class="icon-btn btn-ghost btn-sm info-soft circle text-danger delete-translation-key"
													data-uid="{{$translation->uid}}"
													data-bs-toggle="modal"
													data-bs-target="#deleteTranslationKey"> 
													<i class="ri-delete-bin-line"></i>
													<span class="tooltiptext"> {{ translate("Delete") }} </span>
												</a>
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
					<div class="pagination-wrapper px-4 pt-3">
						<p class="pagination-summary">
							@if ($translations->appends(request()->all()))
								{{ translate("Showing") }} {{ $translations->appends(request()->all())->firstItem() }}-{{ $translations->appends(request()->all())->lastItem() }} {{ translate("from") }} {{ $translations->appends(request()->all())->total() }}
							@endif
						</p>
						<nav aria-label="...">
							@if ($translations->appends(request()->all())->hasPages())
								<nav aria-label="...">
									<ul class="pagination">
										@if ($translations->appends(request()->all())->onFirstPage())
											<li class="page-item disabled">
												<a class="page-link">
													<i class="bi bi-chevron-left"></i>
												</a>
											</li>
										@else
											<li class="page-item">
												<a class="page-link" href="{{ $translations->appends(request()->all())->previousPageUrl() }}" rel="prev">
													<i class="bi bi-chevron-left"></i>
												</a>
											</li>
										@endif
										@foreach ($translations->appends(request()->all())->links()->elements as $element)
											
											@if(is_array($element))
												@foreach ($element as $url)
													
													@php
														if(request()->input("date")) {
															$query_step = 4;
														}
														elseif(request()->input("search")) {
															$query_step = 3;
														} elseif(request()->_token) {
															$query_step = 2;
														} else{
															$query_step = 1;
														}
														
														$page = parse_url($url)['query'] ? explode('=', parse_url($url)['query'])[$query_step] : '1';
													@endphp
													@if ($page == $translations->appends(request()->all())->currentPage())
													
														<li class="page-item active" aria-current="page">
															<span class="page-link">{{ $page }}</span>
														</li>
													@else
													
														<li class="page-item">
															<a class="page-link" href="{{ $url }}">{{ $page }}</a>
														</li>
													@endif
												@endforeach
											@else 
												<li class="page-item" aria-current="page">
													<span class="page-link">{{ "..."}}</span>
												</li>
											@endif
										@endforeach
										@if ($translations->appends(request()->all())->hasMorePages())
											<li class="page-item">
												<a class="page-link" href="{{ $translations->appends(request()->all())->nextPageUrl() }}" rel="next">
													<i class="bi bi-chevron-right"></i>
												</a>
											</li>
										@else
											<li class="page-item disabled">
												<a class="page-link">
													<i class="bi bi-chevron-right"></i>
												</a>
											</li>
										@endif
									</ul>
								</nav>
							@endif
						</nav>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
@section("modal")
<div class="modal fade actionModal" id="deleteTranslationKey" tabindex="-1" aria-labelledby="deleteTranslationKey" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered ">
		<div class="modal-content">
		<div class="modal-header text-start">
			<span class="action-icon danger">
			<i class="bi bi-exclamation-circle"></i>
			</span>
		</div>
		<form action="{{route('admin.system.language.data.delete')}}" method="POST">
			@csrf
			<div class="modal-body">
				<input type="hidden" name="uid">
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
@push('script-push')
<script>
	(function($){
       	"use strict";

		$('.delete-translation-key').on('click', function() {

			var modal = $('#deleteTranslationKey');
			modal.find('input[name=uid]').val($(this).data('uid'));
			modal.modal('show');
		});

        //update lang key method

        $(document).on('click','#updatelanguage',function(e) {

            e.preventDefault()
            const uid 	   = $(this).attr('data-uid')
            const keyValue = $(`#lang-key-value-${uid}`).val()
            const data 	   = {
                "uid" 	  : uid,
                "value"   : keyValue,
              }
            updateLangKeyValue(data)
          })

          //update language value function
          function updateLangKeyValue(data) {
			var responseStatus;

            $.ajax({
				method   : 'post',
				url	   : "{{ route('admin.system.language.data.update') }}",
				headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data	   : { data },
				dataType : 'json',
				success: function (response) {

					if (response) {
						responseStatus = response.status? "success" :"error"
						notify(responseStatus, response.message)
						if(response.reload) {
							
							location.reload();
						}
					}
				},
				error: function (error) {
					if(error && error.responseJSON) {

						if(error.responseJSON.errors) {

							for (let i in error.responseJSON.errors) {
								
								notify('error', error.responseJSON.errors[i][0])
							}
						}
						else{
							notify('error', error.responseJSON.error);
						}
					}
					else {
						notify('error', error.message);
					}
				}
            })
          }
	})(jQuery);
</script>
@endpush
