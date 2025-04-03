<?php
namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\Core\UpgradeService;
use App\Service\Admin\Core\SettingService;

class UpgradeVersionMigrateController extends Controller
{
    public SettingService $settingService;
    public UpgradeService $upgradeService;

    /**
     * __construct
     *
     * @param SettingService $settingService
     * @param UpgradeService $upgradeService
     */
    public function __construct(SettingService $settingService, UpgradeService $upgradeService) { 

        $this->settingService = $settingService;
        $this->upgradeService = $upgradeService;
    }

    /**
     * index
     *
     * @return View
     */
    public function index(): View {

        return $this->upgradeService->loadIndex();
    }

    /**
     * update
     *
     * @return RedirectResponse
     */
    public function update(): RedirectResponse {
        
        return $this->upgradeService->update();
    }
    
    /**
     * verify
     *
     * @return View
     */
    public function verify(): View {
        
        return $this->upgradeService->loadVerify();
    }

    /**
     * store
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {

        return $this->upgradeService->store($request);
    }
}