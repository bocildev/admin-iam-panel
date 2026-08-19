<script>
let globalRelations = null;

function switchMode(mode) {
    if (mode === 'table') {
        document.getElementById('tableModeContainer').classList.remove('hidden');
        document.getElementById('tableModeContainer').classList.add('flex');
        document.getElementById('explorerModeContainer').classList.add('hidden');
        
        document.getElementById('btnTabTable').classList.replace('text-slate-400', 'text-white');
        document.getElementById('btnTabTable').classList.replace('hover:bg-slate-800/50', 'bg-slate-800');
        
        document.getElementById('btnTabExplorer').classList.replace('text-white', 'text-slate-400');
        document.getElementById('btnTabExplorer').classList.replace('bg-slate-800', 'hover:bg-slate-800/50');
    } else {
        document.getElementById('tableModeContainer').classList.add('hidden');
        document.getElementById('tableModeContainer').classList.remove('flex');
        document.getElementById('explorerModeContainer').classList.remove('hidden');
        
        document.getElementById('btnTabExplorer').classList.replace('text-slate-400', 'text-white');
        document.getElementById('btnTabExplorer').classList.replace('hover:bg-slate-800/50', 'bg-slate-800');
        
        document.getElementById('btnTabTable').classList.replace('text-white', 'text-slate-400');
        document.getElementById('btnTabTable').classList.replace('bg-slate-800', 'hover:bg-slate-800/50');
        
        if (!globalRelations) {
            loadExplorer();
        }
    }
}

function loadExplorer() {
    fetch('<?php echo base_url("database_manager/api_all_relations/"); ?>' + APP_ID)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            globalRelations = data;
            renderExplorerInitial();
        } else {
            document.getElementById('explorerColumns').innerHTML = `<div class="text-rose-500">${data.error}</div>`;
        }
    });
}

function renderExplorerInitial() {
    // Column 1: List all tables
    const colDiv = createColumn('Tables', 0);
    let html = '<div class="space-y-1">';
    globalRelations.tables.forEach(t => {
        html += `<button onclick="exploreTable('${t}', 1)" class="w-full text-left px-3 py-2 text-xs rounded hover:bg-slate-800 text-slate-400 hover:text-white transition-colors truncate flex justify-between items-center group">
            <span><i data-lucide="table" class="w-3 h-3 inline-block mr-1"></i> ${t}</span>
            <i data-lucide="chevron-right" class="w-3 h-3 opacity-0 group-hover:opacity-100"></i>
        </button>`;
    });
    html += '</div>';
    colDiv.querySelector('.col-content').innerHTML = html;
    
    document.getElementById('explorerColumns').innerHTML = '';
    document.getElementById('explorerColumns').appendChild(colDiv);
    if(window.lucide) window.lucide.createIcons();
}

function createColumn(title, index) {
    const div = document.createElement('div');
    div.className = `w-72 shrink-0 h-full border-r border-slate-800 flex flex-col explorer-col bg-slate-900/50 animate-in`;
    div.setAttribute('data-col-index', index);
    div.innerHTML = `
        <div class="p-3 border-b border-slate-800 bg-slate-900/80 sticky top-0 z-10 flex justify-between items-center">
            <h3 class="text-xs font-bold text-white truncate w-full pr-2" title="${title}">${title}</h3>
        </div>
        <div class="col-content flex-1 overflow-y-auto p-2">
            <div class="flex justify-center p-4"><i data-lucide="loader" class="w-4 h-4 text-cyan-400 animate-spin"></i></div>
        </div>
    `;
    return div;
}

function clearColumnsAfter(index) {
    const cols = document.querySelectorAll('.explorer-col');
    cols.forEach(col => {
        if (parseInt(col.getAttribute('data-col-index')) >= index) {
            col.remove();
        }
    });
}

function highlightButton(buttonEl) {
    if (!buttonEl) return;
    const parentCol = buttonEl.closest('.explorer-col');
    if (parentCol) {
        parentCol.querySelectorAll('button').forEach(b => b.classList.remove('bg-cyan-500/20', 'text-cyan-400', 'border', 'border-cyan-500/30'));
    }
    buttonEl.classList.add('bg-cyan-500/20', 'text-cyan-400', 'border', 'border-cyan-500/30');
}

