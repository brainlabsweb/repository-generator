<?php

namespace {{namespace}};

use {{modelImport}};

class {{repositoryName}} implements {{contract}}
{

    /**
     * @var {{modelName}}
     */
    private ${{modelVariable}};

    /**
     * {{repositoryName}} constructor.
     * @param  {{modelName}}  ${{modelVariable}}
     */
    public function __construct({{modelName}} ${{modelVariable}})
    {
        $this->{{modelVariable}} = ${{modelVariable}};
    }
}