<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    public function create()
    {
        return view('workspaces.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', Rule::in(Workspace::COLORS)],
            'emoji' => ['nullable', 'string', 'max:8'],
        ]);

        $workspace = $request->user()->workspaces()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'color' => $data['color'] ?? 'brand',
            'emoji' => $data['emoji'] ?? null,
        ]);

        $request->user()->forceFill(['current_workspace_id' => $workspace->id])->save();

        return redirect()->route('dashboard')->with('status', "Workspace \"{$workspace->name}\" dibuat dan diaktifkan.");
    }

    public function switch(Request $request, Workspace $workspace): RedirectResponse
    {
        $this->authorizeOwnership($request, $workspace);

        $request->user()->forceFill(['current_workspace_id' => $workspace->id])->save();

        return redirect()->route('dashboard')->with('status', "Beralih ke \"{$workspace->name}\".");
    }

    public function update(Request $request, Workspace $workspace): RedirectResponse
    {
        $this->authorizeOwnership($request, $workspace);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', Rule::in(Workspace::COLORS)],
            'emoji' => ['nullable', 'string', 'max:8'],
        ]);

        $workspace->update($data);

        return back()->with('status', 'Workspace diperbarui.');
    }

    public function destroy(Request $request, Workspace $workspace): RedirectResponse
    {
        $this->authorizeOwnership($request, $workspace);

        if ($request->user()->workspaces()->count() <= 1) {
            return back()->withErrors(['workspace' => 'Tidak bisa menghapus workspace terakhir.']);
        }

        $wasCurrent = $request->user()->current_workspace_id === $workspace->id;
        $workspace->delete();

        if ($wasCurrent) {
            $next = $request->user()->workspaces()->first();
            $request->user()->forceFill(['current_workspace_id' => $next?->id])->save();
        }

        return redirect()->route('dashboard')->with('status', 'Workspace dihapus.');
    }

    private function authorizeOwnership(Request $request, Workspace $workspace): void
    {
        if ($workspace->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
