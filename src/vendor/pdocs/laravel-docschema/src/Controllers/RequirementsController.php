<?php

namespace Alex\LaravelDocSchema\Controllers;

use Illuminate\Routing\Controller;
use Alex\LaravelDocSchema\Helpers\RequirementsChecker; 

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @param RequirementsChecker $checker
     */
    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('requirements.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('requirements.requirements')
        );

        return view('pdo::requirements', compact('requirements', 'phpSupportInfo'));
    }
}
