<?php

declare(strict_types=1);

function prompt(string $question, string $default = ''): string {
    echo $question . ($default ? " [$default]" : "") . ": ";
    $input = trim(fgets(STDIN) ?: '');
    return $input ?: $default;
}

$projectName = prompt("Project name (e.g. user/my-atom)", basename(getcwd()));
$namespace = prompt("Namespace (e.g. MyVendor\\MyProject)", str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', basename(getcwd())))));
$description = prompt("Project description", "A new PHPRise project based on the OTAKU Manifesto.");

$replacements = [
    '{{PROJECT_NAME}}' => $projectName,
    '{{NAMESPACE}}' => str_replace('\\', '\\\\', $namespace),
    '{{DESCRIPTION}}' => $description,
    'Phprise\\\\AtomicTemplate\\\\' => str_replace('\\', '\\\\', $namespace) . '\\\\',
    'Phprise\\\\AtomicTemplate' => $namespace,
    'App\\\\' => str_replace('\\', '\\\\', $namespace) . '\\\\',
    'App' => $namespace,
    'otaku/atomic' => $projectName,
    'otaku/assembly' => $projectName,
    'otaku/application' => $projectName,
];

$filesToProcess = [
    'composer.json',
    'README.md',
    'PHILOSOPHY.md',
    'GITFLOW.md',
];

// Add src and tests files
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('src'));
foreach ($it as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filesToProcess[] = $file->getPathname();
    }
}

if (is_dir('tests')) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('tests'));
    foreach ($it as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filesToProcess[] = $file->getPathname();
        }
    }
}

foreach ($filesToProcess as $file) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);
    $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
    file_put_contents($file, $newContent);
}

// Special case for composer.json autoload
$composerJson = json_decode(file_get_contents('composer.json'), true);
$composerJson['name'] = $projectName;
$composerJson['description'] = $description;
// The str_replace above might have already fixed the namespace in the string,
// but let's be sure about the JSON structure if it was a direct key replace.
file_put_contents('composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "Setup complete! Cleaning up...\n";

// Remove the setup script and its directory
unlink(__FILE__);
if (is_dir('bin') && count(scandir('bin')) === 2) { // 2 = . and ..
    rmdir('bin');
}

echo "Done.\n";
