<?php

namespace Spatie\Honeypot;

use Illuminate\View\View;

class HoneypotViewComposer
{
    public function compose(View $view)
    {
        $honeyPotValues = Generator::generate();

        $view->with($honeyPotValues);
    }
}
