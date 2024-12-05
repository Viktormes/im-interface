<?php

namespace App\Sessions;

/**
 * @property string $id
 * @property array $identifier
 * @property array $name
 * @property array $generalPractitioner
 * @property array $diagnoses
 * @property string $last_observation
 */
class SelectedPatientSession
{
    const KEY = 'selected_patient';

    public function __get(string $name)
    {
        return session(static::KEY.'.'.$name, default: null);
    }

    public function all(): ?array
    {
        return $this->get();
    }

    public function get(): ?array
    {
        return session(static::KEY, default: null);
    }

    public function set(array $value): void
    {
        session()->put(static::KEY, $value);
    }

    public function put(array $value): void
    {
        $this->set($value);
    }

    public function has(): bool
    {
        return session()->has(static::KEY);
    }

    public function clear(): void
    {
        session()->forget(static::KEY);
    }
}
