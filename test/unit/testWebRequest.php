<?php

class testWebRequest extends UnitTestCase
{
	private $_request;

	public function setUp()
	{
		global $_REQUEST;
		
		$_REQUEST['foobar'] = 'monkey';
	
		$this->_request = new WebRequest();
	}
	
	public function tearDown()
	{
		unset($this->_request);
	}

	public function testInit()
	{
		$this->assertTrue($this->_request instanceof WebRequest);
	}

	public function testGetParameter()
	{
		$this->assertEqual($this->_request->getParameter('foobar'), 'monkey');
	}

	public function testGetParameterIsNull()
	{
		$this->assertEqual($this->_request->getParameter('foobarmonkey'), null);
	}
	
	public function testInstanceDifferences()
	{
		global $_REQUEST;
		
		$_REQUEST['instance_test'] = true;
		
		$request = WebRequest::getInstance();
		$request->setParameter('instance_test', null);

		$this->assertEqual($request->getParameter('instance_test'), null);

		$request = new WebRequest();

		$this->assertEqual($request->getParameter('instance_test'), true);
	}
}
