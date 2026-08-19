<div class="px-6 py-8 w-full">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i data-lucide="database" class="w-6 h-6 text-emerald-400"></i>
                Database Manager
            </h1>
            <p class="text-sm text-slate-400 mt-1">App: <?php echo htmlspecialchars($db_config['db_name']); ?></p>
        </div>
        <div class="flex items-center gap-4">
            <div class="bg-slate-900 border border-slate-800 p-1 rounded-lg flex gap-1">
                <button onclick="switchMode('table')" id="btnTabTable" class="px-3 py-1.5 bg-slate-800 text-white rounded text-xs font-medium shadow transition-colors flex items-center gap-2">
                    <i data-lucide="table" class="w-3 h-3"></i> Table View
                </button>
                <button onclick="switchMode('explorer')" id="btnTabExplorer" class="px-3 py-1.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded text-xs font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="layout-grid" class="w-3 h-3"></i> Relational Explorer
                </button>
            </div>
            <a href="<?php echo base_url('applications'); ?>" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Registry
            </a>
        </div>
    </div>

    <div id="tableModeContainer" class="flex flex-col md:flex-row gap-6 h-[calc(100vh-180px)]">
        <!-- Sidebar: Tables -->
        <div class="w-full md:w-1/4 bg-slate-900 border border-slate-800 rounded-xl overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-800 bg-slate-900/50">
                <h2 class="text-sm font-bold text-white">Tables</h2>
            </div>
            <div id="tableList" class="flex-1 overflow-y-auto p-2 space-y-1">
                <div class="text-xs text-slate-500 p-2 text-center">Loading tables...</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4 bg-slate-900 border border-slate-800 rounded-xl overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-800 bg-slate-900/50 flex justify-between items-center">
                <h2 id="currentTableName" class="text-sm font-bold text-white">Select a table</h2>
                <div class="flex gap-2">
                    <button id="btnAddData" onclick="openFormModal()" class="hidden px-3 py-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 text-xs font-medium rounded transition-colors flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i> Add Data
                    </button>
                    <button id="btnRefresh" onclick="refreshData()" class="hidden px-3 py-1.5 bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-medium rounded transition-colors flex items-center gap-1">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-auto relative bg-slate-950">
                <table class="w-full text-left text-xs text-slate-500 dark:text-slate-400" id="dataTable">
                    <thead class="text-[10px] text-slate-400 uppercase bg-slate-900/80 sticky top-0 backdrop-blur-sm shadow-sm z-10 border-b border-slate-800">
                        <tr id="dataHead"></tr>
                    </thead>
                    <tbody id="dataBody">
                        <tr><td class="p-8 text-center text-slate-500">Silakan pilih tabel di samping kiri.</td></tr>
                    </tbody>
                </table>
                <!-- loader overlay -->
                <div id="dataLoader" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center z-20">
                    <div class="flex flex-col items-center gap-2 text-cyan-400">
                        <i data-lucide="loader" class="w-6 h-6 animate-spin"></i>
                        <span class="text-xs font-medium">Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Relational Explorer Mode -->
    <div id="explorerModeContainer" class="hidden h-[calc(100vh-180px)] w-full overflow-x-auto overflow-y-hidden bg-slate-900 border border-slate-800 rounded-xl p-4">
        <div id="explorerColumns" class="flex gap-4 h-full min-w-max">
            <!-- Columns will be injected here via JS -->
            <div class="flex items-center justify-center w-64 h-full text-slate-500 text-sm">Loading explorer...</div>
        </div>
    </div>
</div>

<!-- Form Modal -->
<?php ob_start(); ?>
<form id="recordForm" onsubmit="submitForm(event)">
    <input type="hidden" id="form_pk_col" name="__pk_col" value="">
    <input type="hidden" id="form_pk_val" name="__pk_val" value="">
    <div id="formFields" class="space-y-4 max-h-[60vh] overflow-y-auto px-1">
        <!-- Fields will be dynamically generated -->
    </div>
