<?php

namespace App\Repositories;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/** JSON storage adapter. Replace this class with an Eloquent adapter in a future MySQL migration. */
abstract class JsonRepository
{
    protected string $file;

    public function all(): array
    {
        $path = storage_path('app/data/'.$this->file.'.json');
        File::ensureDirectoryExists(dirname($path));
        if (! File::exists($path)) File::put($path, '[]');
        return json_decode(File::get($path), true) ?: [];
    }

    public function find(string $id): ?array
    {
        foreach ($this->all() as $item) if (($item['id'] ?? '') === $id) return $item;
        return null;
    }

    public function where(string $field, mixed $value): array
    {
        return array_values(array_filter($this->all(), fn ($item) => ($item[$field] ?? null) == $value));
    }

    public function create(array $data): array
    {
        $items = $this->all();
        $now = now()->toIso8601String();
        $data = array_merge(['id' => (string) Str::uuid(), 'created_at' => $now, 'updated_at' => $now], $data);
        $items[] = $data; $this->save($items);
        return $data;
    }

    public function update(string $id, array $data): ?array
    {
        $items = $this->all();
        foreach ($items as $key => $item) {
            if (($item['id'] ?? '') === $id) {
                $items[$key] = array_merge($item, $data, ['updated_at' => now()->toIso8601String()]);
                $this->save($items); return $items[$key];
            }
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $items = $this->all(); $filtered = array_values(array_filter($items, fn ($item) => ($item['id'] ?? '') !== $id));
        if (count($items) === count($filtered)) return false;
        $this->save($filtered); return true;
    }

    protected function save(array $items): void
    {
        File::put(storage_path('app/data/'.$this->file.'.json'), json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), true);
    }
}
