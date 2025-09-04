<?php
/**
 * Configuration Loader for DRC Embassy Management System
 * Loads settings from settings.json file
 */

class Config {
    private static $config = null;
    private static $settings_file = 'settings.json';
    
    /**
     * Load configuration from settings.json
     */
    public static function load() {
        if (self::$config === null) {
            if (file_exists(self::$settings_file)) {
                $json_content = file_get_contents(self::$settings_file);
                self::$config = json_decode($json_content, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON in settings.json: ' . json_last_error_msg());
                }
            } else {
                throw new Exception('Settings file not found: ' . self::$settings_file);
            }
        }
        return self::$config;
    }
    
    /**
     * Get a configuration value by key (supports dot notation)
     */
    public static function get($key, $default = null) {
        $config = self::load();
        $keys = explode('.', $key);
        $value = $config;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    /**
     * Get database configuration
     */
    public static function getDatabase() {
        return self::get('database');
    }
    
    /**
     * Get XAMPP configuration
     */
    public static function getXampp() {
        return self::get('xampp');
    }
    
    /**
     * Get embassy information
     */
    public static function getEmbassy() {
        return self::get('embassy');
    }
    
    /**
     * Get system configuration
     */
    public static function getSystem() {
        return self::get('system');
    }
    
    /**
     * Get feature flags
     */
    public static function getFeatures() {
        return self::get('features');
    }
    
    /**
     * Get validation rules
     */
    public static function getValidation() {
        return self::get('validation');
    }
    
    /**
     * Get security settings
     */
    public static function getSecurity() {
        return self::get('security');
    }
    
    /**
     * Get file paths
     */
    public static function getPaths() {
        return self::get('paths');
    }
    
    /**
     * Get URLs
     */
    public static function getUrls() {
        return self::get('urls');
    }
    
    /**
     * Get debug URLs
     */
    public static function getDebugUrls() {
        return self::get('debug');
    }
    
    /**
     * Get table configurations
     */
    public static function getTables() {
        return self::get('tables');
    }
    
    /**
     * Get PHP configuration
     */
    public static function getPhp() {
        return self::get('php');
    }
    
    /**
     * Get Apache configuration
     */
    public static function getApache() {
        return self::get('apache');
    }
    
    /**
     * Get MySQL configuration
     */
    public static function getMysql() {
        return self::get('mysql');
    }
    
    /**
     * Get environment settings
     */
    public static function getEnvironment() {
        return self::get('environment');
    }
    
    /**
     * Get maintenance settings
     */
    public static function getMaintenance() {
        return self::get('maintenance');
    }
    
    /**
     * Get notification settings
     */
    public static function getNotifications() {
        return self::get('notifications');
    }
    
    /**
     * Get localization settings
     */
    public static function getLocalization() {
        return self::get('localization');
    }
    
    /**
     * Get logging settings
     */
    public static function getLogging() {
        return self::get('logging');
    }
    
    /**
     * Get performance settings
     */
    public static function getPerformance() {
        return self::get('performance');
    }
    
    /**
     * Get backup settings
     */
    public static function getBackup() {
        return self::get('backup');
    }
    
    /**
     * Get monitoring settings
     */
    public static function getMonitoring() {
        return self::get('monitoring');
    }
    
    /**
     * Get API settings
     */
    public static function getApi() {
        return self::get('api');
    }
    
    /**
     * Get third-party integrations
     */
    public static function getThirdParty() {
        return self::get('third_party');
    }
    
    /**
     * Get customization settings
     */
    public static function getCustomization() {
        return self::get('customization');
    }
    
    /**
     * Get deployment settings
     */
    public static function getDeployment() {
        return self::get('deployment');
    }
    
    /**
     * Check if a feature is enabled
     */
    public static function isFeatureEnabled($feature) {
        return self::get("features.$feature", false);
    }
    
    /**
     * Get database connection string
     */
    public static function getDatabaseDsn() {
        $db = self::getDatabase();
        return "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
    }
    
    /**
     * Get upload directory path
     */
    public static function getUploadDir() {
        return self::get('paths.uploads', 'uploads/');
    }
    
    /**
     * Get assets directory path
     */
    public static function getAssetsDir() {
        return self::get('paths.assets', 'assets/');
    }
    
    /**
     * Get logs directory path
     */
    public static function getLogsDir() {
        return self::get('paths.logs', 'logs/');
    }
    
    /**
     * Get base URL
     */
    public static function getBaseUrl() {
        return self::get('urls.base', 'http://localhost/bomoko/');
    }
    
    /**
     * Get project information
     */
    public static function getProject() {
        return self::get('project');
    }
    
    /**
     * Check if debug mode is enabled
     */
    public static function isDebugMode() {
        return self::get('system.debug_mode', false);
    }
    
    /**
     * Get session timeout
     */
    public static function getSessionTimeout() {
        return self::get('system.session_timeout', 3600);
    }
    
    /**
     * Get maximum file size
     */
    public static function getMaxFileSize() {
        return self::get('system.max_file_size', '10MB');
    }
    
    /**
     * Get allowed file types
     */
    public static function getAllowedFileTypes() {
        return self::get('system.allowed_file_types', ['pdf']);
    }
    
    /**
     * Validate configuration
     */
    public static function validate() {
        $errors = [];
        
        // Check required directories
        $paths = self::getPaths();
        foreach ($paths as $path) {
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                $errors[] = "Cannot create directory: $path";
            }
        }
        
        // Check database connection
        try {
            $db = self::getDatabase();
            $pdo = new PDO(self::getDatabaseDsn(), $db['user'], $db['password']);
        } catch (PDOException $e) {
            $errors[] = "Database connection failed: " . $e->getMessage();
        }
        
        // Check XAMPP paths
        $xampp = self::getXampp();
        foreach ($xampp as $key => $path) {
            if (!file_exists($path)) {
                $errors[] = "XAMPP path not found: $path";
            }
        }
        
        return $errors;
    }
    
    /**
     * Get configuration as array
     */
    public static function toArray() {
        return self::load();
    }
    
    /**
     * Get configuration as JSON
     */
    public static function toJson() {
        return json_encode(self::load(), JSON_PRETTY_PRINT);
    }
}

// Auto-load configuration when this file is included
try {
    Config::load();
} catch (Exception $e) {
    error_log("Configuration error: " . $e->getMessage());
}
?> 