<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact">
    <div class="vf-page__body">

        <!-- Tab Navigation with inline Add Button -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800/80 pb-0 mb-6">
            <div class="flex gap-1">
                <button onclick="switchTab('projects-list-tab', this)" id="tab-btn-projects" class="px-4 py-2.5 font-bold text-sm border-b-2 border-emerald-500 text-emerald-400 transition cursor-pointer rounded-t-lg">
                    <i data-lucide="layout-grid" class="w-4 h-4 inline mr-1.5"></i>Daftar Proyek 3D
                </button>
                <button onclick="switchTab('settings-tab', this)" id="tab-btn-settings" class="px-4 py-2.5 font-semibold text-sm border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200 transition cursor-pointer rounded-t-lg">
                    <i data-lucide="sliders" class="w-4 h-4 inline mr-1.5"></i>Pengaturan Animasi
                </button>
                <button onclick="switchTab('layout-tab', this)" id="tab-btn-layout" class="px-4 py-2.5 font-semibold text-sm border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200 transition cursor-pointer rounded-t-lg">
                    <i data-lucide="component" class="w-4 h-4 inline mr-1.5"></i>Layout Komponen
                </button>
            </div>
            <?php 
            $this->load->view('components/button', [
                'text' => 'Tambah Proyek Baru',
                'variant' => 'primary',
                'icon' => 'plus-circle',
                'class' => 'mb-1 bg-emerald-500 hover:bg-emerald-400 border-emerald-500 shadow-emerald-500/20',
                'attributes' => 'onclick="openAddProjectModal()"'
            ]); 
            ?>
        </div>

        <!-- TAB 1: PROJECTS GRID -->
        <div id="projects-list-tab" class="tab-content space-y-6">
            <?php if (empty($projects)): ?>
            <div class="vf-panel vf-card--elevated text-center py-12 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center mx-auto text-emerald-400">
                    <i data-lucide="folder-open" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Proyek Portofolio</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 max-w-md mx-auto">Data portofolio telah dikosongkan. Klik tombol di bawah untuk menambahkan proyek portofolio baru Anda.</p>
                </div>
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Tambah Proyek Pertama',
                    'variant' => 'primary',
                    'icon' => 'plus-circle',
                    'class' => 'bg-emerald-500 hover:bg-emerald-400 border-emerald-500 mx-auto',
                    'attributes' => 'onclick="openAddProjectModal()"'
                ]); 
                ?>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($projects as $project): ?>
                <div class="vf-panel vf-card--elevated flex flex-col justify-between relative overflow-hidden group">
                    
                    <!-- Color Ambient Preview -->
                    <div class="absolute -right-10 -top-10 w-24 h-24 rounded-full blur-2xl opacity-40 transition-all duration-500 group-hover:scale-150" style="background: radial-gradient(circle, <?php echo $project['nebula1']; ?> 0%, <?php echo $project['nebula2']; ?> 100%);"></div>

                    <div>
                        <!-- Image & Title -->
                        <div class="flex gap-4 items-start relative z-10">
                            <img src="<?php echo htmlspecialchars($project['src']); ?>" alt="<?php echo htmlspecialchars($project['name']); ?>" class="w-20 h-20 rounded-xl object-cover ring-2 ring-slate-800 shrink-0">
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-400 transition-colors"><?php echo htmlspecialchars($project['name']); ?></h3>
                                
                                <!-- Color Palette Badges -->
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?php echo $project['bg']; ?>;"></span>
                                        Dark: <?php echo $project['bg']; ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?php echo $project['nebula1']; ?>;"></span>
                                        Neb1: <?php echo $project['nebula1']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4 space-y-2 text-xs relative z-10">
                            <div>
                                <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider font-mono">EN Description:</span>
                                <p class="text-slate-600 dark:text-slate-300 mt-0.5 leading-relaxed line-clamp-2"><?php echo htmlspecialchars($project['description']); ?></p>
                            </div>
                        </div>

                        <!-- Tech Stack Tags -->
                        <div class="mt-4 flex flex-wrap gap-1 relative z-10">
                            <?php foreach ($project['techStack'] as $tech): ?>
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-900 text-[10px] font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800">
                                <?php echo htmlspecialchars($tech); ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-6 flex justify-end gap-2 relative z-10 border-t border-slate-200 dark:border-slate-800/60 pt-4">
                        <?php 
                        $this->load->view('components/button', [
                            'text' => 'Edit',
                            'variant' => 'subtle',
                            'size' => 'sm',
                            'icon' => 'edit',
                            'class' => 'text-cyan-400 hover:bg-cyan-500/20 border-cyan-500/20',
                            'attributes' => 'onclick=\'openEditProjectModal('.json_encode($project).')\''
                        ]); 
                        ?>
                        <?php 
                        $this->load->view('components/button', [
                            'text' => 'Hapus',
                            'variant' => 'danger',
                            'size' => 'sm',
                            'icon' => 'trash-2',
                            'class' => 'hover:bg-rose-500/20 border-rose-500/20',
                            'attributes' => 'onclick="deleteProject('.$project['id'].', \''.htmlspecialchars($project['name'], ENT_QUOTES).'\')"'
                        ]); 
                        ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    <!-- TAB 2: PORTFOLIO SETTINGS & ANIMATIONS -->
    <div id="settings-tab" class="tab-content hidden space-y-6">
        <form id="portfolio-settings-form" onsubmit="submitPortfolioSettings(event)" class="vf-panel vf-card--elevated space-y-6">
            <div class="vf-panel__header border-b border-slate-200 dark:border-slate-800 pb-3">
                <div class="vf-panel__heading">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="sliders" class="w-5 h-5 text-emerald-400"></i>
                        <span>Pengaturan Global &amp; Transisi Animasi</span>
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mt-1">Konfigurasi parameter visual, splash screen, durasi transisi, dan urutan halaman aplikasi 3D Portfolio.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-splash-letters',
                        'label' => 'Teks Animasi Splash',
                        'value' => htmlspecialchars($settings['splash_letters'] ?? 'STUDIO'),
                        'required' => true,
                        'class' => 'font-mono'
                    ]); 
                    ?>
                    <p class="text-[10px] text-slate-500 mt-1">Contoh: STUDIO, PORTFOLIO, CREATIVE</p>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-star-count',
                        'type' => 'number',
                        'label' => 'Jumlah Partikel Bintang',
                        'value' => htmlspecialchars($settings['star_count'] ?? '24'),
                        'required' => true,
                        'attributes' => 'min="5" max="100"',
                        'class' => 'font-mono'
                    ]); 
                    ?>
                    <p class="text-[10px] text-slate-500 mt-1">Disarankan: 15 - 40</p>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-showcase-count',
                        'type' => 'number',
                        'label' => 'Jumlah Proyek Korsel Utama',
                        'value' => htmlspecialchars($settings['showcase_count'] ?? '4'),
                        'required' => true,
                        'attributes' => 'min="1" max="10"',
                        'class' => 'font-mono'
                    ]); 
                    ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-autoplay-interval',
                        'type' => 'number',
                        'label' => 'Interval Putar Otomatis (ms)',
                        'value' => htmlspecialchars($settings['autoplay_interval'] ?? '5000'),
                        'required' => true,
                        'class' => 'font-mono'
                    ]); 
                    ?>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-transition-speed',
                        'type' => 'number',
                        'label' => 'Durasi Animasi Transisi (ms)',
                        'value' => htmlspecialchars($settings['transition_speed'] ?? '800'),
                        'required' => true,
                        'class' => 'font-mono'
                    ]); 
                    ?>
                </div>
                <div>
                    <?php 
                    $this->load->view('components/input', [
                        'name' => '',
                        'id' => 'setting-brand-label',
                        'label' => 'Label Brand (Pojok Kiri Atas)',
                        'value' => htmlspecialchars($settings['brand_label'] ?? 'SHOWCASE'),
                        'required' => true,
                        'class' => 'font-mono'
                    ]); 
                    ?>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800/80 pt-4">
                <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300 mb-4">Konten Teks Seksi Unggulan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <?php 
                        $this->load->view('components/input', [
                            'name' => '',
                            'id' => 'setting-featured-title-en',
                            'label' => 'Judul (EN)',
                            'value' => htmlspecialchars($settings['featured_title_en'] ?? 'FEATURED APPS')
                        ]); 
                        ?>
                    </div>
                    <div>
                        <?php 
                        $this->load->view('components/input', [
                            'name' => '',
                            'id' => 'setting-featured-title-id',
                            'label' => 'Judul (ID)',
                            'value' => htmlspecialchars($settings['featured_title_id'] ?? 'APLIKASI UNGGULAN')
                        ]); 
                        ?>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Deskripsi Singkat (EN)</label>
                        <textarea id="setting-featured-desc-en" rows="3" class="vf-input w-full  focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"><?php echo htmlspecialchars($settings['featured_desc_en'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Deskripsi Singkat (ID)</label>
                        <textarea id="setting-featured-desc-id" rows="3" class="vf-input w-full  focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"><?php echo htmlspecialchars($settings['featured_desc_id'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-200 dark:border-slate-800/60 pt-4 mt-6">
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Simpan Semua Pengaturan',
                    'variant' => 'primary',
                    'type' => 'submit',
                    'class' => 'bg-emerald-500 hover:bg-emerald-400 border-emerald-500 text-slate-950 shadow-emerald-500/20'
                ]); 
                ?>
            </div>
        </form>
    </div>

    <!-- TAB 3: LAYOUT KOMPONEN -->
    <div id="layout-tab" class="tab-content hidden">
        <div class="flex gap-6" style="height: calc(100vh - 130px); min-height: 500px;">

            <!-- LEFT: Component Controls (scrollable) -->
            <div class="flex-1 min-w-0 overflow-y-auto space-y-4 pr-1">

                <!-- Section: Splash -->
                <div class="vf-panel p-0 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/80 bg-violet-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 rounded-full bg-violet-500"></div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Section: SPLASH SCREEN</h3>
                                <p class="text-slate-500 text-xs">Layar loading pertama saat halaman dibuka</p>
                            </div>
                        </div>
                        <label class="layout-toggle-section" data-section="splash">
                            <input type="checkbox" class="sr-only section-toggle" data-section="splash" <?php echo (!isset($section_layout['splash']['enabled']) || $section_layout['splash']['enabled']) ? 'checked' : ''; ?>>
                            <div class="toggle-track"></div>
                        </label>
                    </div>
                    <div class="p-5 space-y-3" id="splash-components">
                        <?php
                        $splash_comps = $section_layout['splash']['components'] ?? [];
                        $splash_defs = [
                            'letters_animation' => ['label' => 'Animasi Huruf', 'icon' => 'type', 'color' => 'violet', 'has_text' => true, 'text_key' => 'text', 'text_label' => 'Teks Splash'],
                            'progress_bar'      => ['label' => 'Progress Bar', 'icon' => 'loader', 'color' => 'violet'],
                            'loading_text'      => ['label' => 'Teks "Loading Resources"', 'icon' => 'file-text', 'color' => 'violet'],
                        ];
                        foreach ($splash_defs as $key => $def):
                            $comp = $splash_comps[$key] ?? ['enabled' => true];
                            $enabled = $comp['enabled'] ?? true;
                        ?>
                        <div class="comp-row flex items-center gap-3 p-3 rounded-xl bg-slate-100/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/60 hover:border-violet-500/30 transition-colors" data-section="splash" data-comp="<?php echo $key; ?>">
                            <div class="w-8 h-8 rounded-lg bg-violet-500/10 flex items-center justify-center shrink-0">
                                <i data-lucide="<?php echo $def['icon']; ?>" class="w-4 h-4 text-violet-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo $def['label']; ?></p>
                                <?php if (!empty($def['has_text'])): ?>
                                <input type="text" class="comp-text-input mt-1 w-full px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-violet-500 font-mono"
                                    data-section="splash" data-comp="<?php echo $key; ?>" data-prop="<?php echo $def['text_key']; ?>"
                                    placeholder="<?php echo $def['text_label']; ?>"
                                    value="<?php echo htmlspecialchars($comp[$def['text_key']] ?? 'STUDIO'); ?>">
                                <?php endif; ?>
                            </div>
                            <label class="comp-toggle shrink-0" data-section="splash" data-comp="<?php echo $key; ?>">
                                <input type="checkbox" class="sr-only comp-enabled" data-section="splash" data-comp="<?php echo $key; ?>" <?php echo $enabled ? 'checked' : ''; ?>>
                                <div class="toggle-track-sm"></div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Section: Hero -->
                <div class="vf-panel p-0 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/80 bg-cyan-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 rounded-full bg-cyan-500"></div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Section: HERO / CAROUSEL</h3>
                                <p class="text-slate-500 text-xs">Halaman utama dengan carousel 3D interaktif</p>
                            </div>
                        </div>
                        <label class="layout-toggle-section" data-section="hero">
                            <input type="checkbox" class="sr-only section-toggle" data-section="hero" <?php echo (!isset($section_layout['hero']['enabled']) || $section_layout['hero']['enabled']) ? 'checked' : ''; ?>>
                            <div class="toggle-track"></div>
                        </label>
                    </div>
                    <div class="p-5 space-y-3" id="hero-components">
                        <?php
                        $hero_comps = $section_layout['hero']['components'] ?? [];
                        $hero_defs = [
                            'ghost_text'     => ['label' => 'Ghost Text Besar (Nama Proyek)', 'icon' => 'text', 'color' => 'cyan'],
                            'brand_label'    => ['label' => 'Label Brand Pojok Kiri', 'icon' => 'tag', 'color' => 'cyan', 'has_text' => true, 'text_key' => 'text', 'text_label' => 'Teks Brand Label'],
                            'carousel'       => ['label' => 'Carousel Cards', 'icon' => 'layers', 'color' => 'cyan', 'has_props' => true, 'props' => [
                                'card_count'   => ['label' => 'Jumlah Card', 'type' => 'number', 'min' => 1, 'max' => 10],
                                'height_dvh'   => ['label' => 'Tinggi Card (dvh)', 'type' => 'number', 'min' => 20, 'max' => 90],
                                'aspect_ratio' => ['label' => 'Aspect Ratio', 'type' => 'text'],
                            ]],
                            'nav_arrows'     => ['label' => 'Tombol Navigasi (Panah)', 'icon' => 'arrow-left-right', 'color' => 'cyan'],
                            'nav_dots'       => ['label' => 'Titik Navigasi Vertikal', 'icon' => 'more-vertical', 'color' => 'cyan'],
                            'explore_button' => ['label' => 'Tombol "Explore Project"', 'icon' => 'external-link', 'color' => 'cyan'],
                            'featured_title' => ['label' => 'Judul Section Unggulan', 'icon' => 'heading-1', 'color' => 'cyan', 'has_bi_text' => true, 'prop_en' => 'en', 'prop_id' => 'id', 'label_en' => 'Judul EN', 'label_id' => 'Judul ID'],
                            'featured_desc'  => ['label' => 'Deskripsi Section Unggulan', 'icon' => 'align-left', 'color' => 'cyan', 'has_bi_text' => true, 'prop_en' => 'en', 'prop_id' => 'id', 'label_en' => 'Desc EN', 'label_id' => 'Desc ID'],
                        ];
                        foreach ($hero_defs as $key => $def):
                            $comp = $hero_comps[$key] ?? ['enabled' => true];
                            $enabled = $comp['enabled'] ?? true;
                        ?>
                        <div class="comp-row p-3 rounded-xl bg-slate-100/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/60 hover:border-cyan-500/30 transition-colors" data-section="hero" data-comp="<?php echo $key; ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="<?php echo $def['icon']; ?>" class="w-4 h-4 text-cyan-400"></i>
                                </div>
                                <p class="flex-1 text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo $def['label']; ?></p>
                                <label class="comp-toggle shrink-0" data-section="hero" data-comp="<?php echo $key; ?>">
                                    <input type="checkbox" class="sr-only comp-enabled" data-section="hero" data-comp="<?php echo $key; ?>" <?php echo $enabled ? 'checked' : ''; ?>>
                                    <div class="toggle-track-sm"></div>
                                </label>
                            </div>
                            <?php if (!empty($def['has_text'])): ?>
                            <input type="text" class="comp-text-input mt-2 w-full px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-cyan-500 font-mono"
                                data-section="hero" data-comp="<?php echo $key; ?>" data-prop="<?php echo $def['text_key']; ?>"
                                placeholder="<?php echo $def['text_label']; ?>"
                                value="<?php echo htmlspecialchars($comp[$def['text_key']] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (!empty($def['has_bi_text'])): ?>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <input type="text" class="comp-text-input px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-cyan-500"
                                    data-section="hero" data-comp="<?php echo $key; ?>" data-prop="<?php echo $def['prop_en']; ?>"
                                    placeholder="<?php echo $def['label_en']; ?>"
                                    value="<?php echo htmlspecialchars($comp[$def['prop_en']] ?? ''); ?>">
                                <input type="text" class="comp-text-input px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-cyan-500"
                                    data-section="hero" data-comp="<?php echo $key; ?>" data-prop="<?php echo $def['prop_id']; ?>"
                                    placeholder="<?php echo $def['label_id']; ?>"
                                    value="<?php echo htmlspecialchars($comp[$def['prop_id']] ?? ''); ?>">
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($def['has_props'])): ?>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <?php foreach ($def['props'] as $prop_key => $prop_def): ?>
                                <div>
                                    <label class="block text-[10px] text-slate-500 mb-0.5"><?php echo $prop_def['label']; ?></label>
                                    <input type="<?php echo $prop_def['type']; ?>" class="comp-text-input w-full px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-cyan-500 font-mono"
                                        data-section="hero" data-comp="<?php echo $key; ?>" data-prop="<?php echo $prop_key; ?>"
                                        <?php if (!empty($prop_def['min'])) echo 'min="'.$prop_def['min'].'"'; ?>
                                        <?php if (!empty($prop_def['max'])) echo 'max="'.$prop_def['max'].'"'; ?>
                                        value="<?php echo htmlspecialchars($comp[$prop_key] ?? ''); ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Section: Discover -->
                <div class="vf-panel p-0 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/80 bg-emerald-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 rounded-full bg-emerald-500"></div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Section: DISCOVER / GRID</h3>
                                <p class="text-slate-500 text-xs">Grid semua proyek portofolio di bawah hero</p>
                            </div>
                        </div>
                        <label class="layout-toggle-section" data-section="discover">
                            <input type="checkbox" class="sr-only section-toggle" data-section="discover" <?php echo (!isset($section_layout['discover']['enabled']) || $section_layout['discover']['enabled']) ? 'checked' : ''; ?>>
                            <div class="toggle-track"></div>
                        </label>
                    </div>
                    <div class="p-5 space-y-3" id="discover-components">
                        <?php
                        $disc_comps = $section_layout['discover']['components'] ?? [];
                        $disc_defs = [
                            'section_header'      => ['label' => 'Header Section "Discover More"', 'icon' => 'heading-2', 'color' => 'emerald'],
                            'project_count_badge' => ['label' => 'Badge Jumlah Proyek', 'icon' => 'hash', 'color' => 'emerald'],
                            'grid'                => ['label' => 'Grid Layout Kartu Proyek', 'icon' => 'grid', 'color' => 'emerald', 'has_props' => true, 'props' => [
                                'columns' => ['label' => 'Jumlah Kolom', 'type' => 'number', 'min' => 1, 'max' => 6],
                                'gap'     => ['label' => 'Gap Antar Kartu (px)', 'type' => 'number', 'min' => 0, 'max' => 32],
                            ]],
                            'card_tech_badges'    => ['label' => 'Badge Tech Stack di Kartu', 'icon' => 'code', 'color' => 'emerald', 'has_props' => true, 'props' => [
                                'max_badges' => ['label' => 'Maks Badge Tampil', 'type' => 'number', 'min' => 1, 'max' => 10],
                            ]],
                            'card_description'    => ['label' => 'Deskripsi di Kartu', 'icon' => 'file-text', 'color' => 'emerald'],
                            'card_index_badge'    => ['label' => 'Badge Nomor Urut (#01)', 'icon' => 'badge', 'color' => 'emerald'],
                        ];
                        foreach ($disc_defs as $key => $def):
                            $comp = $disc_comps[$key] ?? ['enabled' => true];
                            $enabled = $comp['enabled'] ?? true;
                        ?>
                        <div class="comp-row p-3 rounded-xl bg-slate-100/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/60 hover:border-emerald-500/30 transition-colors" data-section="discover" data-comp="<?php echo $key; ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="<?php echo $def['icon']; ?>" class="w-4 h-4 text-emerald-400"></i>
                                </div>
                                <p class="flex-1 text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo $def['label']; ?></p>
                                <label class="comp-toggle shrink-0">
                                    <input type="checkbox" class="sr-only comp-enabled" data-section="discover" data-comp="<?php echo $key; ?>" <?php echo $enabled ? 'checked' : ''; ?>>
                                    <div class="toggle-track-sm"></div>
                                </label>
                            </div>
                            <?php if (!empty($def['has_props'])): ?>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <?php foreach ($def['props'] as $prop_key => $prop_def): ?>
                                <div>
                                    <label class="block text-[10px] text-slate-500 mb-0.5"><?php echo $prop_def['label']; ?></label>
                                    <input type="<?php echo $prop_def['type']; ?>" class="comp-text-input w-full px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-emerald-500 font-mono"
                                        data-section="discover" data-comp="<?php echo $key; ?>" data-prop="<?php echo $prop_key; ?>"
                                        <?php if (!empty($prop_def['min'])) echo 'min="'.$prop_def['min'].'"'; ?>
                                        <?php if (!empty($prop_def['max'])) echo 'max="'.$prop_def['max'].'"'; ?>
                                        value="<?php echo htmlspecialchars($comp[$prop_key] ?? ''); ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Section: Background -->
                <div class="vf-panel p-0 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800/80 bg-amber-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 rounded-full bg-amber-500"></div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Layer: BACKGROUND / ENVIRONMENT</h3>
                                <p class="text-slate-500 text-xs">Elemen latar belakang luar angkasa</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <?php
                        $bg = $section_layout['background'] ?? [];
                        $bg_defs = [
                            'nebula_glow'    => ['label' => 'Nebula Glow (Cahaya Warna Proyek)', 'icon' => 'sparkles', 'color' => 'amber', 'has_props' => true, 'props' => [
                                'opacity' => ['label' => 'Opacity (%)', 'type' => 'number', 'min' => 0, 'max' => 100],
                            ]],
                            'star_particles' => ['label' => 'Partikel Bintang', 'icon' => 'star', 'color' => 'amber', 'has_props' => true, 'props' => [
                                'count' => ['label' => 'Jumlah Bintang', 'type' => 'number', 'min' => 0, 'max' => 200],
                            ]],
                            'vignette'       => ['label' => 'Vignette (Gradien Tepi Gelap)', 'icon' => 'circle', 'color' => 'amber'],
                        ];
                        foreach ($bg_defs as $key => $def):
                            $comp = $bg[$key] ?? ['enabled' => true];
                            $enabled = $comp['enabled'] ?? true;
                        ?>
                        <div class="comp-row p-3 rounded-xl bg-slate-100/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/60 hover:border-amber-500/30 transition-colors" data-section="background" data-comp="<?php echo $key; ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="<?php echo $def['icon']; ?>" class="w-4 h-4 text-amber-400"></i>
                                </div>
                                <p class="flex-1 text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo $def['label']; ?></p>
                                <label class="comp-toggle shrink-0">
                                    <input type="checkbox" class="sr-only comp-enabled" data-section="background" data-comp="<?php echo $key; ?>" <?php echo $enabled ? 'checked' : ''; ?>>
                                    <div class="toggle-track-sm"></div>
                                </label>
                            </div>
                            <?php if (!empty($def['has_props'])): ?>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <?php foreach ($def['props'] as $prop_key => $prop_def): ?>
                                <div>
                                    <label class="block text-[10px] text-slate-500 mb-0.5"><?php echo $prop_def['label']; ?></label>
                                    <input type="<?php echo $prop_def['type']; ?>" class="comp-text-input w-full px-2 py-1 rounded-lg bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300 focus:outline-none focus:border-amber-500 font-mono"
                                        data-section="background" data-comp="<?php echo $key; ?>" data-prop="<?php echo $prop_key; ?>"
                                        <?php if (!empty($prop_def['min'])) echo 'min="'.$prop_def['min'].'"'; ?>
                                        <?php if (!empty($prop_def['max'])) echo 'max="'.$prop_def['max'].'"'; ?>
                                        value="<?php echo htmlspecialchars($comp[$prop_key] ?? ''); ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="resetLayout()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-sm transition cursor-pointer">
                        Reset ke Default
                    </button>
                    <button onclick="submitLayoutConfig()" id="save-layout-btn" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition shadow-lg shadow-emerald-500/20 cursor-pointer flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Konfigurasi Layout
                    </button>
                </div>
            </div>

            <!-- RIGHT: Live Portfolio Preview (iframe) -->
            <div class="shrink-0 w-80 xl:w-96" style="position: sticky; top: 0; height: calc(100vh - 130px);">
                <div class="vf-panel p-0 overflow-hidden h-full flex flex-col">

                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800/80 flex items-center gap-2 shrink-0">
                        <i data-lucide="globe" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                        <h3 class="font-bold text-slate-600 dark:text-slate-300 text-sm shrink-0">Live Preview</h3>
                        <span class="ml-auto text-[10px] text-emerald-400 font-semibold tracking-wider bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20 shrink-0">LIVE</span>
                    </div>

                    <!-- URL Bar -->
                    <div class="px-3 py-2.5 border-b border-slate-200 dark:border-slate-800/60 flex items-center gap-2 shrink-0 bg-slate-100/40 dark:bg-slate-900/40">
                        <div class="flex-1 flex items-center gap-1.5 bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 rounded-lg px-2.5 py-1.5 focus-within:border-emerald-500/60 transition-colors">
                            <i data-lucide="link" class="w-3 h-3 text-slate-500 shrink-0"></i>
                            <input
                                type="url"
                                id="preview-iframe-url"
                                value="http://localhost:3000"
                                placeholder="https://yourportfolio.com"
                                class="flex-1 bg-transparent text-[11px] text-slate-600 dark:text-slate-300 font-mono focus:outline-none placeholder-slate-600 min-w-0"
                                onkeydown="if(event.key==='Enter') loadPreviewIframe()"
                            >
                        </div>
                        <button onclick="loadPreviewIframe()" title="Muat URL" class="p-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 transition cursor-pointer shrink-0">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </button>
                        <button onclick="openPreviewExternal()" title="Buka di Tab Baru" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-white border border-slate-300 dark:border-slate-700 transition cursor-pointer shrink-0">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    <!-- iframe Container -->
                    <div class="flex-1 relative overflow-hidden bg-slate-950">
                        <!-- Loading state -->
                        <div id="preview-loading" class="absolute inset-0 flex flex-col items-center justify-center gap-3 z-10 bg-slate-950">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center">
                                <i data-lucide="globe" class="w-5 h-5 text-emerald-400"></i>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Klik <span class="text-emerald-400">↺</span> untuk memuat preview</p>
                            <p class="text-[10px] text-slate-600 text-center max-w-[180px]">Pastikan dev server portofolio sudah berjalan di URL yang ditentukan</p>
                        </div>
                        <!-- iframe -->
                        <iframe
                            id="portfolio-preview-iframe"
                            src="about:blank"
                            class="w-full h-full border-0"
                            style="transform-origin: top left;"
                            onload="handleIframeLoad()"
                            onerror="handleIframeError()"
                        ></iframe>
                    </div>

                    <!-- Footer hint -->
                    <div class="px-4 py-2.5 border-t border-slate-200 dark:border-slate-800/60 shrink-0 flex items-center justify-between">
                        <p class="text-[10px] text-slate-600">Preview dari URL dev server portofolio</p>
                        <button onclick="toggleIframeScale()" id="iframe-scale-btn" title="Toggle Scale" class="text-[10px] text-slate-500 hover:text-slate-600 dark:text-slate-300 font-mono transition cursor-pointer">
                            100%
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- MODAL: ADD / EDIT PROJECT -->
<?php ob_start(); ?>
<form id="project-form" onsubmit="submitProject(event)" class="vf-stack vf-stack--gap-md max-h-[70vh] overflow-y-auto pr-2">
    <input type="hidden" id="project-id" name="id">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <?php 
            $this->load->view('components/input', [
                'name' => '',
                'id' => 'project-name',
                'label' => 'Nama Proyek',
                'placeholder' => 'Contoh: SPATIAL AUDIO',
                'required' => true
            ]); 
            ?>
        </div>
        <div>
            <?php 
            $this->load->view('components/input', [
                'name' => '',
                'id' => 'project-src',
                'type' => 'url',
                'label' => 'URL Gambar',
                'placeholder' => 'https://images.unsplash.com/...',
                'required' => true
            ]); 
            ?>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Palet Warna &amp; Glow (3D Ambient)</label>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div>
                <span class="block text-[10px] text-slate-500 font-semibold mb-1">Dark Bg</span>
                <input type="color" id="project-bg" value="#040614" class="w-full h-9 rounded-lg bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-1 cursor-pointer">
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-semibold mb-1">Light Bg</span>
                <input type="color" id="project-lightBg" value="#0f172a" class="w-full h-9 rounded-lg bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-1 cursor-pointer">
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-semibold mb-1">Nebula 1</span>
                <input type="color" id="project-nebula1" value="#6366f1" class="w-full h-9 rounded-lg bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-1 cursor-pointer">
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-semibold mb-1">Nebula 2</span>
                <input type="color" id="project-nebula2" value="#06b6d4" class="w-full h-9 rounded-lg bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-1 cursor-pointer">
            </div>
            <div>
                <span class="block text-[10px] text-slate-500 font-semibold mb-1">Aura Glow</span>
                <input type="color" id="project-aura" value="#818cf8" class="w-full h-9 rounded-lg bg-white dark:bg-[#0A0E1A] border border-slate-200 dark:border-slate-800 p-1 cursor-pointer">
            </div>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Deskripsi Proyek (English)</label>
        <textarea id="project-description" required rows="3" placeholder="Describe the project..." class="vf-input w-full  focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed"></textarea>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Deskripsi Proyek (Indonesian)</label>
        <textarea id="project-description-id" required rows="3" placeholder="Deskripsikan proyek..." class="vf-input w-full  focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 leading-relaxed"></textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Fitur Utama (EN)</label>
            <textarea id="project-features" rows="4" placeholder="Real-time chart&#10;Widget layout&#10;PDF Reports" class="vf-input w-full  text-xs text-slate-700 dark:text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Fitur Utama (ID)</label>
            <textarea id="project-features-id" rows="4" placeholder="Grafik real-time&#10;Tata letak widget&#10;Laporan PDF" class="vf-input w-full  text-xs text-slate-700 dark:text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Tech Stack</label>
            <textarea id="project-techStack" rows="4" placeholder="React&#10;TypeScript&#10;Tailwind CSS&#10;Node.js" class="vf-input w-full  text-xs text-slate-700 dark:text-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"></textarea>
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-4 mt-2">
        <button type="button" onclick="closeProjectModal()" class="vf-button vf-button--subtle">Batal</button>
        <?php 
        $this->load->view('components/button', [
            'text' => 'Simpan',
            'variant' => 'primary',
            'type' => 'submit',
            'class' => 'bg-emerald-500 hover:bg-emerald-400 border-emerald-500 text-slate-950 shadow-emerald-500/20'
        ]); 
        ?>
    </div>
</form>
<?php 
$projectForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'modal-project',
    'title' => 'Tambah Proyek Portofolio',
    'icon' => 'folder-git',
    'content' => $projectForm,
    'onClose' => 'closeProjectModal()',
    'class' => 'max-w-2xl'
]);
?>