</form>
<?php 
$formContent = ob_get_clean();
$this->load->view('components/modal', [
    'id' => 'recordModal',
    'title' => 'Manage Data',
    'icon' => 'edit',
    'content' => $formContent,
    'submit_text' => 'Save Data',
    'submit_id' => 'btnSaveData',
    'submit_attributes' => 'onclick="document.getElementById(\'recordForm\').requestSubmit()"',
    'onClose' => 'closeFormModal()'
]);
?>

<script>
const APP_ID = '<?php echo $app_id; ?>';
let currentTable = null;
let currentSchema = null;
let currentRelations = null;
let currentPK = 'id';

document.addEventListener('DOMContentLoaded', () => {
    loadTables();
});

function loadTables() {
    fetch('<?php echo base_url("database_manager/api_tables/"); ?>' + APP_ID)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let html = '';
            data.tables.forEach(table => {
                html += `<button id="tab_${table}" onclick="selectTable('${table}')" class="w-full text-left px-3 py-2 text-xs rounded hover:bg-slate-800 text-slate-400 hover:text-slate-200 transition-colors truncate"><i data-lucide="table" class="w-3 h-3 inline-block mr-1"></i> ${table}</button>`;
            });
            document.getElementById('tableList').innerHTML = html;
            lucide.createIcons();
        } else {
            document.getElementById('tableList').innerHTML = `<div class="text-xs text-rose-500 p-2">${data.error}</div>`;
        }
    });
}

function selectTable(tableName) {
    if (currentTable) {
        let oldBtn = document.getElementById('tab_' + currentTable);
        if(oldBtn) oldBtn.classList.remove('bg-cyan-500/10', 'text-cyan-400');
    }
    currentTable = tableName;
    let newBtn = document.getElementById('tab_' + currentTable);
    if(newBtn) newBtn.classList.add('bg-cyan-500/10', 'text-cyan-400');
    
    document.getElementById('currentTableName').innerText = tableName;
    document.getElementById('btnAddData').classList.remove('hidden');
    document.getElementById('btnRefresh').classList.remove('hidden');
    
    document.getElementById('dataLoader').classList.remove('hidden');
    
    // First load schema
    fetch(`<?php echo base_url("database_manager/api_table_schema/"); ?>${APP_ID}/${tableName}`)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            currentSchema = data.columns;
            currentRelations = data.relations;
            
            // Find PK (basic heuristic: column with Key == PRI or just 'id')
            currentPK = null;
            let firstCol = null;
            data.columns.forEach(col => {
                if (!firstCol) firstCol = col.Field;
                if (col.Key === 'PRI' || col.Field.toLowerCase() === 'id') {
                    currentPK = col.Field;
                }
            });
            if (!currentPK) currentPK = firstCol;

            loadData();
        } else {
            Swal.fire({title: 'Error', text: 'Failed to load schema', icon: 'error', background: '#0D1322', color: '#F1F5F9'});
        }
    });
}

function refreshData() {
    if (currentTable) {
        document.getElementById('dataLoader').classList.remove('hidden');
        loadData();
    }
}

function loadData() {
    fetch(`<?php echo base_url("database_manager/api_table_data/"); ?>${APP_ID}/${currentTable}?limit=50`)
    .then(res => res.json())
    .then(data => {
        document.getElementById('dataLoader').classList.add('hidden');
        if (data.success) {
            renderTable(data);
        } else {
            document.getElementById('dataBody').innerHTML = `<tr><td class="p-4 text-rose-500">${data.error}</td></tr>`;
        }
    });
}

