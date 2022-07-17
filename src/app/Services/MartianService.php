<?php

namespace App\Services;

use App\Models\Martian;
use Illuminate\Database\Eloquent\Collection;

class MartianService
{
    public function list(string $search)
    {
        return Martian::filterName($search)
            ->paginate(15);
    }

    public function findById(int $id): Martian
    {
        return Martian::find($id);
    }

    public function create(array $data = []): Martian
    {
        return Martian::create($data);
    }

    public function update(Martian $martian, array $data = []): Martian
    {
        $martian->update($data);

        return $martian->refresh();
    }

    public function delete(Martian $martian): bool
    {
        if (is_null($martian)) {
            return false;
        }

        return $martian->delete();
    }

    public function qualityTraders(): Collection
    {
        return Martian::canTrade()->get();
    }
}
