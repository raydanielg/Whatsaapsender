@extends('user.layouts.app')
@section('panel')
<section>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">{{ translate('SMS Credit History')}}</h4>
		</div>
		<div class="card-filter">
			<form action="{{route('user.credit.search')}}" method="GET">
				<div class="filter-form">
					<div class="filter-item">
						<input type="text" autocomplete="off" name="search" placeholder="{{ translate('Search by trxid')}}" class="form-control" id="search" value="{{@$search}}">
					</div>

					<div class="filter-item">
						<input type="text" class="form-control datepicker-here" name="date" value="{{@$searchDate}}" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-position="bottom right" autocomplete="off" placeholder="{{ translate('From Date-To Date')}}" id="date">
					</div>
					<div class="filter-action">
						<button class="i-btn primary--btn btn--md" type="submit">
							<i class="fas fa-search"></i> {{ translate('Search')}}
						</button>
						<button class="i-btn danger--btn btn--md">
							<a class="text-white" href="{{ route('user.credit.history') }}">
								<i class="las la-sync"></i>  {{translate('reset')}}
							</a>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body px-0">
			<div class="responsive-table">
				<table>
					<thead>
						<tr>
                            <th>{{ translate('Date')}}</th>
							<th>{{ translate('Trx Number')}}</th>
							<th>{{ translate('Credit')}}</th>
							<th>{{ translate('Previous Credit')}}</th>
							<th>{{ translate('Detail')}}</th>
						</tr>
					</thead>
					@forelse($credits as $creditdata)
						<tr class="@if($loop->even)@endif">
							<td data-label="{{ translate('Date')}}">
								<span>{{diffForHumans($creditdata->created_at)}}</span><br>
								{{getDateTime($creditdata->created_at)}}
							</td>

							<td data-label="{{ translate('Trx Number')}}">
								{{$creditdata->trx_number}}
							</td>

							<td data-label="{{ translate('Credit')}}">
								<span class="@if($creditdata->credit_type == '+')text--success @else text--danger @endif">{{ $creditdata->credit_type }} {{shortAmount($creditdata->credit)}}
								</span>{{ translate('Credit')}}
							</td>

							<td data-label="{{ translate('Previous Credit')}}">
								{{$creditdata->post_credit}} {{translate('Credit')}}
							</td>

							<td data-label="{{ translate('Details')}}">
								{{$creditdata->details}}
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
				{{$credits->links()}}
			</div>
		</div>
	</div>
</section>
@endsection









