<?php


namespace App\Contracts\CIM\Wires;


/**
 * Interface EnumerationInterface
 * @package App\Contracts\CIM\Wires
 */
interface EnumerationInterface
{
    /**
     * @return string
     */
    public function getLiteral(): string;

    /**
     * @return int
     */
    public function getValue(): int;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string $literal
     */
    public function setLiteral(string $literal): void;

    /**
     * @param int $value
     */
    public function setValue(int $value): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

}
