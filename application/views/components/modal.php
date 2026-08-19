<?php
/**
 * VyrnForge UI Modal Component Structure
 * 
 * Parameters:
 * $id - (string) Modal ID
 * $title - (string) Modal title
 * $icon - (string) lucide icon name for title (optional)
 * $form_id - (string) Form ID (optional, if this modal wraps a form)
 * $onsubmit - (string) Form onsubmit JS handler (optional)
 * $body - (string) Modal body content (can also be passed by including the view and passing content, but typically we might just echo it or use this view as a wrapper if we can)
 * 
 * Note: Since CodeIgniter views don't have block yielding easily without a template engine, 
 * this view will just provide the HTML wrapper. It might be easier to use it as an include.
 * Wait, actually, let's create it as a full modal view where body is passed, OR create modal_header.php and modal_footer.php.
 * Let's just create a full modal and assume $body is passed as HTML string, 
 * OR we can just use the standard HTML in the pages and replace classes with vf- classes. 
 * Since the user wants reusable components, let's pass $body.
 */
?>
<div id="<?php echo $id; ?>" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="vf-panel w-full max-w-lg relative animate-in" style="background: var(--vf-surface-overlay); box-shadow: var(--vf-shadow-md);">
        <div class="vf-panel__header border-b border-slate-200 dark:border-slate-800 pb-3 mb-4">
            <h3 class="vf-panel__title flex items-center gap-2">
                <?php if (isset($icon)): ?>
                    <i data-lucide="<?php echo $icon; ?>" class="w-5 h-5 text-cyan-400"></i> 
                <?php endif; ?>
                <?php echo $title; ?>
            </h3>
            <button onclick="document.getElementById('<?php echo $id; ?>').classList.add('hidden'); document.getElementById('<?php echo $id; ?>').classList.remove('flex');" class="text-slate-500 dark:text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <?php if (isset($form_id)): ?>
        <form id="<?php echo $form_id; ?>" <?php echo isset($onsubmit) ? 'onsubmit="'.$onsubmit.'"' : ''; ?> class="vf-stack vf-stack--gap-lg">
        <?php else: ?>
        <div class="vf-stack vf-stack--gap-lg">
        <?php endif; ?>
        
            <div class="vf-panel__body">
                <?php echo isset($content) ? $content : (isset($body) ? $body : ''); ?>
            </div>

            <?php if (!isset($hide_actions) || !$hide_actions): ?>
            <div class="vf-panel__actions border-t border-slate-200 dark:border-slate-800 pt-4 mt-4 w-full justify-end flex gap-2">
                <?php 
                $this->load->view('components/button', [
                    'text' => 'Batal',
                    'variant' => 'subtle',
                    'attributes' => 'onclick="document.getElementById(\''.$id.'\').classList.add(\'hidden\'); document.getElementById(\''.$id.'\').classList.remove(\'flex\');"'
                ]); 
                ?>
                
                <?php if (isset($form_id)): ?>
                    <?php 
                    $this->load->view('components/button', [
                        'text' => isset($submit_text) ? $submit_text : 'Simpan',
                        'type' => 'submit',
                        'variant' => 'primary',
                        'icon' => 'save',
                        'id' => isset($submit_btn_id) ? $submit_btn_id : ''
                    ]); 
                    ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        <?php if (isset($form_id)): ?>
        </form>
        <?php else: ?>
        </div>
        <?php endif; ?>
    </div>
</div>
