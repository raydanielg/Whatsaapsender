@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('user.layouts.app')
@section('panel')
<main class="main-body">
	<div class="container-fluid px-0 main-content">
		<div class="page-header">
			<div class="page-header-left">
				<h2>{{ $title}}</h2>
				<div class="breadcrumb-wrapper">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
							<a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
							</li>
							<li class="breadcrumb-item">
							<a href="{{ route("user.support.ticket.index") }}">{{ translate("Manage Support Ticket") }}</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<form action="{{route('user.support.ticket.store')}}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="form-element">
						<div class="row gy-4">
							
							<div class="col-xxl-2 col-xl-3">
								<h5 class="form-element-title">{{ translate("Ticket body") }}</h5>
							</div>
							<div class="col-xxl-8 col-xl-9">
								<div class="row gy-4">
									<div class="col-6">
										<label for="subject" class="form-label">{{ translate("Ticket Subject") }}</label>
										<input type="text" name="subject" class="form-control" placeholder="{{ translate('Enter Subject')}}" required>
									</div>
									<div class="col-6">
										<label for="priority" class="form-label">{{ translate("Choose priority") }}<span>
										  <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Choose a priority to let Admin know the importance of your ticket') }}">
											  <i class="ri-question-line"></i>
											</span>
										  </span>
										</label>
										<select data-placeholder="{{translate('Select a priority')}}" class="form-select select2-search" id="priority" name="priority" aria-label="priority">
										  <option value=""></option>
										  @foreach(\App\Enums\PriorityStatusEnum::toArray() as $name => $value)
											  <option value="{{$value}}">{{ucfirst(strtolower($name))}}</option>
										  @endforeach
										</select>
									</div>
									<div class="col-12">
										<div class="form-inner">
											<label for="message" class="form-label"> {{ translate("Write message") }} </label>
											<textarea class="form-control" name="message" id="message" placeholder="{{ translate('Enter your reply')}}"></textarea>
											<p class="form-element-note"> {{ translate("Use the texteditor to edit your reply according to your needs") }} </p>
										</div>
									</div>
									<div class="col-md-9">
										<div class="form-inner">
											<label for="file" class="form-label"> {{ translate("Choose file") }} </label>
											
											<input type="file" id="file" name="file[]" class="form-control" aria-label="file" />
											<p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
										</div>
									</div>
									
									<div class="col-md-3">
										<button class="i-btn btn--primary btn--md w-100 mt-md-4 addnewfile" type="button">
											<i class="ri-add-fill fs-18"></i> {{ translate("Add more") }} 
										</button>
									</div>
									<div class="addnewdata"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xxl-10">
							<div class="form-action justify-content-end">
								<button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
@endpush

@push('script-push')
<script>
	(function($){
		"use strict"
		select2_search($('.select2-search').data('placeholder'));
		ck_editor("#message");

		$('.addnewfile').on('click', function () {
	        var html = `
	        <div class="row newdata my-2">
	    		<div class="mb-3 col-lg-10">
	    			<input type="file" name="file[]" class="form-control" required>
				</div>

	    		<div class="col-lg-2 col-md-12 mt-md-0 mt-2 text-right">
	                <span class="input-group-btn">
	                    <button class="i-btn btn--danger btn--md removeBtn" type="button">
	                        <i class="ri-delete-bin-line"></i>
	                    </button>
	                </span>
	            </div>
	        </div>`;
	        $('.addnewdata').append(html);
		    $(".removeBtn").on('click', function(){
		        $(this).closest('.newdata').remove();
		    });
	    });
		
    })(jQuery);
</script>
@endpush