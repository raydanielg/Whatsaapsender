@extends($layout)
@section('panel')

@push('style-push')
    <link rel="stylesheet" href="{{asset('assets/theme/admin/css/prism.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/theme/admin/css/code-box-copy.min.css')}}"/>
@endpush

<div class="page-title-wrapper">
    <div class="page-title-left">
        <h2 class="page-title ">{{ translate('Api Document')}}</h2>
    </div>

    <div class="page-title-right">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
    </div>
</div>

<section>
    <div class="container-fluid p-0">
	    <div class="row">
	 		<div class="col-lg-12">
	            <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">{{ translate('Documention')}}</h4>
                    </div>
	            	<div class="card-body">
	            		<h6 class="mb-1">{{ translate('Before you get started') }}</h6>
						<div class="lead mb-5">
							A brief overview of the API and its purpose <br>
							<strong>Endpoints:</strong> A list of all the endpoints available in the API, including their URLs and the HTTP methods they support.
							<br>
							<strong>Request and Response:</strong> The expected request format and the format of the response, including examples of how to use the API and the data that it returns.
						</div>


						<h6 class="mb-1">{{ translate('Send Email') }}</h6>
						<div class="lead custiom-accordion mb-5">
							<div class="accordion accordion-flush" id="emailSend">
							  	<div class="accordion-item mb-3">
								    <h2 class="accordion-header" id="headingOne">
								      	<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmail" aria-expanded="true" aria-controls="collapseEmail">
								        	<span class="badge bg--primary">
								        		POST
								        	</span> &nbsp;
								        	<span>{{route('incoming.email.send')}}</span>
								      </button>
								    </h2>
							    	<div id="collapseEmail" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#emailSend">
							      		<div class="accordion-body">
							        		<strong>This PHP method uses cURL to send email data to an API endpoint,</strong> receiving a <code>JSON response</code> with email request status and logs.
							        		<div class="code-box-copy">
    											<button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
							        				<pre>
														<code id="code-block-email-send" class="language-php">
$curl = curl_init();
$postdata = array(
    "contact" => array(
        array(
            "subject" => "demo list info",
            "email" => "receiver1@email.com",
            "message" => "In publishing and graphic design, Lorem ipsum text",
            "sender_name" => "name",
            "reply_to_email" => "demo@gmail.com"
        ),
        array(
            "subject" => "demo list info",
            "email" => "receiver2@email.com",
            "message" => "1",
            "sender_name" => "name",
            "reply_to_email" => "demo@gmail.com"
        )
    )
);

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{route('incoming.email.send')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($postdata),
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
        'Content-Type: application/json'
    ),
));
$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "email_logs": [
        {
            "uid": "20034f80-f778-4d3e-a636-5c1f161b954f",
            "email": "receiver1@email.com",
            "status": "Pending",
            "created_at": "2023-10-16 04:14 PM"
        },
        {
            "uid": "c4ad7ae2-3cfd-4066-ab31-4d31c3d078db",
            "email": "receiver2@email.com",
            "status": "Pending",
            "created_at": "2023-10-16 04:14 PM"
        }
    ],
    "message": "New Email request sent, please see in the Email history for final status"
}
                                                        </code>
														</pre>
													</div>
												</div>
							      			</div>
							    		</div>
							  		</div>
							  	<div class="accordion-item accordion-flush">
							    	<h2 class="accordion-header" id="headingEmailStatus">
							      		<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmailStatus" aria-expanded="false" aria-controls="collapseEmailStatus">
							        		<span class="badge bg--success">
							        			GET
							        		</span>&nbsp;
							        		<span>{{url('api/get/email/{uid}')}}</span>
							      		</button>
							    	</h2>
							    	<div id="collapseEmailStatus" class="accordion-collapse collapse" aria-labelledby="headingEmailStatus" data-bs-parent="#emailSend">
							      		<div class="accordion-body">
                                            <strong>Using cURL, this PHP code fetches email log details for a specific identifier (uid) from an API, including recipient email, content, status, and the latest update time.</strong>
                                            <div class="code-box-copy">
                                                <button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
                                                <pre>
														<code id="code-block-email-send" class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{url('api/get/email/{uid}')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
    ),
));

