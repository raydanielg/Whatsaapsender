<?php
namespace App\Http\Utility;

use App\Enums\CommunicationStatusEnum;
use Exception;
use Mailgun\Mailgun;
use App\Models\User;
use GuzzleHttp\Client;
use Aws\Ses\SesClient;
use App\Models\EmailLog;
use App\Service\Admin\Dispatch\EmailService;
use App\Models\CampaignContact;
use Carbon\Carbon;
use SendGrid\Mail\TypeException;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Mailer\Transport;

class SendEmail
{
    /**
     * @param $emailFrom
     * @param $sitename
     * @param $emailTo
     * @param $subject
     * @param $messages
     * @param $emailLog
     * @return void
     */
    public static function sendPHPMail($emailFrom, $sitename, $emailTo, $subject, $messages, $emailLog): void {

        $headers  = "From: $sitename <$emailFrom> \r\n";
        $headers .= "Reply-To: $sitename <$emailFrom> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        try {

            if($emailLog->contact_id) {

               $status = "Success";
            }
            @mail($emailTo, $subject, $messages, $headers);
            $meta_data = $emailLog->meta_data;
            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
            $emailLog->meta_data = $meta_data;
            $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
            $emailLog->save();
        } catch (\Exception $exception) {
            \Log::error("Email dispatch fail: " . $exception->getMessage());
            if($emailLog->user_id) {

                EmailService::updateEmailLogAndCredit($emailLog, $exception->getMessage());
            }
        }
    }
   
    /**
     * @param $emailFrom
     * @param $fromName
     * @param $emailTo
     * @param $replyTo
     * @param $subject
     * @param $messages
     * @param $emailLog
     * @return void
     */
    public static function sendSMTPMail($emailTo, $replyTo, $subject, $messages, $emailLog, $emailMethod, $emailFromName): void {

        try {

            $username   = $emailMethod->mail_gateways->username;
            $password   = $emailMethod->mail_gateways->password;
            $host       = $emailMethod->mail_gateways->host;
            $port       = $emailMethod->mail_gateways->port;
            $encryption = $emailMethod->mail_gateways->encryption;
            $pattern    = '/[\?#\[\]@!$&\'()\*\+,;=]/';
    
            $encodedUsername = preg_match($pattern, $username) ? urlencode($username) : $username;
            $encodedPassword = preg_match($pattern, $password) ? urlencode($password) : $password;
    
            $dsn = sprintf(
                'smtp://%s:%s@%s:%d?encryption=%s',
                $encodedUsername,
                $encodedPassword,
                $host,
                $port,
                $encryption
            );
            if($encryption != 'ssl') {
                $dsn .= '&verify_peer=false';    
            }
            
            $transport = Transport::fromDsn($dsn);
            $mailer    = new Mailer($transport);
            
            $email = (new Email())
                ->from(new Address($emailMethod->address, $emailFromName))
                ->to($emailTo)
                ->replyTo($replyTo)
                ->subject($subject)
                ->html($messages);

            if ($emailLog->campaign_id !== null && isset($emailLog->meta_data['unsubscribe_link'])) {
                $unsubscribeLink = $emailLog->meta_data['unsubscribe_link']; 
                $email->getHeaders()->addTextHeader('List-Unsubscribe', ' <mailto:' . $emailMethod->address . '?subject=unsubscribe>, <' . $unsubscribeLink . '>');
                $email->getHeaders()->addTextHeader('List-Unsubscribe-Post', 'One-Click');
            }

            $mailer->send($email);
            $meta_data = $emailLog->meta_data;
            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
            $emailLog->meta_data = $meta_data;
            $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
            $emailLog->save();

        } catch (\Exception $exception) {
            
            \Log::error("Email dispatch fail: " . $exception->getMessage());
           
            if($emailLog->user_id) {

                EmailService::updateEmailLogAndCredit($emailLog, CommunicationStatusEnum::FAIL->value, $exception->getMessage());
            } else {
                $emailLog->status = CommunicationStatusEnum::FAIL->value;
                $emailLog->response_message = !is_null($exception->getMessage()) ? $exception->getMessage() : null;
                $emailLog->save();
            }
        }
    }

    /**
     * @param $emailFrom
     * @param $fromName
     * @param $emailTo
     * @param $subject
     * @param $messages
     * @param $emailLog
     * @param $credentials
     * @return void
     * @throws TypeException
     */
    public static function sendGrid($emailFrom, $fromName, $emailTo, $subject, $messages, $emailLog, $credentials): void {

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($emailFrom, $fromName);
        $email->addTo($emailTo);
        $email->setSubject($subject);
        $email->addContent("text/html", $messages);
        $sendgrid = new \SendGrid(@$credentials);

        try {

            $response = $sendgrid->send($email);
            if (!in_array($response->statusCode(), ['201','200','202'])) {

                $emailLog->status = EmailLog::FAILED;
                $emailLog->response_gateway = "Error";
                $emailLog->save();
                $user = User::find($emailLog->user_id);
                if ($user != '') {

                    $user->email_credit += 1;
                    $user->save();
                }
            } else {
                $meta_data = $emailLog->meta_data;
                $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                $emailLog->meta_data = $meta_data;
                $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
                $emailLog->save();
            }
        } catch (\Exception $exception) {
            \Log::error("Email dispatch fail: " . $exception->getMessage());
            if($emailLog->user_id) {
                EmailService::updateEmailLogAndCredit($emailLog, $exception->getMessage());
            } else {
                $emailLog->status = CommunicationStatusEnum::FAIL->value;
                $emailLog->response_message = !is_null($exception->getMessage()) ? $exception->getMessage() : null;
                $emailLog->save();
            }
            
        }
    }

    /**
     * @param $emailFrom
     * @param $emailTo
     * @param $fromName
     * @param $subject
     * @param $messages
     * @return string|void
     */
    public static function sendMailJetMail($emailTo, $subject, $messages, $emailLog, $gateway, $email_reply_to = null, $email_from_name = null) {

        $mailCredential = $gateway->mail_gateways;
        $result         = null;
        $emailParts     = explode('@', $emailTo);
        $receiver       = $emailParts[0];
       
        try {

            $body = [
                'Messages' => [
                    [
                    'From' => [
                        'Email' => $email_reply_to ? $email_reply_to : $gateway->address,
                        'Name'  => $email_from_name ? $email_from_name :$gateway->name
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name'  => $receiver
                        ]
                    ],
                    'Subject'  => $subject,
                    "TextPart" => " ",
                    'HTMLPart' => $messages
                    ]
                ]
            ];
            $client = new Client([
                'base_uri' => 'https://api.mailjet.com/v3.1/',
            ]);
 
            $response = $client->request('POST', 'send', [
                'json' => $body,
                'auth' => [$mailCredential->api_key, $mailCredential->secret_key]
            ]);
            if($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $response = json_decode($body);
                
                if ($response->Messages[0]->Status == 'success') {
                    if ($emailLog->contact_id) {
                        $status = "Success";
                    }
                    $meta_data = $emailLog->meta_data;
                    $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                    $emailLog->meta_data = $meta_data;
                    $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
                    $emailLog->save();
                    
                    Artisan::call('optimize:clear');
                    \Illuminate\Support\Facades\Artisan::call('queue:restart');
                }
                else {
                    if($emailLog->user_id) {
                        EmailService::updateEmailLogAndCredit($emailLog, translate("Email could not be sent"));
                    } else {
                        $emailLog->status = CommunicationStatusEnum::FAIL->value;
                        $emailLog->response_message = translate("Email could not be sent");
                        $emailLog->save();
                    }
                }
            }
        } catch (\Exception $exception) {

            \Log::error("Email dispatch fail: " . $exception->getMessage());
            if($emailLog->user_id) {

                EmailService::updateEmailLogAndCredit($emailLog, $exception->getMessage());
            } else {
                $emailLog->status = CommunicationStatusEnum::FAIL->value;
                $emailLog->response_message = !is_null($exception->getMessage()) ? $exception->getMessage() : null;
                $emailLog->save();
            }
            
        }
    }

    /**
     * send mail using ses
     *
     */
    public static function sendSesMail($recipient_emails, $subject, $messages, $emailLog, $gateway) {

        $result = null;
        $mailCredential = $gateway->mail_gateways;
       
        try {
            $SesClient = new SesClient([
                'profile' => $mailCredential->profile,
                'version' => $mailCredential->version,
                'region'  => $mailCredential->region
            ]);
            $sender_email = $gateway->address;
            $configuration_set = 'ConfigSet';
            $html_body = $messages;
            $char_set = 'UTF-8';
            $result = $SesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => $recipient_emails,
                ],
                'ReplyToAddresses' => [$sender_email],
                'Source'           => $sender_email,
                'Message'          => [
                    'Body' => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data'    => $html_body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data'    => $subject,
                    ],
                ],
                'ConfigurationSetName' => $configuration_set,
            ]);
            \Log::info("Amazon Result Log: " . $result);
            $meta_data = $emailLog->meta_data;
            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
            $emailLog->meta_data = $meta_data;
            $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
            $emailLog->save();
            
        } catch (\Exception $exception) {
            \Log::error("Email dispatch fail: " . $exception->getMessage());
           
            if($emailLog->user_id) {

                EmailService::updateEmailLogAndCredit($emailLog, CommunicationStatusEnum::FAIL->value, $exception->getMessage());
            } else {
                $emailLog->status = CommunicationStatusEnum::FAIL->value;
                $emailLog->response_message = !is_null($exception->getMessage()) ? $exception->getMessage() : null;
                $emailLog->save();
            }
        }
    }

    /**
     * send mail using MailGun
     *
     * @param $details , $email
     */
    public static function sendMailGunMail($recipient_email, $subject, $messages, $emailLog, $gateway) {
        
        $result         = null;
        $mailCredential = $gateway->mail_gateways;
        $mailGun        = Mailgun::create($mailCredential->secret_key);
        $domain         = $mailCredential->verified_domain;
        try {

            $result =  $mailGun->messages()->send( $domain, [
                'from'    => $gateway->address,
                'to'      => $recipient_email,
                'subject' => $subject,
                'html'    => $messages
            ]);

            \Log::info("mailGun Result Log: " . $result);
            $meta_data = $emailLog->meta_data;
            $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
            $emailLog->meta_data = $meta_data;
            $emailLog->status = CommunicationStatusEnum::DELIVERED->value;
            $emailLog->save();

        } catch (\Exception $exception) {

            \Log::error("Email dispatch fail: " . $exception->getMessage());
            if($emailLog->user_id) {

                EmailService::updateEmailLogAndCredit($emailLog, CommunicationStatusEnum::FAIL->value, $exception->getMessage());
            } else {
                $emailLog->status = CommunicationStatusEnum::FAIL->value;
                $emailLog->response_message = !is_null($exception->getMessage()) ? $exception->getMessage() : null;
                $emailLog->save();
            }
        }
    }
}
