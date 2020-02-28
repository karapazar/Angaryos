<?php

namespace App\Libraries;

use Cache;
use DB;

class DashboardLibrary
{
    public function RecordCount($param1, $param2)
    {
        $sumAllTablesCounts = Cache::remember('sumAllTablesCounts', 60 * 60 * 24, function()
        {
            $sum = 0;
            
            $tableNames = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            foreach($tableNames as $tableName)
                if(!strstr($tableName, '_archive'))
                    $sum += DB::table($tableName)->count();
                
            return $sum;
        });
        
        $count = DB::table($param1)->count();
        
        return 
        [
            'table_display_name' => get_attr_from_cache('tables', 'name', $param1, 'display_name'),
            'count' => $count,
            'all' => $sumAllTablesCounts
        ];
    }
}