$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "email_logs": {
        "uid": "60aca9dd-ce15-451c-b8d8-86ca01b0",
        "email": "receiver1@email.com",
        "content": "some content",
        "status": "Pending",
        "updated_at": "2023-10-16 08:15 PM"
    }
}
                                                        </code>
														</pre>
                                            </div>
							      		</div>
							    	</div>
							  	</div>
							</div>

                        <h6 class="mb-1">{{ translate('Send SMS') }}</h6>
                        <div class="lead custiom-accordion mb-5">
                            <div class="accordion accordion-flush" id="smsSend">
                                <div class="accordion-item  mb-3">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSms" aria-expanded="false" aria-controls="collapseEmail">
								        	<span class="badge bg--primary">
								        		POST
								        	</span> &nbsp;
                                            <span>{{route('incoming.sms.send')}}</span>
                                        </button>
                                    </h2>
                                    <div id="collapseSms" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#smsSend">
                                        <div class="accordion-body">
                                            <strong>This PHP method sends SMS messages via cURL to an API endpoint, </strong>receiving a <code>JSON response</code> with SMS request status and logs.
                                            <div class="code-box-copy">
                                                <button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
                                                <pre>
														<code id="code-block-email-send" class="language-php">
$curl = curl_init();
$postdata = array(
    "contact" => array(
        array(
            "number" => "11254352345",
            "body" => "In publishing and graphic design, Lorem ipsum is a",
            "sms_type" => "plain"
        ),
        array(
            "number" => "32234213423",
            "body" => "In publishing and graphic design, Lorem ipsum is a",
            "sms_type" => "unicode"
        )
    )
);

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{route('incoming.sms.send')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($postdata),
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "sms_logs": [
        {
            "uid": "5eb8c1b2-6832-4c91-a813-6e06062c8584",
            "number": "11254352345",
            "status": "Pending",
            "created_at": "2023-10-16 04:59 PM"
        },
        {
            "uid": "3d783e68-8f87-4374-a6b4-d07d23b30fff",
            "number": "32234213423",
            "status": "Pending",
            "created_at": "2023-10-16 04:59 PM"
        }
    ],
    "message": "New SMS request sent, please see in the SMS history for final status"
}

                                                        </code>
														</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEmailStatus">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSmsStatus" aria-expanded="false" aria-controls="collapseEmailStatus">
							        		<span class="badge bg--success">
							        			GET
							        		</span>&nbsp;
                                        <span>{{url('api/get/sms/{uid}')}}</span>
                                    </button>
                                </h2>
                                <div id="collapseSmsStatus" class="accordion-collapse collapse" aria-labelledby="headingEmailStatus" data-bs-parent="#emailSend">
                                    <div class="accordion-body">
                                        <strong>This PHP code, employing cURL, retrieves SMS log data for a unique identifier (uid) from an API, including status, recipient number, message content, and the last update timestamp.</strong>
                                        <div class="code-box-copy">
                                            <button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
                                            <pre>
														<code id="code-block-email-send" class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{url('api/get/sms/{uid}')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
    ),
));

$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "sms_logs": {
        "uid": "43339822-9d44-413e-b8b6-5cafc8be",
        "number": "11254352345",
        "content": "In publishing and graphic design, Lorem ipsum is a",
        "status": "Schedule",
        "updated_at": "2023-10-16 08:10 PM"
    }
}

                                                        </code>
														</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h6 class="mb-1">{{ translate('Send Whatsapp') }}</h6>
                        <div class="lead custiom-accordion mb-5">
                            <div class="accordion accordion-flush" id="whatsappSend">
                                <div class="accordion-item  mb-3">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWhatsapp" aria-expanded="false" aria-controls="collapseEmail">
                                                <span class="badge bg--primary">
                                                    POST
                                                </span> &nbsp;
                                            <span>{{route('incoming.whatsapp.send')}}</span>
                                        </button>
                                    </h2>
                                    <div id="collapseWhatsapp" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#whatsappSend">
                                        <div class="accordion-body">
                                            <strong>This PHP method uses cURL to send WhatsApp messages via an API, offering features like text and media messages. The response contains the status of the sent messages and directs users to check the WhatsApp Log history for final status.</strong>
                                            <div class="code-box-copy">
                                                <button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
                                                <pre>
                                                            <code id="code-block-email-send" class="language-php">