function renderTable(data) {
    let headHtml = '<th class="px-4 py-2 w-10">Aksi</th>';
    if (currentSchema) {
        currentSchema.forEach(col => {
            let extra = '';
            if (currentRelations && currentRelations[col.Field]) {
                extra = ` <i data-lucide="link" class="w-3 h-3 text-emerald-400 inline" title="-> ${currentRelations[col.Field]}"></i>`;
            }
            headHtml += `<th class="px-4 py-2 whitespace-nowrap">${col.Field}${extra}</th>`;
        });
    }
    document.getElementById('dataHead').innerHTML = headHtml;

    let bodyHtml = '';
    if (data.data && data.data.length > 0) {
        data.data.forEach(row => {
            let pkVal = currentPK ? row[currentPK] : '';
            bodyHtml += `<tr class="border-b border-slate-800 hover:bg-slate-800/50 transition-colors group">`;
            
            // Actions column
            bodyHtml += `<td class="px-4 py-2 whitespace-nowrap border-r border-slate-800">
                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick='openEditModal(${JSON.stringify(row).replace(/'/g, "\\'")})' class="p-1 bg-slate-700 hover:bg-cyan-600 text-white rounded"><i data-lucide="edit-2" class="w-3 h-3"></i></button>
                    <button onclick="deleteRecord('${pkVal}')" class="p-1 bg-slate-700 hover:bg-rose-600 text-white rounded"><i data-lucide="trash" class="w-3 h-3"></i></button>
                </div>
            </td>`;
            
            currentSchema.forEach(col => {
                let val = row[col.Field];
                if (val === null) {
                    val = '<span class="text-slate-600 italic">NULL</span>';
                } else {
                    val = String(val).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                    if (val.length > 50) val = val.substring(0, 50) + '...';
                }
                
                let relationLabel = '';
                if (currentRelations && currentRelations[col.Field] && val !== '<span class="text-slate-600 italic">NULL</span>' && val !== '') {
                    // For relation, we could ideally fetch the name, but for now just highlight it
                    relationLabel = ` <span class="text-[9px] bg-emerald-500/10 text-emerald-400 px-1 rounded ml-1 border border-emerald-500/20 cursor-help" title="Refers to ${currentRelations[col.Field]}">rel</span>`;
                }
                
                bodyHtml += `<td class="px-4 py-2 whitespace-nowrap text-slate-300 font-mono text-[11px]">${val}${relationLabel}</td>`;
            });
            bodyHtml += '</tr>';
        });
    } else {
        bodyHtml = `<tr><td colspan="${currentSchema ? currentSchema.length + 1 : 1}" class="p-8 text-center text-slate-500">Tabel kosong.</td></tr>`;
    }
    document.getElementById('dataBody').innerHTML = bodyHtml;
    lucide.createIcons();
}

function openFormModal() {
    document.getElementById('form_pk_col').value = currentPK;
    document.getElementById('form_pk_val').value = '';
    
    let html = '';
    currentSchema.forEach(col => {
        let type = 'text';
        let isAutoInc = col.Extra === 'auto_increment';
        if (col.Type.includes('int') || col.Type.includes('decimal')) type = 'number';
        if (col.Type.includes('text')) type = 'textarea';
        
        let required = col.Null === 'NO' && !isAutoInc && col.Default === null ? 'required' : '';
        let readonly = isAutoInc ? 'readonly disabled placeholder="Auto-increment"' : '';
        let relationHint = '';
        if (currentRelations && currentRelations[col.Field]) {
            relationHint = `<span class="text-[10px] text-emerald-400 float-right">-> ${currentRelations[col.Field]}</span>`;
        }

        html += `<div class="flex flex-col gap-1">`;
        html += `<label class="text-xs font-bold text-slate-400">${col.Field} ${relationHint}</label>`;
        if (type === 'textarea') {
            html += `<textarea name="${col.Field}" ${required} ${readonly} class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none h-20 disabled:opacity-50"></textarea>`;
        } else {
            html += `<input type="${type}" name="${col.Field}" ${required} ${readonly} class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none disabled:opacity-50">`;
        }
        html += `</div>`;
    });
    
    document.getElementById('formFields').innerHTML = html;
    document.querySelector('#recordModal h3').innerText = 'Add Data to ' + currentTable;
    
    document.getElementById('recordModal').classList.remove('hidden');
    document.getElementById('recordModal').classList.add('flex');
}

