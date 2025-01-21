<?php 

declare(strict_types = 1);

namespace Framework;

use Framework\Request;
use Framework\Response;

interface RequestHandlerInterface {
    public function handle( Request $request ): Response;
}