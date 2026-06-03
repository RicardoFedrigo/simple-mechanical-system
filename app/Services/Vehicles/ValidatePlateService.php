<?php

namespace App\Services\Vehicles;

class ValidatePlateService
{
    /**
     * Validate plate number: must contain only letters and numbers.
     * Returns the plate in uppercase if valid.
     *
     * @param string|null $plate
     * @return string|null Uppercase plate or null if empty
     * @throws \InvalidArgumentException If plate contains invalid characters
     */
    public function execute(?string $plate): ?string
    {
        if (empty($plate)) {
            throw new \InvalidArgumentException('Plate number cannot be empty.');
        }

        $trimmed = trim($plate);
        if ($trimmed === '') {
            throw new \InvalidArgumentException('Plate number cannot be empty.');
        }

        if (!preg_match('/^[A-Za-z0-9]+$/', $trimmed)) {
            throw new \InvalidArgumentException('Plate number must contain only letters and numbers.');
        }

        return strtoupper($trimmed);
    }
}
