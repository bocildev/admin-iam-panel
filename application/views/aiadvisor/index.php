<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page vf-page--compact animate-in">
    <div class="vf-page__body">

        <!-- Header Banner -->
        <div class="vf-panel mb-6 bg-gradient-to-r from-[#181126] via-[#16142c] to-[#131B2E] border border-violet-500/30 rounded-2xl shadow-xl">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4 p-5">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-xl bg-violet-500/20 text-violet-300 border border-violet-500/40">
                            <i data-lucide="sparkles" class="w-5 h-5 text-violet-400"></i>
                        </span>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-extrabold text-white">
                                ToonHub <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-fuchsia-300 to-cyan-300">AI Security Advisor</span>
                            </h1>
                            <p class="text-xs text-slate-400 mt-1 max-w-2xl font-normal">
                                Asisten kecerdasan buatan (Gemini Powered) untuk menghasilkan rekomendasi kebijakan IAM, kode PHP CI3, dan auditing keamanan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="vf-panel__actions px-3.5 py-1.5 rounded-xl bg-violet-950/60 border border-violet-500/30 text-violet-300 text-xs font-mono flex items-center gap-2 shrink-0">
                    <i data-lucide="zap" class="w-3.5 h-3.5 text-fuchsia-400"></i>
                    <span>Gemini 2.5 Flash Engine</span>
                </div>
            </div>
        </div>

        <!-- Quick Prompt Templates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
            <button onclick="sendAiTemplate(this)" data-prompt="Buatkan role untuk Kreator Komik yang hanya bisa upload episode dan atur paywall koin" class="p-3.5 rounded-xl bg-[#131B2E] border border-[#1E293B] hover:border-violet-500/50 text-left text-xs text-slate-300 hover:text-white transition-all flex items-center justify-between gap-2 group cursor-pointer shadow-lg hover:shadow-violet-500/5">
                <span class="line-clamp-1">Buatkan role untuk Kreator Komik yang hanya bisa upload episode dan atur paywall koin</span>
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-violet-400 opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
            </button>
            <button onclick="sendAiTemplate(this)" data-prompt="Bagaimana cara mengamankan sesi `ci_sessions` dari serangan Session Hijacking di CodeIgniter 3?" class="p-3.5 rounded-xl bg-[#131B2E] border border-[#1E293B] hover:border-violet-500/50 text-left text-xs text-slate-300 hover:text-white transition-all flex items-center justify-between gap-2 group cursor-pointer shadow-lg hover:shadow-violet-500/5">
                <span class="line-clamp-1">Bagaimana cara mengamankan sesi `ci_sessions` dari serangan Session Hijacking di CodeIgniter 3?</span>
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-violet-400 opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
            </button>
            <button onclick="sendAiTemplate(this)" data-prompt="Berikan contoh PHP Hook CI3 untuk membatasi akses endpoint API berdasarkan X-ToonHub-Api-Key" class="p-3.5 rounded-xl bg-[#131B2E] border border-[#1E293B] hover:border-violet-500/50 text-left text-xs text-slate-300 hover:text-white transition-all flex items-center justify-between gap-2 group cursor-pointer shadow-lg hover:shadow-violet-500/5">
                <span class="line-clamp-1">Berikan contoh PHP Hook CI3 untuk membatasi akses endpoint API berdasarkan X-ToonHub-Api-Key</span>
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-violet-400 opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
            </button>
            <button onclick="sendAiTemplate(this)" data-prompt="Tuliskan SQL query untuk mendeteksi akun pengguna ToonHub yang mencurigakan" class="p-3.5 rounded-xl bg-[#131B2E] border border-[#1E293B] hover:border-violet-500/50 text-left text-xs text-slate-300 hover:text-white transition-all flex items-center justify-between gap-2 group cursor-pointer shadow-lg hover:shadow-violet-500/5">
                <span class="line-clamp-1">Tuliskan SQL query untuk mendeteksi akun pengguna ToonHub yang mencurigakan</span>
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-violet-400 opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
            </button>
        </div>

        <!-- Chat Conversation Box -->
        <div class="vf-panel p-6 flex flex-col justify-between min-h-[420px] bg-[#131B2E] border border-[#1E293B] rounded-2xl shadow-xl">
            
            <!-- Messages List -->
            <div id="ai-messages" class="space-y-4 mb-6 max-h-[500px] overflow-y-auto pr-2">
                
                <!-- Initial AI Message -->
                <div class="flex gap-3 text-xs justify-start">
                    <div class="w-8 h-8 rounded-xl bg-violet-500/20 border border-violet-500/40 text-violet-300 flex items-center justify-center shrink-0 mt-1">
                        <i data-lucide="bot" class="w-4 h-4"></i>
                    </div>
                    <div class="p-4 rounded-2xl max-w-2xl space-y-2 bg-[#0A0E1A] border border-[#1E293B] text-slate-200">
                        <div class="leading-relaxed">
                            Halo! Saya <strong>AI IAM Security Advisor</strong> untuk platform ToonHub (CodeIgniter 3 Stack). <br><br>
                            Saya dapat membantu Anda merancang <strong>Role Policies</strong>, menulis <strong>PHP Auth Hooks</strong>, audit keamanan database `toonhub_db`, dan memberikan praktik terbaik pengamanan token REST API.
                        </div>
                    </div>
                </div>

            </div>

            <!-- Prompt Input Form -->
            <div class="flex items-center gap-2 p-2 rounded-2xl bg-[#0A0E1A] border border-[#1E293B] focus-within:border-violet-500 transition-all">
                <input type="text" id="ai-prompt-input" onkeypress="if(event.key === 'Enter') sendAiPrompt()"
                    placeholder="Ketik pertanyaan atau minta kebijakan IAM ToonHub..."
                    class="flex-1 bg-transparent px-3 text-xs text-white placeholder-slate-500 focus:outline-none">
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Kirim',
                    'variant' => 'primary',
                    'icon' => 'send',
                    'class' => 'text-white border-none bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 font-medium text-xs',
                    'attributes' => 'onclick="sendAiPrompt()" id="ai-send-btn"'
                ]); 
                ?>
            </div>

        </div>

    </div>
