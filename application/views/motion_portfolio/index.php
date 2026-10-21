<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-2xl bg-gradient-to-r from-sky-500/10 via-[#131B2E] to-indigo-500/10 border border-sky-500/30">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded bg-sky-500/20 text-sky-300 border border-sky-500/40">
                    DEDICATED MODULE
                </span>
                <span class="text-xs font-mono text-slate-400">portofolio-motion · Nuxt 3 + Vue 3</span>
            </div>
            <h1 class="text-2xl font-bold text-white font-sans">
                Portofolio Motion Control Center
            </h1>
            <p class="text-xs text-slate-400 max-w-xl">
                Kelola seluruh konten, profil, studi kasus proyek, matriks keahlian, dan software tools yang ditampilkan pada aplikasi portofolio motion Anda.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="http://localhost:3000" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shadow-lg shadow-sky-500/20">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Buka Portofolio (Port 3000)</span>
            </a>
        </div>
    </div>

    <!-- Alert / Toast Container -->
    <div id="motion-toast" class="hidden p-4 rounded-xl text-xs font-medium transition-all"></div>

    <!-- Tab Navigation -->
    <div class="flex items-center justify-between border-b border-[#1E293B] pb-0">
        <div class="flex gap-2 overflow-x-auto">
            <button onclick="switchMotionTab('tab-profile', this)" id="btn-tab-profile" class="motion-tab-btn active px-4 py-2.5 font-bold text-xs border-b-2 border-sky-400 text-sky-400 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span>Profil &amp; Kontak</span>
            </button>
            <button onclick="switchMotionTab('tab-projects', this)" id="btn-tab-projects" class="motion-tab-btn px-4 py-2.5 font-semibold text-xs border-b-2 border-transparent text-slate-400 hover:text-slate-200 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Studi Kasus Proyek (<?php echo count($projects); ?>)</span>
            </button>
            <button onclick="switchMotionTab('tab-skills', this)" id="btn-tab-skills" class="motion-tab-btn px-4 py-2.5 font-semibold text-xs border-b-2 border-transparent text-slate-400 hover:text-slate-200 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="cpu" class="w-4 h-4"></i>
                <span>Matriks Keahlian</span>
            </button>
            <button onclick="switchMotionTab('tab-tools', this)" id="btn-tab-tools" class="motion-tab-btn px-4 py-2.5 font-semibold text-xs border-b-2 border-transparent text-slate-400 hover:text-slate-200 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="wrench" class="w-4 h-4"></i>
                <span>Software Tools</span>
            </button>
            <button onclick="switchMotionTab('tab-api', this)" id="btn-tab-api" class="motion-tab-btn px-4 py-2.5 font-semibold text-xs border-b-2 border-transparent text-slate-400 hover:text-slate-200 flex items-center gap-2 transition cursor-pointer">
                <i data-lucide="network" class="w-4 h-4"></i>
                <span>API &amp; Live Sync</span>
            </button>
        </div>

        <button onclick="openAddProjectModal()" class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-sky-500/15 hover:bg-sky-500/25 border border-sky-500/40 text-sky-300 font-mono text-xs transition-colors mb-1">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambah Proyek</span>
        </button>
    </div>

    <!-- TAB 1: PROFIL & KONTAK -->
    <div id="tab-profile" class="motion-tab-content space-y-6">
        <form id="form-profile" onsubmit="handleSaveProfile(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: Profil Utama (8 cols) -->
            <div class="lg:col-span-8 p-6 rounded-2xl bg-[#0e1117] border border-white/10 space-y-5">
                <h3 class="font-bold text-sm text-white uppercase tracking-wider font-mono flex items-center gap-2">
                    <i data-lucide="badge-check" class="w-4 h-4 text-sky-400"></i>
                    Informasi Biodata &amp; Karier
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars(isset($profile['name']) ? $profile['name'] : 'Moch. Adam Athallahrich'); ?>" required class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Short Name / Brand</label>
                        <input type="text" name="shortName" value="<?php echo htmlspecialchars(isset($profile['shortName']) ? $profile['shortName'] : 'ADAM.ATH'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Role Title</label>
                        <input type="text" name="roleTitle" value="<?php echo htmlspecialchars(isset($profile['roleTitle']) ? $profile['roleTitle'] : 'Fullstack & Frontend Developer'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Perusahaan Saat Ini</label>
                        <input type="text" name="currentCompany" value="<?php echo htmlspecialchars(isset($profile['currentCompany']) ? $profile['currentCompany'] : 'PT. Sigma Cipta Caraka (Telkomsigma)'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Kelahiran / Asal</label>
                        <input type="text" name="birthInfo" value="<?php echo htmlspecialchars(isset($profile['birthInfo']) ? $profile['birthInfo'] : '09 Mei 2001 · Surabaya'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-slate-400 mb-1">Domisili</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars(isset($profile['location']) ? $profile['location'] : 'Malang / Indonesia'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-mono text-slate-400 mb-1">Biografi Profesional (Bahasa Indonesia)</label>
                    <textarea name="bioId" rows="4" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none leading-relaxed"><?php echo htmlspecialchars(isset($profile['bioId']) ? $profile['bioId'] : ''); ?></textarea>
                </div>

                <!-- Kontak & Sosial -->
                <div class="pt-4 border-t border-white/10 space-y-4">
                    <h4 class="font-bold text-xs text-white uppercase tracking-wider font-mono">
                        Kontak &amp; Tautan Profil
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-slate-400 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars(isset($profile['phone']) ? $profile['phone'] : '+6285335769655'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-slate-400 mb-1">Alamat Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars(isset($profile['email']) ? $profile['email'] : 'athallahrich@gmail.com'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-slate-400 mb-1">Instagram Handle / URL</label>
                            <input type="text" name="instagram" value="<?php echo htmlspecialchars(isset($profile['instagram']) ? $profile['instagram'] : 'https://instagram.com/adamath_'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-slate-400 mb-1">GitLab Repositories URL</label>
                            <input type="text" name="gitlab" value="<?php echo htmlspecialchars(isset($profile['gitlab']) ? $profile['gitlab'] : 'https://gitlab.com/athallahrich'); ?>" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-400 hover:bg-sky-300 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </div>

            <!-- Right: Foto Profil & File CV (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="p-6 rounded-2xl bg-[#0e1117] border border-white/10 space-y-4">
                    <h3 class="font-bold text-xs text-white uppercase tracking-wider font-mono">
                        Foto Profil Utama
                    </h3>
                    <div class="aspect-[4/4.2] rounded-xl overflow-hidden bg-[#08090d] border border-white/15 relative">
                        <img id="avatar-preview" src="<?php echo htmlspecialchars(isset($profile['avatar']) ? $profile['avatar'] : '/profile-pics/profile1.webp'); ?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <label class="block text-[11px] font-mono text-slate-400 mb-1">Path / URL Foto</label>
                        <input type="text" name="avatar" id="avatar-input" value="<?php echo htmlspecialchars(isset($profile['avatar']) ? $profile['avatar'] : '/profile-pics/profile1.webp'); ?>" onchange="document.getElementById('avatar-preview').src = this.value" class="w-full px-3 py-2 rounded-xl bg-[#08090d] border border-white/15 text-white text-xs focus:border-sky-400 focus:outline-none">
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-[#0e1117] border border-white/10 space-y-2 text-xs font-mono">
                    <span class="text-sky-400 font-bold block uppercase tracking-wider">
                        Integrasi CV PDF
                    </span>
                    <p class="text-slate-400 text-[11px]">
                        Dokumen CV tersimpan di direktori publik dan dapat diunduh langsung:
                    </p>
                    <div class="p-2 rounded bg-[#08090d] border border-white/10 text-[11px] text-slate-300">
                        • Fullstack: /cv/Fullstack_Moch_Adam_Athallahrich.pdf
                    </div>
                    <div class="p-2 rounded bg-[#08090d] border border-white/10 text-[11px] text-slate-300">
                        • Frontend: /cv/Frontend_Moch_Adam_Athallahrich.pdf
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 2: STUDI KASUS PROYEK -->
    <div id="tab-projects" class="motion-tab-content hidden space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Daftar Studi Kasus Proyek</h3>
                <p class="text-xs text-slate-400">Total <?php echo count($projects); ?> proyek aktif yang ditampilkan pada antarmuka motion.</p>
            </div>
            <button onclick="openAddProjectModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-400 hover:bg-sky-300 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Proyek Baru</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($projects as $proj): ?>
            <div class="p-6 rounded-2xl bg-[#0e1117] border border-white/10 hover:border-sky-500/40 transition-all flex flex-col justify-between space-y-4">
                <div>
                    <!-- Top Bar -->
                    <div class="flex items-center justify-between pb-3 border-b border-white/10 text-xs font-mono">
                        <span class="px-2 py-0.5 rounded bg-sky-400/15 text-sky-300 border border-sky-400/30 font-bold">
                            <?php echo htmlspecialchars($proj['number']); ?> · <?php echo strtoupper($proj['category']); ?>
                        </span>
                        <span class="text-slate-400"><?php echo htmlspecialchars($proj['period']); ?></span>
                    </div>

                    <!-- Image Preview -->
                    <div class="aspect-video rounded-xl overflow-hidden bg-[#08090d] border border-white/10 mt-3 relative">
                        <img src="<?php echo htmlspecialchars($proj['cover_image']); ?>" alt="<?php echo htmlspecialchars($proj['title']); ?>" class="w-full h-full object-cover">
                        <div class="absolute bottom-2 right-2 px-2 py-1 rounded bg-[#08090d]/85 text-[10px] font-mono text-white border border-white/15">
                            <?php echo count($proj['gallery']); ?> Screenshots
                        </div>
                    </div>

                    <div class="mt-4 space-y-1">
                        <span class="text-[11px] font-mono text-sky-400 uppercase tracking-wider block">
                            <?php echo htmlspecialchars($proj['company']); ?>
                        </span>
                        <h4 class="font-bold text-lg text-white">
                            <?php echo htmlspecialchars($proj['title']); ?>
                        </h4>
                        <p class="text-xs text-slate-300 line-clamp-2 mt-1">
                            <?php echo htmlspecialchars($proj['description']); ?>
                        </p>
                    </div>

                    <!-- Tech Stack Pills -->
                    <div class="flex flex-wrap gap-1 mt-3">
                        <?php foreach (array_slice($proj['tech_stack'], 0, 4) as $tech): ?>
                        <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] font-mono text-slate-300">
                            <?php echo htmlspecialchars($tech); ?>
                        </span>
                        <?php endforeach; ?>
                        <?php if (count($proj['tech_stack']) > 4): ?>
                        <span class="px-1.5 py-0.5 text-[10px] font-mono text-slate-400">
                            +<?php echo count($proj['tech_stack']) - 4; ?> more
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-white/10 flex items-center justify-between gap-2">
                    <button onclick='openEditProjectModal(<?php echo json_encode($proj, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-mono transition-colors">
                        <i data-lucide="edit" class="w-3.5 h-3.5 text-sky-400"></i>
                        <span>Edit Data</span>
                    </button>
                    <button onclick="handleDeleteProject(<?php echo $proj['id']; ?>)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-mono transition-colors">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- TAB 3: MATRIKS KEAHLIAN (SKILLS) -->
    <div id="tab-skills" class="motion-tab-content hidden space-y-6">
        <div class="p-6 rounded-2xl bg-[#0e1117] border border-white/10 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Matriks Keahlian &amp; Indikator CV</h3>
                    <p class="text-xs text-slate-400">Keahlian sinkron dengan CV resmi Moch. Adam Athallahrich.</p>
                </div>
                <button onclick="handleSaveSkills()" class="px-4 py-2 rounded-xl bg-sky-400 hover:bg-sky-300 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors">
                    Simpan Perubahan Keahlian
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead class="bg-[#08090d] border-b border-white/10 text-slate-400 uppercase">
                        <tr>
                            <th class="p-3">Nama Skill</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Level (%)</th>
                            <th class="p-3">Dots CV (1-5)</th>
                            <th class="p-3">Konteks Produksi</th>
                        </tr>
                    </thead>
                    <tbody id="skills-tbody" class="divide-y divide-white/5 text-slate-200">
                        <?php foreach ($skills as $idx => $s): ?>
                        <tr>
                            <td class="p-3 font-bold text-white"><?php echo htmlspecialchars($s['name']); ?></td>
                            <td class="p-3 text-sky-400 uppercase"><?php echo htmlspecialchars($s['category']); ?></td>
                            <td class="p-3">
                                <input type="number" min="10" max="100" value="<?php echo (int)$s['level']; ?>" data-idx="<?php echo $idx; ?>" data-field="level" class="w-16 px-2 py-1 rounded bg-[#08090d] border border-white/15 text-white"> %
                            </td>
                            <td class="p-3">
                                <select data-idx="<?php echo $idx; ?>" data-field="dots" class="px-2 py-1 rounded bg-[#08090d] border border-white/15 text-white">
                                    <?php for ($d = 1; $d <= 5; $d++): ?>
                                    <option value="<?php echo $d; ?>" <?php echo ($s['dots'] == $d) ? 'selected' : ''; ?>><?php echo $d; ?> Dots</option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td class="p-3 text-slate-400"><?php echo htmlspecialchars($s['experience']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: SOFTWARE TOOLS -->
    <div id="tab-tools" class="motion-tab-content hidden space-y-6">
        <div class="p-6 rounded-2xl bg-[#0e1117] border border-white/10 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Software &amp; Database Tools (14 Tools)</h3>
                    <p class="text-xs text-slate-400">Alat bantu rekayasa harian yang tertera pada CV Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <?php foreach ($software_tools as $tool): ?>
                <div class="p-3.5 rounded-xl bg-[#08090d] border border-white/10 hover:border-sky-400/40 transition-colors">
                    <span class="text-[10px] font-mono text-sky-400 uppercase block mb-1">
                        <?php echo htmlspecialchars($tool['badge']); ?>
                    </span>
                    <span class="font-bold text-sm text-white block">
                        <?php echo htmlspecialchars($tool['name']); ?>
                    </span>
                    <span class="text-[11px] text-slate-400 block mt-0.5">
                        <?php echo htmlspecialchars($tool['group']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- TAB 5: API & LIVE SYNC -->
    <div id="tab-api" class="motion-tab-content hidden space-y-6">
        <div class="p-6 rounded-2xl bg-[#0e1117] border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white font-sans flex items-center gap-2">
                <i data-lucide="radio" class="w-4 h-4 text-emerald-400"></i>
                REST API Endpoints untuk Portofolio Motion
            </h3>
            <p class="text-xs text-slate-400">
                Gunakan URL berikut sebagai penyedia data dinamis untuk aplikasi frontend Anda (Nuxt 3):
            </p>

            <div class="space-y-3 font-mono text-xs">
                <div class="p-3 rounded-xl bg-[#08090d] border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold mr-2">GET</span>
                        <span class="text-slate-200"><?php echo base_url('api/motion/portfolio'); ?></span>
                    </div>
                    <a href="<?php echo base_url('api/motion/portfolio'); ?>" target="_blank" class="text-sky-400 hover:underline">
                        Test Endpoint &rarr;
                    </a>
                </div>

                <div class="p-3 rounded-xl bg-[#08090d] border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold mr-2">GET</span>
                        <span class="text-slate-200"><?php echo base_url('api/motion/projects'); ?></span>
                    </div>
                    <a href="<?php echo base_url('api/motion/projects'); ?>" target="_blank" class="text-sky-400 hover:underline">
                        Test Endpoint &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ADD / EDIT PROJECT -->
<div id="project-modal" class="fixed inset-0 z-50 hidden bg-[#0A0E1A]/80 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-3xl rounded-2xl bg-[#131B2E] border border-[#1E293B] p-6 shadow-2xl my-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-[#1E293B] mb-5">
            <h3 id="modal-title" class="font-bold text-base text-white font-sans">
                Tambah Proyek Portofolio
            </h3>
            <button onclick="closeProjectModal()" class="text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="project-form" onsubmit="handleSaveProject(event)" class="space-y-4 text-xs font-mono">
            <input type="hidden" name="id" id="proj-id">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">Nomor Indeks</label>
                    <input type="text" name="number" id="proj-number" value="01" required class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Kategori</label>
                    <select name="category" id="proj-category" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                        <option value="enterprise">Enterprise</option>
                        <option value="personal">Personal / Commercial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Periode</label>
                    <input type="text" name="period" id="proj-period" placeholder="2020 — Present" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Judul Proyek</label>
                <input type="text" name="title" id="proj-title" placeholder="MANGO — Human Capital Management" required class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white font-sans text-sm">
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Subtitle / Fokus Teknis</label>
                <input type="text" name="subtitle" id="proj-subtitle" placeholder="Enterprise Organization Structure..." class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Perusahaan / Entitas</label>
                <input type="text" name="company" id="proj-company" placeholder="PT. Sigma Cipta Caraka (Telkomsigma)" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Deskripsi Lengkap</label>
                <textarea name="description" id="proj-desc" rows="3" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white leading-relaxed"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">Path Cover Image</label>
                    <input type="text" name="cover_image" id="proj-cover" placeholder="/work/sigma/mango/dashboard.png" required class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Path Logo (Opsional)</label>
                    <input type="text" name="logo" id="proj-logo" placeholder="/work/sigma/mango/logo.png" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">Live Demo URL</label>
                    <input type="text" name="live_url" id="proj-live-url" placeholder="https://..." class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">GitLab / GitHub URL</label>
                    <input type="text" name="repo_url" id="proj-repo-url" placeholder="https://gitlab.com/..." class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
                </div>
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Tech Stack (Pisahkan dengan koma)</label>
                <input type="text" name="tech_stack_raw" id="proj-tech-stack" placeholder="NuxtJS, Vue.js, Buefy, REST API, Oracle" class="w-full px-3 py-2 rounded bg-[#08090d] border border-white/15 text-white">
            </div>

            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <button type="button" onclick="closeProjectModal()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/15 text-white">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded bg-sky-400 hover:bg-sky-300 text-slate-950 font-bold uppercase">
                    Simpan Proyek
                </button>
            </div>
        </form>
    </div>
</div>

<script>
window.motionSkillsData = <?php echo json_encode($skills); ?>;

function switchMotionTab(tabId, btn) {
    document.querySelectorAll('.motion-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.motion-tab-btn').forEach(b => {
        b.classList.remove('border-sky-400', 'text-sky-400', 'font-bold');
        b.classList.add('border-transparent', 'text-slate-400', 'font-semibold');
    });
    
    document.getElementById(tabId).classList.remove('hidden');
    btn.classList.remove('border-transparent', 'text-slate-400', 'font-semibold');
    btn.classList.add('border-sky-400', 'text-sky-400', 'font-bold');
}

function showToast(msg, isSuccess = true) {
    const t = document.getElementById('motion-toast');
    t.innerHTML = msg;
    t.className = isSuccess 
        ? 'p-3 rounded-xl text-xs font-medium bg-emerald-500/15 border border-emerald-500/40 text-emerald-300'
        : 'p-3 rounded-xl text-xs font-medium bg-rose-500/15 border border-rose-500/40 text-rose-300';
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 3500);
}

async function handleSaveProfile(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('<?php echo base_url("motion-portfolio/save-profile"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            showToast('Profil berhasil disimpan dan diperbarui!');
        } else {
            showToast('Gagal menyimpan profil: ' + (json.error || 'Unknown'), false);
        }
    } catch (err) {
        showToast('Error koneksi ke server: ' + err.message, false);
    }
}

function openAddProjectModal() {
    document.getElementById('modal-title').innerText = 'Tambah Proyek Baru';
    document.getElementById('project-form').reset();
    document.getElementById('proj-id').value = '';
    document.getElementById('project-modal').classList.remove('hidden');
}

function openEditProjectModal(proj) {
    document.getElementById('modal-title').innerText = 'Edit Proyek: ' + proj.title;
    document.getElementById('proj-id').value = proj.id || '';
    document.getElementById('proj-number').value = proj.number || '01';
    document.getElementById('proj-category').value = proj.category || 'enterprise';
    document.getElementById('proj-period').value = proj.period || '';
    document.getElementById('proj-title').value = proj.title || '';
    document.getElementById('proj-subtitle').value = proj.subtitle || '';
    document.getElementById('proj-company').value = proj.company || '';
    document.getElementById('proj-desc').value = proj.description || '';
    document.getElementById('proj-cover').value = proj.cover_image || '';
    document.getElementById('proj-logo').value = proj.logo || '';
    document.getElementById('proj-live-url').value = proj.live_url || '';
    document.getElementById('proj-repo-url').value = proj.repo_url || '';
    document.getElementById('proj-tech-stack').value = Array.isArray(proj.tech_stack) ? proj.tech_stack.join(', ') : '';
    document.getElementById('project-modal').classList.remove('hidden');
}

function closeProjectModal() {
    document.getElementById('project-modal').classList.add('hidden');
}

async function handleSaveProject(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const raw = Object.fromEntries(formData.entries());
    
    // Parse tech stack
    raw.tech_stack = raw.tech_stack_raw ? raw.tech_stack_raw.split(',').map(s => s.trim()).filter(Boolean) : [];
    delete raw.tech_stack_raw;

    try {
        const res = await fetch('<?php echo base_url("motion-portfolio/save-project"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(raw)
        });
        const json = await res.json();
        if (json.success) {
            closeProjectModal();
            showToast('Proyek berhasil disimpan!');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Gagal menyimpan: ' + (json.error || 'Unknown'), false);
        }
    } catch (err) {
        showToast('Error koneksi: ' + err.message, false);
    }
}

async function handleDeleteProject(id) {
    if (!confirm('Yakin ingin menghapus proyek ini dari portofolio?')) return;
    try {
        const res = await fetch('<?php echo base_url("motion-portfolio/delete-project/"); ?>' + id, {
            method: 'POST'
        });
        const json = await res.json();
        if (json.success) {
            showToast('Proyek berhasil dihapus');
            setTimeout(() => window.location.reload(), 800);
        }
    } catch (err) {
        showToast('Error saat menghapus: ' + err.message, false);
    }
}

async function handleSaveSkills() {
    const inputs = document.querySelectorAll('#skills-tbody [data-idx]');
    inputs.forEach(inp => {
        const idx = inp.getAttribute('data-idx');
        const field = inp.getAttribute('data-field');
        if (field === 'level') {
            window.motionSkillsData[idx].level = parseInt(inp.value) || 75;
        } else if (field === 'dots') {
            window.motionSkillsData[idx].dots = parseInt(inp.value) || 3;
        }
    });

    try {
        const res = await fetch('<?php echo base_url("motion-portfolio/save-skills"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(window.motionSkillsData)
        });
        const json = await res.json();
        if (json.success) {
            showToast('Daftar keahlian berhasil diperbarui!');
        }
    } catch (err) {
        showToast('Error: ' + err.message, false);
    }
}
</script>
