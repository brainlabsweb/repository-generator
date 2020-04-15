<?php

namespace {{controllerNamespace}};

use {{contractUse}};

class {{className}} extends Controller
{
    /**
     * @var {{contract}}
     */
    private ${{variableName}};

    /**
     * {{className}} constructor.
     * @param  {{contract}}  ${{variableName}}
     */
    public function __construct({{contract}} ${{variableName}})
    {
        $this->{{variableName}} = ${{variableName}};
    }
}