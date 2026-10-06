<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Location;

class LocationController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->view('admin/locations/index', ['locations' => Location::all()], 'layouts/admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->view('admin/locations/form', ['location' => new Location()], 'layouts/admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        Location::create($this->validatedData());

        $this->flash('success', 'Location added.');
        $this->redirect('/admin/locations');
    }

    public function edit(int $id): void
    {
        $this->requireAdmin();
        $this->view('admin/locations/form', ['location' => Location::findOrFail($id)], 'layouts/admin');
    }

    public function update(int $id): void
    {
        $this->requireAdmin();
        Location::findOrFail($id)->update($this->validatedData());

        $this->flash('success', 'Location updated.');
        $this->redirect('/admin/locations');
    }

    private function validatedData(): array
    {
        $validator = new Validator($_POST, [
            'name'      => 'required|max:100',
            'city'      => 'required|max:60',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $this->backWithErrors($validator->errors());
        }

        return [
            'name'      => $this->input('name'),
            'city'      => $this->input('city'),
            'latitude'  => (float) $this->input('latitude'),
            'longitude' => (float) $this->input('longitude'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
    }
}
