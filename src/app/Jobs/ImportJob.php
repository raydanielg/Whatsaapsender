<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\System\Contact\ContactService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $imported;

    /**
     * Create a new job instance.
     *
     * @param string $imported importId
     */
    public function __construct(string $imported)
    {
        $this->imported = $imported;
        $this->onQueue('default');
    }

    
    /**
     * handle
     *
     * @param ContactService $contactService
     * 
     * @return void
     */
    public function handle(ContactService $contactService): void
    {
       try {
            $import = Import::where('id', $this->imported)
                                ->where('status', 0)
                                ->first();
            if (!$import) return;
            $import->status = 1;
            $import->save();
            if($import->mime == 'csv' || $import->mime = 'xlsv') { 
                            
                $contactService->importContactFormFile($import->name, $import->path, $import->contact_structure, $import->group_id, $import->user_id);

            } else {
                return;
            }
       } catch(\Exception $e) {
        
        \Log::error("ImportJob failed: " . $e->getMessage());
       }
    }
}
