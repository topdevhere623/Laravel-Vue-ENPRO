<?php


namespace App\Contracts\CIM\Wires;


use App\Models\Name;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface IdentifiedObjectInterface
 * @package App\Contracts
 */
interface IdentifiedObjectInterface
{
    /**
     * @return string
     */
    public function getAliasName() : string;

    /**
     * @param string $aliasName
     */
    public function setAliasName(string $aliasName) : void;

    /**
     * @return string
     */
    public function getDescription() : string;

    /**
     * @param string $descriptiojn
     */
    public function setDescription(string $descriptiojn): void;

    /**
     * @return string
     */
    public function getMRID() : string;

    /**
     * @param string $mRID
     */
    public function setMRID(string $mRID): void;

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @param String $name
     */
    public function setName(String $name) : void;

    /**
     * @return array
     */
    public function getNames() : array;

    /**
     * @param Name $name
     */
    public function addName(Name $name) : void;

    /**
     * @param Name $name
     */
    public function removeName(Name $name) : void;

}
