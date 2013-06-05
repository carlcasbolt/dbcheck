DB Comparison Tool - Readme
===========================

A set of PHP-scripts to compare the database structure between 2 servers.

## Version 0.6

## Requirements:
 * php 5.1.0 or later
 * mysql 4.x
 * a web browser

## Summary

The DB Comparison Tool is intended to identify and resolve the differences which
can exist between 2 versions of a database when it exists on 2 seperate machines. 

## Folder structure:
```
    dbcheck/
      cache/
      config.php
      config.php-dist
      init.php
      lib/
        Cache.class.php
        CompareEngine.class.php
        Controller.class.php
        DatabaseData.class.php
        DbTools.class.php
        IpChecker.class.php
        Query.class.php
        ServerInformation.class.php
        Statistics.class.php
        WebRequest.class.php
        XmlDocReader.class.php
        XmlDocBuilder.class.php
      LICENSE
      README
      test/
        index.php
        initTests.php
        simpletest/
        unit/
      view/
        alter_sql.php
        comparison.php
        database_group_picker.php
        database_list_page.php
        database_status_page.php
        environment_matrix.php
        filter_information.php
        navigation.php
        server_status.php
        statistics.php
        status_comparison.php
        swap_master.php
        table_list_page.php
        table_structure_page_comparison_charset.php
        table_structure_page_comparison_columns.php
        table_structure_page_comparison_keys.php
        table_structure_page.php
        template.php
        toggle_result_links.php
        welcome_page.php
      web/
        css/
          colours.css
          screen.css
        index.php
        js/
          prototype.js
          toggler.js
        query.php
        status.php
```

## Installation:

Each database server will require it's own version of the Database Comparsion Tool
installed on it. One of these installs (your development server is recommended) 
will have a complete list of the other servers and the full URL to each of the
installations. 

