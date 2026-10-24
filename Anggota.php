<?php

declare(strict_types=1);

class Anggota
{
    private string $id;
    private string $nama;

    public function __construct(string $id, string $nama)
    {
        $this->id = $id;
        $this->nama = $nama;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }
}
