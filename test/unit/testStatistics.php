<?php

class testStatistics extends UnitTestCase
{
	private $_statistics1;
	private $_statistics2;

	private $_selected_servers = array(
		'master' => 'mysql_server_1',
		'slave'  => 'mysql_server_2',
		);
	private $_comparison_data1 = array(
		'database::foobar1' => array(
			'name'  => 'foobar1',
			'data'  => array(
				'mysql_server_1' => array(
					'state' => 'present',
					'text'  => 'present',
				),
				'mysql_server_2' => array(
					'state' => 'present',
					'text'  => 'present',
				),
			),
			'state' => 'identical',
		),
		'database::foobar2' => array(
			'name'  => 'foobar2',
			'data'  => array(
				'mysql_server_1' => array(
					'state' => 'present',
					'text'  => 'present',
				),
				'mysql_server_2' => array(
					'state' => 'present',
					'text'  => 'present',
				),
			),
			'state' => 'identical',
		),
		'database::foobar3' => array(
			'name'  => 'foobar3',
			'data'  => array(
				'mysql_server_1' => array(
					'state' => 'missing',
					'text'  => 'MISSING',
				),
				'mysql_server_2' => array(
					'state' => 'present',
					'text'  => 'present',
				),
			),
			'state' => 'different',
		),
		'database::foobar4' => array(
			'name'  => 'foobar4',
			'data'  => array(
				'mysql_server_1' => array(
					'state' => 'present',
					'text'  => 'present',
				),
				'mysql_server_2' => array(
					'state' => 'present',
					'text'  => 'present',
				),
			),
			'state' => 'different',
		),
	);

