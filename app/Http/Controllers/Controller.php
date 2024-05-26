<?php



namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

//HTTP Codes (rajouter ceux qui manque)
define('OK', 200);
define('CREATED', 201);
define('NO_CONTENT', 204);
define('BAD_REQUEST', 400);
define('FORBIDDEN', 403);
define('NOT_FOUND', 404);
define('INVALID_DATA', 422);
define('TOO_MANY_REQUESTS', 429);
define('SERVER_ERROR', 500);

//Pagination
define('SEARCH_PAGINATION', 20);

//Roles
define('USER', 1);
define('ADMIN', 2);

/** * @OA\Info(title="Films API", version="0.1") */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
