<?php

require_once 'initTests.php';

class AllTests extends TestSuite {
	function AllTests() {
		$this->TestSuite('DB Comparison Tool ' . DBCHECK_VERSION . ' Test Suit');
		
		$this->addFile(APP_DIR . '/test/unit/testCache.php');
//		$this->addFile(APP_DIR . '/test/unit/testCompareEngine.php');
		$this->addFile(APP_DIR . '/test/unit/testConfig.php');
		$this->addFile(APP_DIR . '/test/unit/testController.php');
		$this->addFile(APP_DIR . '/test/unit/testDatabaseData.php');
		$this->addFile(APP_DIR . '/test/unit/testDbTools.php');
		$this->addFile(APP_DIR . '/test/unit/testIpChecker.php');
		$this->addFile(APP_DIR . '/test/unit/testQuery.php');
		$this->addFile(APP_DIR . '/test/unit/testServerInformation.php');
		$this->addFile(APP_DIR . '/test/unit/testStatistics.php');
		$this->addFile(APP_DIR . '/test/unit/testWebRequest.php');
		$this->addFile(APP_DIR . '/test/unit/testXmlDocBuilder.php');
		$this->addFile(APP_DIR . '/test/unit/testXmlDocReader.php');
	}
}

