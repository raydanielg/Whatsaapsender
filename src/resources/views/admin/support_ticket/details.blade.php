@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
	<div class="container-fluid px-0 main-content">
		<div class="page-header">
			<div class="page-header-left">
				<h2>{{ translate('Reply to Customer: ').ucfirst($supportTicket->user?->name)}} {{ "(".ucfirst(strtolower(App\Enums\TicketStatusEnum::getValue($supportTicket->status))).")" }}</h2>
				<div class="breadcrumb-wrapper">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
							<a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
							</li>
							<li class="breadcrumb-item">
							<a href="{{ route("admin.support.ticket.index") }}">{{ translate("Manage Support Ticket") }}</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<form action="{{route('admin.support.ticket.reply', $supportTicket->id)}}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="form-element">
						<div class="row gy-4">
							<div class="col-xxl-2 col-xl-3">
								<h5 class="form-element-title">{{ translate("Ticket body") }}</h5>
							</div>
							<div class="col-xxl-8 col-xl-9">
								<div class="row gy-4">
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
								<button type="button" class="i-btn btn--danger outline btn--md close-ticket" data-ticket_id="{{ $supportTicket->id }}"> {{ translate("Close ticket") }} </button>
								<button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Reply") }} </button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		@foreach($supportTicket->messages as $meg)
			@if($meg->admin_id == 0)
				<div class="card mt-4">
					<div class="card-body">
						<div class="form-element border-bottom-0 py-0">
							<div class="row gy-3">
								<div class="col-xxl-2 col-xl-3">
									<div class="d-flex flex-xl-column justify-content-between">
										<h5 class="form-element-title">{{ translate("Reply by : ") }}<span class="fw-medium text-muted"><a href="{{route('admin.user.details',$supportTicket->user_id)}}" class="text-dark">{{$supportTicket->user?->name}}</a></span>
										</h5>
										<span class="d-inline-block text-muted fs-14 mt-xl-3">{{ translate('Created at')}}<br /> {{getDateTime($meg->created_at) }} </span>
									</div>
								</div>
								<div class="col-xxl-8 col-xl-9">
									<div class="row gy-1 align-items-center">
										<div class="col-12">
											<div class="bg-light rounded-2 p-3 fs-15 text-muted border h-100">
												<p>@php echo $meg->message @endphp</p>
												<div class="my-3">
													@if($meg->supportfiles()->count() > 0)
														@foreach($meg->supportfiles as $key=> $file)
															<span class="me-2">{{ translate("Attachments:") }}</span><a href="{{route('admin.support.ticket.download',encrypt($file->id))}}" class="mr-3 text-dark"><i class="text-primary fa fa-file me-1"></i>{{ translate('File')}} {{++$key}}</a>
														@endforeach
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@else
				<div class="card mt-4">
					<div class="card-body">
						<div class="form-element border-bottom-0 py-0">
							<div class="row gy-3">
								<div class="col-xxl-2 col-xl-3">
									<div class="d-flex flex-xl-column justify-content-between">
										<h5 class="form-element-title">{{ translate("Reply by : ") }}<span class="fw-medium text-muted">{{ translate('Admin')}}</span>
										</h5>
										<span class="d-inline-block text-muted fs-14 mt-xl-3">{{ translate('Created at')}}<br />{{getDateTime($meg->created_at)}}</span>
									</div>
								</div>
								<div class="col-xxl-8 col-xl-9">
									<div class="row gy-1 align-items-center">
										<div class="col-12">
											<div class="bg-light rounded-2 p-3 fs-15 text-muted border h-100">
												<p> @php echo $meg->message @endphp </p>
												@if($meg->supportfiles()->count() > 0)
													<div class="my-3">
														@foreach($meg->supportfiles as $key=> $file)
															<a href="{{route('admin.support.ticket.download',encrypt($file->id))}}" class="mr-3"><i class="fa fa-file"></i> {{ translate('File')}} {{++$key}} </a>
														@endforeach
													</div>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		@endforeach
	</div>
</main>

@endsection
@section("modal")
<div class="modal fade actionModal" id="closeTicket" tabindex="-1" aria-labelledby="closeTicket" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('admin.support.ticket.closeds', $supportTicket->id)}}" method="POST">
			@csrf
            <div class="modal-body">
                
                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to want close this ticket?") }}</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Close Ticket") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection


@push('script-push')
<script>
	(function($){
		"use strict"
		ck_editor("#message");

		$('.addnewfile').on('click', function () {
	        var html = `
	        <div class="row newdata my-2">
	    		<div class="mb-3 col-lg-10">
	    			<input type="file" name="file[]" class="form-control" required>
				</div>

	    		<div class="col-lg-2 col-md-12 mt-md-0 mt-2 text-right">
	                <span class="input-group-btn">
	                    <button class="i-btn btn--danger btn--md" type="button">
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
		$('.close-ticket').on('click', function() {

			const modal = $('#closeTicket');
			modal.find('input[name=id]').val($(this).data('ticket_id'));
			modal.modal('show');
		});
    })(jQuery);
</script>
@endpush
