<?php

namespace Alex\LaravelDocSchema\Controllers;

use Illuminate\Routing\Controller;
use Alex\LaravelDocSchema\Events\LaravelInstallerFinished;
use Alex\LaravelDocSchema\Helpers\EnvironmentManager;
use Alex\LaravelDocSchema\Helpers\FinalInstallManager;
use Alex\LaravelDocSchema\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Alex\LaravelDocSchema\Helpers\InstalledFileManager $fileManager
     * @param \Alex\LaravelDocSchema\Helpers\FinalInstallManager $finalInstall
     * @param \Alex\LaravelDocSchema\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent(); 
        event(new LaravelInstallerFinished);
        return view('pdo::finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
