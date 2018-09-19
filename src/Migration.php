<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <jgrodriguezcarrion@gmail.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration;

interface Migration
{
    public function upOperation(): void;
    public function downOperation(): void;
}