function openEditModal(rowData) {
    document.getElementById('form_pk_col').value = currentPK;
    document.getElementById('form_pk_val').value = rowData[currentPK];
    
    let html = '';
    currentSchema.forEach(col => {
        let type = 'text';
        let isAutoInc = col.Extra === 'auto_increment';
        let isPK = col.Field === currentPK;
        if (col.Type.includes('int') || col.Type.includes('decimal')) type = 'number';
        if (col.Type.includes('text')) type = 'textarea';
        
        let required = col.Null === 'NO' && !isAutoInc ? 'required' : '';
        let readonly = isPK ? 'readonly class="bg-slate-800 text-slate-500"' : ''; // Protect PK on edit
        
        let val = rowData[col.Field];
        if (val === null) val = '';
        
        html += `<div class="flex flex-col gap-1">`;
        html += `<label class="text-xs font-bold text-slate-400">${col.Field}</label>`;
        if (type === 'textarea') {
            html += `<textarea name="${col.Field}" ${required} ${readonly} class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none h-20 disabled:opacity-50">${val}</textarea>`;
        } else {
            html += `<input type="${type}" name="${col.Field}" value="${val}" ${required} ${readonly} class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none disabled:opacity-50">`;
        }
        html += `</div>`;
    });
    
    document.getElementById('formFields').innerHTML = html;
    document.querySelector('#recordModal h3').innerText = 'Edit Data in ' + currentTable;
    
    document.getElementById('recordModal').classList.remove('hidden');
    document.getElementById('recordModal').classList.add('flex');
}

function closeFormModal() {
    document.getElementById('recordModal').classList.add('hidden');
    document.getElementById('recordModal').classList.remove('flex');
}

function submitForm(e) {
    e.preventDefault();
    const form = document.getElementById('recordForm');
    const formData = new FormData(form);
    
    const pkVal = document.getElementById('form_pk_val').value;
    const isEdit = pkVal !== '';
    const endpoint = isEdit ? 'api_update' : 'api_insert';
    
    // convert formData to JSON manually because some fields might be empty strings instead of null, 
    // but PHP will handle them or we can just send as JSON
    const jsonObj = {};
    formData.forEach((value, key) => jsonObj[key] = value);

    fetch(`<?php echo base_url("database_manager/"); ?>${endpoint}/${APP_ID}/${currentTable}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonObj)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil disimpan.',
                icon: 'success',
                background: '#0D1322',
                color: '#F1F5F9',
                timer: 1500,
                showConfirmButton: false
            });
            closeFormModal();
            refreshData();
        } else {
            Swal.fire({ title: 'Gagal', text: data.error, icon: 'error', background: '#0D1322', color: '#F1F5F9' });
        }
    })
    .catch(err => {
        Swal.fire({ title: 'Error', text: 'Gagal terhubung ke server.', icon: 'error', background: '#0D1322', color: '#F1F5F9' });
    });
}

function deleteRecord(pkVal) {
    Swal.fire({
        title: 'Hapus data?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        background: '#0D1322',
        color: '#F1F5F9',
        confirmButtonColor: '#f43f5e'
    }).then(result => {
        if (result.isConfirmed) {
            const jsonObj = {
                __pk_col: currentPK,
                __pk_val: pkVal
            };
            fetch(`<?php echo base_url("database_manager/api_delete/"); ?>${APP_ID}/${currentTable}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(jsonObj)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({title:'Terhapus!', icon:'success', background: '#0D1322', color: '#F1F5F9', timer:1000, showConfirmButton:false});
                    refreshData();
                } else {
                    Swal.fire({title:'Gagal', text:data.error, icon:'error', background: '#0D1322', color: '#F1F5F9'});
                }
            });
        }
    });
}
</script>
<?php $this->load->view('database_manager/explorer_js'); ?>
