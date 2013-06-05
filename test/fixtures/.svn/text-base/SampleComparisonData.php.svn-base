<?php

class SampleComparisonData
{
	public static function getCompareStatusLists()
	{
		$status_lists = array();
		
		foreach (SampleTestData::getDatabaseStatuses() as $status) {
			$name  = $status['var_name'];
			$value = $status['value'];
			
			$status_lists["status::{$name}"] = array();
		}
		return $status_lists;
	}

	public static function getCompareDatabases()
	{
		$database_lists = array();

		foreach (SampleTestData::getDatabases() as $database){
			
		}
		return $database_lists;
	}

	public static function getCompareTables()
	{
		return array();
	}

	public static function getCompareTableStructure()
	{
		return array();
	}

	public static function getGenerateAlterDatabaseStructure()
	{
		return array();
	}

	public static function getGenerateAlterTableStructure()
	{
		return array();
	}
}

