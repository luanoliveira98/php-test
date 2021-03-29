<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed(): FileCollection
    {
        $collection = new FileCollection();
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data', 'data2', 'data3']);
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value');

        $this->assertEquals('value', $collection->get('index1'));
    }
    
    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrievedArray()
    {
        $collection = new FileCollection();
        $collection->set('index5', ['data','data1','data2']);

        $this->assertIsArray($collection->get('index5'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new FileCollection();

        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = new FileCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new FileCollection();
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index5', ['data','data1','data2']);

        $this->assertEquals(4, $collection->count());
    }
}