$curl = curl_init();
$postdata = array(
    "contact" => array(
        array(
            "number" => "880123456789",
            "message" => "In publishing and graphic design, Lorem ipsum"
        ),
        array(
            "number" => "880123456789",
            "message" => "In publishing and graphic design, Lorem ipsum",
            "media" => "image",
            "url" => "https://example.com/image.jpeg"
        ),
        array(
            "number" => "880123456799",
            "message" => "In publishing an audio file, Lorem ipsum",
            "media" => "audio",
            "url" => "https://example.com/audio.mp3"
        ),
        array(
            "number" => "880123456799",
            "message" => "In publishing a video file, Lorem ipsum",
            "media" => "video",
            "url" => "https://example.com/video.mp4"
        ),
        array(
            "number" => "880123456799",
            "message" => "In publishing a document file, Lorem ipsum",
            "media" => "document",
            "url" => "https://example.com/document.pdf"
        )
    )
);

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{route('incoming.whatsapp.send')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($postdata),
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "whatsapp_logs": [
        {
            "uid": "b6ca078b-8a6d-4293-8118-b1498d4588aa",
            "to": "880123456789",
            "status": "Pending",
            "created_at": "2023-10-16 08:04 PM"
        },
        {
            "uid": "83beaaae-27d1-42fd-879d-464e393990d8",
            "to": "880123456789",
            "status": "Pending",
            "created_at": "2023-10-16 08:04 PM"
        },
        {
            "uid": "66c54d4b-b02e-41ab-9651-6805a7679dcb",
            "to": "880123456799",
            "status": "Pending",
            "created_at": "2023-10-16 08:04 PM"
        },
        {
            "uid": "ed4a8aa8-820b-4019-b889-b93ebcd7a636",
            "to": "880123456799",
            "status": "Pending",
            "created_at": "2023-10-16 08:04 PM"
        },
        {
            "uid": "9ade281f-341d-49bb-9a8f-f43c47f0cc8c",
            "to": "880123456799",
            "status": "Pending",
            "created_at": "2023-10-16 08:04 PM"
        }
    ],
    "message": "New WhatsApp Message request sent, please see in the WhatsApp Log history for final status"
}
                                                            </code>
                                                            </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEmailStatus">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWhatsappStatus" aria-expanded="false" aria-controls="collapseEmailStatus">
                                                <span class="badge bg--success">
                                                    GET
                                                </span>&nbsp;
                                        <span>{{url('api/get/whatsapp/{uid}')}}</span>
                                    </button>
                                </h2>
                                <div id="collapseWhatsappStatus" class="accordion-collapse collapse" aria-labelledby="headingEmailStatus" data-bs-parent="#emailSend">
                                    <div class="accordion-body">
                                        <strong>Using cURL, this PHP code fetches WhatsApp log data for a unique identifier (uid) from an API, including recipient number, message content, status, and the latest update time.</strong>
                                        <div class="code-box-copy">
                                            <button class="code-box-copy__btn" data-clipboard-target="#code-block-email-send" title="Copy"></button>
                                            <pre>
														<code id="code-block-email-send" class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => '{{url('api/get/whatsapp/{uid}')}}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Api-key: ###########################,
    ),
));

$response = curl_exec($curl);
curl_close($curl);

//response will return data in this format
{
    "status": "success",
    "whats_log": {
        "uid": "f2e43ee3-7fd5-4587-9f10-0e686c3b",
        "number": "880123456789",
        "content": "In publishing and graphic design, Lorem ipsum",
        "status": "Pending",
        "updated_at": "2023-10-16 08:19 PM"
    }
}

                                                        </code>
														</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-include')
    <script src="{{asset('assets/theme/admin/js/prism.js')}}"></script>
    <script src="{{asset('assets/theme/admin/js/clipboard.min.js')}}"></script>
    <script src="{{asset('assets/theme/admin/js/code-box-copy.min.js')}}"></script>
@endpush


@push('script-push')
<script>
    "use strict";
    (function($) {
        $('.code-box-copy').codeBoxCopy({
            tooltipText: 'Copied',
            tooltipShowTime: 1000,
            tooltipFadeInTime: 300,
            tooltipFadeOutTime: 300
        });
    })(jQuery);
</script>
@endpush

