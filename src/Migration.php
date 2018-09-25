<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration;

interface Migration
{
    public function upOperation(): void;
    public function downOperation(): void;
}
