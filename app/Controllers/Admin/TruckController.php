<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Truck;
use App\Services\TruckSyncService;
use Throwable;

class TruckController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->view('admin/trucks/index', ['trucks' => Truck::all()], 'layouts/admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->view('admin/trucks/form', ['truck' => new Truck()], 'layouts/admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        Truck::create($this->validatedData());

        $this->flash('success', 'Truck added.');
        $this->redirect('/admin/trucks');
    }

    public function edit(int $id): void
    {
        $this->requireAdmin();
        $this->view('admin/trucks/form', ['truck' => Truck::findOrFail($id)], 'layouts/admin');
    }

    public function update(int $id): void
    {
        $this->requireAdmin();
        Truck::findOrFail($id)->update($this->validatedData());

        $this->flash('success', 'Truck updated.');
        $this->redirect('/admin/trucks');
    }

    /** Quick available / not available switch. */
    public function toggle(int $id): void
    {
        $this->requireAdmin();
        $truck = Truck::findOrFail($id);
        $truck->update(['is_available' => $truck->is_available ? 0 : 1]);

        $this->flash('success', "{$truck->name} is now " . ($truck->is_available ? 'available' : 'not available') . '.');
        $this->redirect('/admin/trucks');
    }

    /** Import / update trucks from the Truck API. */
    public function sync(): void
    {
        $this->requireAdmin();

        try {
            $result = (new TruckSyncService())->sync();
            $this->flash('success', "Sync finished: {$result['created']} new, {$result['updated']} updated, {$result['skipped']} skipped.");
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->flash('danger', 'Could not sync trucks: ' . $e->getMessage());
        }
        $this->redirect('/admin/trucks');
    }

    private function validatedData(): array
    {
        $validator = new Validator($_POST, [
            'name'         => 'required|max:100',
            'type'         => 'required|in:' . implode(',', Truck::TYPES),
            'size_ft'      => 'required|numeric|gte:1',
            'capacity_ton' => 'required|numeric|gte:0.1',
            'base_fare'    => 'required|numeric|gte:0',
            'per_km_rate'  => 'required|numeric|gte:1',
            'image_url'    => 'max:255',
            'description'  => 'max:2000',
        ]);
        if ($validator->fails()) {
            $this->backWithErrors($validator->errors());
        }

        return [
            'name'         => $this->input('name'),
            'type'         => $this->input('type'),
            'size_ft'      => (float) $this->input('size_ft'),
            'capacity_ton' => (float) $this->input('capacity_ton'),
            'base_fare'    => (float) $this->input('base_fare'),
            'per_km_rate'  => (float) $this->input('per_km_rate'),
            'image_url'    => $this->input('image_url') ?: null,
            'description'  => $this->input('description') ?: null,
            'is_available' => isset($_POST['is_available']) ? 1 : 0,
        ];
    }
}
