<?php

namespace App\Service;

use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\Support\Arr;

class MailService
{
     ## Email Verification Check
     
     /**
      * verifyEmail
      *
      * @param string $email
      * 
      * @return array
      */
     public function verifyEmail(string $email): array {

          $result = [
               'valid' => false,
               'checks' => [],
               'suggested_email' => null,
               'reason' => null
          ];

          $emailAdditionalChecks = site_settings(SettingKey::VERIFY_EMAIL_ADDITIONAL_CHECKS->value);
          if (!$emailAdditionalChecks) return $result;

          $emailAdditionalChecks = json_decode($emailAdditionalChecks, true);
          
          if (Arr::get($emailAdditionalChecks, 'invalid_syntax') == StatusEnum::TRUE->status()) {
               if (!preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', $email)) {

                    $result['reason'] = Arr::get($emailAdditionalChecks, 'invalid_syntax_message');
                    return $result;
               }
               $result['checks']['syntax'] = true;
          }
 
          list($localPart, $domain) = explode('@', $email);
 
          if (Arr::get($emailAdditionalChecks, 'invalid_domain') == StatusEnum::TRUE->status()) {
               
               if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                    $result['reason'] = Arr::get($emailAdditionalChecks, 'invalid_domain_message');
                    return $result;
               }
               $result['checks']['mx'] = true;
          }

          if (Arr::get($emailAdditionalChecks, 'disposable_domain') == StatusEnum::TRUE->status()) {
          
               $disposableDomainList = site_settings(SettingKey::DISPOSABLE_DOMAIN_LIST->value);
               $disposableDomains  = $disposableDomainList 
                                        ? json_decode($disposableDomainList, true) 
                                        : [];
               
               if (in_array($domain, $disposableDomains)) {
                    $result['reason'] = Arr::get($emailAdditionalChecks, 'disposable_domain_message');
                    $result['checks']['disposable'] = false;
                    return $result;
               }
               $result['checks']['disposable'] = true;
          }
 
          if (Arr::get($emailAdditionalChecks, 'domain_typos') == StatusEnum::TRUE->status()) {

               $commonDomainList = site_settings(SettingKey::COMMON_DOMAIN->value);
               $commonDomains = $commonDomainList ? json_decode($commonDomainList, true) : [];
               $suggestedDomain = collect($commonDomains)
                                        ->first(function ($domainData) use ($domain) {
                                             return in_array($domain, Arr::get($domainData, 'typo', []));
                                        });

               if ($suggestedDomain) {
                    $result['suggested_email'] = $localPart . '@' . Arr::get($suggestedDomain, 'name.0', $domain);
                    $result['checks']['typo'] = false;
                    $result['reason'] = Arr::get($emailAdditionalChecks, 'domain_typo_message', 'Possible typo detected');
               } else {
                    $result['checks']['typo'] = true;
               }
          }
 
          if (Arr::get($emailAdditionalChecks, 'role_based_email') == StatusEnum::TRUE->status()) {

               $commonRoleList     = site_settings(SettingKey::EMAIL_ROLE_LIST->value);
               $commonRoles        = $commonRoleList 
                                        ? json_decode($commonRoleList, true) 
                                        : [];
     
               if (in_array(strtolower($localPart), (array) $commonRoles)) {
                    $result['reason'] = Arr::get($emailAdditionalChecks, 'role_based_message');
                    return $result;
               }
          }
 
          if (Arr::get($emailAdditionalChecks, 'check_tld') == StatusEnum::TRUE->status()) {

               $suspiciousTLDList  = site_settings(SettingKey::TLD_LIST->value);
               $suspiciousTLDs     = $suspiciousTLDList 
                                        ? json_decode($suspiciousTLDList, true) 
                                        : [];
     
               $hasSuspiciousTLD = collect($suspiciousTLDs)
                                        ->contains(function ($tld) use ($domain) {
                                             return str_ends_with($domain, $tld);
                                        });
     
               if ($hasSuspiciousTLD) {
                    $result['reason'] = Arr::get($emailAdditionalChecks, 'tld_message');
                    return $result;
               }
          }
 
          $result['valid'] = collect($result['checks'])->every(function ($value) {
               return $value === true;
          });
     
          return $result;
     }

     /**
      * processMailVerificationMessage
      *
      * @param array $data
      * 
      * @return string
      */
     public function processMailVerificationMessage(array $data): string {

          $status         = Arr::get($data, "valid", false);
          $suggestedEmail = Arr::get($data, "suggested_email");
          $reason         = $suggestedEmail 
                              ? Arr::get($data, "reason") . translate(". Suggested Email: "). $suggestedEmail
                              : Arr::get($data, "reason");
          return $status 
                    ? translate('Successfully checked Email Address Validity') 
                    : ($reason 
                         ? translate('The email address is invalid or cannot receive emails. Reason: ').$reason
                         : translate('The email address is invalid or cannot receive emails.'));
     }
}