</div>

<script>
function sendAiTemplate(btn) {
    const prompt = btn.getAttribute('data-prompt');
    document.getElementById('ai-prompt-input').value = prompt;
    sendAiPrompt();
}

function sendAiPrompt() {
    const input = document.getElementById('ai-prompt-input');
    const prompt = input.value.trim();
    if (!prompt) return;

    const container = document.getElementById('ai-messages');
    
    // Append User Message
    const userMsgHtml = `
        <div class="flex gap-3 text-xs justify-end">
            <div class="p-4 rounded-2xl max-w-2xl space-y-2 text-white font-medium shadow-lg bg-gradient-to-r from-cyan-600 to-blue-600">
                <div class="whitespace-pre-wrap leading-relaxed">${prompt}</div>
            </div>
            <div class="w-8 h-8 rounded-xl bg-cyan-500/20 border border-cyan-500/40 text-cyan-300 flex items-center justify-center shrink-0 mt-1">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', userMsgHtml);
    input.value = '';
    container.scrollTop = container.scrollHeight;
    lucide.createIcons();

    // Loading indicator
    const loadingId = 'loading-' + Date.now();
    container.insertAdjacentHTML('beforeend', `
        <div id="${loadingId}" class="flex items-center gap-3 text-xs text-violet-300 font-mono">
            <div class="w-8 h-8 rounded-xl bg-violet-500/20 border border-violet-500/40 flex items-center justify-center">
                <i data-lucide="refresh-cw" class="w-4 h-4 animate-spin text-violet-400"></i>
            </div>
            <span>Menganalisis arsitektur keamanan CodeIgniter 3...</span>
        </div>
    `);
    container.scrollTop = container.scrollHeight;
    lucide.createIcons();

    fetch(BASE_URL + 'api/security_advice', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt, context: { appName: 'ToonHub IAM', stack: 'CodeIgniter 3' } })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById(loadingId)?.remove();
        const advice = data.advice || 'Rekomendasi keamanan berhasil dibuat.';
        container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-3 text-xs justify-start">
                <div class="w-8 h-8 rounded-xl bg-violet-500/20 border border-violet-500/40 text-violet-300 flex items-center justify-center shrink-0 mt-1">
                    <i data-lucide="bot" class="w-4 h-4"></i>
                </div>
                <div class="p-4 rounded-2xl max-w-2xl space-y-2 bg-[#0A0E1A] border border-[#1E293B] text-slate-200">
                    <div class="whitespace-pre-wrap leading-relaxed">${advice}</div>
                </div>
            </div>
        `);
        container.scrollTop = container.scrollHeight;
        lucide.createIcons();
    })
    .catch(() => {
        document.getElementById(loadingId)?.remove();
        container.insertAdjacentHTML('beforeend', `
            <div class="flex gap-3 text-xs justify-start">
                <div class="w-8 h-8 rounded-xl bg-violet-500/20 border border-violet-500/40 text-violet-300 flex items-center justify-center shrink-0 mt-1">
                    <i data-lucide="bot" class="w-4 h-4"></i>
                </div>
                <div class="p-4 rounded-2xl max-w-2xl bg-[#0A0E1A] border border-[#1E293B] text-slate-200">
                    <div class="leading-relaxed">Maaf, terjadi kendala saat merespons. Silakan coba lagi.</div>
                </div>
            </div>
        `);
        container.scrollTop = container.scrollHeight;
        lucide.createIcons();
    });
}
</script>