function exploreTable(tableName, colIndex, fkCol = null, fkVal = null) {
    clearColumnsAfter(colIndex);
    if (event && event.currentTarget) highlightButton(event.currentTarget);
    
    const colDiv = createColumn(`Data: ${tableName}`, colIndex);
    document.getElementById('explorerColumns').appendChild(colDiv);
    if(window.lucide) window.lucide.createIcons();
    
    let url = `<?php echo base_url("database_manager/api_table_data/"); ?>${APP_ID}/${tableName}?limit=100`;
    if (fkCol && fkVal) {
        url = `<?php echo base_url("database_manager/api_table_data_fk/"); ?>${APP_ID}/${tableName}?fk_col=${fkCol}&fk_val=${fkVal}&limit=100`;
    }
    
    fetch(url)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let html = '<div class="space-y-1.5">';
            if (data.data.length === 0) {
                html += `<div class="text-xs text-slate-500 text-center p-4">No records found.</div>`;
            } else {
                // Find primary key heuristically
                let pk = 'id';
                if (data.data.length > 0) {
                    const keys = Object.keys(data.data[0]);
                    pk = keys.find(k => k === 'id' || k.endsWith('_id')) || keys[0];
                }
                
                data.data.forEach(row => {
                    const pkVal = row[pk];
                    // Create a readable label from the first few columns
                    let label = `#${pkVal}`;
                    const keys = Object.keys(row);
                    const nameKey = keys.find(k => k.includes('name') || k.includes('title') || k.includes('email') || k.includes('desc'));
                    if (nameKey && row[nameKey]) {
                        let l = String(row[nameKey]);
                        if(l.length > 25) l = l.substring(0, 25) + '...';
                        label += ` - ${l}`;
                    } else if (keys.length > 1 && keys[1] !== pk) {
                        let l = String(row[keys[1]]);
                        if(l.length > 25) l = l.substring(0, 25) + '...';
                        label += ` - ${l}`;
                    }
                    
                    const encodedRow = encodeURIComponent(JSON.stringify(row));
                    
                    html += `<div class="group relative">
                        <button onclick="exploreRecord('${tableName}', '${pkVal}', '${label.replace(/'/g, "\\'")}', ${colIndex + 1})" class="w-full text-left p-2 pr-16 bg-slate-800/40 rounded border border-slate-700/50 hover:bg-slate-700 hover:border-slate-600 transition-colors flex justify-between items-center group-btn">
                            <span class="text-xs font-mono text-slate-300 truncate">${label}</span>
                            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-500 group-btn-hover:text-cyan-400"></i>
                        </button>
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="editExplorerRecord('${tableName}', '${pk}', '${pkVal}', '${encodedRow}')" class="p-1.5 bg-slate-600 hover:bg-emerald-500 hover:text-white rounded text-slate-300 transition-colors" title="Edit">
                                <i data-lucide="edit" class="w-3 h-3"></i>
                            </button>
                            <button onclick="toggleSoftDelete('${tableName}', '${pk}', '${pkVal}', event)" class="p-1.5 bg-slate-600 hover:bg-amber-500 hover:text-white rounded text-slate-300 transition-colors" title="Toggle Soft Delete / Active">
                                <i data-lucide="power" class="w-3 h-3"></i>
                            </button>
                            <button onclick="deleteExplorerCascading('${tableName}', '${pk}', '${pkVal}', '${label.replace(/'/g, "\\'")}', event)" class="p-1.5 bg-slate-600 hover:bg-rose-500 hover:text-white rounded text-slate-300 transition-colors" title="Cascading Delete">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>`;
                });
            }
            html += '</div>';
            colDiv.querySelector('.col-content').innerHTML = html;
        } else {
            colDiv.querySelector('.col-content').innerHTML = `<div class="text-xs text-rose-500 p-2">${data.error}</div>`;
        }
        if(window.lucide) window.lucide.createIcons();
    });
}

function exploreRecord(tableName, recordId, recordLabel, colIndex) {
    clearColumnsAfter(colIndex);
    if (event && event.currentTarget) highlightButton(event.currentTarget);
    
    // Check if this table has children
    const children = globalRelations.parent_to_children[tableName];
    
    const colDiv = createColumn(`Relations of ${recordLabel}`, colIndex);
    document.getElementById('explorerColumns').appendChild(colDiv);
    if(window.lucide) window.lucide.createIcons();
    
    let html = '<div class="space-y-1">';
    if (children && children.length > 0) {
        html += `<div class="text-[10px] font-bold text-slate-500 uppercase px-2 mb-2">Has Many</div>`;
        children.forEach(child => {
            html += `<button onclick="exploreTable('${child.table}', ${colIndex + 1}, '${child.fk_col}', '${recordId}')" class="w-full text-left px-3 py-2 text-xs rounded hover:bg-slate-800 text-slate-400 hover:text-white transition-colors truncate flex justify-between items-center group">
                <span><i data-lucide="link" class="w-3 h-3 text-emerald-400 inline-block mr-1"></i> ${child.table}</span>
                <span class="text-[9px] text-slate-600 font-mono">on ${child.fk_col}</span>
            </button>`;
        });
    } else {
        html += `<div class="text-xs text-slate-500 text-center p-4">No child relations found.</div>`;
    }
    html += '</div>';
    
    colDiv.querySelector('.col-content').innerHTML = html;
}

