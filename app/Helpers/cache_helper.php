<?php

/**
 * Caching Helper
 * 
 * Provides caching functionality for commonly accessed data
 * in the classroom management system
 */

if (! function_exists('cache_remember')) {
    /**
     * Cache data with a fallback function to generate it if not found
     *
     * @param string $key
     * @param callable $fallback
     * @param int $ttl
     * @return mixed
     */
    function cache_remember(string $key, callable $fallback, int $ttl = 3600)
    {
        $cache = \Config\Services::cache();
        
        // Try to get from cache
        $data = $cache->get($key);
        
        if ($data === null) {
            // Generate data using fallback function
            $data = $fallback();
            
            // Store in cache
            $cache->save($key, $data, $ttl);
        }
        
        return $data;
    }
}

if (! function_exists('cache_students_by_class')) {
    /**
     * Cache students by class
     *
     * @param int $classId
     * @return array
     */
    function cache_students_by_class(int $classId): array
    {
        $key = "students_class_{$classId}";
        
        return cache_remember($key, function() use ($classId) {
            $siswaModel = new \App\Models\SiswaModel();
            return $siswaModel->where('kelas', $classId)->findAll() ?? [];
        }, 1800); // Cache for 30 minutes
    }
}

if (! function_exists('cache_class_attendance_stats')) {
    /**
     * Cache attendance statistics for a class
     *
     * @param int $classId
     * @param string $month
     * @return array
     */
    function cache_class_attendance_stats(int $classId, string $month): array
    {
        $key = "attendance_stats_{$classId}_{$month}";
        
        return cache_remember($key, function() use ($classId, $month) {
            $absensiModel = new \App\Models\AbsensiModel();
            // Get attendance stats for the class and month
            $stats = $absensiModel->getAttendanceStats($classId, $month);
            return $stats ?? [];
        }, 3600); // Cache for 1 hour
    }
}

if (! function_exists('cache_school_profile')) {
    /**
     * Cache school profile data
     *
     * @return array
     */
    function cache_school_profile(): array
    {
        return cache_remember('school_profile', function() {
            $profilModel = new \App\Models\ProfilSekolahModel();
            $profile = $profilModel->first();
            return $profile ? $profile : [];
        }, 7200); // Cache for 2 hours
    }
}

if (! function_exists('invalidate_cache')) {
    /**
     * Invalidate cache entries by pattern
     *
     * @param string $pattern
     * @return void
     */
    function invalidate_cache(string $pattern): void
    {
        $cache = \Config\Services::cache();
        
        // For file cache, we need to manually delete files
        if (method_exists($cache, 'deleteMatching')) {
            $cache->deleteMatching($pattern);
        } else {
            // For other cache drivers or fallback
            $cache->clean();
        }
        
        log_message('info', "Cache invalidated for pattern: {$pattern}");
    }
}

if (! function_exists('warm_cache')) {
    /**
     * Warm up commonly used cache entries
     *
     * @return void
     */
    function warm_cache(): void
    {
        try {
            // Warm up school profile cache
            cache_school_profile();
            
            // Warm up student data for each class
            for ($class = 1; $class <= 6; $class++) {
                cache_students_by_class($class);
            }
            
            log_message('info', 'Cache warmed successfully');
        } catch (\Exception $e) {
            log_message('error', 'Cache warming failed: ' . $e->getMessage());
        }
    }
}

if (! function_exists('get_cache_stats')) {
    /**
     * Get cache statistics if available
     *
     * @return array
     */
    function get_cache_stats(): array
    {
        $cache = \Config\Services::cache();
        
        if (method_exists($cache, 'getMetaData')) {
            return $cache->getMetaData('');
        }
        
        return [
            'status' => 'Cache statistics not available for this driver',
            'handler' => get_class($cache)
        ];
    }
}