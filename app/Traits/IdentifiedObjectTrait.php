<?php


namespace App\Traits;


use App\Models\Identifiedobject;
use App\Models\Name;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

trait IdentifiedObjectTrait
{

    public $names;

    public function selfIdentification()
    {
        if($this->id) $this->getIdentifiedObject()->class_object = self::class . ':' . $this->id;
    }

    public function getIdentification()
    {
        return $this->getIdentifiedObject()->class_object;
    }

    public function setAssetIdentification($className, $classId)
    {
        if($this->id) $this->getIdentifiedObject()->class_object = $className . ':' . $classId;
    }


    /**
     * @return array
     */
    public function getNames() : array
    {
        if($this->getIdentifiedObject()->names) return $this->getIdentifiedObject()->names;
        $this->getIdentifiedObject()->names = [];
        foreach($this->getIdentifiedObject()->names()->get() as $name) {
            $this->getIdentifiedObject()->names[] = $name;
        };
        return $this->getIdentifiedObject()->names;
    }

    /**
     * @param Name|null $name
     */
    public function addName(Name $name = null) : void
    {
        $this->getIdentifiedObject()->names = $this->getNames();
        if(!in_array($name, $this->getIdentifiedObject()->names)) {
            array_push($this->getIdentifiedObject()->names, $name);
        }
    }

    /**
     * @param Name|null $name
     * @throws \Exception
     */
    public function removeName(Name $name ) : void
    {
        if(in_array($name, $this->getNames())) {
            array_splice($this->getIdentifiedObject()->names, array_search($name, $this->getIdentifiedObject()->names ), 1);
            if($name->id) {
                $name->delete();
            }
        }
    }

    public function getName() : string
    {
        return $this->getIdentifiedObject()->name ?: '';
    }

    public function setName($name = '') : void
    {
        $this->getIdentifiedObject()->name = empty($name) ? '' : $name;
    }

    /**
     * @return string
     */
    public function getAliasName(): string
    {
        return $this->getIdentifiedObject()->aliasname ?: '';
    }

    /**
     * @param string $aliasName
     */
    public function setAliasName($aliasName = ''): void
    {
        $this->getIdentifiedObject()->aliasname = $aliasName;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->getIdentifiedObject()->description ?: '';
    }

    /**
     * @param string $description
     */
    public function setDescription($description = ''): void
    {
        $this->getIdentifiedObject()->description = $description;
    }

    /**
     * @return string
     */
    public function getMRID() : string
    {
        if(!$this->getIdentifiedObject()->mrid) {
            $this->getIdentifiedObject()->mrid = Uuid::uuid4()->toString();
            //$this->getIdentifiedObject()->mrid =
        }
        return $this->getIdentifiedObject()->mrid ?: '';
    }

    /**
     * @param string $mRID
     */
    public function setMRID($mRID = ''): void
    {
        $this->getIdentifiedObject()->mrid = $mRID;
    }




}
