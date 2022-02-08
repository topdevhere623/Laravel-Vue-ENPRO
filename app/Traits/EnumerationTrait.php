<?php


namespace App\Traits;


trait EnumerationTrait
{
    /**
     * @return string
     */
    public function getLiteral(): string
    {
        return $this->literal;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $literal
     */
    public function setLiteral(string $literal): void
    {
        $this->literal = $literal;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