	private $_comparison_data2 = array(
		'table::foobar1' => array(
			'name'  => 'foobar1',
			'data'  => array(
				'mysql_server_1' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
				'mysql_server_2' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
			),
			'state' => 'identical'
		),
		'table::foobar2' => array(
			'name'  => 'foobar2',
			'data'  => array(
				'mysql_server_1' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'latin1',
				),
				'mysql_server_2' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
			),
			'state' => 'different'
		),
		'table::foobar3' => array(
			'name'  => 'foobar3',
			'data'  => array(
				'mysql_server_1' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
				'mysql_server_2' => array(
					'state'   => 'missing',
				),
			),
			'state' => 'different'
		),
		'table::foobar4' => array(
			'name'  => 'foobar4',
			'data'  => array(
				'mysql_server_1' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
				'mysql_server_2' => array(
					'state'   => 'present',
					'engine'  => 'MyISAM',
					'charset' => 'utf8',
				),
			),
			'state' => 'identical'
		),
	);

	public function setUp()
	{
		$this->_statistics1 = new Statistics($this->_selected_servers, $this->_comparison_data1);
		$this->_statistics2 = new Statistics($this->_selected_servers, $this->_comparison_data2);
	}
	
	public function tearDown()
	{
		unset($this->_statistics1);
		unset($this->_statistics2);
	}

	public function testInit()
	{
		$this->assertTrue($this->_statistics1 instanceof Statistics);
		$this->assertTrue($this->_statistics2 instanceof Statistics);
	}

	public function testGetIdentical()
	{
		$this->assertEqual($this->_statistics1->getIdentical(), 2);
		$this->assertEqual($this->_statistics2->getIdentical(), 2);
	}

	public function testGetDifferent()
	{
		$this->assertEqual($this->_statistics1->getDifferent(), 2);
		$this->assertEqual($this->_statistics2->getDifferent(), 2);
	}

	public function testGetDifferentAndPresentOnBoth()
	{
		$this->assertEqual($this->_statistics1->getDifferentAndPresentOnBoth(), 1);
		$this->assertEqual($this->_statistics2->getDifferentAndPresentOnBoth(), 1);
	}
	
	public function testGetMissing()
	{
		$this->assertEqual($this->_statistics1->getMissing('master'), 1);
		$this->assertEqual($this->_statistics1->getMissing('slave'),  0);
		
		$this->assertEqual($this->_statistics2->getMissing('master'), 0);
		$this->assertEqual($this->_statistics2->getMissing('slave'),  1);
	}

	public function testGetTotalMissing()
	{
		$this->assertEqual($this->_statistics1->getTotalMissing(), 1);
		$this->assertEqual($this->_statistics2->getTotalMissing(), 1);
	}

	public function testGetTotalCount()
	{
		$this->assertEqual($this->_statistics1->getTotalCount(), 4);
		$this->assertEqual($this->_statistics2->getTotalCount(), 4);
	}

	public function testHasEngineStatistics()
	{
		$this->assertFalse($this->_statistics1->hasEngineStatistics());
		$this->assertTrue($this->_statistics2->hasEngineStatistics());
	}

	public function testHasCharacterSetStatistics()
	{
		$this->assertFalse($this->_statistics1->hasCharacterSetStatistics());
		$this->assertTrue($this->_statistics2->hasCharacterSetStatistics());
	}

	public function testGetAllEngines()
	{
		$this->assertEqual(count($this->_statistics1->getAllEngines()), 0);
		$this->assertEqual(count($this->_statistics2->getAllEngines()), 1);

		$engines = $this->_statistics2->getAllEngines();

		$this->assertTrue(in_array('MyISAM', $engines));
	}

	public function testGetAllCharacterSets()
	{
		$this->assertEqual(count($this->_statistics1->getAllCharacterSets()), 0);
		$this->assertEqual(count($this->_statistics2->getAllCharacterSets()), 2);

		$charsets = $this->_statistics2->getAllCharacterSets();

		$this->assertTrue(in_array('latin1', $charsets));
		$this->assertTrue(in_array('utf8',   $charsets));
	}

	public function testGetEngineCount()
	{
		$this->assertEqual($this->_statistics1->getEngineCount('master', 'MyISAM'), null);
		$this->assertEqual($this->_statistics1->getEngineCount('slave',  'MyISAM'), null);
		$this->assertEqual($this->_statistics2->getEngineCount('master', 'MyISAM'), 4);
		$this->assertEqual($this->_statistics2->getEngineCount('slave',  'MyISAM'), 3);
	}

	public function testGetCharacterSetCount()
	{
		$this->assertEqual($this->_statistics1->getCharacterSetCount('master', 'latin1'), null);
		$this->assertEqual($this->_statistics1->getCharacterSetCount('slave',  'latin1'), null);
		$this->assertEqual($this->_statistics1->getCharacterSetCount('master', 'utf8'),   null);
		$this->assertEqual($this->_statistics1->getCharacterSetCount('slave',  'utf8'),   null);
		$this->assertEqual($this->_statistics2->getCharacterSetCount('master', 'latin1'), 1);
		$this->assertEqual($this->_statistics2->getCharacterSetCount('slave',  'latin1'), null);
		$this->assertEqual($this->_statistics2->getCharacterSetCount('master', 'utf8'),   3);
		$this->assertEqual($this->_statistics2->getCharacterSetCount('slave',  'utf8'),   3);
	}

	public function testHasIdenticalEngineCount()
	{
		$this->assertTrue($this->_statistics1->hasIdenticalEngineCount('MyISAM'));
		$this->assertTrue($this->_statistics2->hasIdenticalEngineCount('MyISAM'));
	}

	public function testAddEngine()
	{
		$engines = $this->_statistics1->getAllEngines();

		$this->assertFalse(in_array('Steam', $engines));
		$this->assertEqual($this->_statistics1->getEngineCount('master', 'Steam'), null);
		$this->assertEqual($this->_statistics1->getEngineCount('slave',  'Steam'), null);
		$this->assertTrue($this->_statistics1->hasIdenticalEngineCount('Steam'));
	
		$this->_statistics1->addEngine('Steam', 'master');

		$this->assertTrue($engines = $this->_statistics1->getAllEngines());

		$this->assertTrue(in_array('Steam', $engines));
		$this->assertEqual($this->_statistics1->getEngineCount('master', 'Steam'), 1);
		$this->assertEqual($this->_statistics1->getEngineCount('slave',  'Steam'), null);
		$this->assertFalse($this->_statistics1->hasIdenticalEngineCount('Steam'));
	}

	public function test()
	{
		$charsets = $this->_statistics1->getAllCharacterSets();

		$this->assertFalse(in_array('Pencil', $charsets));
		$this->assertEqual($this->_statistics1->getCharacterSetCount('master', 'Pencil'), null);
		$this->assertEqual($this->_statistics1->getCharacterSetCount('slave',  'Pencil'), null);
		$this->assertTrue($this->_statistics1->hasIdenticalCharacterSetCount('Steam'));

		$this->_statistics1->addCharset('Pencil', 'slave');

		$charsets = $this->_statistics1->getAllCharacterSets();

		$this->assertTrue(in_array('Pencil', $charsets));
		$this->assertEqual($this->_statistics1->getCharacterSetCount('master', 'Pencil'), null);
		$this->assertEqual($this->_statistics1->getCharacterSetCount('slave',  'Pencil'), 1);
		$this->assertFalse($this->_statistics1->hasIdenticalCharacterSetCount('Pencil'));
	}

}



