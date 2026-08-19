<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page animate-in">
    <div class="vf-page__body">
        
        <!-- Header Banner -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.9); border-color: rgba(30, 41, 59, 1);">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/30">
                            <i data-lucide="file-search" class="w-5 h-5"></i>
                        </span>
                        <h1 class="text-xl lg:text-2xl font-extrabold text-white">Log Audit &amp; Telemetri Keamanan</h1>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-2xl">
                        Catatan aktivitas login, pembekuan IP, perubahan hak akses, dan eksekusi API CodeIgniter 3 secara real-time.
                    </p>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Export Audit JSON',
                        'variant' => 'secondary',
                        'icon' => 'download',
                        'class' => 'bg-slate-800 hover:bg-slate-700 text-slate-700 dark:text-slate-200 border-slate-300 dark:border-slate-700',
                        'attributes' => 'onclick="exportAuditLogs()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="vf-panel mb-6" style="background: rgba(19, 27, 46, 0.8); border-color: rgba(30, 41, 59, 1);">
            <div class="vf-panel__body flex flex-col md:flex-row gap-3 items-center justify-between p-4">
                <!-- Search -->
                <div class="relative flex-1 w-full">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 dark:text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="log-search-input" onkeyup="filterLogTable()" placeholder="Cari IP, email, actor, atau aktivitas..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-[#0F1626] border border-slate-200 dark:border-slate-800 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-cyan-500">
                </div>
                <!-- Risk Filter -->
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select id="risk-filter" onchange="filterLogTable()" class="px-3 py-2 rounded-xl bg-[#0F1626] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 text-xs focus:outline-none focus:border-cyan-500 font-mono">
                        <option value="">Semua Tingkat Risiko (Risk)</option>
                        <option value="low">Low Risk (Rendah)</option>
                        <option value="medium">Medium Risk (Sedang)</option>
                        <option value="high">High Risk (Tinggi)</option>
                        <option value="critical">Critical (Sangat Tinggi)</option>
                    </select>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono pl-2" id="log-count"><?php echo count($logs); ?> Records</span>
                </div>
            </div>
        </div>

        <!-- Audit Log Feed List -->
        <div class="space-y-3" id="audit-log-list">
            <?php foreach ($logs as $log): ?>
            <?php
                $risk = $log['riskLevel'];
                if ($risk === 'critical') {
                    $riskClass = 'bg-rose-500/20 text-rose-300 border-rose-500/40';
                } elseif ($risk === 'high') {
                    $riskClass = 'bg-amber-500/20 text-amber-300 border-amber-500/40';
                } elseif ($risk === 'medium') {
                    $riskClass = 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40';
                } else {
                    $riskClass = 'bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-slate-700';
                }
                $status = isset($log['status']) ? $log['status'] : 'success';
                $statusColor = ($status === 'success') ? 'text-emerald-400' : 'text-rose-400';
            ?>
            <div class="log-card p-5 rounded-3xl bg-slate-50 dark:bg-[#131B2E]/90 border border-[#1E293B] hover:border-slate-300 dark:border-slate-700 transition-all shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4 font-mono"
                 data-search="<?php echo strtolower($log['actor'] . ' ' . $log['actorEmail'] . ' ' . $log['action'] . ' ' . $log['ipAddress'] . ' ' . $log['details']); ?>"
                 data-risk="<?php echo $risk; ?>">
                <div class="space-y-1.5 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Risk Badge -->
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border <?php echo $riskClass; ?>">
                            <?php echo strtoupper($risk); ?> RISK
                        </span>
                        <!-- Action Key -->
                        <span class="font-bold text-white text-xs bg-[#0F1626] px-2.5 py-0.5 rounded border border-slate-200 dark:border-slate-800">
                            <?php echo htmlspecialchars($log['action']); ?>
                        </span>
                        <!-- Status -->
                        <span class="text-[10px] font-bold <?php echo $statusColor; ?>">
                            • <?php echo strtoupper($status); ?>
                        </span>
                    </div>
                    <!-- Detail Message -->
                    <p class="text-xs text-slate-700 dark:text-slate-200 font-sans font-medium pt-1"><?php echo htmlspecialchars($log['details']); ?></p>
                    <!-- Sub-info -->
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-3 pt-1">
                        <span>Actor: <strong class="text-white"><?php echo htmlspecialchars($log['actor']); ?></strong> (<?php echo htmlspecialchars($log['actorEmail']); ?>)</span>
                        <span>•</span>
                        <span>Resource: <span class="text-cyan-300"><?php echo htmlspecialchars(isset($log['resource']) ? $log['resource'] : 'system'); ?></span></span>
                    </div>
                </div>
                <!-- Time & IP metadata -->
                <div class="text-right shrink-0 border-t md:border-t-0 md:border-l border-slate-200 dark:border-slate-800 pt-3 md:pt-0 md:pl-4">
                    <div class="text-xs text-slate-600 dark:text-slate-300 font-bold"><?php echo htmlspecialchars($log['ipAddress']); ?></div>
                    <div class="text-[10px] text-slate-500 font-sans"><?php echo htmlspecialchars($log['timestamp']); ?></div>
                    <div class="text-[9px] text-slate-600 truncate max-w-[150px]" title="<?php echo htmlspecialchars($log['userAgent']); ?>"><?php echo htmlspecialchars($log['userAgent']); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<script>
// Override filter for new card layout
function filterLogTable() {
    const q = (document.getElementById('log-search-input')?.value || '').toLowerCase();
    const risk = (document.getElementById('risk-filter')?.value || '');
    const cards = document.querySelectorAll('.log-card');
    let visible = 0;
    cards.forEach(card => {
        const search = card.dataset.search || '';
        const lRisk = card.dataset.risk || '';
        const matchQ = !q || search.includes(q);
        const matchRisk = !risk || lRisk === risk;
        const show = matchQ && matchRisk;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    const countEl = document.getElementById('log-count');
    if (countEl) countEl.textContent = visible + ' Records';
}

function exportAuditLogs() {
    const logs = <?php echo json_encode($logs); ?>;
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(logs, null, 2));
    const a = document.createElement('a');
    a.setAttribute("href", dataStr);
    a.setAttribute("download", "toonhub_audit_logs_" + new Date().toISOString().slice(0,10) + ".json");
    document.body.appendChild(a);
    a.click();
    a.remove();
}
</script>
