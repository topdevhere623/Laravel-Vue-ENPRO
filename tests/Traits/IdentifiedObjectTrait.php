<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\Name;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait IdentifiedObjectTrait
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function IdentifiedObject(IdentifiedObjectInterface $identifiedobject)
    {

        $faker = \Faker\Factory::create();
        $this->assertIsArray($identifiedobject->getNames());
        $name = $faker->text(200);
        $this->assertEmpty($identifiedobject->setName($name));
        $this->assertEquals($identifiedobject->getName(), $name);
        $name2 = new Name();
        $name2->name = $faker->name();
        $this->assertEmpty($identifiedobject->addName($name2));
        $this->assertTrue(in_array($name2, $identifiedobject->getNames()));
        $name3 = new Name();
        $name3->name = $faker->name();
        $identifiedobject->addName($name3);
        $this->assertTrue(in_array($name3, $identifiedobject->getNames()));
        $this->assertEmpty($identifiedobject->removeName($name2));
        $this->assertFalse(in_array($name2, $identifiedobject->getNames()));
        $aliasName = $faker->text(200);
        $this->assertEmpty($identifiedobject->setAliasName($aliasName));
        $this->assertEquals($identifiedobject->getAliasName(), $aliasName);
        $description = $faker->text(400);
        $this->assertEmpty($identifiedobject->setDescription($description));
        $this->assertEquals($identifiedobject->getDescription(), $description);
        $uuid = $identifiedobject->getMRID();
        $this->assertEquals(36, strlen($identifiedobject->getMRID()), 'The MRID should to have 36 chars');
        $identifiedobject->save();
        $id = $identifiedobject->id;
        $identifiedobject2 = $this->getSaved($id);
        $this->assertEquals($uuid, $identifiedobject2->getMRID(), 'The MRID does not equals with saved in DB');
        $this->assertEquals($identifiedobject2->getDescription(), $description, 'The Description does not equals with saved in DB');
        $this->assertEquals($identifiedobject2->getAliasName(), $aliasName, 'The AliasName does not equals with saved in DB');
        $this->assertEquals($identifiedobject2->getName(), $name, 'The Name does not equals with saved in DB');
        $this->assertEquals($name3->name, $identifiedobject2->getNames()[0]['name'], 'The Name of object in Names does not equals with saved in DB');
    }
}
