<?php

namespace App\Libraries;

use Config\Database;

class BackupHandler
{
    private string $settingsFile;
    private string $backupDir;

    public function __construct()
    {
        $this->settingsFile = WRITEPATH . 'auto_backup_settings.json';
        $this->backupDir = WRITEPATH . 'backups/';
    }

    /**
     * Get the auto backup settings.
     */
    public function getSettings(): array
    {
        $default = [
            'enabled'         => false,
            'retention_count' => 10,
            'last_backup'     => null,
            'last_status'     => 'Never run',
        ];

        if (file_exists($this->settingsFile)) {
            $data = json_decode(file_get_contents($this->settingsFile), true);
            if (is_array($data)) {
                return array_merge($default, $data);
            }
        }

        return $default;
    }

    /**
     * Save auto backup settings.
     */
    public function saveSettings(array $settings): bool
    {
        $current = $this->getSettings();
        $newSettings = [
            'enabled'         => (bool)($settings['enabled'] ?? $current['enabled']),
            'retention_count' => max(1, (int)($settings['retention_count'] ?? $current['retention_count'])),
            'last_backup'     => $settings['last_backup'] ?? $current['last_backup'],
            'last_status'     => $settings['last_status'] ?? $current['last_status'],
        ];

        // Ensure directory is writable and exists
        $dir = dirname($this->settingsFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return file_put_contents($this->settingsFile, json_encode($newSettings, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Run the backup logic.
     * 
     * @param bool $force Bypass enabled check and 24-hour frequency check.
     * @return array [success => bool, message => string]
     */
    public function runBackup(bool $force = false): array
    {
        $settings = $this->getSettings();

        if (!$settings['enabled'] && !$force) {
            return [
                'success' => false,
                'message' => 'Auto backup is disabled.',
            ];
        }

        // Check if 24 hours have passed since last backup
        if (!$force && !empty($settings['last_backup'])) {
            $lastTime = strtotime($settings['last_backup']);
            if (time() - $lastTime < 86400) { // 24 hours
                return [
                    'success' => false,
                    'message' => 'Backup was already executed within the last 24 hours. Last backup: ' . $settings['last_backup'],
                ];
            }
        }

        // To prevent concurrent trigger race condition, update last_backup to current time FIRST
        $settings['last_backup'] = date('Y-m-d H:i:s');
        $settings['last_status'] = 'Running auto backup...';
        $this->saveSettings($settings);

        // Perform the backup
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0777, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '_' . time() . '.sql';
        $filePath = $this->backupDir . $filename;

        try {
            $db = Database::connect();
            $sql = $this->exportDatabase($db);

            if (file_put_contents($filePath, $sql) === false) {
                throw new \Exception('Could not write backup file to disk.');
            }

            // Prune old backups
            $prunedCount = $this->pruneOldBackups($settings['retention_count']);

            // Update settings with success info
            $settings['last_backup'] = date('Y-m-d H:i:s');
            $settings['last_status'] = 'Success: Backup "' . $filename . '" created. Pruned ' . $prunedCount . ' old file(s).';
            $this->saveSettings($settings);

            return [
                'success' => true,
                'message' => 'Backup "' . $filename . '" created successfully. ' . ($prunedCount > 0 ? "Pruned {$prunedCount} old backups." : ""),
            ];
        } catch (\Throwable $e) {
            $settings['last_status'] = 'Failed: ' . $e->getMessage();
            $this->saveSettings($settings);

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Prune old backups to match retention count.
     */
    private function pruneOldBackups(int $retentionCount): int
    {
        if (!is_dir($this->backupDir)) {
            return 0;
        }

        $files = array_diff(scandir($this->backupDir), ['.', '..']);
        $backups = [];
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $filePath = $this->backupDir . $file;
                $backups[] = [
                    'filename' => $file,
                    'mtime'    => filemtime($filePath),
                ];
            }
        }

        if (count($backups) <= $retentionCount) {
            return 0;
        }

        // Sort backups by modification time, oldest first
        usort($backups, function ($a, $b) {
            return $a['mtime'] <=> $b['mtime'];
        });

        $toRemove = count($backups) - $retentionCount;
        $pruned = 0;
        for ($i = 0; $i < $toRemove; $i++) {
            $filePath = $this->backupDir . $backups[$i]['filename'];
            if (file_exists($filePath)) {
                unlink($filePath);
                $pruned++;
            }
        }

        return $pruned;
    }

    /**
     * Memory-safe database exporter.
     */
    private function exportDatabase($db): string
    {
        $tables = $db->listTables();
        
        $sql = "-- Martech TMS Database Backup\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- --------------------------------------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Don't backup sessions table
            if ($table === 'ci_sessions') {
                continue;
            }

            // 1. SHOW CREATE TABLE
            $query = $db->query("SHOW CREATE TABLE `" . $db->escapeString($table) . "`")->getRowArray();
            if (isset($query['Create Table'])) {
                $sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
                $sql .= $query['Create Table'] . ";\n\n";
            }

            // 2. Dump all rows
            $rows = $db->table($table)->get()->getResultArray();
            if (!empty($rows)) {
                $chunks = array_chunk($rows, 100);
                foreach ($chunks as $chunk) {
                    $sql .= "INSERT INTO `" . $table . "` VALUES \n";
                    $inserts = [];
                    foreach ($chunk as $row) {
                        $vals = [];
                        foreach ($row as $field => $val) {
                            if ($val === null) {
                                $vals[] = 'NULL';
                            } else {
                                $vals[] = "'" . $db->escapeString($val) . "'";
                            }
                        }
                        $inserts[] = "(" . implode(', ', $vals) . ")";
                    }
                    $sql .= implode(",\n", $inserts) . ";\n\n";
                }
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }
}
