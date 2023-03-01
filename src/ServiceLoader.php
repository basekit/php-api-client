<?php

declare(strict_types=1);

namespace BaseKit\Api;

class ServiceLoader
{
    private const SERVICE_DIR = __DIR__ . '/../service/';
    private const INDEX_FILE  = 'basekit.json';

    public function load(string $filePath = self::SERVICE_DIR . self::INDEX_FILE): array
    {
        $fileContents = json_decode(file_get_contents($filePath), true);

        if (isset($fileContents["includes"])) {
            foreach ($fileContents["includes"] as $importedFileName) {
                $includedFileContents = $this->load(self::SERVICE_DIR . $importedFileName);
                $fileContents = array_merge_recursive($fileContents, $includedFileContents);
            }

            unset($fileContents["includes"]);
        }

        return $fileContents;
    }
}
