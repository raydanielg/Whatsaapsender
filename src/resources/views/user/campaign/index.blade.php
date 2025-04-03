@extends('user.layouts.app')
@section('panel')
    <section>
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ translate('All Campaigns')}}</h4>
                </div>
                <div class="card-filter">
                    <form action="{{route('user.campaign.search')}}" method="post">
                        @csrf
                        <div class="filter-form">
                            <div class="filter-item">
                                <input type="text" autocomplete="off" name="search"  placeholder="{{ translate('Search With Name')}}" class="form-control" id="search" value="{{@$search}}">
                            </div>
                            @php
                                $statuses = ['Active', 'Deactive', 'Completed', 'Ongoing'];
                            @endphp
                            <input type="hidden" name="channel" value="{{@$channel}}">

                            <div class="filter-item">
                                <select name="status" class="form-select">
                                    <option value="">{{translate('Select Status')}}</option>
                                    @foreach( $statuses as $status)
                                        <option {{ @$searchStatus ==  $status ? 'selected' :""}} value="{{ $status}}"> {{ $status}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-action">
                                <button class="i-btn primary--btn btn--md" type="submit">
                                    <i class="fas fa-search"></i> {{ translate('Search')}}
                                </button>
                                @php
                                    $routeName =  "user.campaign.".strtolower($channel);
                                @endphp
								<button class="i-btn danger--btn btn--md" type="submit">
									<a href="{{route($routeName)}}" class="text-white" >
										<i class="fas fa-sync"></i> {{ translate('Reset')}}
									</a>
								</button>

								<a href="{{route('user.campaign.create',strtolower($channel))}}" class="i-btn info--btn btn--md @php
									if(strtolower($channel) == \App\Models\Campaign::SMS && Auth::user()->credit == 0){ echo 'd-none'; }
									elseif(strtolower($channel) == \App\Models\Campaign::EMAIL && Auth::user()->email_credit == 0){ echo 'd-none'; }
									elseif(strtolower($channel) == \App\Models\Campaign::WHATSAPP && Auth::user()->whatsapp_credit == 0){ echo 'd-none'; }
									else{ echo '' ; }
								@endphp" type="submit">
									<i class="fas fa-plus"></i> {{ translate('Add New')}}
								</a>

                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0">
                    <div class="responsive-table">
		                <table>
		                    <thead>
		                        <tr>
		                            <th>{{ translate('Name')}}</th>
		                            <th>{{ translate('Channel')}}</th>
		                            <th>{{ translate('Total Contacts')}}</th>
									<th>{{ translate('Schedule Time')}}</th>
		                            <th>{{ translate('Status')}}</th>
		                            <th>{{ translate('Action')}}</th>
		                        </tr>
		                    </thead>
		                    @forelse($campaigns as $campaign)
			                    <tr class="@if($loop->even)@endif">


				                     <td data-label="{{ translate('Name')}}">
				                     	 {{$campaign->name}}
				                    </td>

				                     <td data-label="{{ translate('Channel')}}">
				                     	 {{$campaign->channel}}
				                    </td>

				                    <td data-label="{{ translate('Contacts')}}">
										<a href="{{route('user.campaign.contacts',$campaign->id)}}" class="badge badge--primary p-2"> {{ translate('view Contact')}} ({{$campaign->contacts->count()}}) </a>
				                    </td>


									<td data-label="{{ translate('Time')}}">
										{{getDateTime($campaign->schedule_time)}}
								    </td>

				                    <td data-label="{{ translate('Status')}}">
				                    	@if($campaign->status == 'Active')
				                    		<span class="badge badge--primary">{{ translate('Active')}}</span>
				                    	@elseif($campaign->status == 'Ongoing')
				                    		<span class="badge badge--info">{{ translate('Ongoing')}}</span>
				                    	@elseif($campaign->status == 'DeActive')
				                    		<span class="badge badge--danger">{{ translate('Inctive')}}</span>
				                    	@elseif($campaign->status == 'Completed')
				                    		<span class="badge badge--success">{{ translate('Completed')}}</span>
										@endif
				                    </td>

				                    <td data-label={{ translate('Action')}}>
										<div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
											<a href="{{route('user.campaign.edit',['type' => strtolower($channel), 'id' => $campaign->id])}}" class="i-btn primary--btn btn--sm"><i class="las la-pen"></i></a>
											<a href="javascript:void(0)" class=" i-btn danger--btn btn--sm campDelete"
												data-bs-toggle="modal"
												data-bs-target="#delete"
												data-delete_id="{{$campaign->id}}"
												><i class="las la-trash"></i>
											</a>
										</div>
				                    </td>
			                    </tr>
			                @empty
			                	<tr>
			                		<td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
			                	</tr>
			                @endforelse
		                </table>
	            	</div>
	                <div class="m-3">
	                	{{$campaigns->appends(request()->all())->onEachSide(1)->links()}}
					</div>
	            </div>
	        </div>
	    </div>
    </section>

<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form action="{{route('user.campaign.delete')}}" method="POST">
        		@csrf
        		<input type="hidden" name="id" value="">
	            <div class="modal_body2">
	                <div class="modal_icon2">
	                    <i class="las la-trash"></i>
	                </div>
	                <div class="modal_text2 mt-3">
	                    <h6>{{ translate('Are you sure to delete this Camapign')}}</h6>
	                </div>
	            </div>
	            <div class="modal_button2 modal-footer">
					<div class="d-flex align-items-center justify-content-center gap-3">
						<button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
						<button type="submit" class="i-btn danger--btn btn--md">{{ translate('Delete')}}</button>
					</div>
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
		$('.campDelete').on('click', function(){
			var modal = $('#delete');
			modal.find('input[name=id]').val($(this).data('delete_id'));
			modal.modal('show');
		});
	})(jQuery);
</script>
@endpush
