<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PopupController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $popups = Popup::orderByDesc('created_at')->paginate(10);
        return view('admin.popups.index', compact('popups'));
    }

    public function create()
    {
        return view('admin.popups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_name' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $this->compressAndUpload($request->file('image'), 'uploads/popups');
            $data['image_path'] = 'storage/' . $path;
        }

        Popup::create($data);

        return redirect()->route('admin.popups.index')->with('success', 'Popup created successfully.');
    }

    public function edit(Popup $popup)
    {
        return view('admin.popups.edit', compact('popup'));
    }

    public function update(Request $request, Popup $popup)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_name' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($popup->image_path) {
                $storagePath = str_replace('storage/', '', $popup->image_path);
                $this->deleteImage($storagePath);
            }

            $path = $this->compressAndUpload($request->file('image'), 'uploads/popups');
            $data['image_path'] = 'storage/' . $path;
        }

        $popup->update($data);

        return redirect()->route('admin.popups.index')->with('success', 'Popup updated successfully.');
    }

    public function destroy(Popup $popup)
    {
        if ($popup->image_path) {
            $storagePath = str_replace('storage/', '', $popup->image_path);
            $this->deleteImage($storagePath);
        }
        $popup->delete();
        return redirect()->route('admin.popups.index')->with('success', 'Popup deleted successfully.');
    }

    public function toggleStatus(Popup $popup)
    {
        $popup->is_active = !$popup->is_active;
        $popup->save();
        return response()->json(['success' => true, 'is_active' => $popup->is_active]);
    }
}
