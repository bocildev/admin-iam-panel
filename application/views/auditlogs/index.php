<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="vf-page animate-in">
    <div class="vf-page__body">
        
        <!-- Header Banner -->
        <div class="vf-panel mb-6 bg-[#131B2E] border border-[#1E293B] rounded-2xl shadow-xl">
            <div class="vf-panel__header flex-col md:flex-row md:items-center justify-between gap-4 p-5">
                <div class="vf-panel__heading">
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/30">
                            <i data-lucide="file-search" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-extrabold text-white">Log Audit &amp; Telemetri Keamanan</h1>
                            <p class="text-xs text-slate-400 mt-1 max-w-2xl font-normal">
                                Catatan aktivitas login, pembekuan IP, perubahan hak akses, dan eksekusi API CodeIgniter 3 secara real-time.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="vf-panel__actions">
                    <?php 
                    $this->load->view('components/button', [
                        'text' => 'Export Audit JSON',
                        'variant' => 'secondary',
                        'icon' => 'download',
                        'class' => 'bg-[#0A0E1A] hover:bg-slate-800 text-slate-200 border-[#1E293B] hover:border-slate-700 font-mono text-xs',
                        'attributes' => 'onclick="exportAuditLogs()"'
                    ]); 
                    ?>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="vf-panel mb-6 bg-[#131B2E] border border-[#1E293B] rounded-2xl shadow-xl">
            <div class="vf-panel__body flex flex-col md:flex-row gap-3 items-center justify-between p-4">
                <!-- Search -->
                <div class="relative flex-1 w-full">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="log-search-input" onkeyup="filterLogTable()" placeholder="Cari IP, email, actor, atau aktivitas..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-[#0A0E1A] border border-[#1E293B] text-slate-100 placeholder-slate-500 text-xs focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-sans transition-colors">
                </div>
                <!-- Risk Filter -->
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select id="risk-filter" onchange="filterLogTable()" class="px-3 py-2 rounded-xl bg-[#0A0E1A] border border-[#1E293B] text-slate-200 text-xs focus:outline-none focus:border-cyan-500 font-mono transition-colors">
                        <option value="">Semua Tingkat Risiko (Risk)</option>
                        <option value="low">Low Risk (Rendah)</option>
                        <option value="medium">Medium Risk (Sedang)</option>
                        <option value="high">High Risk (Tinggi)</option>
                        <option value="critical">Critical (Sangat Tinggi)</option>
                    </select>
                    <span class="text-xs text-slate-400 font-mono pl-2" id="log-count"><?php echo count($logs); ?> Records</span>
                </div>
            </div>
        </div>

        <!-- Audit Log Feed List -->
        <div class="space-y-3" id="audit-log-list">
            <?php foreach ($logs as $log): ?>
            <?php
                $risk = $log['riskLevel'];
                if ($risk === 'critical') {
                    $riskClass = 'bg-rose-500/10 text-rose-300 border-rose-500/30';
                } elseif ($risk === 'high') {
                    $riskClass = 'bg-amber-500/10 text-amber-300 border-amber-500/30';
                } elseif ($risk === 'medium') {
                    $riskClass = 'bg-cyan-500/10 text-cyan-300 border-cyan-500/30';
                } else {
                    $riskClass = 'bg-[#0A0E1A] text-slate-300 border-[#1E293B]';
                }
                $status = isset($log['status']) ? $log['status'] : 'success';
                $statusColor = ($status === 'success') ? 'text-emerald-400' : 'text-rose-400';
            ?>
            <div class="log-card p-5 rounded-2xl bg-[#131B2E] border border-[#1E293B] hover:border-slate-600/50 transition-all shadow-lg hover:shadow-cyan-500/5 flex flex-col md:flex-row md:items-center justify-between gap-4 font-mono"
                 data-search="<?php echo strtolower($log['actor'] . ' ' . $log['actorEmail'] . ' ' . $log['action'] . ' ' . $log['ipAddress'] . ' ' . $log['details']); ?>"
                 data-risk="<?php echo $risk; ?>">
                <div class="space-y-1.5 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Risk Badge -->
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border <?php echo $riskClass; ?>">
                            <?php echo strtoupper($risk); ?> RISK
                        </span>
                        <!-- Action Key -->
                        <span class="font-bold text-slate-200 text-xs bg-[#0A0E1A] px-2.5 py-0.5 rounded-lg border border-[#1E293B]">
                            <?php echo htmlspecialchars($log['action']); ?>
                        </span>
                        <!-- Status -->
                        <span class="text-[10px] font-bold <?php echo $statusColor; ?>">
                            • <?php echo strtoupper($status); ?>
                        </span>
                    </div>
                    <!-- Detail Message -->
                    <p class="text-xs text-slate-200 font-sans font-medium pt-1"><?php echo htmlspecialchars($log['details']); ?></p>
                    <!-- Sub-info -->
                    <div class="text-[10px] text-slate-400 flex flex-wrap items-center gap-3 pt-1">
                        <span>Actor: <strong class="text-white"><?php echo htmlspecialchars($log['actor']); ?></strong> (<?php echo htmlspecialchars($log['actorEmail']); ?>)</span>
                        <span>•</span>
                        <span>Resource: <span class="text-cyan-300"><?php echo htmlspecialchars(isset($log['resource']) ? $log['resource'] : 'system'); ?></span></span>
                    </div>
                </div>
                <!-- Time & IP metadata -->
                <div class="text-right shrink-0 border-t md:border-t-0 md:border-l border-[#1E293B] pt-3 md:pt-0 md:pl-4">
                    <div class="text-xs text-slate-200 font-bold"><?php echo htmlspecialchars($log['ipAddress']); ?></div>
                    <div class="text-[10px] text-slate-400 font-sans"><?php echo htmlspecialchars($log['timestamp']); ?></div>
                    <div class="text-[9px] text-slate-500 truncate max-w-[150px]" title="<?php echo htmlspecialchars($log['userAgent']); ?>"><?php echo htmlspecialchars($log['userAgent']); ?></div>
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