function editExplorerRecord(tableName, pkCol, pkVal, encodedRow) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    const row = JSON.parse(decodeURIComponent(encodedRow));
    
    // Switch to table mode temporarily to reuse form logic
    switchMode('table');
    document.getElementById('currentTableName').textContent = tableName;
    currentTable = tableName;
    currentPK = pkCol;
    
    fetch('<?php echo base_url("database_manager/api_table_schema/"); ?>' + APP_ID + '/' + tableName)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            currentSchema = data.columns;
            currentRelations = data.relations;
            
            // Build the form using the existing openFormModal function logic but skip fetching schema again
            document.getElementById('form_pk_col').value = pkCol;
            document.getElementById('form_pk_val').value = pkVal;
            
            let html = '';
            currentSchema.forEach(col => {
                if (col.Field === pkCol) return;
                const isRequired = col.Null === 'NO' ? 'required' : '';
                const val = row[col.Field] || '';
                html += `
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">${col.Field} <span class="text-slate-500 font-normal">(${col.Type})</span></label>
                        <input type="text" name="${col.Field}" value="${val.replace(/"/g, '&quot;')}" ${isRequired} class="w-full bg-slate-950 border border-slate-700 rounded p-2 text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all">
                    </div>
                `;
            });
            document.getElementById('formFields').innerHTML = html;
            document.getElementById('recordModal').classList.remove('hidden');
        }
    });
}

function deleteExplorerCascading(tableName, pkCol, pkVal, label, ev) {
    if (ev) {
        ev.stopPropagation();
        ev.preventDefault();
    }
    
    Swal.fire({
        title: 'Checking impact...',
        text: 'Menghitung data yang akan terhapus',
        background: '#0D1322',
        color: '#F1F5F9',
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(`<?php echo base_url("database_manager/api_cascading_impact/"); ?>${APP_ID}/${tableName}?pk_val=${pkVal}`)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let impactHtml = `<div class="text-sm text-slate-400 mt-2 text-left bg-slate-900 p-4 rounded-lg border border-slate-800">`;
            impactHtml += `<p class="mb-2 text-rose-400 font-bold">Data berikut akan dihapus secara permanen:</p><ul class="list-disc pl-5 space-y-1">`;
            for (const [t, count] of Object.entries(data.impact)) {
                impactHtml += `<li><b>${count}</b> baris dari tabel <code>${t}</code></li>`;
            }
            impactHtml += `</ul></div>`;
            
            Swal.fire({
                title: 'Hapus Berjenjang?',
                html: `<p>Anda yakin ingin menghapus <b>${label}</b>?</p>${impactHtml}`,
                icon: 'warning',
                background: '#0D1322',
                color: '#F1F5F9',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus Semua!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({title: 'Menghapus...', background: '#0D1322', color: '#F1F5F9', didOpen: () => {Swal.showLoading();}});
                    
                    fetch('<?php echo base_url("database_manager/api_delete_cascading/"); ?>' + APP_ID + '/' + tableName, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ __pk_col: pkCol, __pk_val: pkVal })
                    })
                    .then(res => res.json())
                    .then(resData => {
                        if (resData.success) {
                            Swal.fire({title: 'Terhapus!', icon: 'success', background: '#0D1322', color: '#F1F5F9', timer: 1500, showConfirmButton: false});
                            // Reload explorer
                            loadExplorer();
                        } else {
                            Swal.fire({title: 'Gagal', text: resData.error, icon: 'error', background: '#0D1322', color: '#F1F5F9'});
                        }
                    });
                }
            });
        } else {
            Swal.fire({title: 'Gagal', text: data.error, icon: 'error', background: '#0D1322', color: '#F1F5F9'});
        }
    });
}

function toggleSoftDelete(tableName, pkCol, pkVal, ev) {
    if (ev) {
        ev.stopPropagation();
        ev.preventDefault();
    }
    
    Swal.fire({title: 'Memproses...', background: '#0D1322', color: '#F1F5F9', didOpen: () => {Swal.showLoading();}});
    
    fetch('<?php echo base_url("database_manager/api_soft_delete/"); ?>' + APP_ID + '/' + tableName, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ __pk_col: pkCol, __pk_val: pkVal })
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            Swal.fire({
                title: 'Berhasil!', 
                html: `Status diubah menjadi <b>${resData.new_status}</b> (kolom: <code>${resData.column}</code>)`,
                icon: 'success', 
                background: '#0D1322', 
                color: '#F1F5F9', 
                timer: 2000, 
                showConfirmButton: false
            });
            loadExplorer();
        } else {
            Swal.fire({title: 'Tidak Didukung', text: resData.error, icon: 'error', background: '#0D1322', color: '#F1F5F9'});
        }
    }).catch(err => {
        Swal.fire({title: 'Error', text: 'Terjadi kesalahan jaringan', icon: 'error', background: '#0D1322', color: '#F1F5F9'});
    });
}
</script>
