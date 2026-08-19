<?php
$dir = new RecursiveDirectoryIterator('c:\xampp\htdocs\iam-admin-panel\application\views');
foreach (new RecursiveIteratorIterator($dir) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        
        // Skip header, navbar, sidebar
        if (strpos($path, 'header.php') !== false || strpos($path, 'navbar.php') !== false || strpos($path, 'sidebar.php') !== false) {
            continue;
        }

        $c = file_get_contents($path);
        $original = $c;

        // Replace bg-slate-900 variations
        $c = str_replace('bg-slate-900/40', 'bg-slate-100/40 dark:bg-slate-900/40', $c);
        $c = str_replace('bg-slate-900/60', 'bg-slate-100/60 dark:bg-slate-900/60', $c);
        $c = str_replace('bg-slate-900/80', 'bg-slate-100/80 dark:bg-slate-900/80', $c);
        
        // For exact 'bg-slate-900' we use regex to avoid messing up the above ones if already replaced
        $c = preg_replace('/\bbg-slate-900\b(?![\/])/', 'bg-slate-100 dark:bg-slate-900', $c);

        // Hover bg-slate-900
        $c = preg_replace('/\bhover:bg-slate-900\b(?![\/])/', 'hover:bg-slate-200 dark:hover:bg-slate-900', $c);

        // Replace text-white that should be dark mode only text-white
        // But only in specific views, or maybe just change all text-white to text-slate-800 dark:text-white?
        // Wait, text-white on a cyan button should remain text-white!
        // We'll leave text-white alone and handle it manually if necessary, or just not touch text-white on buttons.
        // Actually, VyrnForge components handle their own text colors. We only need to fix text-white in custom elements.
        
        // Also look at border-slate-700
        $c = str_replace('border-slate-700', 'border-slate-300 dark:border-slate-700', $c);

        if ($c !== $original) {
            file_put_contents($path, $c);
        }
    }
}
echo "Replacement complete.";
?>