<style>
/* Toggle styles */
.toggle-track {
    width: 44px; height: 24px;
    background: #334155; border-radius: 12px;
    position: relative; cursor: pointer; transition: background 0.2s;
}
.toggle-track::after {
    content: ''; position: absolute;
    top: 3px; left: 3px;
    width: 18px; height: 18px;
    background: white; border-radius: 50%;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
input:checked ~ .toggle-track { background: #10b981; }
input:checked ~ .toggle-track::after { transform: translateX(20px); }

.toggle-track-sm {
    width: 36px; height: 20px;
    background: #334155; border-radius: 10px;
    position: relative; cursor: pointer; transition: background 0.2s;
}
.toggle-track-sm::after {
    content: ''; position: absolute;
    top: 2px; left: 2px;
    width: 16px; height: 16px;
    background: white; border-radius: 50%;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
input:checked ~ .toggle-track-sm { background: #10b981; }
input:checked ~ .toggle-track-sm::after { transform: translateX(16px); }

.comp-row { transition: opacity 0.2s; }
.comp-row.disabled { opacity: 0.4; }
</style>

<!-- Modal Register App -->
<?php ob_start(); ?>
<form id="registerAppForm" onsubmit="registerNewApp(event)" class="vf-stack vf-stack--gap-md">
    <?php 
    $this->load->view('components/input', [
        'name' => 'name',
        'id' => 'app_name_input',
        'label' => 'Application Name *',
        'placeholder' => 'e.g. E-Commerce Multi-Tenant',
        'required' => true
    ]); 
    ?>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Category</label>
        <select name="category" class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            <option value="3d_portfolio">3D Portfolio</option>
            <option value="saas_tenant">SaaS Multi-Tenant</option>
            <option value="external_app">External Managed App</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Description</label>
        <textarea name="description" rows="3" placeholder="Deskripsi aplikasi atau lokasi direktori..." class="vf-input w-full  focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
    </div>

    <div class="p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-xs text-cyan-300 flex items-start gap-2 mt-4">
        <i data-lucide="database" class="w-4 h-4 text-cyan-400 shrink-0 mt-0.5"></i>
        <div>
            <strong>Automated Database Provisioning:</strong><br>
            Sistem akan secara otomatis membuat database terpisah (`db_<slug>`), user MySQL baru (`usr_<slug>`), dan kredensial terenkripsi AES-256.
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-4 mt-2">
        <button type="button" onclick="closeRegisterAppModal()" class="vf-button vf-button--subtle">Batal</button>
        <button type="submit" id="submitRegisterBtn" class="vf-button vf-button--primary">
            <span class="vf-button__label flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4"></i> Register &amp; Provision DB</span>
        </button>
    </div>
</form>
<?php 
$registerForm = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'registerAppModal',
    'title' => 'Register New Application',
    'icon' => 'layers',
    'content' => $registerForm,
    'onClose' => 'closeRegisterAppModal()'
]);
?>

<script>
function openRegisterAppModal() {
    document.getElementById('registerAppModal').classList.remove('hidden');
    document.getElementById('registerAppModal').classList.add('flex');
}

function closeRegisterAppModal() {
    document.getElementById('registerAppModal').classList.add('hidden');
    document.getElementById('registerAppModal').classList.remove('flex');
}

function registerNewApp(e) {
    e.preventDefault();
    const form = document.getElementById('registerAppForm');
    const formData = new FormData(form);
    const btn = document.getElementById('submitRegisterBtn');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Provisioning...';

    fetch('<?php echo base_url("applications/create"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.application) {
            Swal.fire({
                title: 'Aplikasi & Database Berhasil Diprovinsi!',
                html: `Aplikasi <strong>${data.application.name}</strong> telah terdaftar.<br><br>
                       <strong>Provisioned DB:</strong> <code>${data.provisioning_details ? data.provisioning_details.db_name : 'db_' + data.application.slug}</code><br>
                       <strong>DB User:</strong> <code>${data.provisioning_details ? data.provisioning_details.db_user : 'usr_app'}</code><br>
                       <strong>Raw Password:</strong> <code>${data.provisioning_details ? data.provisioning_details.raw_password : 'N/A'}</code>`,
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9',
                confirmButtonColor: '#06b6d4'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({ title: 'Gagal', text: data.error || 'Terjadi kesalahan', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Gagal terhubung ke server.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Register &amp; Provision DB';
    });
}

function testAppDatabase(appId) {
    Swal.fire({
        title: 'Menguji Koneksi DB...',
        text: 'Melakukan dekripsi AES-256 dan pengujian PDO...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); },
        background: '#0D1322',
        color: '#F1F5F9'
    });

    const formData = new FormData();
    formData.append('application_id', appId);

    fetch('<?php echo base_url("applications/test-db"); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Koneksi Berhasil!',
                text: 'Koneksi ke database terisolasi berjalan sempurna.',
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9',
                confirmButtonColor: '#10b981'
            });
        } else {
            Swal.fire({
                title: 'Koneksi Gagal',
                text: data.error || 'Gagal terhubung ke database.',
                icon: 'error',
                background: '#0D1322',
                color: '#F1F5F9'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
    });
}
</script>
