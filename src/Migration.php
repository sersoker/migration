<?php
declare(strict_types=1);

namespace PcComponentes\Migration;

interface Migration
{
    public function upOperation(): void;
    public function downOperation(): void;
}
