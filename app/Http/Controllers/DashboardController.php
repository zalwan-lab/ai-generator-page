<?php

namespace App\Http\Controllers;

use App\Services\ContextManager;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ContextManager $context)
    {
        $user = $request->user();
        $workspace = $user->currentWorkspace();
        $bundle = $context->buildForWorkspace($workspace);
        $recent = $workspace->salesPages()->limit(5)->get();

        return view('dashboard', [
            'workspace' => $workspace,
            'workspaces' => $user->workspaces,
            'recent' => $recent,
            'context' => $bundle,
            'totalPages' => $workspace->salesPages()->count(),
        ]);
    }
}
